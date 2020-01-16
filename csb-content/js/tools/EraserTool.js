function EraserTool(options) {
    // Inherit from Tool
    Tool.call(this, options);
    this.isDeletingMark = false;

    /**
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onMouseDown = function (event, appInterface) {
        var mousePosition = appInterface.getMouseOrTouchPosition(event);
        var closestMark = appInterface.csbApp.currentImage.getClosestMarkTo(mousePosition);
        if (closestMark != null && appInterface.distanceBetween(closestMark, mousePosition) < closestMark.diameter / 2) {
            appInterface.csbApp.currentImage.deleteMark(closestMark);
            this.isDeletingMark = true;
            if (appInterface.csbApp.tutorial != null && appInterface.csbApp.tutorial.getCurrentTutorialStep()["type"] == "erase-marks") {
                appInterface.csbApp.tutorial.moveToNextTutorialStep();
            }
        } else {
            // Special case for the painting tool
            for (var key in appInterface.tools) {
                var tool = appInterface.tools[key];
                if (tool.constructor.name == "PaintingTool" && tool.isActive) {
                    tool.paintMark.drawCircle(mousePosition.x, mousePosition.y, tool.brushSize, {
                        r: 0,
                        g: 0,
                        b: 0,
                        a: 0
                    });
                }
            }
        }
    };


    /**
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onMouseMove = function (event, appInterface) {
        if (appInterface.isMouseButtonDown && !this.isDeletingMark) {
            var mousePosition = appInterface.getMouseOrTouchPosition(event);
            for (var key in appInterface.tools) {
                var tool = appInterface.tools[key];
                if (tool.constructor.name == "PaintingTool") {
                    tool.paintMark.drawCircle(mousePosition.x, mousePosition.y, tool.brushSize, {
                        r: 0,
                        g: 0,
                        b: 0,
                        a: 0
                    });
                }
            }
        }
    };

    /**
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onMouseUp = function (event, appInterface) {
        this.isDeletingMark = false;
    };
}