currentTutorialStep = null;

function isSet(value) {
    return (typeof value != "undefined" && value !== null);
}

function distanceBetween(point1, point2) {
    return Math.sqrt((point1.x - point2.x) * (point1.x - point2.x) + (point1.y - point2.y) * (point1.y - point2.y));
}


function CsbApp(canvasElementName, isDebugging) {
    this.canvasName = canvasElementName;
    this.canvas = null;
    this.context = null;
    this.isDebugging = isDebugging;
    this.isAppOn = false;
    this.applicationName = null;
    this.application = null;
    this.currentImage = null;
    this.acceptsUserInput = true;
    this.isUserLoggedIn = false;

    this.needToRedrawCanvas = false;
    this.isGallery = false;
    this.verifiedCraterId = null;
    this.currentImageData = null;
    this.tutorial = null;
    this.tutorialsCompleted = [];
    this.isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    this.imagesDone = 0;
    this.unsubmittedData = [];

    this.appInterface = null;
    this.appImage = null;

    this.attemptCount = 0;

    var self = this;

    this.initialize = function (tutorialsCompleted, isUserLoggedIn) {
        this.isUserLoggedIn = isUserLoggedIn;
        this.isMobile = this.isUserUsingMobileOrTablet();
        this.tutorialsCompleted = tutorialsCompleted;
        this.appInterface = new AppInterface(this);
        this.initializeAjax();
        this.initializeApplications();
        this.maybeUpdateTutorialsCompleted();
        this.canvas = document.getElementById(this.canvasName);
        this.context = this.canvas.getContext("2d");
        this.appInterface.initializeInterface();
        this.appInterface.initializeCanvas();
    };

    this.initializeApplications = function () {
        this.applications = {
            "moon_mappers":
                new MoonMappers(this),
            "mars_mappers":
                new MarsMappers(this),
            "mercury_mappers":
                new MercuryMappers(this),
            /*"vesta_mappers":
                new VestaMappers(this),*/
            "bennu_mappers":
                new BennuMappers(this)
        };
    }

    this.startApp = function (applicationName) {
        this.applicationName = applicationName;
        this.application = this.applications[this.applicationName];
        this.appInterface.onAppInitialized();
        $("#app-title").html("CosmoQuest " + this.application.title);

        if (this.isMobile)
            return;

        this.appInterface.displayExamples();

        if (this.applications[this.applicationName].tutorialSteps != null) {
            if (this.tutorialsCompleted.indexOf(applicationName) < 0) {
                this.tutorial = new Tutorial(this);
                this.tutorial.initialize();
            } else {
                $("#app-tutorial-button").html("Take Tutorial");
            }
        }

        this.attemptNewImageRequest();
        setInterval(function () {
            self.drawCanvas.call(self)
        }, 10);

        $("#science-application-inner").show();
        $("#app-selector").hide();
        $("#back-button").show();
    };

    this.maybeUpdateTutorialsCompleted = function () {
        var cookieTutorials = this.getTutorialsFromCookie();
        var self = this;
        var differences = cookieTutorials.filter(function (tutorial) {
            return self.tutorialsCompleted.indexOf(tutorial) < 0;
        });
        if (differences.length > 0) {
            cookieTutorials.forEach(function (tutorial) {
                if (self.tutorialsCompleted.indexOf(tutorial) < 0)
                    self.tutorialsCompleted.push(tutorial);
            });
            this.setTutorialsCookie();
            postData("/finish_tutorial", {tutorials_complete: this.tutorialsCompleted}).then( returnData => {
                console.log(returnData);
            }).catch( err => {
                console.log("Fail: " + err);
            });
        }
    };

    this.initializeAjax = function () {
        /* TODO not sure this is necessary...
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        */
    };

    this.requestImage = function (imageId) {
        if (this.attemptCount > 5) {
            this.attemptCount = 0;
            this.updateDisplayedImage(null);
            return;
        }

        var fileToPostTo = "/api/image/next/" + this.applicationName;

        if (isSet(imageId)) {
            fileToPostTo = "/api/image/id/" + imageId;
        }

        var self = this;

        getData(fileToPostTo).then( returnData => {
            //if (self.isDebugging)
            console.log(JSON.stringify(returnData));

            if (returnData.error == "out_of_images") {
                self.updateDisplayedImage(null);
                return;
            }

            self.lastImageData = self.currentImageData;
            self.currentImageData = returnData;
            self.loadImageData(returnData);
        }).catch( err => {
            console.log("Fail: " + err);
            this.attemptCount++;
            self.requestImage(imageId);
        });
    };

    this.loadImageData = function (data) {
        this.appImage = new AppImage(data, this.applicationName, this);

        this.updateDisplayedImage(this.appImage);
        this.isAppOn = true;
        if (this.tutorial)
            this.appInterface.disableSubmit();
        else
            this.appInterface.enableSubmit();
    };

    this.submitImage = function (existingData) {
        if (this.tutorial) {
            this.tutorial.submit();
            return;
        }

        var dataToSubmit = [];
        if (existingData) {
            if (this.unsubmittedData.length == 0)
                return;
            dataToSubmit = this.unsubmittedData.pop();
        } else {
            var submittableMarks = [];
            for (var i = 0; i < this.currentImage.marks.length; i++) {
                var mark = this.currentImage.marks[i];
                submittableMarks.push(mark.getSubmitData());
            }

            this.currentImage.endTime = new Date();
            dataToSubmit = {
                image_id: this.currentImage.id,
                submit_time: (this.currentImage.endTime - this.currentImage.startTime) / 1000,
                application_name: this.applicationName,
                marks: JSON.stringify(submittableMarks),
                user_data: this.currentImage.userData
            };
        }
        if (isDebugging)
            console.log(JSON.stringify(dataToSubmit));

        var self = this;

        self.imagesDone += 1;
        if (this.isUserLoggedIn) {
            postData("/app/" + this.applicationName + "/submit_data", dataToSubmit).then( returnText => {
                //if(isDebugging)
                console.log(returnText);

                if (returnText != "") {
                    var returnData = returnText;

                    if (isSet(returnData.data) && isSet(returnData.data['score']) && this.tutorial.isSubmitted == false) {
                        self.appInterface.showTutorialText(returnData);
                        var scientistMarks = returnData.data['score']['original_marks'];
                        for (var i = 0; i < scientistMarks.length; i++) {
                            var mark = scientistMarks[i];
                            var scientistMark = new Mark(mark.type, mark.x, mark.y, mark.diameter);
                            scientistMark.owner = "premarked_machine";
                            self.currentImage.marks.push(scientistMark);
                        }

                        self.appInterface.disableSubmit();
                        self.needToRedrawCanvas = true;
                        self.tutorial.isSubmitted = true;
                    } else
                        self.attemptNewImageRequest();
                } else
                    self.attemptNewImageRequest();

                $(".undo-button").attr("class", "undo-button undo-unpressed");
            }).catch( err => {
                console.error(err);
            })

            var data = {
                "app": this.applicationName,
                "count": 1
            };

            // Post to our api, puts request on Queue
            postData("/api/scistarter", data).then( data => {
                console.log(data);
            }).catch( err => {
                console.error(err);
            });

        } else {
            //this.unsubmittedData.push(dataToSubmit);
            this.attemptNewImageRequest();
        }
    };

    this.loginUser = function () {
        var self = this;
        $.post('/auth/ajax_login', {
            'username-or-email': $("#app-login-username-or-email").val(),
            'password': $("#app-login-password").val()
        }, function (result) {
            if (isSet(result['error'])) {
                var error = result['error'];
                if (error == "incorrect_username_or_email")
                    $("#app-login-error-message").html("That username or email doesn't exist");
                else if (error == "reset_password")
                    window.location = "/password/reset";
                else if (error == "incorrect_password")
                    $("#app-login-error-message").html("Incorrect password");
            } else if (isSet(result['user'])) {
                self.isUserLoggedIn = true;
                // Handle tutorial completion here
                while (self.unsubmittedData.length > 0)
                    self.submitImage(true);
                self.appInterface.setupBeingLoggedIn();
                $.post("/finish_tutorial", {tutorials_complete: this.csbApp.tutorialsCompleted}, function (returnData) {
                    console.log(returnData);
                });
            }
        }).fail(function (event) {
            console.log("Fail: " + JSON.stringify(event));
        });
    };

    this.updateDisplayedImage = function (newImage) {
        var self = this;
        var canvas = this.canvas;
        var outOfImagesSrc = "/csb-content/images/applications/out-of-images.png";

        if (newImage == null) {
            var data = {image: {file_location: outOfImagesSrc, sun_angle: null}};
            this.currentImage = new AppImage(data, self.applicationName, this);
        } else
            this.currentImage = newImage;

        var onLoadFunction = function () {
            self.currentImage.startTime = new Date();
            canvas.width = self.currentImage.image.width;
            canvas.height = self.currentImage.image.height;
            $('#' + self.canvasName).css("background-size", "100%");
            $('#' + self.canvasName).css("background-image", "url(" + self.currentImage.fileLocation + ")");
            self.needToRedrawCanvas = true;
            self.appInterface.onImageLoad();
        };

        if (this.currentImage.image.complete)
            onLoadFunction();
        else
            this.currentImage.image.onload = onLoadFunction;
    };

    this.attemptNewImageRequest = function () {
        this.appInterface.mouseDown = false;
        if (this.tutorial) {
            //this.tutorial.isSubmitted = false;

            //var tutorialStep = this.tutorial.getCurrentTutorialStep();
            //this.loadImageData({image: {file_location: tutorialStep.image, id: this.tutorial.currentStepIndex}});
        } else {
            this.requestImage();
            $("#tutorial-steps-complete").hide();
        }

        //if(isMobile)
        //doOnOrientationChange();
    };

    this.returnToPreviousImage = function () {
        if (lastImageData != null && lastImageData != currentImageData) {
            this.loadImageData(lastImageData);
            this.currentImageData = lastImageData;
            $(".undo-button").attr("class", "undo-button undo-pressed");
            this.appInterface.mouseDown = false;
            this.appInterface.selectedMark = null;
        }
    };


    this.drawCanvas = function () {
        if (this.currentImage == null || this.currentImage.image == null)
            return;

        if (this.needToRedrawCanvas && this.currentImage.image.complete) {
            this.needToRedrawCanvas = false;
            this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);

            this.context.scale(this.appInterface.cameraZoom, this.appInterface.cameraZoom);
            //this.context.drawImage(this.currentImage.image, 0, 0, this.canvas.width, this.canvas.height);
            if (this.appInterface.areMarksVisible) {
                for (var i = 0; i < this.currentImage.marks.length; i++)
                    this.drawMark(this.currentImage.marks[i], this.context, 1);
            }
            //this.currentImage.drawSun(this.canvas, this.context);
            this.context.scale(1 / this.appInterface.cameraZoom, 1 / this.appInterface.cameraZoom);

            if (this.tutorial != null) {
                this.tutorial.drawFakeCursor(this.context);
            }
        }

        if (this.appInterface.tools['zoom-toggle'].isToggledOn)
            this.drawZoomCanvas();

        if (this.currentImage.image.complete && this.appInterface.selectedButton.attr("id") == "rock")
            this.drawZoomCanvas();
    };

    this.drawZoomCanvas = function () {
        var zoomCanvas = document.getElementById("zoom-canvas");
        var zoomContext = zoomCanvas.getContext("2d");

        var mousePosition = this.appInterface.getSavedMousePosition();
        var canvasPosition = this.appInterface.getCanvasPosition();
        $("#zoom-canvas").css("top", canvasPosition.y + "px");
        if (mousePosition.y <= zoomCanvas.height + 50 && mousePosition.x >= this.canvas.width - zoomCanvas.width - 50) {
            $("#zoom-canvas").css("left", canvasPosition.x + "px");
        } else {
            $("#zoom-canvas").css("left", (canvasPosition.x + this.canvas.width - zoomCanvas.width / 2) - 2 + "px");
        }

        var zoomLevel = 3;
        var totalZoom = this.canvas.width / zoomCanvas.width * zoomLevel;
        var center = {
            x: Math.max(zoomCanvas.width / 2 / zoomLevel, Math.min(this.canvas.width - zoomCanvas.width / 2 / zoomLevel, mousePosition.x)),
            y: Math.max(zoomCanvas.height / 2 / zoomLevel, Math.min(this.canvas.height - zoomCanvas.height / 2 / zoomLevel, mousePosition.y))
        };


        zoomContext.translate(-center.x * zoomLevel + zoomCanvas.width / 2, -center.y * zoomLevel + zoomCanvas.width / 2);
        zoomContext.scale(totalZoom, totalZoom);
        zoomContext.globalAlpha = 1;
        zoomContext.drawImage(this.currentImage.image, 0, 0, zoomCanvas.width, zoomCanvas.height);
        if (this.appInterface.areMarksVisible) {
            for (var i = 0; i < this.currentImage.marks.length; i++)
                this.drawMark(this.currentImage.marks[i], zoomContext, zoomLevel / totalZoom);
        }
        zoomContext.setTransform(1, 0, 0, 1, 0, 0);
        // Draw crosshair
    };

    this.drawMark = function (mark, context, zoomLevel) {
        if (this.isGallery)
            if (!mark.isShared && this.appInterface.isDrawingCratersMadeBy(mark.owner) || this.appInterface.isDrawingCratersMadeBy('shared') && mark.isShared)
                return;

        mark.draw(context, this.appInterface, zoomLevel);
    };


    this.getCookie = function (name) {
        name = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
        }
        return "";
    };

    this.setTutorialsCookie = function () {
        var date = new Date();
        date.setTime(date.getTime() + 30 * 24 * 60 * 60 * 1000);
        document.cookie = "tutorials_complete=" + this.tutorialsCompleted.join(',') + "; expires=" + date.toUTCString() + "; path=/";
    };

    this.getTutorialsFromCookie = function () {
        var cookies = document.cookie.split(';');
        cookies = cookies.filter(function (cookie) {
            return cookie.indexOf("tutorials_complete") >= 0;
        });
        if (cookies.length == 0)
            return [];

        var cookie = cookies[0];
        var tutorialString = cookie.substring(cookie.indexOf('=') + 1);
        var tutorials = tutorialString.split(',');
        return tutorials;
    };

    this.isUserUsingMobileOrTablet = function () {
        var check = false;
        (function (a) {
            if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true;
        })(navigator.userAgent || navigator.vendor || window.opera);
        return check;
    };
}
