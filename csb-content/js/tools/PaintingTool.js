/**
 * Created by theya on 6/8/2016.
 */

function PaintingTool(options) {
    // Inherit from Tool
    Tool.call(this, options);
    this.isCurrentlyPainting = false;
    this.paintMark = null;
    this.brushSize = 10;
    this.brushColor = {r: 255, g: 170, b: 0, a: 80};

    this.onMouseDown = function (event, appInterface) {
        if (this.paintMark == null)
            return;

        this.isCurrentlyPainting = true;
        var mousePosition = appInterface.getMouseOrTouchPosition(event);
        this.paintMark.drawCircle(mousePosition.x, mousePosition.y, this.brushSize, this.brushColor);
    };

    this.onMouseMove = function (event, appInterface) {
        if (this.paintMark == null)
            return;

        if (this.isCurrentlyPainting == true) {
            var mousePosition = appInterface.getMouseOrTouchPosition(event);
            this.paintMark.drawCircle(mousePosition.x, mousePosition.y, this.brushSize, this.brushColor);
        }
    };

    this.onMouseUp = function (event, appInterface) {
        if (this.paintMark == null)
            return;

        this.isCurrentlyPainting = false;

    };

    this.onImageLoad = function (appInterface) {
        if (appInterface.isPaintingToolEnabled) {
            this.paintMark = new PaintMark(appInterface.csbApp.currentImage.image.width, appInterface.csbApp.currentImage.image.height, appInterface);
            appInterface.csbApp.currentImage.marks.unshift(this.paintMark);
        }
    };

    this.onMouseEnter = function (event, appInterface) {
        $('#' + appInterface.csbApp.canvasName).css("cursor", "url(/csb-content/images/applications/markers/marker-paint.png) 13 13, auto");
    };

    this.onMouseLeave = function (event, appInterface) {
        $('#' + appInterface.csbApp.canvasName).css("cursor", "default");
    };

    this.onDoubleClick = function (event, appInterface) {
        var data = this.paintMark.generateData();
        this.paintMark.imageData = appInterface.csbApp.context.createImageData(this.paintMark.width, this.paintMark.height);
        this.paintMark.drawGeneratedData(data);

    };
}