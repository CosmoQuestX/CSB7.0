/**
 * Created by Ryan Owens on 6/14/16.
 */
function XMark(x, y, size, appInterface) {
    Mark.call(this, 'X', x, y);
    this.diameter = size;

    this.draw = function (context) {
        context.globalAlpha = this.alpha;
        context.strokeStyle = "#920000";
        context.lineWidth = this.diameter / 10;
        context.beginPath();
        context.moveTo(this.x - this.diameter / 2, this.y - this.diameter / 2);
        context.lineTo(this.x + this.diameter / 2, this.y + this.diameter / 2);
        context.moveTo(this.x - this.diameter / 2, this.y + this.diameter / 2);
        context.lineTo(this.x + this.diameter / 2, this.y - this.diameter / 2);
        context.closePath();
        context.stroke();
    };
}