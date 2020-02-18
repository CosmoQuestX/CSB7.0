function Tool(options) {
    this.name = options.name;
    this.htmlId = options.htmlId;
    this.htmlTitle = options.htmlTitle;
    this.htmlSelectedImage = options.htmlSelectedImage;
    this.htmlNotSelectedImage = options.htmlNotSelectedImage;
    this.doesToggle = false;
    this.exampleImages = options.exampleImages;
    this.description = options.description;
    this.isToggleable = false;
    this.isToggledOn = false;
    this.isActive = true;
    this.exampleText = "Examples";


    /**
     * The action taken when clicking on a button (toggling it or setting it as the active tool)
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onButtonClick = function (event, appInterface) {
        if (this.isToggleable) {
            this.onToggle();
        } else {
            if (appInterface.selectedTool != this)
                appInterface.selectTool(this);
        }
    };

    this.onToggle = function () {
        alert("Toggle effect not implemented");
    };

    /**
     * The action taken when clicking down in the app interface
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onMouseDown = function (event, appInterface) {

    };

    /**
     * The action taken when releasing the mouse in the app interface
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onMouseUp = function (event, appInterface) {

    };

    /**
     * The action taken when double clicking in the app interface.
     * The default action is to delete the mark underneath the mouse.
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onDoubleClick = function (event, appInterface) {
        if (appInterface.csbApp.isMobile)
            event.preventDefault();
        var mousePosition = appInterface.getMousePosition(event);
        var closestMark = appInterface.csbApp.currentImage.getClosestMarkTo(mousePosition);
        if (closestMark != null && closestMark.isBeingMade == false && appInterface.distanceBetweenMarkAndMouse(closestMark) < closestMark.diameter / 2) {
            // onDoubleClick is actually triggered by the 2nd mouse "release". This .5 second check removes a lot of accidental
            // deletions when resizing or creating marks after two clicks in quick succession
            if ((new Date()).getTime() - appInterface.mouseDownTime < 500) {
                // Remove the crater
                appInterface.csbApp.currentImage.deleteMark(closestMark);
                appInterface.selectMark(null);
                appInterface.csbApp.needToRedrawCanvas = true;
            }
        }
    };

    /**
     * The action taken when moving the mouse in the app interface
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onMouseMove = function (event, appInterface) {
        var mousePosition = appInterface.getGlobalMousePosition(event);
        if (appInterface.selectedMark != null && appInterface.selectedMark.isBeingMoved) {
            appInterface.switchCursorTo('pointer');
            appInterface.selectedMark.x = mousePosition.x + appInterface.selectedMark.relativeMousePosition.x;
            appInterface.selectedMark.y = mousePosition.y + appInterface.selectedMark.relativeMousePosition.y;
            if (appInterface.selectedMark.type == "feature")
                appInterface.selectedMark.displayMarkType(mousePosition);
        }
    };

    /**
     * The action taken when touching the app interface on a mobile device.
     * The default action is the same as clicking
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onTouchMove = function (event, appInterface) {
        event.preventDefault();
        this.onMouseMove(event, appInterface);
    };

    /**
     * The action taken when moving the mouse into the app interface
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onMouseEnter = function (event, appInterface) {

    };

    /**
     * The action taken when moving the mouse out of the app interface
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onMouseLeave = function (event, appInterface) {

    };

    this.onToggle = function (appInterface) {

    };

    this.onImageLoad = function (appInterface) {

    };

    this.createButton = function () {
        var html =
            '<div class="app-button" id="' + this.htmlId + '">' +
            '<img class="selected" src="' + this.htmlSelectedImage + '" />' +
            '<img class="not-selected" src="' + this.htmlNotSelectedImage + '" />' +
            '</div>';
        $("#buttons").append(html);
    };

    function muteItem(id) {
        $("#" + id).addClass("muted-button");
        $("#" + id).removeClass("app-tool-button");
    }

    this.mute = function () {
        this.isActive = false;
        if (this.isToggleable) {
            muteItem(this.toggleOnHtmlId);
            muteItem(this.toggleOffHtmlId);
        } else {
            muteItem(this.htmlId);
        }
    };

    function unmuteItem(id) {
        $("#" + id).removeClass("muted-button");
        $("#" + id).addClass("app-tool-button");
    }

    this.unmute = function () {
        this.isActive = true;
        if (this.isToggleable) {
            unmuteItem(this.toggleOnHtmlId);
            unmuteItem(this.toggleOffHtmlId);
        } else {
            unmuteItem(this.htmlId);
        }
    }
}