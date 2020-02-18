function ZoomWindowTool(options) {
    // Inherit from Tool
    Tool.call(this, options);
    this.isToggleable = true;
    this.toggleOnHtmlId = options.toggleOnHtmlId;
    this.toggleOffHtmlId = options.toggleOffHtmlId;
    this.isToggledOn = false;

    /**
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onToggle = function (appInterface) {
        if (this.isToggledOn) {
            $("#zoom-canvas").show();
        } else
            $("#zoom-canvas").hide();
        csbApp.needToRedrawCanvas = true;
    }
}