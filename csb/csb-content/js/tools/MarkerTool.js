function MarkerTool(options) {
    // Inherit from Tool
    Tool.call(this, options);

    /**
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onMouseDown = function (event, appInterface) {
        var mousePosition = appInterface.getMouseOrTouchPosition(event);
        var closestBoulder = appInterface.csbApp.currentImage.getClosestMarkTo(mousePosition, "rock");
        // Check if a mark is near the mouse
        if (closestBoulder != null && appInterface.distanceBetween(closestBoulder, mousePosition) <= closestBoulder.diameter / 2) {
            appInterface.selectMark(closestBoulder);
            appInterface.selectedMark.isBeingMoved = true;
            appInterface.selectedMark.relativeMousePosition = {
                x: appInterface.selectedMark.x - mousePosition.x,
                y: appInterface.selectedMark.y - mousePosition.y
            };
        } else {
            // If not, create a new one
            appInterface.selectMark(new Rock(mousePosition.x, mousePosition.y, appInterface));
            appInterface.csbApp.currentImage.marks.push(appInterface.selectedMark);
            appInterface.isMouseButtonDown = false;
        }
    };

    this.onMouseMove = function (event, appInterface) {
        var selectedMark = appInterface.selectedMark;

        if (appInterface.isMouseButtonDown) {
            if (selectedMark != null) {
                appInterface.switchCursorTo('pointer');
                if (selectedMark.isBeingMoved) {
                    var mousePosition = appInterface.getMouseOrTouchPosition(event);
                    selectedMark.x = mousePosition.x + selectedMark.relativeMousePosition.x;
                    selectedMark.y = mousePosition.y + selectedMark.relativeMousePosition.y;
                }
            }
        }
    };
}