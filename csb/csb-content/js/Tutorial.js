function Tutorial(csbApp) {
    this.csbApp = csbApp;
    this.currentStepIndex = -1;
    this.correctMarks = [];
    this.currentHint = null;
    this.desiredMark = null;
    this.markBeingMade = null;
    this.fakeCursor = null;
    this.markBeingMadeStep = null;
    this.mouseUpImage = null;
    this.mouseDownImage = null;
    this.autoCraterCreationLoop = -1;
    this.isPausing = false;
    this.autoCraterCreationQueue = [];

    this.initialize = function () {
        this.moveToNextTutorialStep();
        $("#tutorial-steps-complete").show();
        this.csbApp.isAppOn = true;
        this.mouseUpImage = document.createElement('img');
        this.mouseUpImage.src = "/csb-content/images/applications/tutorials/cursor-up.png";
        this.mouseDownImage = document.createElement('img');
        this.mouseDownImage.src = "/csb-content/images/applications/tutorials/cursor-down.png";

        var self = this;
        $("#text-bubble-okay-button").click(function () {
            $("#invisible-app-cover").hide();
            self.moveToNextTutorialStep();
        });
    };

    this.getCurrentTutorialStep = function () {
        return this.csbApp.applications[this.csbApp.applicationName].tutorialSteps[this.currentStepIndex];
    };

    this.submit = function () {
        var success = this.checkAllMarksAndRenewHint(null);
        if (success) {
            csbApp.canSubmit = false;
            this.csbApp.appInterface.selectedMark = null;
            this.csbApp.appInterface.updateAllButtonAppearances();
            this.moveToNextImage();
        }
    };

    this.moveToNextImage = function () {
        this.moveToNextTutorialStep();
    };

    this.completeTutorial = function () {
        $("#tutorial-steps-complete").hide();
        $("#invisible-app-cover").hide();
        this.csbApp.tutorial = null;
        this.csbApp.requestImage();
        $("#text-bubble-arrow").hide();
        $("#text-bubble-blob").hide();
        $("#app-tutorial-button").html("Take Tutorial");
        this.csbApp.setTutorialsCookie();

        if (this.csbApp.tutorialsCompleted.indexOf(this.csbApp.applicationName) >= 0)
            return;

        this.csbApp.tutorialsCompleted.push(this.csbApp.applicationName);

        postData("/finish_tutorial", {tutorials_complete: this.csbApp.tutorialsCompleted}).then( returnData => {
            console.log(returnData);
        });
    };

    this.onImageLoad = function () {
        var step = this.getCurrentTutorialStep();
        this.muteTools();
        if (!step.isLiveHelpActive)
            this.csbApp.appInterface.enableSubmit();
        this.displayText(step.startingHelpText);
        this.checkAllMarksAndRenewHint(null);
    };

    this.updateStatusOfAllMarks = function (marks, correctMarks) {
        for (var i = 0; i < correctMarks.length; i++) {
            correctMarks[i].status = "missed";
        }

        for (i = 0; i < marks.length; i++) {
            if (marks[i].type == "crater")
                this.checkCrater(marks[i], correctMarks, false);
            else if (marks[i].type == "boulder")
                this.checkSegment(marks[i], correctMarks, false);
        }
        // Look for any missed craters
        //var missedCraters = correctCraters.filter(function(x) {return x.status == "missed"});
        //return missedCraters;
    };

    this.checkSegment = function (segment, correctMarks, closeSegmentsOnly) {
        var closestMatch = this.findBestMatchingMark(segment, correctMarks, closeSegmentsOnly);
        if (closestMatch == null) {
            segment.status = "way_too_far";
            return segment;
        }
        segment.score = this.getAdjacencyBetweenMarks(segment, closestMatch, false);
        if (segment.score <= -.5)
            segment.status = "way_too_far";
        else if (segment.score <= .3)
            segment.status = "not_great";
        else
            segment.status = "correct";

        if (segment.status == "correct") {
            closestMatch.status = "found";
        }
        return segment;
    };

    this.checkCrater = function (crater, correctMarks, closeCratersOnly) {
        var closestMatch = this.findBestMatchingMark(crater, correctMarks, closeCratersOnly);
        if (closestMatch == null) {
            crater.status = "way_too_far";
            return crater;
        }

        crater.score = this.getAdjacencyBetweenMarks(crater, closestMatch, false);
        var diameterScore = this.getDiameterAdjacency(crater, closestMatch, false);
        var distanceScore = this.getDistanceAdjacency(crater, closestMatch, false, true);
        setCraterStatusBasedOnScore(crater, diameterScore, distanceScore);

        crater.correctCrater = closestMatch;
        if (crater.status == "correct") {
            closestMatch.status = "found";
        }
        return crater;

        function setCraterStatusBasedOnScore(crater, diameterScore, distanceScore) {
            var mousePosition = csbApp.appInterface.getSavedMousePosition();
            if (crater.isBeingMade) {
                var score = findPercentThatCircleOccupiesCircle(closestMatch, crater);
                if (score >= .6 && distanceScore >= .6 && diameterScore >= .5)
                    crater.status = "made_correctly";
                else if (score >= .5 && score <= .8 && pointDistance(mousePosition, closestMatch) > pointDistance(crater, closestMatch) && diameterScore >= .6)
                    crater.status = "too_far";
                else if (score >= .5)
                    crater.status = "getting_there";
                else if (score > .25) {
                    if (diameterScore <= .5 && crater.diameter > closestMatch.diameter)
                        crater.status = "too_large";
                    else
                        crater.status = "not_great";
                } else
                    crater.status = "bad";

            } else {
                if (distanceScore < -.3 || diameterScore <= 0) {
                    crater.status = "way_too_far";
                } else if (distanceScore <= 0.6) {
                    crater.status = "too_far";
                } else if (diameterScore <= 0.5) {
                    if (crater.diameter < closestMatch.diameter)
                        crater.status = "too_small";
                    else
                        crater.status = "too_large";
                } else {
                    crater.status = "correct";
                }
            }

            function pointDistance(p1, p2) {
                return Math.sqrt((p1.x - p2.x) * (p1.x - p2.x) + (p1.y - p2.y) * (p1.y - p2.y));
            }
        }

        function findPercentThatCircleOccupiesCircle(circle1, circle2) {
            var dx = circle2.x - circle1.x;
            var dy = circle2.y - circle1.y;
            var r1 = circle1.diameter / 2;
            var r2 = circle2.diameter / 2;
            var d = Math.sqrt(dx * dx + dy * dy);

            // Circle2 is completely inside circle1
            if (r1 > r2 + d)
                return 1;
            //
            else if (r2 > r1 + d)
                return (r1 * r1) / (r2 * r2);
            else if (r2 + r1 < d)
                return 0;

            var dCentroid = (r1 * r1 - r2 * r2 + d * d) / (2 * d);
            var h = Math.sqrt(r1 * r1 - dCentroid * dCentroid);
            var centroid = {
                x: circle1.x + dCentroid * (circle2.x - circle1.x) / d,
                y: circle1.y + dCentroid * (circle2.y - circle1.y) / d
            };
            var p3 = {x: centroid.x + h * (circle2.y - circle1.y) / d, y: centroid.y - h * (circle2.x - circle1.x) / d};
            var p4 = {x: centroid.x - h * (circle2.y - circle1.y) / d, y: centroid.y + h * (circle2.x - circle1.x) / d};

            var angle1 = Math.acos((2 * r1 * r1 - 4 * h * h) / (2 * r1 * r1));
            var angle2 = Math.acos((2 * r2 * r2 - 4 * h * h) / (2 * r2 * r2));
            var slice1 = angle1 * r1 * r1 / 2;
            var slice2 = angle2 * r2 * r2 / 2;

            var overlapArea = (slice1 + slice2 - r1 * r1 * Math.sin(angle1) / 2 - r2 * r2 * Math.sin(angle2) / 2);

            if (d < r1) {
                var t1d1 = Math.sqrt((p3.x - circle1.x) * (p3.x - circle1.x) + (p3.y - circle1.y) * (p3.y - circle1.y));
                var t1d2 = Math.sqrt((p3.x - circle2.x) * (p3.x - circle2.x) + (p3.y - circle2.y) * (p3.y - circle2.y));
                var t2d1 = Math.sqrt((p4.x - circle1.x) * (p4.x - circle1.x) + (p4.y - circle1.y) * (p4.y - circle1.y));
                var t2d2 = Math.sqrt((p4.x - circle2.x) * (p4.x - circle2.x) + (p4.y - circle2.y) * (p4.y - circle2.y));
                var z1 = (t1d1 + t1d2 + d) / 2;
                var z2 = (t2d1 + t2d2 + d) / 2;
                var triangleArea1 = Math.sqrt(z1 * (z1 - t1d1) * (z1 - t1d2) * (z1 - d));
                var triangleArea2 = Math.sqrt(z2 * (z2 - t2d1) * (z2 - t2d2) * (z2 - d));
                if (r1 < r2)
                    overlapArea = r1 * r1 * Math.PI - (slice1 - slice2 + triangleArea1 + triangleArea2);
                else
                    overlapArea = r2 * r2 * Math.PI - (slice2 - slice1 + triangleArea1 + triangleArea2);
            }
            var overlapPercent = overlapArea / (r2 * r2 * Math.PI);
            return overlapPercent;
        }
    };

    this.getAdjacencyBetweenMarks = function (mark1, mark2, clampZeroToOne) {
        if (mark1.type != mark2.type)
            if (clampZeroToOne)
                return 0;
            else
                return -10000;

        var type = mark1.type;
        // Find the distance between the two marks

        if (type == 'crater') {
            var diameterScore = this.getDiameterAdjacency(mark1, mark2, clampZeroToOne);
            var distanceScore = this.getDistanceAdjacency(mark1, mark2, clampZeroToOne, true);
            var totalScore = 1 - (1 - diameterScore) - (1 - distanceScore);
            //totalScore = this.findPercentThatCircleOccupiesCircle(mark2, mark1);
            if (clampZeroToOne)
                totalScore = Math.max(0, Math.min(1, totalScore));

            return totalScore;
        } else if (type == 'boulder') {
            if (mark1.points.length < 2 || mark2.points.length < 2)
                return 0;
            var variance = mark1.length() / 2 + .0001;
            var center1 = {
                x: (mark1.points[0].x + mark1.points[1].x) / 2,
                y: (mark1.points[0].y + mark1.points[1].y) / 2
            };
            var center2 = {
                x: (mark2.points[0].x + mark2.points[1].x) / 2,
                y: (mark2.points[0].y + mark2.points[1].y) / 2
            };

            /*
            var closestPointIndex0 = 0;
            var closestPointIndex1 = 1;
            if (distanceBetween(mark2.points[1], mark1.points[0]) < distanceBetween(mark2.points[0], mark1.points[0])) {
                closestPointIndex0 = 1;
                closestPointIndex1 = 0;
            }

            var d1 = distanceBetween(mark1.points[0], mark2.points[closestPointIndex0]);
            var d2 = distanceBetween(mark1.points[1], mark2.points[closestPointIndex1]);

            var score = 1 - (d1 / variance + d2 / variance);*/
            var distance = distanceBetween(center1, center2);
            var score = 1 - distance / variance;
            if (clampZeroToOne)
                score = Math.max(0, Math.min(1, score));
            return score;
        } else
            return 0;

        function distanceBetween(p1, p2) {
            return Math.sqrt((p1.x - p2.x) * (p1.x - p2.x) + (p1.y - p2.y) * (p1.y - p2.y));
        }
    };

    this.getDiameterAdjacency = function (mark1, mark2, clampZeroToOne) {
        // multiplier of 2.0 basically makes 1/2 radius or x2 radius a score of zero
        var reductionMultiplier = 2.0;
        var smallerDiameter = Math.min(mark1.diameter, mark2.diameter);
        var biggerDiameter = Math.max(mark1.diameter, mark2.diameter);
        var score = 1 - (1 - smallerDiameter / biggerDiameter) * reductionMultiplier;
        if (clampZeroToOne)
            score = Math.max(0, min(1, score));
        return score;
    };

    this.getDistanceAdjacency = function (mark1, mark2, clampZeroToOne, oneSided) {
        // multiplier of 2.0 basically makes 1 radius away a score of zero
        var score;
        var reductionMultiplier = 2.0;
        var dx = mark2.x - mark1.x;
        var dy = mark2.y - mark1.y;
        var distance = Math.sqrt(dx * dx + dy * dy);
        if (oneSided) {
            var averageDiameter = (mark1.diameter + mark2.diameter) / 2;
            score = 1 - (distance / averageDiameter * reductionMultiplier);
        } else
            score = 1 - (distance / mark1.diameter * reductionMultiplier);

        if (clampZeroToOne)
            score = Math.max(0, min(1, score));
        return score;
    };

    this.findBestMatchingMark = function (mark, potentialMatches, closeMarksOnly) {
        var highestScore = -1000;
        if (closeMarksOnly)
            highestScore = 0;
        var bestMatchingMark = null;
        for (var i = 0; i < potentialMatches.length; i++) {
            var potentialMatch = potentialMatches[i];
            var score = this.getAdjacencyBetweenMarks(mark, potentialMatch, false);
            if (score > highestScore) {
                highestScore = score;
                bestMatchingMark = potentialMatch;
            }
        }
        return bestMatchingMark;
    };

    this.checkAllMarksAndRenewHint = function (mark) {
        var correctMarks = this.correctMarks;
        var currentTutorialStep = this.getCurrentTutorialStep();
        if (currentTutorialStep && currentTutorialStep.hasOwnProperty('mark-types') 
            && typeof currentTutorialStep["mark-types"] != "undefined"
        ) {
            correctMarks = this.getMarksOfType(this.getCurrentTutorialStep()["mark-types"]);
        }

        if (typeof (correctMarks) == "undefined")
            return;

        var userMarks = this.csbApp.currentImage.marks;
        this.updateStatusOfAllMarks(userMarks, correctMarks);

        var possibleHints = {
            "made_correctly": "Looks good to me!",
            "getting_there": "Keep Going.",
            "not_great": "Close, but not quite right.",
            "bad": "I don't think this is a crater.",
            "too_small": "This one's a little too small.<br />Try enlarging it by dragging the edge of the circle.",
            "too_large": "This one's a little too big.<br />Try shrinking it by dragging the edge of the circle.",
            "too_far": "Close, but you aren't centered right. Drag the circle to move it.",
            "way_too_far": "I don't think this is correct. Erase it and try again.",
            "missed": "You missed this one. Try marking it now.",
            "correct": "Looks good to me!",
            "done": "Great job! Click on the 'Image Done' button to go to the next image!"
        };

        if (mark)
            mark.hint = possibleHints[mark.status];
        var missedCraters = correctMarks.filter(function (x) {
            return x.status == "missed"
        });
        var wrongCraters = userMarks.filter(function (x) {
            return x.status != "correct"
        });
        var success = (correctMarks.length - missedCraters.length - wrongCraters.length >= this.csbApp.currentImage.requiredNumberOfTutorialCraters);


        if (success) {
            if (userMarks.length == 0)
                return;
            if (this.getCurrentTutorialStep()["type"] == "find-marks")
                this.moveToNextTutorialStep();
            this.csbApp.appInterface.enableSubmit();
        } else if (this.getCurrentTutorialStep()["type"] == "mark-ejecta" && userMarks.length > 0) {
            this.csbApp.appInterface.disableSubmit();
            var mark = userMarks[userMarks.length - 1];
            if (mark.status == "correct") {
                if (mark.correctCrater.isEjecta)
                    this.moveToNextTutorialStep();
                else {
                    this.csbApp.appInterface.displayTextBubbleOnMark(mark, {
                        text: "This isn't the <b>ejecta</b> crater. Erase it and try again!",
                        delay: 0,
                        isTemporary: true,
                        showArrow: true
                    });
                    this.currentHint = {"crater": mark, "hint": mark.hint};
                }
            } else {
                if (!mark.correctCrater.isEjecta && mark.score > 0)
                    this.csbApp.appInterface.displayTextBubbleOnMark(mark, {
                        text: "This isn't the <b>ejecta</b> crater. Erase it and try again!",
                        delay: 0,
                        isTemporary: true,
                        showArrow: true
                    });
                else {
                    this.csbApp.appInterface.displayTextBubbleOnMark(mark, {
                        text: mark.hint,
                        delay: 0,
                        isTemporary: true,
                        showArrow: true
                    });
                    this.currentHint = {"crater": mark, "hint": mark.hint};
                }
            }
        } else {
            this.csbApp.appInterface.disableSubmit();
            if (userMarks.length == 0)
                return;
            if (this.getCurrentTutorialStep()["type"] == "find-marks") {
                if (mark && mark.status != "correct") {
                    if (this.currentHint != {"crater": mark, "hint": mark.hint}) {
                        this.csbApp.appInterface.displayTextBubbleOnMark(mark, {
                            text: mark.hint,
                            delay: 0,
                            isTemporary: true,
                            showArrow: true
                        });
                        this.currentHint = {"crater": mark, "hint": mark.hint};
                    }
                }
                if (missedCraters.length == 0 && wrongCraters.length == 0 && userMarks.length == correctMarks.length)
                    this.moveToNextTutorialStep();
                /*else if (this.currentHint != this.getCurrentTutorialStep()["text"]) {
                    this.csbApp.appInterface.displayTextBubbleOnElement($("#project_canvas"), {text: this.getCurrentTutorialStep()["text"], delay: 0, isTemporary: false, showArrow: true});
                    this.currentHint = this.getCurrentTutorialStep()["text"];
                }*/
            }
        }
        return success;
    };

    this.getMarksOfType = function (types) {
        var marksOfType = [];
        this.correctMarks.forEach(function (mark) {
            if (types.indexOf(mark.type) >= 0)
                marksOfType.push(mark);
        });
        return marksOfType;
    };

    this.displayText = function (text) {
        //$("#app-text-box p").html(text);
    };

    this.muteTools = function () {
        if (typeof (this.getCurrentTutorialStep()["active-tools"]) == "undefined")
            return;

        var activeTools = this.getCurrentTutorialStep()["active-tools"];
        var tools = this.csbApp.appInterface.tools;
        for (var key in tools) {
            if (tools.hasOwnProperty(key)) {
                var tool = tools[key];
                if (activeTools.indexOf(tool.name) >= 0)
                    tool.unmute();
                else
                    tool.mute();
            }
        }
        this.csbApp.appInterface.updateAllButtonAppearances();
    };

    this.moveToNextTutorialStep = function () {
        this.csbApp.appInterface.hideTextBubble();
        var self = this;
        var appInterface = this.csbApp.appInterface;
        var step = this.getCurrentTutorialStep();
        if (step != null) {
            if (step["type"] == "click") {
                $(step["jquery-id"]).removeClass("tutorial-highlight");
            }
        }

        this.currentStepIndex++;
        step = this.getCurrentTutorialStep();

        if (step == null) {
            appInterface.hideTextBubble();
            if (this.currentStepIndex >= this.csbApp.applications[this.csbApp.applicationName].tutorialSteps.length)
                this.completeTutorial();
        } else {
            var delay = 0;
            if (typeof (step["delay"]) != "undefined")
                delay = step["delay"];

            if (step["type"] == "set-image") {
                var appImage = new AppImage({
                    image: {
                        file_location: step["image-location"],
                        id: 0
                    }
                }, this.applicationName, this);

                this.csbApp.appImage = appImage;
                this.csbApp.updateDisplayedImage(appImage);
                this.isAppOn = true;

                if (typeof (step["required-score"]) != "undefined")
                    appImage.requiredNumberOfTutorialCraters = step["required-score"];
                if (typeof (step["correct-marks"]) != "undefined")
                    this.correctMarks = step["correct-marks"];

                if (typeof (step["existing-marks"]) != "undefined") {
                    // Create each crater
                    var marks = step["existing-marks"];
                    for (var i = 0; i < marks.length; i++) {
                        this.csbApp.currentImage.marks.push(marks[i]);
                    }
                }
                this.csbApp.needToRedrawCanvas = true;
                self.moveToNextTutorialStep();
            } else if (step["type"] == "dropdown") {
                // Change tutorial overlay text to be useful
                $("#app-tutorial-title").html(step["title"]);
                $("#app-tutorial-text").html(step["text"]);
                if (typeof (step["is-end"]) == "undefined")
                    step["is-end"] = false;

                if (step["is-end"]) {
                    if (self.csbApp.isUserLoggedIn) {
                        $("#app-tutorial-okay-button").show();
                        $("#app-tutorial-register-button").hide();
                        $("#app-tutorial-login-button").hide();
                        $("#app-tutorial-keep-practicing").hide();
                    } else {
                        $("#app-tutorial-okay-button").hide();
                        $("#app-tutorial-register-button").show();
                        $("#app-tutorial-login-button").show();
                        $("#app-tutorial-keep-practicing").show();
                    }
                    appInterface.hideTextBubble();
                } else {
                    $("#app-tutorial-okay-button").show();
                    $("#app-tutorial-register-button").hide();
                    $("#app-tutorial-login-button").hide();
                    $("#app-tutorial-keep-practicing").hide();
                }

                appInterface.showExample($("#app-tutorial-overlay"));
            } else if (step["type"] == "click") {
                var target = $(step["jquery-id"]);
                target.addClass("tutorial-highlight");
                this.csbApp.appInterface.displayTextBubbleOnElement($(step["jquery-id"]), {
                    text: step["text"],
                    delay: delay,
                    isTemporary: false,
                    showArrow: true
                });

                var onClickFunction = function () {
                    self.csbApp.tutorial.moveToNextTutorialStep();
                    target.unbind("click", onClickFunction);
                };
                $(step["jquery-id"]).bind("click", onClickFunction);
            } else if (step["type"] == "wait-until-video-stopped") {
                $(step["jquery-id"]).onStateChange(function (event) {
                    if (event.data == 0 || event.data == 2) {
                        self.moveToNextTutorialStep();
                    }
                });
            }
            /*else if (step["type"] == "timed") {
                var position = appInterface.getAbsolutePositionOfElement($("#project_canvas"));
                position.x += parseInt($("#project_canvas").css("width")) / 2;
                position.y += parseInt($("#project_canvas").css("height")) / 2;
                this.csbApp.appInterface.displayTextBubble(position.x, position.y, step["text"], delay, false, false);
                $("#invisible-app-cover").show();
                setTimeout(function() {
                    $("#invisible-app-cover").hide();
                    self.moveToNextTutorialStep();
                }, step["time"]);
            }*/
            else if (step["type"] == "text-bubble") {
                var position = appInterface.getAbsolutePositionOfElement($("#project_canvas"));
                position.x += parseInt($("#project_canvas").css("width")) / 2;
                position.y += parseInt($("#project_canvas").css("height")) / 2;
                this.csbApp.appInterface.displayTextBubble({
                    x: position.x,
                    y: position.y,
                    text: step["text"],
                    title: step["title"],
                    delay: delay,
                    isTemporary: false,
                    showArrow: false
                });
                $("#invisible-app-cover").show();
            } else if (step["type"] == "mark-ejecta") {
                this.csbApp.appInterface.displayTextBubbleOnElement($("#project_canvas"), {
                    text: step["text"],
                    delay: delay,
                    isTemporary: false,
                    showArrow: true
                });
            } else if (step["type"] == "find-marks") {
                this.csbApp.appInterface.displayTextBubbleOnElement($("#project_canvas"), {
                    text: step["text"],
                    delay: delay,
                    isTemporary: false,
                    showArrow: true
                });
            } else if (step["type"] == "erase-marks") {
                var mark = this.csbApp.currentImage.marks[0];
                this.csbApp.appInterface.displayTextBubbleOnMark(mark, {
                    text: step["text"],
                    delay: delay,
                    isTemporary: false,
                    showArrow: true
                });
            } else if (step["type"] == "auto-create-mark") {
                this.autoCraterCreationQueue = Object.assign([], step["marks"]);
                this.startNewMarkExample(self.autoCraterCreationQueue.shift());
            } else if (step["type"] == "wait-until-marks-have-been-made") {
                this.csbApp.appInterface.displayTextBubbleOnElement($("#project_canvas"), {
                    text: step["text"],
                    delay: delay,
                    isTemporary: false,
                    showArrow: true
                });
                var onClickFunction = function () {
                    if (self.csbApp.currentImage.marks.length >= step["mark-count"]) {
                        self.csbApp.tutorial.moveToNextTutorialStep();
                        $("#project_canvas").unbind("click", onClickFunction);
                    }
                };
                $("#project_canvas").bind("click", onClickFunction);
            }

            this.currentHint = step["text"];
            if (typeof (this.currentHint) == "undefined")
                this.csbApp.appInterface.hideTextBubble();
            if (typeof (step["tutorial-percent"]) != "undefined") {
                $("#tutorial-steps-complete-foreground").width(step["tutorial-percent"] + "%");
                $("#tutorial-steps-complete-text").html("Tutorial " + step["tutorial-percent"] + "% Complete");
            }
            if (typeof (step["force-image-complete"]) != "undefined" && step["force-image-complete"] == true)
                this.csbApp.appInterface.enableSubmit();
            $("#tutorial-steps-complete").show();
        }
    };

    this.autoMarkExampleLoop = function () {
        if (this.markBeingMadeStep == "moving-mouse-toward-mark") {
            this.moveTowardMakingAMark();
        } else if (this.markBeingMadeStep == "pause-before-making-mark") {
            this.pauseBeforeMakingMark();
        } else if (this.markBeingMadeStep == "making-mark") {
            this.makingMark();
        } else if (this.markBeingMadeStep == "pause-after-making-mark") {
            this.pauseAfterMakingMark();
        } else if (this.markBeingMadeStep == "done") {
            this.finishMakingMark();
        }
    };

    this.drawFakeCursor = function (context) {
        if (this.fakeCursor != null) {
            context.globalAlpha = 1;
            if (this.fakeCursor.type == "mouse-up")
                context.drawImage(this.mouseUpImage, this.fakeCursor.x, this.fakeCursor.y);
            else if (this.fakeCursor.type == "mouse-down")
                context.drawImage(this.mouseDownImage, this.fakeCursor.x, this.fakeCursor.y);
        }
    };

    this.startNewMarkExample = function (crater) {
        this.desiredMark = crater;
        $("#invisible-app-cover").show();
        if (this.fakeCursor == null)
            this.fakeCursor = {x: 150, y: -110, type: "mouse-up"};
        this.markBeingMadeStep = "moving-mouse-toward-mark";
        var self = this;
        this.autoCraterCreationLoop = setInterval(function () {
            self.autoMarkExampleLoop.call(self);
        }, 33);
    };

    this.moveTowardMakingAMark = function () {
        var desiredPosition = null;
        if (this.desiredMark instanceof Crater) {
            desiredPosition = {
                x: this.desiredMark.x + Math.cos(Math.PI * .85) * this.desiredMark.diameter / 2,
                y: this.desiredMark.y + Math.sin(Math.PI * .85) * this.desiredMark.diameter / 2
            };
        } else if (this.desiredMark instanceof Segment) {
            desiredPosition = {
                x: this.desiredMark.points[0].x,
                y: this.desiredMark.points[0].y
            };
        } else if (this.desiredMark instanceof Rock) {
            desiredPosition = {
                x: this.desiredMark.x,
                y: this.desiredMark.y
            };
        }
        var dx = desiredPosition.x - this.fakeCursor.x;
        var dy = desiredPosition.y - this.fakeCursor.y;
        var distance = Math.sqrt(dx * dx + dy * dy);
        this.fakeCursor.x += dx / distance * (distance + 7) / 35;
        this.fakeCursor.y += dy / distance * (distance + 7) / 35;
        if (distance < 1) {
            this.fakeCursor.x = desiredPosition.x;
            this.fakeCursor.y = desiredPosition.y;

            this.markBeingMadeStep = "pause-before-making-mark";
        }
        this.csbApp.needToRedrawCanvas = true;
    };

    this.actuallyMakeMark = function () {
        if (this.desiredMark instanceof Crater) {
            this.markBeingMade = new Crater(this.fakeCursor.x, this.fakeCursor.y, 0, this.csbApp.appInterface);
        } else if (this.desiredMark instanceof Segment) {
            this.markBeingMade = new Segment(this.fakeCursor.x, this.fakeCursor.y, this.desiredMark.type, this.csbApp.appInterface);
            this.markBeingMade.points.push({x: this.fakeCursor.x, y: this.fakeCursor.y});
        } else if (this.desiredMark instanceof Rock) {
            this.markBeingMade = new Rock(this.fakeCursor.x, this.fakeCursor.y, this.csbApp.appInterface);
        }
        this.markBeingMade.creationStartPosition = {x: this.fakeCursor.x, y: this.fakeCursor.y};
        this.csbApp.currentImage.marks.push(this.markBeingMade);
    };

    this.pauseBeforeMakingMark = function () {
        if (!this.isPausing) {
            this.isPausing = true;
            var self = this;
            setTimeout(function () {
                self.fakeCursor.type = "mouse-down";
                self.csbApp.needToRedrawCanvas = true;
                self.actuallyMakeMark();
                setTimeout(function () {
                    self.markBeingMadeStep = "making-mark";
                    self.isPausing = false;
                    self.csbApp.needToRedrawCanvas = true;
                }, 500);
            }, 500);
        }
    };

    this.makingMark = function () {
        if (this.desiredMark instanceof Crater) {
            // Draw circle from this.markBeingMadeStartingPosition to this.fakeCursor
            var dx = (this.desiredMark.x - this.markBeingMade.creationStartPosition.x) * 2;
            var dy = (this.desiredMark.y - this.markBeingMade.creationStartPosition.y) * 2;
            var fakeCursorDx = this.fakeCursor.x - this.markBeingMade.creationStartPosition.x;
            var fakeCursorDy = this.fakeCursor.y - this.markBeingMade.creationStartPosition.y;
            var distance = Math.sqrt(fakeCursorDx * fakeCursorDx + fakeCursorDy * fakeCursorDy) + .0001;

            if (distance > this.desiredMark.diameter)
                this.markBeingMadeStep = "pause-after-making-mark";
            else {
                var movementPerFrame = 1;
                this.fakeCursor.x += dx / this.desiredMark.diameter * movementPerFrame;
                this.fakeCursor.y += dy / this.desiredMark.diameter * movementPerFrame;
                this.markBeingMade.x = (this.fakeCursor.x + this.markBeingMade.creationStartPosition.x) / 2;
                this.markBeingMade.y = (this.fakeCursor.y + this.markBeingMade.creationStartPosition.y) / 2;
                this.markBeingMade.diameter = distance;
            }
        } else if (this.desiredMark instanceof Segment) {
            var dx = this.desiredMark.points[1].x - this.desiredMark.points[0].x;
            var dy = this.desiredMark.points[1].y - this.desiredMark.points[0].y;
            var fakeCursorDx = this.fakeCursor.x - this.desiredMark.points[0].x;
            var fakeCursorDy = this.fakeCursor.y - this.desiredMark.points[0].y;
            var distance = Math.sqrt(fakeCursorDx * fakeCursorDx + fakeCursorDy * fakeCursorDy) + .0001;

            if (distance > Math.sqrt(dx * dx + dy * dy))
                this.markBeingMadeStep = "pause-after-making-mark";
            else {
                var movementPerFrame = 1;
                this.fakeCursor.x += dx / this.desiredMark.diameter * movementPerFrame;
                this.fakeCursor.y += dy / this.desiredMark.diameter * movementPerFrame;
                this.markBeingMade.points[1].x = this.fakeCursor.x;
                this.markBeingMade.points[1].y = this.fakeCursor.y;
            }
        } else if (this.desiredMark instanceof Rock) {
            this.markBeingMadeStep = "pause-after-making-mark";
        }
        this.csbApp.needToRedrawCanvas = true;
    };

    this.pauseAfterMakingMark = function () {
        if (!this.isPausing) {
            this.isPausing = true;
            var self = this;
            setTimeout(function () {
                self.fakeCursor.type = "mouse-up";
                self.csbApp.needToRedrawCanvas = true;
                setTimeout(function () {
                    self.markBeingMadeStep = "done";
                    self.isPausing = false;
                    self.csbApp.needToRedrawCanvas = true;
                }, 500);
            }, 500);
        }
    };

    this.finishMakingMark = function () {
        this.desiredMark = null;
        this.markBeingMade = null;
        this.markBeingMadeStep = null;
        $("#invisible-app-cover").hide();
        this.csbApp.needToRedrawCanvas = true;
        var self = this;
        clearInterval(this.autoCraterCreationLoop);
        if (this.autoCraterCreationQueue.length > 0) {
            this.startNewMarkExample(this.autoCraterCreationQueue.shift());
        } else {
            this.fakeCursor = null;
            self.moveToNextTutorialStep();
        }
    };
}

