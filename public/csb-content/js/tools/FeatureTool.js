function FeatureTool() {
    // Inherit from Tool
    Tool.call(this);

    /**
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onMouseDown = function (event, appInterface) {
        var mousePosition = appInterface.getMouseOrTouchPosition(event);
        var closestFeature = appInterface.csbApp.currentImage.getClosestFeatureTo(mousePosition);

        // Check if a mark is near the mouse
        if (closestFeature != null && appInterface.distanceBetween(closestFeature, mousePosition) <= closestFeature.diameter / 2) {
            appInterface.selectMark(closestFeature);
            appInterface.selectedMark.isBeingMoved = true;
            appInterface.selectedMark.relativeMousePosition = {
                x: appInterface.selectedMark.x - mousePosition.x,
                y: appInterface.selectedMark.y - mousePosition.y
            };
        } else {
            // If not, create a new one
            appInterface.selectMark(new Feature(mousePosition.x, mousePosition.y, this));
            appInterface.selectedMark.isBeingMade = true;
            appInterface.csbApp.isAppOn = false;
            appInterface.csbApp.currentImage.marks.push(appInterface.selectedMark);
            $('#mark-choices').prependTo($(".app-controls"));
            $('#mark-choices').show();
            $('#controls').hide();
            appInterface.isMouseButtonDown = false;
        }
    };

    /**
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onMouseUp = function (event, appInterface) {

    };
}