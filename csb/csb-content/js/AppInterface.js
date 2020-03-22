function AppInterface(csbApp) {
    this.csbApp = csbApp;

    this.icons = null;
    this.tools = [];

    this.mouseDownTime = 0;
    this.isCreatingCirclesFromCenter = false;
    this.isMouseButtonDown = false;
    this.selectedMark = null;
    this.selectedTool = null;
    this.markUnderMouse = null;
    this.selectedButton = null;
    this.craterTypesToDraw = [];
    this.cameraX = 0;
    this.cameraY = 0;
    this.cameraZoom = 1;
    this.savedMousePosition = {x: 0, y: 0};
    this.areMarksVisible = true;
    this.canSubmit = false;
    this.isPaintingToolEnabled = false;
    this.isHelpButtonEngaged = false;
    this.currentTextBubble = {x: 0, y: 0, text: "", delay: 0, showArrow: false, isTemporary: false};
    this.lastHintedMark = {x: 0, y: 0, diameter: 0};
    this.textBubbleQueue = [];
    this.currentStartingExampleImageIndex = 0;

    this.initializeInterface = function () {

        if (this.csbApp.isMobile) {
            $("#app-tutorial-title").html("You need to use a computer for Mappers projects");
            $("#app-tutorial-text").html("Because of the limited screen space available on your mobile devices, it's difficult to see details in photos, so we're currently requiring that people use a computer.");
            $("#app-tutorial-okay-button").html("Close Window");
            this.showExample($("#app-tutorial-overlay"));
            $("#app-header").css("font-size", "1.7em");
            $("#app-tutorial-okay-button").click(function () {
                $("#app-holder").hide();
            });
            return;
        }

        // Set the selected button to be the first button
        this.selectedButton = $(".app-tool-button").eq(0);
        // Set all of the button starting images (selected vs not selected)
        $("#app-login-box-container").hide();
        $("#science-application-inner").hide();

        if (this.csbApp.isUserLoggedIn)
            $("#app-login-popup-button").hide();

        var self = this;

        $("#submit-button").click(function () {
            if ($("#submit-button").attr("class") == "submit-button submit-unpressed") {
                if (self.canSubmit) {
                    if (csbApp.tutorial) {
                        csbApp.tutorial.submit();
                    } else {
                        csbApp.submitImage();
                    }
                }
            }
        });

        $("#continue-without-login").click(function () {
            self.csbApp.displayApp();
        });

        $(".mark-choice").click(function () {
            self.selectedMark.subType = $(this).html();
            self.selectedMark.isBeingMade = false;
            $("#mark-choices").hide();
            $('#controls').show();
            self.csbApp.needToRedrawCanvas = true;
            self.csbApp.isAppOn = true;
        });

        $(".mask").click(function () {
            $("#mark-choices").hide();
            $("#mask").hide();
            self.currentImage.deleteMark(selectedMark);
        });

        self.craterTypesToDraw = [];
        if ($("#my-marks").is(':checked'))
            self.craterTypesToDraw.push('user');
        if ($("#their-marks").is(':checked'))
            self.craterTypesToDraw.push('other_user');
        if ($("#shared-marks").is(':checked'))
            self.craterTypesToDraw.push('shared');

        $(".mark-owner-display-button").click(function () {
            self.needToRedrawCanvas = true;
            self.craterTypesToDraw = [];
            if ($("#my-marks").is(':checked'))
                self.craterTypesToDraw.push('user');
            if ($("#their-marks").is(':checked'))
                self.craterTypesToDraw.push('other_user');
            if ($("#shared-marks").is(':checked'))
                self.craterTypesToDraw.push('shared');
        });

        $("#undo-button").click(function () {
            csbApp.returnToPreviousImage();
            $("#crater").click();
        });


        $('#chat-button').click(function () {
            $('#chat-button-holder').hide();
            $('#tlkio').show();
        });

        $('#navigation').children('li').on("touchend", function () {
            $(this).css("visibility", "visible");
            $(this).css("max-height", "500px");
        });

        if (this.csbApp.applicationName == "simply_craters_verification") {
            $(document).keyup(function (evt) {
                if (evt.keyCode == 17) {
                    $("#show-hide").find('.show').hide();
                    $("#show-hide").find('.hide').show();
                    self.needToRedrawCanvas = true;
                }
            }.call(self)).keydown(function (evt) {
                if (evt.ctrlKey) {
                    $("#show-hide").find('.hide').hide();
                    $("#show-hide").find('.show').show();
                    self.needToRedrawCanvas = true;
                }
            }.call(self));
        }

        $("#app-tutorial-okay-button").click(function () {
            self.hideExample();
            self.csbApp.tutorial.moveToNextTutorialStep();
        });

        $("#x-button").click(function () {
            $("#cq-mapping-tool").hide();
        });

        $("#app-dont-want-to-login-button").click(function () {
            self.hideExample();
        });

        $("#score-display").hide();

        $(".okay-button").click(function () {
            self.hideExample($(".app-example :visible"));
        });

        $("#app-example-up-arrow").click(function () {

        });
        $("#app-example-down-arrow").click(function () {
            // Cycle to next 3 examples for the current tool (default to crater if nothing)
        });

        $("#app-login-button").click(function () {
            self.loginButtonPressed();
        });
        $("#app-login-password").keyup(function (event) {
            if (event.keyCode == 13)
                $("#app-login-button").click();
        });

        $("#app-register-button").click(function () {
            self.registerButtonPressed();
        });
        $("#app-register-password").blur(function () {
            if ($("#app-register-password").val().length < 6)
                $("#app-register-error-text").html("Your password must be at least 6 characters.");
        });
        $("#app-register-password-confirm").keyup(function (event) {
            if (event.keyCode == 13)
                $("#app-register-button").click();
        });

        $("#app-tutorial-login-button").click(function () {
            self.hideExample();
            self.showExample($("#app-login-register-window"));
        });
        $("#app-tutorial-register-button").click(function () {
            self.hideExample();
            self.showExample($("#app-login-register-window"));
        });

        $("#app-example-down-arrow").click(function () {
            self.cycleExamples(3);
        });
        $("#app-example-up-arrow").click(function () {
            self.cycleExamples(-3);
        });
        $("#app-tutorial-keep-practicing").click(function () {
            self.hideExample();
            // self.csbApp.tutorial.moveToNextTutorialStep();
            self.csbApp.tutorial.completeTutorial();
        });

        $("#app-login-popup-button").click(function () {
            self.showExample($("#app-login-register-window"));
        });
        $("#app-tutorial-button").click(function () {
            if (self.csbApp.tutorial == null) {
                self.csbApp.tutorial = new Tutorial(self.csbApp);
                self.csbApp.tutorial.initialize();
                self.csbApp.attemptNewImageRequest();
                $("#app-tutorial-button").html("Skip Tutorial");
            } else {
                self.csbApp.tutorial.completeTutorial();
                $("#app-tutorial-button").html("Take Tutorial");
            }
        });

        $("#text-bubble-blob").bind("transitionend", function () {
            // Only trigger if the text bubble has completely disappeared
            if ($("#text-bubble-blob").css("opacity") == 0) {
                if (self.textBubbleQueue.length == 0) {
                    $("#text-bubble-arrow").hide();
                    $("#text-bubble-blob").hide();
                } else {
                    var nextTextBubble = self.textBubbleQueue.shift();
                    if (nextTextBubble.isTemporary)
                        self.textBubbleQueue.unshift(self.currentTextBubble);
                    self.currentTextBubble = nextTextBubble;
                    self.makeBubbleAppear(self.currentTextBubble);
                }
            }
        });
    };

    this.onAppInitialized = function () {
        this.setupAllButtons();
        this.selectedTool = this.tools[this.selectedButton.attr('id')];
    };

    this.initializeCanvas = function () {
        var self = this;
        if (this.csbApp.acceptsUserInput) {
            if (this.csbApp.isMobile) {
                this.csbApp.canvas.addEventListener('touchmove', function (e) {
                    self.onTouchMove.call(self, e)
                }, false);
                this.csbApp.canvas.addEventListener('touchstart', function (e) {
                    self.onMouseDown.call(self, e)
                }, false);
                this.csbApp.canvas.addEventListener('touchend', function (e) {
                    self.onMouseUp.call(self, e)
                }, false);
                //window.addEventListener('orientationchange', doOnOrientationChange);
            } else {
                this.csbApp.canvas.addEventListener("mousemove", function (e) {
                    self.onMouseMove.call(self, e)
                }, false);
                this.csbApp.canvas.addEventListener("mousedown", function (e) {
                    self.onMouseDown.call(self, e)
                }, false);
                $("body").mouseup(function (e) {
                    self.onMouseUp.call(self, e)
                });
                this.csbApp.canvas.addEventListener("mouseover", function (e) {
                    self.onMouseEnterCanvas.call(self, e)
                }, false);
                this.csbApp.canvas.addEventListener("mouseout", function (e) {
                    self.onMouseLeaveCanvas.call(self, e)
                }, false);
                $(".app_holder_control").css("height", this.csbApp.canvas.height + "px");
            }
            this.csbApp.canvas.addEventListener("dblclick", function (e) {
                self.onDoubleClick.call(self, e)
            }, false);
            this.csbApp.canvas.addEventListener("mousewheel", function (e) {
                self.onMouseWheel.call(self, e)
            }, false);
        }

        $('#submit').attr("class", "submit-unpressed");

        this.icons = {};

        this.icons.featureImage = new Image();
        this.icons.featureImage.src = "/csb-content/images/applications/markers/marker.png";

        this.icons.featureSelectedImage = new Image();
        this.icons.featureSelectedImage.src = "/csb-content/images/applications/markers/marker-selected.png";

        this.icons.rockImage = new Image();
        this.icons.rockImage.src = "/csb-content/images/applications/markers/rock.png";

        this.icons.sunImage = new Image();
        this.icons.sunImage.src = "/csb-content/images/applications/sun.png";
    };


    this.updateAllButtonAppearances = function () {
        for (var key in csbApp.appInterface.tools) {
            if (!csbApp.appInterface.tools.hasOwnProperty(key))
                break;
            var tool = csbApp.appInterface.tools[key];

            if (tool.isToggleable) {
                updateToggleableToolAppearance(tool);
            } else if (tool == this.selectedTool)
                setAppearanceAndExamplesForSelectedTool(tool);
            else
                updateButtonAppearance($("#" + tool.htmlId), false, tool);
        }

        // Set the appropriate images for each button (selected vs not selected)
        function setAppearanceAndExamplesForSelectedTool(tool) {
            // This is the selected button
            updateButtonAppearance($("#" + tool.htmlId), true, tool);
            if (tool != null) {
                $("#tool-description").html(tool.description);

                if (tool.exampleImages != null) {
                    for (var i = 0; i < tool.exampleImages.length; i++) {
                        var image = tool.exampleImages[i];
                        $("#tool-examples").append("<img class='example-image' src='" + image.default + "' " +
                            "onmouseover='this.src=\"" + image.mouseOver + "\"' " +
                            "onmouseout='this.src=\"" + image.default + "\"'>");
                    }
                }
            }
        }

        function updateButtonAppearance(button, isSelected, tool) {
            if (tool.isActive) {
                tool.unmute();
                if (isSelected) {
                    button.addClass('selected');
                    button.removeClass('not-selected');
                } else {
                    button.removeClass('selected');
                    button.addClass('not-selected');
                }
            } else {
                tool.mute();
            }
        }

        function updateToggleableToolAppearance(tool) {
            updateButtonAppearance($("#" + tool.toggleOnHtmlId), tool.isToggledOn, tool);
            updateButtonAppearance($("#" + tool.toggleOffHtmlId), !tool.isToggledOn, tool);
        }
    };

    this.setupAllButtons = function () {
        var self = this;
        var circleTool = new CircleTool({
            name: "circle-button",
            htmlId: "circle-button",
            htmlTitle: "Crater",
            isEjecta: false
        });
        this.tools["circle-button"] = circleTool;

        this.tools["eraser-button"] = new EraserTool({
            name: "eraser-button",
            htmlId: "eraser-button"
        });


        this.tools["crater-chain-button"] = new LinearTool({
            name: "crater-chain-button",
            htmlId: "crater-chain-button"
        });


        this.tools["marks-toggle"] = new ShowHideTool({
            name: "marks-toggle",
            toggleOnHtmlId: "show-marks-toggle",
            toggleOffHtmlId: "hide-marks-toggle"
        });

        this.tools["ejecta-button"] = new CircleTool({
            name: "ejecta-button",
            htmlId: "ejecta-button",
            htmlTitle: "Crater With Ejecta",
            isEjecta: true
        });

        this.tools["boulder-button"] = new LinearTool({
            name: "boulder-button",
            htmlId: "boulder-button",
            type: "boulder"
        });

        this.tools["rock-button"] = new MarkerTool({
            name: "rock-button",
            htmlId: "rock-button"
        });

        this.tools["zoom-toggle"] = new ZoomWindowTool({
            name: "zoom-toggle",
            toggleOnHtmlId: "show-zoom-toggle",
            toggleOffHtmlId: "hide-zoom-toggle"
        });

        this.tools["painting-tool"] = new PaintingTool({
            htmlId: "painting-tool",
            htmlTitle: "Paint on the image"
        });

        // Set the effects of clicking each button
        $(".app-tool-button").click(function () {
            if ($(this).attr('id') == "app-tool-help-button") {
                $(".app-tool-button").css("cursor", "help");
                self.isHelpButtonEngaged = true;
            } else {
                if (self.isHelpButtonEngaged) {
                    $(".app-tool-button").css("cursor", "pointer");
                    // Bring up help window for tool
                    self.showExample($("#app-example-" + $(this).attr('id')));
                    self.isHelpButtonEngaged = false;
                } else {
                    var tool = findToolFromButtonId($(this).attr('id'));
                    if (tool.isActive) {
                        if (tool.isToggleable) {
                            tool.isToggledOn = !tool.isToggledOn;
                            tool.onToggle(csbApp.appInterface);
                            csbApp.needToRedrawCanvas = true;
                        } else {
                            self.selectedButton = $(this);
                            self.selectedTool = tool;
                            self.selectMark(null);
                        }
                        self.updateAllButtonAppearances();
                    }
                    self.displayExamples();
                }
            }
        });

        Object.keys(this.tools).forEach(function (key) {
            if (key in self.csbApp.application.exampleImages)
                self.tools[key].unmute();
            else
                self.tools[key].mute();
        });
        this.tools["marks-toggle"].unmute();

        this.selectTool(circleTool);
        this.selectedButton = $("#circle-button");

        this.updateAllButtonAppearances();

        function findToolFromButtonId(htmlId) {
            if (htmlId.indexOf("toggle") >= 0)
                return csbApp.appInterface.tools[htmlId.slice(htmlId.indexOf("-") + 1)];
            else
                return csbApp.appInterface.tools[htmlId];
        }
    };

    this.displayExamples = function () {
        var imageSet = null;
        if (this.csbApp.application.exampleImages[this.selectedButton.attr("id")].length > 0)
            imageSet = this.csbApp.application.exampleImages[this.selectedButton.attr("id")];
        else
            imageSet = this.csbApp.application.exampleImages["circle-button"];
        for (var i = 0; i < 3; i++) {
            var index = (this.currentStartingExampleImageIndex + i) % imageSet.length;
            $(".example-image").eq(i).attr("src", imageSet[index]);
        }
    };

    this.cycleExamples = function (amount) {
        var imageSet = null;
        if (this.csbApp.application.exampleImages[this.selectedButton.attr("id")].length > 0)
            imageSet = this.csbApp.application.exampleImages[this.selectedButton.attr("id")];
        else
            imageSet = this.csbApp.application.exampleImages["circle-button"];
        // Proper modulo function. Javascript stupid modulo returns a negative number of the input is negative. This fixes it.
        this.currentStartingExampleImageIndex = ((this.currentStartingExampleImageIndex + amount) % imageSet.length + imageSet.length) % imageSet.length;
        this.displayExamples();
    };

    this.loginButtonPressed = function () {
        var data = {"username": $("#app-login-username").val(), "password": $("#app-login-password").val()};
        var self = this;

        $.post("/api/user/login", data, function (response) {
            if (typeof (response["errors"]) != "undefined") {
                $("#app-login-error-text").show();
                // A hack to only show the first error
                var errors = [].concat(response["errors"]);
                var firstError = [].concat(errors[0])[0];
                $("#app-login-error-text").html(firstError);
            } else if (typeof (response["success"] != "undefined")) {
                self.csbApp.requestImage();
                self.hideExample($("#app-login-register-window"));
                self.user = response["user"]["name"];
                self.setupBeingLoggedIn();
            } else {
                console.log(JSON.stringify(response));
            }
        }).fail(function (response) {
            console.log(response.responseText);
        });
    };

    this.registerButtonPressed = function () {
        var data = {
            "name": $("#app-register-username").val(),
            "email": $("#app-register-email").val(),
            "password": $("#app-register-password").val(),
            "password_confirmation": $("#app-register-password-confirm").val()
        };
        var self = this;

        $.post("/api/user/register", data, function (response) {
            if (typeof (response["errors"]) != "undefined") {
                $("#app-register-error-text").show();
                // A hack to only show the first error, since different parts of laravel show errors in different ways
                var error = response["errors"];
                while (typeof (error) !== "object") {
                    error = error[Object.keys(error)[0]];
                }
                var error = typeof (response["errors"]) === "object" ? response["errors"][Object.keys(response["errors"])[0]] : response["errors"];
                $("#app-register-error-text").html(error);
            } else if (typeof (response["success"] != "undefined")) {
                $.post("/api/user/login", data, function (response) {
                    if (typeof (response["errors"]) != "undefined") {
                        $("#app-register-error-text").show();
                        $("#app-register-error-text").html(response["errors"]);
                    } else if (typeof (response["success"] != "undefined")) {
                        self.csbApp.requestImage();
                        self.hideExample($("#app-login-register-window"));
                        $(".app-login-register-button").hide();
                        self.user = response["user"]["name"];
                        self.setupBeingLoggedIn();
                    } else {
                        console.log(JSON.stringify(response));
                    }
                });
            } else {
                console.log(JSON.stringify(response));
            }
        }).fail(function (response) {
            console.log(response);
        });
    };

    this.setupBeingLoggedIn = function () {
        $("#app-login-popup-button").hide();
        this.hideExample($("#app-login-register-window"));
        location.reload();
    };

    this.getGlobalMousePosition = function (e) {
        return {x: e.clientX, y: e.clientY};

    };

    this.getMousePosition = function (e) {
        var globalMousePosition = this.getGlobalMousePosition(e);
        var mainImage = document.getElementById("project_canvas").getBoundingClientRect();
        var mousePosition = {x: globalMousePosition.x - mainImage.left, y: globalMousePosition.y - mainImage.top};


        if (this.csbApp.applicationName == "simply_craters_verification") {
            mousePosition.x = (mousePosition.x - 225) / this.cameraZoom + this.cameraX;
            mousePosition.y = (mousePosition.y - 225) / this.cameraZoom + this.cameraY;
        }
        mousePosition.x = mousePosition.x * csbApp.currentImage.image.width / mainImage.width;
        mousePosition.y = mousePosition.y * csbApp.currentImage.image.height / mainImage.height;
        this.savedMousePosition = mousePosition;
        return mousePosition;
    };

    this.getCanvasPosition = function () {
        var obj = this.csbApp.canvas;
        var position = {x: 0, y: 0};
        while (obj.offsetParent) {
            position.x += obj.offsetLeft;
            position.y += obj.offsetTop;
            obj = obj.offsetParent;
        }
        return position;
    };

    this.getGlobalPositionOfMark = function (mark) {
        var mainImage = document.getElementById("project_canvas").getBoundingClientRect();
        return {
            x: mark.x * mainImage.width / csbApp.currentImage.image.width + this.getCanvasPosition().x,
            y: mark.y * mainImage.height / csbApp.currentImage.image.height + this.getCanvasPosition().y
        };
    };

    this.getTouchPositions = function (e) {
        var x;
        var y;
        var touches = [];
        touches.push(e['targetTouches'][0]);
        if (e['targetTouches'].length >= 2)
            touches.push(e['targetTouches'][1]);

        var resultTouches = [];
        for (var i = 0; i < touches.length; i++) {
            if (touches[i].pageX != undefined && touches[i].pageY != undefined) {
                x = touches[i].pageX;
                y = touches[i].pageY;
            } else {
                x = touches[i].clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
                y = touches[i].clientY + document.body.scrollTop + document.documentElement.scrollTop;
            }
            var parent = this.csbApp.canvas;
            if (parent.offsetParent) {
                do {
                    x -= parent.offsetLeft;
                    y -= parent.offsetTop;
                } while (parent = parent.offsetParent);
            }
            resultTouches.push({x: x, y: y});
        }

        if (resultTouches.length > 0)
            this.savedMousePosition = resultTouches[0];

        return resultTouches;
    };

    this.onTouchMove = function (event) {
        event.preventDefault();
        this.onMouseMove(event);
    };

    this.onMouseMove = function (event) {
        if (!this.csbApp.isAppOn || !this.areMarksVisible || this.csbApp.isGallery) return;

        var mousePosition = this.getMouseOrTouchPosition(event);

        this.markUnderMouse = null;
        $("#marker-type").hide();

        // Trigger the selected tool's action
        if (this.selectedTool != null)
            this.selectedTool.onMouseMove(event, this);

        // Just moving the mouse around, no button down
        if (!this.isMouseButtonDown) {
            var closestMark = csbApp.currentImage.getClosestMarkTo(mousePosition);
            if (closestMark != null) {
                // If a mark is directly under the mouse, it turns into the highlighted markUnderMouse
                if (this.distanceBetween(closestMark, mousePosition) < closestMark.diameter / 2) {
                    this.markUnderMouse = closestMark;
                    this.csbApp.needToRedrawCanvas = true;
                    if (!this.csbApp.isMobile)
                        this.switchCursorTo('pointer');
                    if (this.markUnderMouse.type == "feature") {
                        this.markUnderMouse.displayMarkType(this.getGlobalMousePosition(e));
                    }
                }
            }
            if (this.selectedMark != null) {
                // If the mouse is near the selected mark, display the resize arrow
                if (this.distanceBetween(this.selectedMark, mousePosition) < this.selectedMark.diameter / 2 + this.selectedMark.getResizeArrowSize) {
                    if (!this.csbApp.isMobile)
                        this.switchCursorTo('pointer');
                    this.markUnderMouse = this.selectedMark;
                }
            }
        }
        if (this.markUnderMouse == null && (this.selectedMark == null || (!this.selectedMark.isBeingMade && !this.selectedMark.isBeingMoved && !this.selectedMark.isBeingResized)))
            this.switchCursorTo('default');

        //if (this.csbApp.tutorial)
        //    this.csbApp.tutorial.checkAllMarksAndRenewHint(this.selectedMark);

        this.csbApp.needToRedrawCanvas = true;
    };

    this.onMouseDown = function (event) {
        // Only trigger when the app is interactable
        if (this.csbApp.isAppOn == false || this.csbApp.isGallery)
            return;

        // Make sure it's left click only
        if (this.csbApp.isMobile == false && event.which != 1)
            return;

        // Clear standard canvas clicking operations
        document.onselectstart = function () {
            return false;
        };
        if (window.getSelection)
            window.getSelection().removeAllRanges();
        else if (document.selection)
            document.selection.empty();

        this.isMouseButtonDown = true;
        // Used for double-clicking for most tools. See Tool.onDoubleClick()
        this.mouseDownTime = (new Date()).getTime();

        // Trigger the selected tool's action
        if (this.selectedTool != null)
            this.selectedTool.onMouseDown(event, this);

        //if (this.csbApp.tutorial)
        //    this.csbApp.tutorial.checkAllMarksAndRenewHint(this.selectedMark);

        this.csbApp.needToRedrawCanvas = true;
    };

    this.onDoubleClick = function (event) {
        /*if (this.selectedTool != null)
            this.selectedTool.onDoubleClick(event, this);

        if (this.csbApp.tutorial)
            this.csbApp.tutorial.checkAllMarksAndRenewHint(this.selectedMark);*/
    };

    this.onMouseUp = function (event) {
        if (!this.csbApp.isAppOn || this.csbApp.isGallery)
            return;

        this.isMouseButtonDown = false;
        this.csbApp.needToRedrawCanvas = true;
        if (this.csbApp.isAppOn == false)
            return;

        if (this.selectedMark != null)
            this.selectedMark.lastChangeTime = ((new Date() - this.csbApp.currentImage.startTime)) / 1000;

        // Trigger the selected tool's action
        if (this.selectedTool != null)
            this.selectedTool.onMouseUp(event, this);


        if (this.csbApp.tutorial)
            this.csbApp.tutorial.checkAllMarksAndRenewHint(this.selectedMark);

        this.switchCursorTo('default');
    };

    this.onMouseEnterCanvas = function (event) {
        // Trigger the selected tool's action
        if (this.selectedTool != null)
            this.selectedTool.onMouseEnter(event, this);
    };

    this.onMouseLeaveCanvas = function (event) {
        // Trigger the selected tool's action
        if (this.selectedTool != null)
            this.selectedTool.onMouseLeave(event, this);
    };

    this.onMouseWheel = function (event) {

        return false;
    };

    this.onImageLoad = function () {
        for (var key in this.tools) {
            this.tools[key].onImageLoad(this);
        }
        if (this.csbApp.tutorial) {
            this.csbApp.tutorial.onImageLoad();
        }
    };

    this.setCamera = function (x, y, zoom) {
        this.cameraX = x;
        this.cameraY = y;
        this.cameraZoom = zoom;
    };

    this.distanceBetweenMarkAndMouse = function (mark) {
        var mousePosition = this.getSavedMousePosition();
        if (mark.type == "segment") {
            var closestDistance = 10000000;
            for (var j = 0; j < mark.points.length; j++) {
                var subMark = mark.points[j];
                var distance = distanceBetween(mousePosition, subMark);
                if (distance < closestDistance) {
                    closestDistance = distance;
                }
            }
            return closestDistance;
        } else {
            return this.distanceBetween(mark, mousePosition);
        }
    };

    this.distanceBetween = function (point1, point2) {
        return Math.sqrt((point1.x - point2.x) * (point1.x - point2.x) + (point1.y - point2.y) * (point1.y - point2.y));
    };


    this.switchCursorTo = function (cursor) {
        if (document.body.style.cursor != cursor && !this.csbApp.isMobile)
            document.body.style.cursor = cursor;
    };

    this.popup = function (index) {
        $("#csb-menu1").hide();
        $("#csb-menu2").hide();
        var offset = $("#tools" + index).offset();
        var menu = $("#csb-menu" + index);
        menu.css("top", (offset.top - menu.outerHeight(true)) + "px");
        menu.show();
    };

    this.popdown = function (index) {
        $("#csb-menu" + index).hide();
    };

    this.getHtmlParameter = function (key) {
        var result = null;
        var items = location.search.substr(1).split("&");
        for (var index = 0; index < items.length; index++) {
            var keyValuePair = items[index].split("=");
            if (keyValuePair[0] === key)
                result = decodeURIComponent(keyValuePair[1]);
        }
        return result;
    };

    this.showTutorialText = function (returnData) {
        var scoreData = returnData.data.score;

        var correct = scoreData.correct_marks_count;
        var missed = scoreData.missed_marks_count;
        var incorrectMarks = scoreData.incorrect_marks_count;
        var originalMarks = scoreData.original_marks;
        var percent = scoreData.score * 100;

        for (var i = 0; i < originalMarks.length; i++) {
            var craterData = originalMarks[i];
            this.csbApp.currentImage.marks.push(new Mark("crater", craterData.x, craterData.y, craterData.diameter));
        }

        $("#total-score").html("Score: " + percent.toFixed(0) + "%");

        if (percent >= 75) {
            $("#score-description").html("" +
                "Hey! You've just marked one of our test images. Great job, your markings closely matched that of crater experts. " +
                "Way to go!  Click the continue button to move on to the next image.");
            $("score-tutorial-button").hide();
        } else if (percent >= 50) {
            $("#score-description").html("" +
                "Hey! You've just marked one of our test images. You're doing a good job! <br /><br />" +
                "Remember, your results will be combined with other citizen scientists which provide accurate results.");
            $("score-tutorial-button").show();
        } else {
            $("#score-description").html("" +
                "Be sure to take your time when working through each image. Sometimes it takes a bit of practice to get the hang of it. " +
                "You can always sharpen your crater marking skills by reviewing the tutorial or viewing the help pages. <br /><br />" +
                "Remember, the experts aren’t perfect either, and your results will be combined with that of other citizen scientists which provide accurate results.");
            $("score-tutorial-button").show();
        }
        $("#score-display").fadeIn(1000);
        $("#chat-button-holder").hide();
    };

    /**
     * Used in the gallery to only draw craters made by the user, other users, or shared marks
     * @param owner One of these values: user, other_user, shared
     * @returns {boolean} returns true if the user wants to see marks from this user/group
     */
    this.isDrawingCratersMadeBy = function (owner) {
        return appInterface.craterTypesToDraw.indexOf(owner) != -1;
    };

    this.getMouseOrTouchPosition = function (e) {
        if (this.csbApp.isMobile) {
            var touchPositions = this.getTouchPositions(e);
            if (touchPositions.length > 0)
                return touchPositions[0];
        } else
            return this.getMousePosition(e);
    };

    this.getSavedMousePosition = function () {
        return this.savedMousePosition;
    };

    this.selectMark = function (mark) {
        if (this.selectedMark != null) {
            this.selectedMark.isSelected = false;
        }
        this.selectedMark = mark;
        if (mark != null) {
            mark.isSelected = true;
            if (isSet(mark.correctCrater) && csbApp.tutorial != null) {
                mark.hint = csbApp.tutorial.checkAllMarksAndRenewHint(mark);
            }
        }
        return mark;
    };

    this.selectTool = function (tool) {
        if (tool == this.selectedTool)
            return;

        if (tool.doesToggle) {
            alert("Selecting a toggled button. This should not happen. The button's onClick function should call onToggle.");
            return;
        }

        this.selectedTool = tool;
        this.selectMark(null);
    };

    this.disableSubmit = function () {
        if (this.canSubmit) {
            this.canSubmit = false;
            this.updateSubmitButtonAppearance();
        }
    };

    this.enableSubmit = function () {
        if (!this.canSubmit) {
            this.canSubmit = true;
            this.updateSubmitButtonAppearance();
        }
    };

    this.updateSubmitButtonAppearance = function () {
        if (this.canSubmit) {
            $("#submit-button").removeClass("submit-pressed");
            $("#submit-button").addClass("submit-unpressed");
        } else {
            $("#submit-button").removeClass("submit-unpressed");
            $("#submit-button").addClass("submit-pressed");
        }
    };

    this.displayTextBubble = function (newBubble) {
        if (this.textBubbleQueue.length == 0 && $("#text-bubble-blob").css("opacity") == 0) {
            this.currentTextBubble = newBubble;
            this.makeBubbleAppear(this.currentTextBubble);
        } else
            this.textBubbleQueue.push(newBubble);
    };

    this.makeBubbleAppear = function (textBubble) {
        // Make the bubble re-appear
        $("#text-bubble-blob").show();
        //$(".app-menu").find(".slider").hide();
        if (typeof (textBubble["title"]) != "undefined") {
            $("#text-bubble-blob h4").show();
            $("#text-bubble-blob h4").html(textBubble.title);
        } else
            $("#text-bubble-blob h4").hide();
        $("#text-bubble-blob p").html(textBubble.text);
        var leftOffset = parseInt($("#text-bubble-arrow").css("width"), 10) / -2;
        var topOffset = parseInt($("#text-bubble-arrow").css("height"), 10) / -2;
        var leftBlobOffset = parseInt($("#text-bubble-blob").css("width"), 10) / -2;
        if (textBubble.showArrow) {
            $("#text-bubble-arrow").show();
            $("#text-bubble-okay-button").hide();
        } else {
            $("#text-bubble-arrow").hide();
            $("#text-bubble-okay-button").show();
        }
        $("#text-bubble-blob").show();
        $("#text-bubble-arrow").css("left", textBubble.x + leftOffset + "px");
        $("#text-bubble-arrow").css("top", textBubble.y + topOffset + "px");
        $("#text-bubble-blob").css("left", textBubble.x + leftBlobOffset + "px");
        $("#text-bubble-blob").css("top", (textBubble.y + 20) + topOffset + "px");

        var self = this;
        setTimeout(function () {
            if (textBubble.showArrow)
                $("#text-bubble-arrow").css("opacity", 1);
            $("#text-bubble-blob").css("opacity", 1);
            if (textBubble.isTemporary) {
                setTimeout(function () {
                    self.hideTextBubble();
                }, 3000);
            }
        }, 200 + textBubble.delay);
    };

    this.displayTextBubbleOnElement = function (element, textBubble) {
        var self = this;
        setTimeout(function () {
            var position = self.getAbsolutePositionOfElement(element);
            position.x += parseInt(element.css("width")) / 2;
            position.y += parseInt(element.css("height")) - 3;
            textBubble.x = position.x;
            textBubble.y = position.y;
            self.displayTextBubble(textBubble);
        }, textBubble.delay);
    };

    this.displayTextBubbleOnMark = function (mark, textBubble) {
        var position = this.csbApp.appInterface.getGlobalPositionOfMark(mark);
        position.y += mark.diameter / 2 + 10;
        textBubble.x = position.x;
        textBubble.y = position.y;
        if (!(this.lastHintedMark.diameter == mark.diameter && this.lastHintedMark.x == mark.x && this.lastHintedMark.y == mark.y)) {
            this.displayTextBubble(textBubble);
            this.csbApp.appInterface.hideTextBubble();
            this.lastHintedMark.x = mark.x;
            this.lastHintedMark.y = mark.y;
            this.lastHintedMark.diameter = mark.diameter;
        }
    };

    this.hideTextBubble = function () {
        $("#text-bubble-arrow").css("opacity", 0);
        $("#text-bubble-blob").css("opacity", 0);
    };

    this.getAbsolutePositionOfElement = function (element) {
        element = element.get()[0];
        var position = {x: element.offsetLeft, y: element.offsetTop};
        var parent = element;
        while (parent = parent.offsetParent) {
            position.x += parent.offsetLeft;
            position.y += parent.offsetTop;
        }
        return position;
    };

    this.showExample = function (div) {
        div.show();
        setTimeout(function () {
            div.addClass("app-example-opened");
        }, 30);
        setTimeout(function () {
            $(".app-menu").find(".slider").hide();
        }, 500);
    };

    this.hideExample = function () {
        var div = $(".app-example-opened");
        $(".app-menu").find(".slider").show();
        div.bind("transitionend", function () {
            div.unbind("transitionend");
            div.hide();
        });
        div.removeClass("app-example-opened");
    };
}
