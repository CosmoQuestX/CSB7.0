function DarkenScreenTool(options) {
    // Inherit from Tool
    Tool.call(this, options);
    this.isToggleable = true;

    /**
     * @param {Event} event
     * @param {AppInterface} appInterface
     */
    this.onToggle = function (appInterface) {
        if (this.isToggledOn)
            $('body').css('background-color', 'black').css('transition', 'all 2s ease');
        else
            $('body').css('background-color', 'white').css('transition', 'all 2s ease');
    }
}