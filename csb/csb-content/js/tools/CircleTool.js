function CircleTool(options) {
    // Inherit from Tool
    Tool.call(this, options);

    this.mouseDownTime = 0;
    this.isEjecta = options.isEjecta;

    /**
     * Moves or resizes existing craters, and creates new ones
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onMouseDown = function (event, appInterface) {
        var mousePosition = appInterface.getMouseOrTouchPosition(event);
        var closestCrater = null;
        closestCrater = appInterface.csbApp.currentImage.getClosestCraterTo(mousePosition);
        var mark = appInterface.selectedMark;
        var isEjecta = this.isEjecta;

        // Is a crater already selected?
        if (isSet(appInterface.selectedMark) && appInterface.selectedMark instanceof Crater) {
            var distance = appInterface.distanceBetween(mark, mousePosition);

            if (distance > mark.diameter / 2 - mark.getResizeArrowSize() && distance < mark.diameter / 2 + mark.getResizeArrowSize()) {
                // The mouse is near the edge of the crater. Resize it
                resizeCrater();
            } else if (distance <= mark.diameter / 2 - mark.getResizeArrowSize()) {
                // The mouse is close to the center. Move it
                moveCrater();
            } else {
                // The mouse isn't close to the selected crater so deselect it
                appInterface.selectMark(null);
                createNewCrater.call(this);
            }
        }

        // If no crater is currently selected
        if (mark == null || !(mark.isBeingMoved || mark.isBeingMade || mark.isBeingResized)) {
            // If the mouse is close enough to a crater, select and move the closest crater
            if (closestCrater != null && appInterface.distanceBetween(closestCrater, mousePosition) < closestCrater.diameter / 2) {
                mark = appInterface.selectMark(closestCrater);
                moveCrater();
            }
        }

        // If no craters have been selected or modified up to this point, make a new one
        if (mark == null) {
            if (appInterface.csbApp.applicationName != "simply_craters_verification" || appInterface.csbApp.currentImage.marks.length == 0) {
                createNewCrater.call(this);
            }
        }

        function resizeCrater() {
            mark.isBeingResized = true;
            mark.relativeMouseDistance = mark.diameter / 2 - distance;
        }

        function moveCrater() {
            mark.isBeingMoved = true;
            mark.relativeMousePosition = {x: mark.x - mousePosition.x, y: mark.y - mousePosition.y};
        }

        function createNewCrater() {
            mark = appInterface.selectMark(new Crater(mousePosition.x, mousePosition.y, 0, this));
            mark.isBeingMade = true;
            mark.creationStartPosition = {x: mousePosition.x, y: mousePosition.y};
            mark.isEjecta = isEjecta;
            appInterface.csbApp.currentImage.marks.push(mark);
        }
    };

    this.onMouseUp = function (event, appInterface) {
        if (appInterface.selectedMark == null)
            return;

        if (appInterface.selectedMark.diameter < 7) {
            appInterface.csbApp.currentImage.deleteMark(appInterface.selectedMark);
            appInterface.selectMark(null);
        } else if (appInterface.selectedMark.diameter < 18) {
            if (appInterface.csbApp.tutorial != null)
                appInterface.displayTextBubbleOnMark(appInterface.selectedMark, {
                    text: "This crater is too small. We're only looking for larger craters.",
                    delay: 0,
                    isTemporary: true,
                    showArrow: true
                });
            appInterface.csbApp.currentImage.deleteMark(appInterface.selectedMark);
            appInterface.selectMark(null);
        } else if (appInterface.selectedMark.isBeingMade) {
            appInterface.selectedMark.isBeingMade = false;
        } else {
            appInterface.selectedMark.isBeingMoved = false;
            appInterface.selectedMark.isBeingResized = false;
        }
    };

    this.onMouseMove = function (event, appInterface) {
        var mousePosition = appInterface.getMouseOrTouchPosition(event);
        var selectedMark = appInterface.selectedMark;

        if (appInterface.isMouseButtonDown) {
            if (selectedMark != null) {
                if (selectedMark.isBeingMade) {
                    appInterface.switchCursorTo('pointer');
                    if (appInterface.isCreatingCirclesFromCenter) {
                        selectedMark.diameter = appInterface.distanceBetween(selectedMark.creationStartPosition, mousePosition) * 2;
                    } else {
                        selectedMark.diameter = appInterface.distanceBetween(selectedMark.creationStartPosition, mousePosition);
                        selectedMark.x = (selectedMark.creationStartPosition.x + mousePosition.x) / 2;
                        selectedMark.y = (selectedMark.creationStartPosition.y + mousePosition.y) / 2;
                    }
                } else if (selectedMark.isBeingMoved) {
                    appInterface.switchCursorTo('pointer');
                    selectedMark.x = mousePosition.x + selectedMark.relativeMousePosition.x;
                    selectedMark.y = mousePosition.y + selectedMark.relativeMousePosition.y;
                } else if (selectedMark.isBeingResized) {
                    appInterface.switchCursorTo('pointer');
                    var mouseDistance = appInterface.distanceBetween(mousePosition, selectedMark);
                    selectedMark.diameter = Math.max(0, (mouseDistance + selectedMark.relativeMouseDistance) * 2);
                }
            }
        }
    };

    this.onChange = function () {
    }
}