<?php

    /**
     * Global constants
    */
    define("IMAGE_MAX_FILE_SIZE", 10000000);
    define("ACCEPTED_EXTENSIONS", array("jpg", "jpeg", "png"));

    /**
     * Check if the $_FILES array is empty
    */
    if(count($_FILES) <= 0) {
        http_response_code(400);
        echo json_encode(array("success" => false, "message" => "An unknown upload error occured"));
    }
    
    $uploadedfile = $_FILES["uploadedfile"];
    $uploadedFileExtensionPosition = count(explode(".", $uploadedfile["name"])) - 1;
    $fileExtension = explode(".", $uploadedfile["name"])[$uploadedFileExtensionPosition];
    $mimeType = getimagesize($uploadedfile["tmp_name"]);

    $intent = $_POST["intent"]; // User intent

    $compressionquality = isset($_POST["compressionquality"]) ? (int)$_POST["compressionquality"] : null;

    $newwidth = isset($_POST["newwidth"]) ? $_POST["newwidth"] : null;
    $newheight = isset($_POST["newheight"]) ? $_POST["newheight"] : null;

    // check if an error occured
    if($uploadedfile["error"] === 0) {
        try {
            if($mimeType === false && !in_array($fileExtension, ACCEPTED_EXTENSIONS)) {
                http_response_code(400);
                header("Content-Type: application/json");
                throw new UploadException("INVALID_EXT", $fileExtension, $uploadedfile["size"], $uploadedfile["error"]);
            }

            if($uploadedfile["size"] > IMAGE_MAX_FILE_SIZE) {
                http_response_code(400);
                header("Content-Type: application/json");
                throw new UploadException("EXCEEDED_FILE_SIZE", $fileExtension, $uploadedfile["size"], $uploadedfile["error"]);
            }

            // No errors encountered
            // upload successful
            $imageTargetDir = dirname(__FILE__). DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.$uploadedfile["name"];
            
            $plainImageName = explode(".", $uploadedfile["name"]);
            array_pop($plainImageName);
            $plainImageName = implode("", $plainImageName);

            header("Content-Type: application/json");

            if($intent === "compress") {
                $compressedImage = new ManipulateImage($uploadedfile["tmp_name"], $plainImageName, strtolower(pathinfo($uploadedfile["name"], PATHINFO_EXTENSION)), $uploadedfile["size"], $newwidth, $newheight);
                $compressedImage->compressImage($compressionquality);

            } else if (strtolower($intent) === "resize") {
                $resizedImage = new ManipulateImage($uploadedfile["tmp_name"], $plainImageName, strtolower(pathinfo($uploadedfile["name"], PATHINFO_EXTENSION)), $uploadedfile["size"]);
                $resizedImage->resizeImage($newwidth, $newheight);
            }

            exit;
        } catch(Exception $e) {
            http_response_code(500);
            echo json_encode(array("success" => false, "message" => $e->getMessage()));
        }

    } else {
        http_response_code(400);
        echo json_encode(array("success" => false, "message" => "An upload error ocurred .."));
        throw new UploadException("", $fileExtension, $uploadedfile["size"], $uploadedfile["error"]);
    }

    /**
     * Image manipulation class
     * All image actions happen here
    */

    class ManipulateImage {
        public function __construct($image, $imageName, $imageExtension, $originalSize) {
            $this->Image = new Imagick($image);
            $this->imageName = $imageName;
            $this->imageExtension = $imageExtension;
            $this->originalSize = $originalSize;
        }

        public function compressImage($quality=20) {

            if($quality > 70) {
                echo json_encode( array("success" => false, "message" => "Compression quality exceeds logical limits", "downloadlink" => "", "size" => null) );
                $this->destroyImage();
                die();
            }

            try {
                $compressedImageFolder = dirname(__FILE__) . DIRECTORY_SEPARATOR . "uploads" .DIRECTORY_SEPARATOR . "" . date("d-m-Y"); // Folder containing todays images to be deleted by a cron job
                if(!file_exists($compressedImageFolder)) {
                    mkdir($compressedImageFolder, 0777, true);
                }

                $compressedImagePath = $compressedImageFolder . DIRECTORY_SEPARATOR .time(). $this->imageName . "-min." . $this->imageExtension;

                switch(strtolower($this->imageExtension)) {
                    case "png":
                        $Image->setImageCompression(Imagick::COMPRESSION_UNDEFINED);
                        $Image->setOption("png: compression-level", 5);
                        break;

                    case "jpg":
                    case "jpeg":
                        $this->Image->setImageCompression(Imagick::COMPRESSION_JPEG);
                        $this->Image->setImageCompressionQuality($quality);
                        break;

                    default:
                        $this->Image->setImageCompression(Imagick::COMPRESSION_UNDEFINED);
                        break;
                }

                $this->Image->writeImage($compressedImagePath);
                $downloadlinkarray = explode(DIRECTORY_SEPARATOR, $compressedImagePath);
                
                echo json_encode( array(
                        "success"      => true, 
                        "message"      => "Image compressed succesfully", 
                        "downloadlink" => "uploads/".date("d-m-Y") . "/" .$downloadlinkarray[count($downloadlinkarray) - 1], 
                        "original"     => $this->originalSize, 
                        "size"         => filesize($compressedImagePath)
                    ) 
                );
            } catch(Exception $e) {
                new ImageManipulationException($e->getMessage());
                echo json_encode( array("success" => false, "message" => "An error occured when compressing your image", "downloadlink" => "", "size" => null) );
            }
            $this->destroyImage();
        }

        public function resizeImage($newWidth=NULL, $newHeight=NULL) {
            if(is_null($newWidth) || is_null($newHeight)) {
                echo json_encode( array(
                    "success"      => false, 
                    "message"      => "New image dimensions are not defined",
                    "downloadlink" => ""
                    ) 
                );
                return;
            }

            try {
                $resizedImageFolder = dirname(__FILE__) . DIRECTORY_SEPARATOR . "uploads" .DIRECTORY_SEPARATOR . "" . date("d-m-Y"); // Folder containing todays images to be deleted by a cron job
                if(!file_exists($resizedImageFolder)) {
                    mkdir($resizedImageFolder, 0777, true);
                }

                $resizedImagePath = $resizedImageFolder . DIRECTORY_SEPARATOR .time(). $this->imageName . "-resized." . $this->imageExtension;

                $this->Image->resizeImage((int)$newWidth, (int)$newHeight, Imagick::FILTER_LANCZOS, 0.9, true);
                $this->Image->writeImage($resizedImagePath);
                $downloadlinkarray = explode(DIRECTORY_SEPARATOR, $resizedImagePath);

                echo json_encode( array(
                        "success"      => true, 
                        "message"      => "Image resized succesfully", 
                        "downloadlink" => "uploads/".date("d-m-Y") . "/" .$downloadlinkarray[count($downloadlinkarray) - 1], 
                        "original"     => $this->originalSize, 
                        "size"         => filesize($resizedImagePath) 
                    )
                );
            } catch(Exception $e) {
                new ImageManipulationException($e->getMessage());
                echo json_encode( array(
                    "success"      => false, 
                    "message"      => "An error occured when resizing your image", 
                    "downloadlink" => ""
                    ) 
                );
            }
            $this->destroyImage();
        }


        private function destroyImage() {
            $this->Image->clear();
            $this->Image->destroy();
        }
    }

    /**
     * Error handling
     * Custom class that extends PHPs Exceptions class
    */
    class UploadException extends Exception {
        public function __construct($error="", $fileextension="", $filesize=0, $errcode) {
            $message = $this->errToMessage($error, $fileextension, $filesize);
            $errorLogMessage = $this->errcodeToLog($errcode);
            $this->logErrorToFile($errorLogMessage);
            parent::__construct($message);
        }

        private function errToMessage($error, $fileextension, $filesize) {
            switch($error) {
                case "INVALID_EXT":
                    $message = ".{$fileextension} is an invalid file type. Accepted file types are " . implode(", ", ACCEPTED_EXTENSIONS);
                    break;
                case "EXCEEDED_FILE_SIZE":
                    $message = "Your image of " . (int)$filesize / 1000000 . "mb is too large. Max accepted image size is " . (int)IMAGE_MAX_FILE_SIZE / 1000000 . "mb";
                    break;
                default:
                    $message = "Unknown upload error";
                    break;
            }
            return $message;
        }

        private function errcodeToLog($errcode) {
            switch($errcode) {
                case 1:
                    $errorLogMessage = "The uploaded file exceeds upload_max_filesize directive";
                    break;
                case 2:
                    $errorLogMessage = "The uploaded filesize exceeds the MAX_FILE_SIZE specified in the form";
                    break;
                case 3:
                    $errorLogMessage = "File was only partially uploaded";
                    break;
                case 4:
                    $errorLogMessage = "No file was uploaded";
                    break;
                case 6:
                    $errorLogMessage = "Missing a temporary folder";
                    break;
                case 7:
                    $errorLogMessage = "Failed to write file to disk";
                    break;
                case 8:
                    $errorLogMessage = "File upload stopped by extension";
                    break;
                default:
                    $errorLogMessage = "Unknown upload error.";
                    break;
            }

            return $errorLogMessage;
        }

        private function logErrorToFile($errorLogMessage) {
            file_put_contents(dirname(__FILE__) . "/logs/uploaderrorlog.txt", "". date("d-m-Y") . " ". date("h:i:sa") . " $errorLogMessage \n", FILE_APPEND);
        }
    }

    class ImageManipulationException extends Exception {
        public function __construct($error) {
            $this->error = $error;
            $this->logErrorToFile();
            parent::__construct($error);
        }

        public static function logErrorToFile() {
            $errorLog = dirname(__FILE__) . DIRECTORY_SEPARATOR."logs".DIRECTORY_SEPARATOR."imagemanipulationerrorlog.txt";
            file_put_contents($errorLog, "".date("d-m-Y")." ".date("h:i:sa")." ".$this->error."\n", FILE_APPEND);
        }
    }

    class Log {
        public function __construct($logMessage) {
            $this->message = $logMessage;
        }

        public static function log($logMessage) {
            $messageLog = dirname(__FILE__) . DIRECTORY_SEPARATOR."logs".DIRECTORY_SEPARATOR."messagelog.txt";
            file_put_contents($messageLog, "".date("d-m-Y")." ".date("h:i:sa")." ".$logMessage."\n", FILE_APPEND);
        }
    }
    
?>