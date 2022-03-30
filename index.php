<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Free online image editor that can help reduce image sizes and edit image dimensions">
    <meta name="keywords" content="image, editor, compress, resize, crop">
    <title>Image editor online | Compress and resize your images for free</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
    <link rel="manifest" href="./favicon/site.webmanifest">
</head>
<body>

    <nav>
        <div class="container">
            <div class="logo">
                <div class="logo-wrapper">
                    <img src="./assets/logo.png" alt="image-editor-online logo">
                </div>
            </div>

            <ul class="navigation-list-items">
                <li class="navigation-list-item"><button class="open-tab-trigger" data-target-tab="compressimage" title="compress image">COMPRESS</button></li>
                <li class="navigation-list-item"><button class="open-tab-trigger" data-target-tab="resizeimage" title="edit image">RESIZE</button></li>
            </ul>
        </div>
    </nav>

    <main>
        <div id="notifications-wrapper"></div>

        <div id="tabs-wrapper">

            <!--- compress image tab ---->
            <div id="compress-image-tab" class="container tab shadow--primary-light" data-tabname="compressimage">
                
                <div class="tab-display-controls">
                    <div class="tab-display-control">
                        <button class="tab-control-btn tab-control-close-btn btn--danger"><i class="fas fa-times"></i></button>
                    </div>
                </div> 

                <h2 class="text--center">Compress image</h2>
                <section class="tab-hero file-drop-area b-radius-md">
                    <h4 class="file-name-placeholder text--center">You can drag your image into this area ...</h4>

                    <div class="upload-progress-wrapper">

                        <svg class="upload-progress-svg" width="156" height="156" viewBox="0 0 156 156" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <path class="upload-svg-wrapper" d="M156 78C156 121.078 121.078 156 78 156C34.9218 156 0 121.078 0 78C0 34.9218 34.9218 0 78 0C121.078 0 156 34.9218 156 78ZM7.8 78C7.8 116.77 39.2296 148.2 78 148.2C116.77 148.2 148.2 116.77 148.2 78C148.2 39.2296 116.77 7.8 78 7.8C39.2296 7.8 7.8 39.2296 7.8 78Z" fill="#90ADC6"/>
                                <path class="upload-svg-inner-wrapper" d="M148 78C148 116.66 116.66 148 78 148C39.3401 148 8 116.66 8 78C8 39.3401 39.3401 8 78 8C116.66 8 148 39.3401 148 78ZM18.5 78C18.5 110.861 45.1391 137.5 78 137.5C110.861 137.5 137.5 110.861 137.5 78C137.5 45.1391 110.861 18.5 78 18.5C45.1391 18.5 18.5 45.1391 18.5 78Z" fill="#333652"/>
                                <circle class="upload-progress-ring" cx="78.5" cy="77.5" r="62.5" fill="none" stroke-linecap="round" stroke="#E2D23C" stroke-width="5" />
                                <path class="upload-svg-pending-icon" d="M77 64C73.7006 64.0103 70.5137 65.2003 68.015 67.355C66.1 69.005 64.7125 71.155 64.355 73.3125C60.165 74.2375 57 77.8875 57 82.295C57 87.415 61.27 91.5 66.4525 91.5H88.7175C93.255 91.5 97 87.925 97 83.4325C97 79.3425 93.895 76.01 89.915 75.4475C89.3075 68.9975 83.725 64 77 64ZM82.885 76.865C83.0012 76.9812 83.0934 77.1192 83.1563 77.271C83.2192 77.4229 83.2516 77.5856 83.2516 77.75C83.2516 77.9144 83.2192 78.0771 83.1563 78.229C83.0934 78.3808 83.0012 78.5188 82.885 78.635C82.7688 78.7512 82.6308 78.8434 82.479 78.9063C82.3271 78.9692 82.1644 79.0016 82 79.0016C81.8356 79.0016 81.6729 78.9692 81.521 78.9063C81.3692 78.8434 81.2312 78.7512 81.115 78.635L78.25 75.7675V85.25C78.25 85.5815 78.1183 85.8995 77.8839 86.1339C77.6495 86.3683 77.3315 86.5 77 86.5C76.6685 86.5 76.3505 86.3683 76.1161 86.1339C75.8817 85.8995 75.75 85.5815 75.75 85.25V75.7675L72.885 78.635C72.6503 78.8697 72.3319 79.0016 72 79.0016C71.6681 79.0016 71.3497 78.8697 71.115 78.635C70.8803 78.4003 70.7484 78.0819 70.7484 77.75C70.7484 77.4181 70.8803 77.0997 71.115 76.865L76.115 71.865C76.2311 71.7486 76.3691 71.6562 76.5209 71.5932C76.6728 71.5302 76.8356 71.4978 77 71.4978C77.1644 71.4978 77.3272 71.5302 77.4791 71.5932C77.6309 71.6562 77.7689 71.7486 77.885 71.865L82.885 76.865Z" fill="#5E8FBA"/>
                                <path class="upload-svg-success-icon" d="M77 64C73.7006 64.0103 70.5137 65.2003 68.015 67.355C66.1 69.005 64.7125 71.155 64.355 73.3125C60.165 74.2375 57 77.8875 57 82.295C57 87.415 61.27 91.5 66.4525 91.5H88.7175C93.255 91.5 97 87.925 97 83.4325C97 79.3425 93.895 76.01 89.915 75.4475C89.3075 68.9975 83.725 64 77 64ZM82.885 76.135L75.385 83.635C75.2689 83.7514 75.1309 83.8438 74.9791 83.9068C74.8272 83.9698 74.6644 84.0022 74.5 84.0022C74.3356 84.0022 74.1728 83.9698 74.0209 83.9068C73.8691 83.8438 73.7311 83.7514 73.615 83.635L69.865 79.885C69.7488 79.7688 69.6566 79.6308 69.5937 79.479C69.5308 79.3271 69.4984 79.1644 69.4984 79C69.4984 78.8356 69.5308 78.6729 69.5937 78.521C69.6566 78.3692 69.7488 78.2312 69.865 78.115C69.9812 77.9988 70.1192 77.9066 70.271 77.8437C70.4229 77.7808 70.5856 77.7484 70.75 77.7484C70.9144 77.7484 71.0771 77.7808 71.229 77.8437C71.3808 77.9066 71.5188 77.9988 71.635 78.115L74.5 80.9825L81.115 74.365C81.3497 74.1303 81.6681 73.9984 82 73.9984C82.3319 73.9984 82.6503 74.1303 82.885 74.365C83.1197 74.5997 83.2516 74.9181 83.2516 75.25C83.2516 75.5819 83.1197 75.9003 82.885 76.135V76.135Z" fill="#F8F8F8"/>
                            </g>
                        </svg>

                        <div class="upload-progress-svg-waves">
                            <div class="upload-progress-svg-waves-inner water-tank">
                                <img src="./assets/wave-blob-2.png" alt="wave 2">
                                <img src="./assets/wave-blob-1.png" alt="wave 1">
                                <img src="./assets/drop.png" alt="">
                            </div>
                            <div class="upload-progress-analog-progress">
                                <div class="upload-progress-analog-progress-inner">
                                    <div class="upload-progress-analog-progress-hundreds">
                                        <div class="upload-progress-digits-wrapper">
                                            <p>0</p>
                                            <p>1</p>
                                        </div>
                                    </div>
                                    <div class="upload-progress-analog-progress-tens">
                                        <div class="upload-progress-digits-wrapper">
                                            <p>0</p>
                                            <p>1</p>
                                            <p>2</p>
                                            <p>3</p>
                                            <p>4</p>
                                            <p>5</p>
                                            <p>6</p>
                                            <p>7</p>
                                            <p>8</p>
                                            <p>9</p>
                                            <p>0</p>
                                        </div>
                                    </div>
                                    <div class="upload-progress-analog-progress-ones">
                                        <div class="upload-progress-digits-wrapper">
                                            <p>0</p>
                                            <p>1</p>
                                            <p>2</p>
                                            <p>3</p>
                                            <p>4</p>
                                            <p>5</p>
                                            <p>6</p>
                                            <p>7</p>
                                            <p>8</p>
                                            <p>9</p>
                                            <p>0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <form enctype="multipart/form-data" method="post" action="" id="compress__file-upload-form" class="text--center">
                        <input type="hidden" name="MAX_FILE_SIZE" value="10000000"/>
                        <input type="file" id="compress__file-upload-input" accept="image/*"/>
                        <label for="compress__file-upload-input" class="btn btn--primary-color-lite btn--upload file-upload-input-label">CHOOSE IMAGE</label>
                    </form>

                    <div class="drag-and-drop-dialog b-radius-md"></div>
                </section>
            </div>

            <!---- resize image tab ---->
            <div id="resize-image-tab" class="container tab shadow--primary-light" data-tabname="resizeimage">
                <div class="tab-display-controls">
                    <div class="tab-display-control">
                        <button class="tab-control-btn tab-control-close-btn btn--danger"><i class="fas fa-times"></i></button>
                    </div>
                </div> 

                <h2 class="text--center">Resize image</h2>
                <section class="tab-hero file-drop-area b-radius-md">
                    
                        <h4 class="file-name-placeholder text--center">You can drag your image into this area ...</h4>
                        <div class="upload-progress-wrapper">

                            <svg class="upload-progress-svg" width="156" height="156" viewBox="0 0 156 156" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path class="upload-svg-wrapper" d="M156 78C156 121.078 121.078 156 78 156C34.9218 156 0 121.078 0 78C0 34.9218 34.9218 0 78 0C121.078 0 156 34.9218 156 78ZM7.8 78C7.8 116.77 39.2296 148.2 78 148.2C116.77 148.2 148.2 116.77 148.2 78C148.2 39.2296 116.77 7.8 78 7.8C39.2296 7.8 7.8 39.2296 7.8 78Z" fill="#90ADC6"/>
                                    <path class="upload-svg-inner-wrapper" d="M148 78C148 116.66 116.66 148 78 148C39.3401 148 8 116.66 8 78C8 39.3401 39.3401 8 78 8C116.66 8 148 39.3401 148 78ZM18.5 78C18.5 110.861 45.1391 137.5 78 137.5C110.861 137.5 137.5 110.861 137.5 78C137.5 45.1391 110.861 18.5 78 18.5C45.1391 18.5 18.5 45.1391 18.5 78Z" fill="#333652"/>
                                    <circle class="upload-progress-ring" cx="78.5" cy="77.5" r="62.5" fill="none" stroke-linecap="round" stroke="#E2D23C" stroke-width="5" />
                                    <path class="upload-svg-pending-icon" d="M77 64C73.7006 64.0103 70.5137 65.2003 68.015 67.355C66.1 69.005 64.7125 71.155 64.355 73.3125C60.165 74.2375 57 77.8875 57 82.295C57 87.415 61.27 91.5 66.4525 91.5H88.7175C93.255 91.5 97 87.925 97 83.4325C97 79.3425 93.895 76.01 89.915 75.4475C89.3075 68.9975 83.725 64 77 64ZM82.885 76.865C83.0012 76.9812 83.0934 77.1192 83.1563 77.271C83.2192 77.4229 83.2516 77.5856 83.2516 77.75C83.2516 77.9144 83.2192 78.0771 83.1563 78.229C83.0934 78.3808 83.0012 78.5188 82.885 78.635C82.7688 78.7512 82.6308 78.8434 82.479 78.9063C82.3271 78.9692 82.1644 79.0016 82 79.0016C81.8356 79.0016 81.6729 78.9692 81.521 78.9063C81.3692 78.8434 81.2312 78.7512 81.115 78.635L78.25 75.7675V85.25C78.25 85.5815 78.1183 85.8995 77.8839 86.1339C77.6495 86.3683 77.3315 86.5 77 86.5C76.6685 86.5 76.3505 86.3683 76.1161 86.1339C75.8817 85.8995 75.75 85.5815 75.75 85.25V75.7675L72.885 78.635C72.6503 78.8697 72.3319 79.0016 72 79.0016C71.6681 79.0016 71.3497 78.8697 71.115 78.635C70.8803 78.4003 70.7484 78.0819 70.7484 77.75C70.7484 77.4181 70.8803 77.0997 71.115 76.865L76.115 71.865C76.2311 71.7486 76.3691 71.6562 76.5209 71.5932C76.6728 71.5302 76.8356 71.4978 77 71.4978C77.1644 71.4978 77.3272 71.5302 77.4791 71.5932C77.6309 71.6562 77.7689 71.7486 77.885 71.865L82.885 76.865Z" fill="#5E8FBA"/>
                                    <path class="upload-svg-success-icon" d="M77 64C73.7006 64.0103 70.5137 65.2003 68.015 67.355C66.1 69.005 64.7125 71.155 64.355 73.3125C60.165 74.2375 57 77.8875 57 82.295C57 87.415 61.27 91.5 66.4525 91.5H88.7175C93.255 91.5 97 87.925 97 83.4325C97 79.3425 93.895 76.01 89.915 75.4475C89.3075 68.9975 83.725 64 77 64ZM82.885 76.135L75.385 83.635C75.2689 83.7514 75.1309 83.8438 74.9791 83.9068C74.8272 83.9698 74.6644 84.0022 74.5 84.0022C74.3356 84.0022 74.1728 83.9698 74.0209 83.9068C73.8691 83.8438 73.7311 83.7514 73.615 83.635L69.865 79.885C69.7488 79.7688 69.6566 79.6308 69.5937 79.479C69.5308 79.3271 69.4984 79.1644 69.4984 79C69.4984 78.8356 69.5308 78.6729 69.5937 78.521C69.6566 78.3692 69.7488 78.2312 69.865 78.115C69.9812 77.9988 70.1192 77.9066 70.271 77.8437C70.4229 77.7808 70.5856 77.7484 70.75 77.7484C70.9144 77.7484 71.0771 77.7808 71.229 77.8437C71.3808 77.9066 71.5188 77.9988 71.635 78.115L74.5 80.9825L81.115 74.365C81.3497 74.1303 81.6681 73.9984 82 73.9984C82.3319 73.9984 82.6503 74.1303 82.885 74.365C83.1197 74.5997 83.2516 74.9181 83.2516 75.25C83.2516 75.5819 83.1197 75.9003 82.885 76.135V76.135Z" fill="#F8F8F8"/>
                                </g>
                            </svg>

                            <div class="upload-progress-svg-waves">
                                <div class="upload-progress-svg-waves-inner water-tank">
                                    <img src="./assets/wave-blob-2.png" alt="wave 2">
                                    <img src="./assets/wave-blob-1.png" alt="wave 1">
                                    <img src="./assets/drop.png" alt="">
                                </div>
                                <div class="upload-progress-analog-progress">
                                    <div class="upload-progress-analog-progress-inner">
                                        <div class="upload-progress-analog-progress-hundreds">
                                            <div class="upload-progress-digits-wrapper">
                                                <p>0</p>
                                                <p>1</p>
                                            </div>
                                        </div>
                                        <div class="upload-progress-analog-progress-tens">
                                            <div class="upload-progress-digits-wrapper">
                                                <p>0</p>
                                                <p>1</p>
                                                <p>2</p>
                                                <p>3</p>
                                                <p>4</p>
                                                <p>5</p>
                                                <p>6</p>
                                                <p>7</p>
                                                <p>8</p>
                                                <p>9</p>
                                                <p>0</p>
                                            </div>
                                        </div>
                                        <div class="upload-progress-analog-progress-ones">
                                            <div class="upload-progress-digits-wrapper">
                                                <p>0</p>
                                                <p>1</p>
                                                <p>2</p>
                                                <p>3</p>
                                                <p>4</p>
                                                <p>5</p>
                                                <p>6</p>
                                                <p>7</p>
                                                <p>8</p>
                                                <p>9</p>
                                                <p>0</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <form enctype="multipart/form-data" method="post" action="" id="resize__file-upload-form" class="text--center">
                            <input type="hidden" name="MAX_FILE_SIZE" value="10000000"/>
                            <input type="file" id="resize__file-upload-input" accept="image/*"/>
                            <label for="resize__file-upload-input" class="btn btn--primary-color-lite btn--upload file-upload-input-label">CHOOSE IMAGE</label>
                        </form>

                        <div class="drag-and-drop-dialog b-radius-md"></div>
                </section>
            </div>
            <!-- end resize tab --->

            <!---- DOWNLOAD CARDS --->
            <div class="container" id="manipulated-images-cards-wrapper">
                    <header class="ui--hide">
                        <h2>Images ready for download</h2>
                    </header>
                    <?php # this is populated when the server sends back the image(s); ?>

            </div>
            <!--- / END DOWNLOAD CARDS --->
            <section id="how-it-works" class="container">

                <header>
                    <h2>How it works</h2>
                </header>
                <div class="how-it-works-cards">

                    <div class="how-it-works-card b-radius-sm overflow-hidden">
                        <div class="how-it-works-card-top">
                            <img src="./assets/upload.png" alt="">
                        </div>
                        <div class="how-it-works-card-bottom">

                            <div class="how-it-works-card-bottom-text">
                            <h4>Select your image</h4>
                            <p>Select an option from the top right of the navigation bar</p>
                            <p>You can either drag and drop or select an image from your system.</p>
                            </div>
                        </div>
                        <div class="how-it-works-card-bottom-bottom-info">
                                <p>Formats: PNG, JPG, JPEG</p>
                                <i class="fas fa-upload fa-2x"></i>
                        </div>                        
                    </div>

                    <div class="how-it-works-card b-radius-sm overflow-hidden">
                        <div class="how-it-works-card-top">
                            <img src="./assets/system.png" alt="">
                        </div>
                        <div class="how-it-works-card-bottom">
                            <div class="how-it-works-card-bottom-text">
                            <h4>The image is uploaded to the server</h4>
                            <p>Your image is compressed or resized on the server depending on the option you have selected.</p>
                        </div>
                        </div>
                        <div class="how-it-works-card-bottom-bottom-info">
                                <p>Working</p>
                                <i class="fas fa-cogs fa-2x"></i>
                        </div>                        
                    </div>

                    <div class="how-it-works-card b-radius-sm overflow-hidden">
                        <div class="how-it-works-card-top">
                            <img src="./assets/download.png" alt="">
                        </div>
                            <div class="how-it-works-card-bottom">
                                <div class="how-it-works-card-bottom-text">
                                <h4>Download your image then rinse and repeat.</h4>
                                <p>You can then proceed to download your image.</p>
                                <p>Need to work on some more images? No problemo, just rinse and repeat this processes.</p>
                                
                            </div>
                        </div>
                        <div class="how-it-works-card-bottom-bottom-info">
                                <p>Download</p>
                                <i class="far fa-save fa-2x"></i>
                        </div>
                    </div>

                </div>
            </section>

        </div>
    </main>

    <!---- MODALS ------->

    <!--- resize modal --->
    <div class="modal" data-modal="compressModal">
        <div class="modal-content b-radius-sm overflow-hidden">
            <div class="modal-header text--right">
                <button class="btn btn--xs btn-close--danger modal-close-btn ui--hide"><i class="fas fa-times"></i></button>
            </div>

            <div class="modal-body">
                <form action="" id="resize-dimensions">
                    <small>Select compression quality</small>
                    
                    <div class="input-group">
                        <div class="input-group-inner range_input">
                            <label for="">Quality</label>
                            <input type="range" id="compression_quality" list="compressionlabels" name="compression_quality" min="20" max="70" value="20" step="10" />

                            <datalist id="compressionlabels" class="range_datalist">
                                <option>20%</option>
                                <option>70%</option>
                            </datalist>
                            <div class="range_tooltip" id="compression_tooltip">20</div>
                        </div>
                    </div>
 
                        <button id="compressSubmit" class="btn btn--block btn--primary-color-lite" type="submit">Compress</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal-footer"></div>
    </div>
    <!-- end resize modal---->
    <!--- resize modal --->
    <div class="modal" data-modal="resizeModal">
        <div class="modal-content b-radius-sm overflow-hidden">
            <div class="modal-header text--right">
                <button class="btn btn--xs btn-close--danger modal-close-btn ui--hide"><i class="fas fa-times"></i></button>
            </div>

            <div class="modal-body">
                <form action="" id="resize-dimensions">
                    <small>Current dimensions</small>
                    <div class="input-group">
                        <div class="input-group-inner">
                            <label for="">Width</label>
                            <input type="number" value="0" name="current_width">
                        </div>
                        <div class="input-group-inner">
                            <label for="">Height</label>
                            <input type="number" value="0" name="current_height">
                        </div>
                    </div>

                    <div id="new-dimensions-input-group">
                        <small>Resize to : </small>
                        <div class="input-group">
                            <div class="input-group-inner">
                                <label for="">Width</label>
                                <input type="number" value="0" name="new_width">
                            </div>
                            <div class="input-group-inner">
                                <label for="">Height</label>
                                <input type="number" value="0" name="new_height">
                            </div>
                        </div>

                        <label for=""><small>Maintain aspect ratio ?</small></label>
                        <input type="checkbox" checked class="checkbox-switch" name="maintain_aspect_ratio">

                        <small class="text--right aspect-ratio-disclaimer">*An appropriate aspect ratio is still applied even if you untick the box*</small>
                        
                        <button id="resizeSubmit" class="btn btn--block btn--primary-color-lite btn--disabled" type="submit">Resize</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal-footer"></div>
    </div>
    <!-- end resize modal---->
    <!----END MODALS  --->
    <footer>
    <!-- <a href="https://www.gogetssl.com" rel="nofollow" title="GoGetSSL Site Seal Logo" ><div id="gogetssl-animated-seal" style="width:180px; height:58px;"></div></a> <script src="https://gogetssl-cdn.s3.eu-central-1.amazonaws.com/site-seals/gogetssl-seal.js"></script> -->
        <p>Made with 💗 - <?php echo date('Y'); ?></p>
        <a href="mailto:webmaster@image-editor-online.com"><small>webmaster@image-editor-online.com</small></a>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="./main.js"></script>
</body>
</html>