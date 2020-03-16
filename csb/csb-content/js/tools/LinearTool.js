function LinearTool(options) {
    // Inherit from Tool
    Tool.call(this, options);
    this.type = options.type;

    /**
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onMouseDown = function (event, appInterface) {
        var mousePosition = appInterface.getMouseOrTouchPosition(event);
        /*if (appInterface.selectedMark == null) {
            appInterface.selectMark(new LinearFeature(mousePosition.x, mousePosition.y, appInterface));
            appInterface.selectedMark.points = [mousePosition];
            appInterface.csbApp.currentImage.marks.push(appInterface.selectedMark);
            appInterface.isMouseButtonDown = false;
        }
        else {
            appInterface.selectedMark.points.push(mousePosition);
            appInterface.isMouseButtonDown = false;
        }
        */


        // Segment
        if (appInterface.selectedMark == null) {
            var roundedPoint = {x: Math.round(mousePosition.x), y: Math.round(mousePosition.y)};
            appInterface.selectMark(new Segment(roundedPoint.x, roundedPoint.y, this.type, appInterface));
            appInterface.csbApp.currentImage.marks.push(appInterface.selectedMark);
            appInterface.isMouseButtonDown = false;
        }
        /*
                // Blanket
                if (appInterface.selectedMark == null) {
                    appInterface.selectMark(new Blanket(mousePosition.x, mousePosition.y, appInterface.csbApp.currentImage));
                    appInterface.selectedMark.points = [{x: mousePosition.x, y: mousePosition.y}];
                    appInterface.csbApp.currentImage.marks.push(appInterface.selectedMark);
                    appInterface.isMouseButtonDown = false;
                }
                else {
                    appInterface.selectedMark.points.push({x: mousePosition.x, y: mousePosition.y});
                    appInterface.isMouseButtonDown = false;
                }*/
    };

    /**
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onMouseUp = function (event, appInterface) {
        if (appInterface.selectedMark == null)
            return;

        var mousePosition = appInterface.getMouseOrTouchPosition(event);
        var roundedPoint = {x: Math.round(mousePosition.x), y: Math.round(mousePosition.y)};

        // Delete if too short
        if (appInterface.selectedMark.length() < 10)
            appInterface.csbApp.currentImage.marks.splice(appInterface.csbApp.currentImage.marks.indexOf(appInterface.selectedMark), 1);
        appInterface.selectMark(null);
        appInterface.isMouseButtonDown = false;
    };

    /**
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onMouseMove = function (event, appInterface) {
        var mousePosition = appInterface.getMouseOrTouchPosition(event);
        if (appInterface.selectedMark) {
            appInterface.selectedMark.points[1] = {x: Math.round(mousePosition.x), y: Math.round(mousePosition.y)};
            appInterface.selectedMark.diameter = appInterface.selectedMark.length() / 2;
        }
    };

    this.onMouseLeave = function (event, appInterface) {
        if (appInterface.selectedMark == null)
            return;

        if (appInterface.selectedMark.points.length <= 1)
            appInterface.csbApp.currentImage.deleteMark(appInterface.selectedMark);
        appInterface.selectMark(null);
    };
}