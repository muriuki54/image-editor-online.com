"use strict";

let intent = "compress"; // What the user intends to do
let uploading = false;   // Are we currently uploading
let proceed = false;     // Is there something preventing us from proceeding, perhaps some info we need to input first
let files;               // Files array

const tabTriggers = nodeListToArray(document.querySelectorAll(".open-tab-trigger"));
const tabs = nodeListToArray(document.querySelectorAll(".tab"));

const compressImageUploadSection = new ImageManipulationTab(document.getElementById("compress-image-tab"));
const resizeImageUploadSection = new ImageManipulationTab(document.getElementById("resize-image-tab"));

const compress__uploadForm = document.querySelector("#compress__file-upload-form"); // form
const compress__uploadInput = document.querySelector("#compress__file-upload-input"); // input

const resize__uploadForm = document.querySelector("#resize__file-upload-form"); // form
const resize__uploadInput = document.querySelector("#resize__file-upload-input"); // input

const compressSubmitBtn = document.querySelector("#compressSubmit");
const resizeSubmitBtn = document.querySelector("#resizeSubmit");

const disabledButtons = document.querySelectorAll("btn--disabled");

nodeListToArray(disabledButtons).forEach(function(btn) {
    btn.addEventListener("click", function(e) {
        e.preventDefault();
        console.log("click");
    })
})

/** Modals */

function Modal(modal) {
    this.modal = modal;
    let modalBody = modal.querySelector(".modal-body");
    let closeBtn = modal.querySelector(".modal-close-btn");

    // this.modal.addEventListener("click", (e) => {
    //     this.closeModal();
    // })

    modalBody.addEventListener("click", function(e) {
        // e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        return false;
    })

    closeBtn.addEventListener("click", this.closeModal);

    this.openModal = function() {
        this.modal.classList.add("show");
    }
    this.closeModal = function() {
        this.modal.classList.remove("show");
    }
}

let compressModal = new Modal(document.querySelector("[data-modal='compressModal']"));
let resizeModal = new Modal(document.querySelector("[data-modal='resizeModal']"));
// compressModal.openModal();
// resizeModal.openModal();
/** End Modals */

/********************** COMPRESSION FORM INPUT(S)     */
const compress__compressionQuality = document.getElementsByName("compression_quality")[0];
/********************** END COMPRESSION FORM INPUT(S) */

/*********************  RESIZING FORM INPUTS ********************************** */
const resize__dimensionsForm = document.getElementById("resize-dimensions");
const resize__currentWidthInput = document.getElementsByName("current_width")[0];
const resize__currentHeightInput = document.getElementsByName("current_height")[0];
const resize__newDimensionsGroup = document.getElementById("new-dimensions-input-group");
const resize__newWidthInput = document.getElementsByName("new_width")[0];
const resize__newHeightInput = document.getElementsByName("new_height")[0];
const resize__maintainAspectratioInput = document.getElementsByName("maintain_aspect_ratio")[0];

resize__newWidthInput.addEventListener("input", function(e) {
    let resize__newWidthInputValue = parseInt(e.target.value);
    if(parseInt(resize__currentWidthInput.value) !== 0  && parseInt(resize__currentHeightInput.value) !== 0) {
        let aspectRatio = parseInt(resize__currentWidthInput.value) / parseInt(resize__currentHeightInput.value);

        if(resize__maintainAspectratioInput.checked) resize__newHeightInput.value = resize__newWidthInputValue / aspectRatio;
    }
    if(resize__newWidthInputValue !== 0 && parseInt(resize__newHeightInput.value) !== 0) resizeSubmitBtn.classList.remove("btn--disabled");

})

resize__newHeightInput.addEventListener("input", function(e) {
    if(parseInt(resize__newWidthInput.value) !== 0 && parseInt(e.target.value) !== 0) resizeSubmitBtn.classList.remove("btn--disabled");
})

resize__dimensionsForm.addEventListener("submit", function(e) {
    e.preventDefault();

    if(parseInt(resize__newWidthInput.value) === 0) {
        new Notification("Please enter a new width value");
        return;
    }
    if(parseInt(resize__newHeightInput.value) === 0) {
        new Notification("Please enter a new height value");
        return;
    }
    handleFileUpload(files, intent);
})

/********************* END RESIZING FORM INPUTS ***********************************/

let downloadButtonIndex = 0;

function ImageManipulationTab(tab) {
    this.fileDropArea = tab.querySelector(".tab-hero");
    this.uploadSvgRing = new UploadProgressSvg(tab.querySelector(".upload-progress-ring"));
    
    this.odometer = new Odometer(tab.querySelector(".upload-progress-analog-progress-inner"));

    this.waterTank = new WaterTank(tab.querySelector(".water-tank"));
    
    this.fileNamePlaceholder = tab.querySelector(".file-name-placeholder");
    this.inputButtonLabel = tab.querySelector(".file-upload-input-label");
}

const notificationsWrapper = document.getElementById("notifications-wrapper");

const imageCardsContainer = document.querySelector("#manipulated-images-cards-wrapper"); // div that holds cards with the download links

/* TABS */
function Tab(tab) {
    this.closeButton = tab.querySelector(".tab-control-close-btn");

    this.closeButton.addEventListener("click", function() {
        resetTabs();
    })
}

tabs.forEach(function(tab) {
    new Tab(tab);
})

/* END TABS */

/** TABS DISPLAY (HIDE) */
tabTriggers.forEach(function(trigger) {
    trigger.addEventListener("click", function() {
        resetTabs();
        let targetTab = trigger.getAttribute("data-target-tab");
        switch(targetTab) {
            case "compressimage":
                intent = "compress";
                proceed = false;
                break;
            case "resizeimage":
                intent = "resize";
                proceed = false;
                break;
            default:
                intent = "compress";
                proceed = false;
                break;
            
        }
        openTab(targetTab);
    })
})

tabTriggers[0].click(); // initially see the 1st tab

function openTab(targetTabAttr) {
    tabTriggers.forEach(function(trigger) {
        if(trigger.getAttribute("data-target-tab") === targetTabAttr) trigger.classList.add("current");
    })

    tabs.forEach(function(tab) {
        if(tab.getAttribute("data-tabname") === targetTabAttr) tab.classList.add("show");
    })
}

function resetTabs() {
    tabTriggers.forEach(function(trigger) {
        if(trigger.classList.contains("current")) trigger.classList.remove("current");
    })

    tabs.forEach(function(tab) {
        if(tab.classList.contains("show")) tab.classList.remove("show");
    })
}

/** END TABS DISPLAY(HIDE) */

/** SVG PROGRESS */
function UploadProgressSvg(svg) {
    this.svg = svg;
    // let svgPathLength = this.svg.getTotalLength();
    let svgPathLengthRadius = svg.getAttribute("r");
    this.svgPathLength = 2 * Math.PI * svgPathLengthRadius;
    // let strokeDasharrayVal = svgPathLength - 100;

    this.total = 0;
    this.uploaded = 0;

    let progress = this.svgPathLength - (this.uploaded / this.total) * this.svgPathLength;

    this.svg.style.strokeDasharray = this.svgPathLength;
    this.svg.style.strokeDashoffset = this.svgPathLength;

    this.update = function() {
        progress = this.svgPathLength - (this.uploaded / this.total) * this.svgPathLength;
        this.svg.style.strokeDashoffset = progress;
    }
}

/** odometer */
function Odometer(odometer) {
    this.current = 0; // pass a percentage of the currently uploaded bytes
    this.target = 100; // 100%
    this.hundredsColumn = odometer.querySelector(".upload-progress-analog-progress-hundreds .upload-progress-digits-wrapper");
    this.tensColumn = odometer.querySelector(".upload-progress-analog-progress-tens .upload-progress-digits-wrapper");
    this.onesColumn = odometer.querySelector(".upload-progress-analog-progress-ones .upload-progress-digits-wrapper");
    let showHundreds = false;
    let showTens = false;

    if(!showTens) this.tensColumn.style.visibility = "hidden";
    if(!showHundreds) this.hundredsColumn.style.visibility = "hidden";

    this.update = function() {
        switch(true) {
            case this.current <= 10:
                this.onesColumn.style.transform = "translateY("+ -this.current * 28+"px)";
                break;
            case this.current > 10 && this.current <= 20:
                this.onesColumn.style.transform = "translateY("+ -(this.current - 10) * 28+"px)";
                break;
            case this.current > 20 && this.current <= 30:
                this.onesColumn.style.transform = "translateY("+ -(this.current - 20) * 28+"px)";
                break;
            case this.current > 30 && this.current <= 40:
                this.onesColumn.style.transform = "translateY("+ -(this.current - 30) * 28+"px)";
                break;
            case this.current > 40 && this.current <= 50:
                this.onesColumn.style.transform = "translateY("+ -(this.current - 40) * 28+"px)";
                break;
            case this.current > 50 && this.current <= 60:
                this.onesColumn.style.transform = "translateY("+ -(this.current - 50) * 28+"px)";
                break;
            case this.current > 60 && this.current <= 70:
                this.onesColumn.style.transform = "translateY("+ -(this.current - 60) * 28+"px)";
                break;
            case this.current > 70 && this.current <= 80:
                this.onesColumn.style.transform = "translateY("+ -(this.current - 70) * 28+"px)";
                break;
            case this.current > 80 && this.current <= 90:
                this.onesColumn.style.transform = "translateY("+ -(this.current - 80) * 28+"px)";
                break;
            case this.current > 90 && this.current <= 100:
                this.onesColumn.style.transform = "translateY("+ -(this.current - 90) * 28+"px)";
                break;
        }

        if((this.current / 10) >= 1) { // if greater than 1 then current is greater than or equal to 10 
            showTens = true;
        } else {
            showTens = false;
            this.tensColumn.style.visibility = "hidden";
        }

        if(showTens) this.tensColumn.style.visibility = "visible";

        if(this.current <= 100) {
            this.tensColumn.style.transform = "translateY("+ ((-this.current * 28) / 10)+"px)";
        }

        if(this.current >= 100) {
            showHundreds = true;
            this.hundredsColumn.style.transform = "translateY("+ ((-this.current * 28) / 100)+"px)";
        } else {
            showHundreds = false;
        }

        if(showHundreds) {
            this.hundredsColumn.style.visibility = "visible";
        } else {
            this.hundredsColumn.style.visibility = "hidden";
        }
    }

    // testing
    // let interval = setInterval(() => {
    //     if(this.current >= this.target) {
    //         clearInterval(interval);
    //         return;
    //     }
    //     this.current ++;
    //     this.update();
    //     console.log(this.current);
    // }, 100);
}

function WaterTank(tank) {
    this.tank = tank;
    this.currentValue = 0; // later pass a value of the current uploaded progress in bytes
    this.update = function() {
        this.tank.style.top = this.currentValue + "%";
    }
}

// uploadOdometer.update();
/** end odometer */

/** END SVG PROGRESS */

/** IMAGE UPLOAD      */

/*prevent form submission */
compress__uploadForm.addEventListener("submit", (e) => e.preventDefault());
compress__uploadInput.addEventListener("change", (e) => handleFileUpload(e.target.files, "resize")); // @params: file, intent

resize__uploadForm.addEventListener("submit", (e) => e.preventDefault());
resize__uploadInput.addEventListener("change", (e) => handleFileUpload(e.target.files, "resize")); // @params: file, intent

;["dragenter", "dragover", "dragleave", "drop"].forEach(function(eventName) {
    compressImageUploadSection.fileDropArea.addEventListener(eventName, preventDefaults, false);
    resizeImageUploadSection.fileDropArea.addEventListener(eventName, preventDefaults, false);
})

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

;["dragenter", "dragover"].forEach(function(eventName) {
    compressImageUploadSection.fileDropArea.addEventListener(eventName, () => highLight(compressImageUploadSection.fileDropArea), false);
    resizeImageUploadSection.fileDropArea.addEventListener(eventName, () => highLight(resizeImageUploadSection.fileDropArea), false);
})

;["dragleave", "drop"].forEach(function(eventName) {
    compressImageUploadSection.fileDropArea.addEventListener(eventName, () => unhighLight(compressImageUploadSection.fileDropArea), false);
    resizeImageUploadSection.fileDropArea.addEventListener(eventName, () => unhighLight(resizeImageUploadSection.fileDropArea), false);

    // reset the progress visuals

    /////////  COMPRESS IMAGE SECTION ///////////////////////
    compressImageUploadSection.uploadSvgRing.uploaded = 0;
    compressImageUploadSection.uploadSvgRing.total = 0;
    compressImageUploadSection.uploadSvgRing.update();

    compressImageUploadSection.odometer.current = 0;
    compressImageUploadSection.odometer.update();
    
    compressImageUploadSection.waterTank.currentValue = 100;
    compressImageUploadSection.waterTank.update();
    /////////  RESIZE IMAGE SECTION ///////////////////////
    resizeImageUploadSection.uploadSvgRing.uploaded = 0;
    resizeImageUploadSection.uploadSvgRing.total = 0;
    resizeImageUploadSection.uploadSvgRing.update();

    resizeImageUploadSection.odometer.current = 0;
    resizeImageUploadSection.odometer.update();
    
    resizeImageUploadSection.waterTank.currentValue = 100;
    resizeImageUploadSection.waterTank.update();
                
})

function highLight(section) {
    section.classList.add("showOverlay");
}

function unhighLight(section) {
    section.classList.remove("showOverlay");
}

compressImageUploadSection.fileDropArea.addEventListener("drop", function(e) {
    files = e.dataTransfer.files;
    handleFileUpload(files, "compress");
})

resizeImageUploadSection.fileDropArea.addEventListener("drop", function(e) {
    files = e.dataTransfer.files;
    handleFileUpload(files, "resize");
})

function handleFileUpload(files, uploadintent) {
    const extensionTypes = ["jpg", "jpeg", "png"];
    let imageType = files[0].name.split(".");
    
    /** error messages */
    if(extensionTypes.indexOf(imageType[imageType.length - 1].toLowerCase()) < 0) {
        new Notification(`.${imageType[imageType.length - 1]} is an invalid file type. Accepted file types: jpg, jpeg, png`, "error");
        return;
    } 

    if(files[0].size > 10000000) {
        new Notification("Image too large. Max accepted size: 10 MB", "error");
        return;
    }
    /** End error messages */

    
    if(intent === "compress") { /** Pass compresion quality */
        proceed = false;
        compressModal.openModal();
        compressSubmit.addEventListener("click", function(e) {
            e.preventDefault();
            compressModal.closeModal();
            proceed = true;
            upload();
        })
    } else if(intent === "resize") { /** Handle dimensions if resizing     */
        proceed = false;
        let _URL = window.URL || window.webkitURL;

        let newImage = new Image();
        let objectURL = _URL.createObjectURL(files[0]);
        newImage.onload = function() {
            resize__currentWidthInput.value = this.width;
            resize__currentHeightInput.value = this.height;

            _URL.revokeObjectURL(objectURL);
            resize__newDimensionsGroup.classList.add("show");
        }
        newImage.src = objectURL;

        resizeModal.openModal(); // Open the modal so we can input our new dimensions
        resizeSubmitBtn.addEventListener("click", function(e) {
            e.preventDefault();
            resizeModal.closeModal();
            let currentWidth = parseInt(resize__currentWidthInput.value);
            let currentHeight = parseInt(resize__currentHeightInput.value);
            let newWidth = parseInt(resize__newWidthInput.value);
            let newHeight = parseInt(resize__newHeightInput.value);

            if(newWidth >= currentWidth || newHeight >= currentHeight) {
                new Notification("New dimensions can't be equal or larger that the original dimensions", "error");
                resizeModal.openModal(); // re-open the modal so we can input proper dimensions
            } else if(newWidth !== 0 && newHeight !== 0) {
                proceed = true;
                upload();
            }
        })
    } else {
        upload();
    }
    /** End handle dimensions if resizing */

    function upload() {
        console.log(uploading);
        if(!uploading) {
    
            if(!proceed) {
                console.log("Cannot proceed with the operation");
            } else {
                let fileName = files[0].name;
                uploading = true;
                checkUploadingStatus(uploading, fileName);
                            
                // send image to backend
                let formData = new FormData();
                formData.append("uploadedfile", files[0]);
                formData.append("intent", intent);
                if(intent === "compress") {
                    formData.append("compressionquality", parseInt(compress__compressionQuality.value));
                }
                if(intent === "resize") {
                    formData.append("newwidth", parseInt(resize__newWidthInput.value));
                    formData.append("newheight", parseInt(resize__newHeightInput.value));
                }
                
                axios.post("uploadimage.php", formData, {
                    headers: { "Content-Type": "multipart/form-data"},
                    onUploadProgress: function(progressEvent) {
                        if(progressEvent.lengthComputable) {
                            switch(uploadintent) {
                                case "compress":
                                    compressImageUploadSection.uploadSvgRing.uploaded = progressEvent.loaded;
                                    compressImageUploadSection.uploadSvgRing.total = progressEvent.total;
                                    compressImageUploadSection.uploadSvgRing.update();
    
                                    compressImageUploadSection.odometer.current = Math.round((progressEvent.loaded / progressEvent.total) * 100);
                                    compressImageUploadSection.odometer.update();
                                    
                                    // waterTank.style.top = 100 - ((progressEvent.loaded / progressEvent.total) * 100) + "%";
                                    compressImageUploadSection.waterTank.currentValue = 100 - ((progressEvent.loaded / progressEvent.total) * 100);
                                    compressImageUploadSection.waterTank.update();
                                    break;
    
                                case "resize":
                                    resizeImageUploadSection.uploadSvgRing.uploaded = progressEvent.loaded;
                                    resizeImageUploadSection.uploadSvgRing.total = progressEvent.total;
                                    resizeImageUploadSection.uploadSvgRing.update();
    
                                    resizeImageUploadSection.odometer.current = Math.round((progressEvent.loaded / progressEvent.total) * 100);
                                    resizeImageUploadSection.odometer.update();
                                    
                                    // waterTank.style.top = 100 - ((progressEvent.loaded / progressEvent.total) * 100) + "%";
                                    resizeImageUploadSection.waterTank.currentValue = 100 - ((progressEvent.loaded / progressEvent.total) * 100);
                                    resizeImageUploadSection.waterTank.update();
                                    break;
                                
                                default:
                                    console.log("Intent unknown");
                                    break;
                            }
                        }
                    }
                })
                .then(function(response) {
                    // Reset the progress visuals
                    switch(uploadintent) {
                        case "compress":
                            compressImageUploadSection.uploadSvgRing.uploaded = 0;
                            compressImageUploadSection.uploadSvgRing.total = compressImageUploadSection.uploadSvgRing.svgPathLength;
                            compressImageUploadSection.uploadSvgRing.update();
    
                            compressImageUploadSection.odometer.current = 0;
                            compressImageUploadSection.odometer.update();
                            
                            compressImageUploadSection.waterTank.currentValue = 100;
                            compressImageUploadSection.waterTank.update();
                            break;
    
                        case "resize":
                            resizeImageUploadSection.uploadSvgRing.uploaded = 0;
                            resizeImageUploadSection.uploadSvgRing.total = resizeImageUploadSection.uploadSvgRing.svgPathLength;
                            resizeImageUploadSection.uploadSvgRing.update();
    
                            resizeImageUploadSection.odometer.current = 0;
                            resizeImageUploadSection.odometer.update();
                            
                            resizeImageUploadSection.waterTank.currentValue = 100;
                            resizeImageUploadSection.waterTank.update();
                            break;
                        
                        default:
                            console.log("Intent unknown");
                            break;
                    }
                    console.log(response.data);
                    // document.getElementById("download-btn").setAttribute("href", response.data.compressedImagePath);
                    response.data.success ? 
                        new Notification("SUCCESS: "+ response.data.message, "success")
                        :
                        new Notification("ERROR: "+ response.data.message, "warning");
                    // Reset uploading status
                    uploading = false;
                    checkUploadingStatus(uploading, fileName);
    
                    // create compressed image download card
                    createCard();
    
    
                    // download link            
                    if(response.data.success && response.data.downloadlink) {
                        diplayDownLoadButton(response.data.success, response.data.message, response.data.downloadlink, response.data.original, response.data.size, downloadButtonIndex);
                    } else {
                        diplayDownLoadButton(response.data.success, response.data.message, response.data.downloadlink, response.data.original, response.data.size, downloadButtonIndex);
                    }

                    document.getElementById("manipulated-images-cards-wrapper").scrollIntoView({behavior: "smooth", block: "end"});
                })
                .catch(function(error) {
                    if(error.response) { 
                        // The request was made and the server responded with a status code
                        // that falls out of the range of 2xx
                        console.log(error.response);
                        console.log(error.response.data);
                        new Notification("ERR "+ error.response.status + ": " + error.response.data.message, "error");
                    } else if(error.request) {
                        // The request was made but no response was received
                        // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                        // http.ClientRequest in node.js
                        console.log(error.request);
                        new Notification("ERR "+ error.request, "error");
                    } else {
                    // Something happened in setting up the request that triggered an Error
                    console.log('Error', error.message);
                    new Notification("ERR "+ error.message, "error");
                    }
                    uploading = false;
                    checkUploadingStatus(uploading, fileName);
                })
            }
        } 
        // else {
        //     new Notification("Please wait until the current upload is completed", "info");
        //     return;
        // }
    }
}
/** END IMAGE UPLOAD  */

/**CHECK IF WE'RE UPLOADING */
function checkUploadingStatus(uploading, fileName) {

    if(intent === "compress") {
        compressImageUploadSection.fileNamePlaceholder.innerHTML = `${uploading ? "Uploading" : "Uploaded"} <span class="text--secondary">${fileName}</span> ...`;

        if(uploading) {
            compressImageUploadSection.inputButtonLabel.innerText = "WORKING";
            compressImageUploadSection.inputButtonLabel.classList.remove("btn--upload");
            compressImageUploadSection.inputButtonLabel.classList.add("btn--disabled");
            compressImageUploadSection.inputButtonLabel.classList.add("btn--working");
        } else {
            compressImageUploadSection.inputButtonLabel.innerText = "CHOOSE IMAGE";
            compressImageUploadSection.inputButtonLabel.classList.add("btn--upload");
            compressImageUploadSection.inputButtonLabel.classList.remove("btn--disabled");
            compressImageUploadSection.inputButtonLabel.classList.remove("btn--working");
        }    
    } else if(intent === "resize") {
        resizeImageUploadSection.fileNamePlaceholder.innerHTML = `${uploading ? "Uploading" : "Uploaded"} <span class="text--secondary">${fileName}</span> ...`;

        if(uploading) {
            resizeImageUploadSection.inputButtonLabel.innerText = "WORKING";
            resizeImageUploadSection.inputButtonLabel.classList.remove("btn--upload");
            resizeImageUploadSection.inputButtonLabel.classList.add("btn--disabled");
            resizeImageUploadSection.inputButtonLabel.classList.add("btn--working");
        } else {
            resizeImageUploadSection.inputButtonLabel.innerText = "CHOOSE IMAGE";
            resizeImageUploadSection.inputButtonLabel.classList.add("btn--upload");
            resizeImageUploadSection.inputButtonLabel.classList.remove("btn--disabled");
            resizeImageUploadSection.inputButtonLabel.classList.remove("btn--working");
        }    
    }
}


/** NOTIFICATIONS */
function Notification(message = "", status = "info") {
    if(message.length < 0) { return; }

    this.timeout = 16000;
    this.timeElapsed = 0;

    let notification = document.createElement("div");
    notification.classList.add("notification");
    notification.classList.add("notification--"+status);

    let notificationContainer = document.createElement("div");
    notificationContainer.classList.add("container");

    let notificationText = document.createTextNode(message);
    let removeNotificationProgressBar = document.createElement("div");
    removeNotificationProgressBar.classList.add("remove-notification-progress")    
    
    notificationContainer.appendChild(notificationText);
    notificationContainer.appendChild(removeNotificationProgressBar);

    notification.appendChild(notificationContainer);
    notificationsWrapper.appendChild(notification)
    
    let notificationDisplayInterval;
    notificationDisplayInterval = setInterval(() => {
        play();
    }, 100);

    let play = () => {

        if(this.timeElapsed >= this.timeout) {
            notification.remove();
            clearInterval(notificationDisplayInterval);
            return;
        }
        this.timeElapsed += 100;
        notification.querySelector(".remove-notification-progress").style.width = 100 - ((this.timeElapsed / this.timeout) * 100) + "%";
    }

    let pause = function() {
        clearInterval(notificationDisplayInterval);
    }

    notification.addEventListener("mouseenter", pause);
    notification.addEventListener("mouseleave", function() {
        notificationDisplayInterval = setInterval(() => {
            play();
        }, 100);
    });
    play()
}

/**
 * IMAGE DOWNLOAD CARD(S)
 */

let imageCards = [];
function createCard() {
    /**
     * 
     * <div class="compressed-images-card">
                        <div class="image-manipulation-state">
                            <p>Your image has been compressed</p>
                        </div>
                        <div class="image-manipulation-progress-visual">
                            <div class="image-manipulation-progress-bar-icon"><img src="./assets/image-icon.png" alt=""></div>

                            <div class="image-manipulation-progress-bar-wrapper">

                                <div class="manipulated-image-new-value">compressed 2mb</div>
                                <div class="manipulated-image-original-value">Original 4mb</div>

                                <div class="image-manipulation-progress-bar-outer">
                                    <div class="image-manipulation-progress-bar-inner"></div>
                                </div>

                            </div>
                            <div class="download-button-wrapper">
                                <a href="" download="" id="download-btn" class="btn btn--rounded btn--primary-color-lite download-button btn--download">DOWNLOAD</a>
                            </div>
                        </div>
                    </div>

     */

    let cardWrapper = document.createElement("div");
    cardWrapper.classList.add("compressed-images-card"); // outermost wrapper

    let cardInnerWrapper = document.createElement("div");
    cardInnerWrapper.classList.add("image-manipulation-progress-visual"); // inner container

    let progressStateTextWraper = document.createElement("div");
    progressStateTextWraper.classList.add("image-manipulation-state");

    let progressStateTextParagraph = document.createElement("p");
    let progressStateText = document.createTextNode("Your image is being worked on");
    progressStateTextParagraph.appendChild(progressStateText);
    progressStateTextWraper.appendChild(progressStateTextParagraph); // append progress state text

    let cardProgressBarIconWrapper = document.createElement("div");
    cardProgressBarIconWrapper.classList.add("image-manipulation-progress-bar-icon");

    let cardProgressBarIcon = new Image();
    cardProgressBarIcon.setAttribute("src", "./assets/image-icon.png");
    cardProgressBarIconWrapper.appendChild(cardProgressBarIcon); // image icon on the left

    let cardProgressBar = document.createElement("div");
    cardProgressBar.classList.add("image-manipulation-progress-bar-wrapper"); // progress bar & tootips wrapper

    let newAttributesTooltip = document.createElement("div");
    newAttributesTooltip.classList.add("manipulated-image-new-value"); // new images size tooltip
    cardProgressBar.appendChild(newAttributesTooltip);

    let originalAttributesTooltip = document.createElement("div");
    originalAttributesTooltip.classList.add("manipulated-image-original-value"); // original images size tooltip
    cardProgressBar.appendChild(originalAttributesTooltip);
    
    let cardProgressBarOuter = document.createElement("div");
    cardProgressBarOuter.classList.add("image-manipulation-progress-bar-outer");

    let cardProgressBarInnerBar = document.createElement("div");
    cardProgressBarInnerBar.classList.add("image-manipulation-progress-bar-inner");
    cardProgressBarOuter.appendChild(cardProgressBarInnerBar); // append inner animated bar

    cardProgressBar.appendChild(cardProgressBarOuter);

    let downloadButtonWrapper = document.createElement("div");
    downloadButtonWrapper.classList.add("download-button-wrapper");

    let downloadButton = document.createElement("a");
    let downloadButtonTextWrapper = document.createElement("p");
    let downloadButtonText = document.createTextNode("WORKING");
    downloadButtonTextWrapper.appendChild(downloadButtonText);
    downloadButton.setAttribute("href", "#");
    downloadButton.setAttribute("id", "download-btn");
    downloadButton.classList.add("btn");
    downloadButton.classList.add("btn--rounded");
    downloadButton.classList.add("btn--primary-color-lite");
    downloadButton.classList.add("download-button");
    downloadButton.classList.add("btn--working");
    downloadButton.classList.add("btn--disabled");
    downloadButton.appendChild(downloadButtonText);
    downloadButtonWrapper.appendChild(downloadButton); // download button

    // append to inner wrapper
    cardInnerWrapper.appendChild(cardProgressBarIconWrapper);
    cardInnerWrapper.appendChild(cardProgressBar);
    cardInnerWrapper.appendChild(downloadButtonWrapper);

    // append all to the main outer wrapper
    cardWrapper.appendChild(progressStateTextWraper);
    cardWrapper.appendChild(cardInnerWrapper);
    imageCardsContainer.appendChild(cardWrapper); // apppend to div that holds all cards

    imageCards.push(cardWrapper);
}

function diplayDownLoadButton(success, message, downloadLink, originalSize, newSize, index) {
    imageCards[index].querySelector("#download-btn").classList.remove("btn--disabled");
    imageCards[index].querySelector("#download-btn").classList.add("download-ready");

    if(! success) {
        setTimeout(() => {
         
            imageCards[index].querySelector(".image-manipulation-progress-bar-inner").style.animation = "none";
            imageCards[index].querySelector(".image-manipulation-progress-bar-inner").style.width = `${Math.ceil((newSize / originalSize) * 100)}%`;
    
            imageCards[index].querySelector("#download-btn").classList.remove("btn--working");
            imageCards[index].querySelector("#download-btn").classList.add("btn--download");
    
            imageCards[index].querySelector(".image-manipulation-state p").innerText = "Error: " + message;
    
            imageCards[index].querySelector("#download-btn").style.display = "none";
            imageCards[index].querySelector("#download-btn").innerText = "ERROR";
            imageCards[index].querySelector("#download-btn").setAttribute("download", "");        
        }, 1000);
    } else {
        setTimeout(() => {
         
            imageCards[index].querySelector(".image-manipulation-progress-bar-inner").style.animation = "none";
            imageCards[index].querySelector(".image-manipulation-progress-bar-inner").style.width = `${Math.ceil((newSize / originalSize) * 100)}%`;
    
            imageCards[index].querySelector("#download-btn").classList.remove("btn--working");
            imageCards[index].querySelector("#download-btn").classList.add("btn--download");
    
            imageCards[index].querySelector(".image-manipulation-state p").innerText = `Your image is has been ${intent === "compress" ? "compressed" : ""} ${intent === "resize" ? "resized" : ""} ${intent === "crop" ? "cropped" : ""} (Size reduced by ${Math.floor(100 - ((newSize / originalSize) * 100))} %)`;
    
            imageCards[index].querySelector(".manipulated-image-new-value").style.display = "block";
            imageCards[index].querySelector(".manipulated-image-new-value").innerText = `${(newSize / 1000000).toFixed(3)} mb`;
            imageCards[index].querySelector(".manipulated-image-new-value").style.transform = "translateX(-50%)";
    
            imageCards[index].querySelector(".manipulated-image-original-value").style.display = "block";
            imageCards[index].querySelector(".manipulated-image-new-value").style.left = `${Math.ceil((newSize / originalSize) * 100)}%`;
            imageCards[index].querySelector(".manipulated-image-original-value").innerText = `original ${(originalSize / 1000000).toFixed(2)} mb`;
            imageCards[index].querySelector("#download-btn").innerText = "DOWNLOAD";
            imageCards[index].querySelector("#download-btn").setAttribute("download", "");        
            imageCards[index].querySelector("#download-btn").setAttribute("href", window.location.href+""+downloadLink);        
        }, 1000);
    }

    downloadButtonIndex ++;  // increment this so next time we doing this actions on the next card

}

// helper function that converts a nodelist to an Array
function nodeListToArray(nodelist) {
    return Array.from(nodelist);
}


/********* RANGES ***************/
setTooltip(document.getElementsByName("compression_quality")[0], document.getElementById("compression_tooltip"));

document.getElementsByName("compression_quality")[0].addEventListener("input", function() {
    setTooltip(this, document.getElementById("compression_tooltip"))
})
function setTooltip(range, tooltip) {
    // set tooltip text
    tooltip.innerText = range.value + "%";

    let rangeVal = parseInt(range.value);
    let min = range.min ? range.min : 0;
    let max = range.max ? range.max : 24;
    let newVal = Number((rangeVal - min) * 100) / (max - min);
    tooltip.style.left = newVal + "%";

    // progress
    range.style.backgroundSize = (rangeVal - min) * 100 / (max - min) + "% 100%";

    // Tooltip
    tooltip.style.setProperty("left", "calc("+newVal+"% - 13px)");
}
/********* END RANGES ***********/