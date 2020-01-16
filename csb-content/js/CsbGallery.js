function CsbGallery() {
    this.selectedCanvas = null;

    this.formGalleryCanvas = function (canvasId) {
        var canvas = $("#" + canvasId);
        canvas.css("width", 450);
        canvas.css("height", 450);

        var gallery = this;
        canvas.click(function () {
            $(this).css("width", 450);
            $(this).css("height", 450);
            gallery.selectedCanvas = this;
        });
    };
}