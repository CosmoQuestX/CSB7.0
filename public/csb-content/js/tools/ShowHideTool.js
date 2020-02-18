function ShowHideTool(options) {
    // Inherit from Tool
    Tool.call(this, options);
    this.isToggleable = true;
    this.toggleOnHtmlId = options.toggleOnHtmlId;
    this.toggleOffHtmlId = options.toggleOffHtmlId;
    this.isToggledOn = true;

    /**
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onToggle = function (appInterface) {
        appInterface.areMarksVisible = this.isToggledOn;
        appInterface.csbApp.needToRedrawCanvas = true;
    }
}