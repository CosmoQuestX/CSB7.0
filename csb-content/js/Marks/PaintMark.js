/**
 * Created by Ryan Owens on 6/14/16.
 */
function PaintMark(width, height, appInterface) {
    Mark.call(this, 'paint_mark', 0, 0);
    this.width = width;
    this.height = height;
    this.brushSize = 10;
    this.brushColor = {r: 255, g: 170, b: 0, a: 80};

    var context = appInterface.csbApp.context;
    this.imageData = context.createImageData(this.width, this.height);

    this.draw = function (context) {

        context.putImageData(this.imageData, 0, 0);
    };

    this.generateData = function () {
        var rows = [];
        for (var y = 0; y < this.height; y++) {
            var startedBlob = false;
            var currentRow = [];
            for (var x = 0; x < this.width; x++) {
                var index = (x + y * this.width) * 4;
                // If the alpha value is not 0, something was painted there
                if (this.imageData.data[index + 3] != 0) {
                    if (!startedBlob) {
                        currentRow.push(x);
                        startedBlob = true;
                    }
                } else {
                    if (startedBlob) {
                        currentRow.push(x);
                        startedBlob = false;
                    }
                }
            }
            rows.push(currentRow);
        }
        return rows;
    };
    this.drawGeneratedData = function (data) {
        for (var y = 0; y < this.height; y++) {
            var row = data[y];
            for (var blobIndex = 0; blobIndex < row.length; blobIndex += 2) {
                for (var x = row[blobIndex]; x <= row[blobIndex + 1]; x++) {
                    this.setPixel(x, y, {r: 255, g: 0, b: 255, a: 255});
                }
            }
        }
    };

    this.setPixel = function (x, y, color) {
        var index = (parseInt(x) + parseInt(y) * this.width) * 4;
        this.imageData.data[index] = color.r;
        this.imageData.data[index + 1] = color.g;
        this.imageData.data[index + 2] = color.b;
        this.imageData.data[index + 3] = color.a;
    };

    this.drawCircle = function (x, y, radius, color) {
        for (var i = -radius; i <= radius; i++) {
            for (var j = -radius; j <= radius; j++) {
                if (Math.sqrt(i * i + j * j) <= radius
                    && x + i < this.width
                    && x + i >= 0
                    && y + j < this.height
                    && y + i >= 0)
                    this.setPixel(x + i, y + j, color);
            }
        }
    };

    this.getSubmitData = function () {
        return {
            x: this.x,
            y: this.y,
            diameter: this.diameter,
            owner: this.owner,
            type: this.type,
            details: {painting: this.generateData()}
        };
    };
}