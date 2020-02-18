/**
 * Created by Ryan Owens on 6/14/16.
 */
function CheckMark(x, y, size, appInterface) {
    Mark.call(this, 'checkmark', x, y);
    this.diameter = size;

    this.draw = function (context) {
        context.globalAlpha = this.alpha;
        context.strokeStyle = "#000000";
        context.lineWidth = this.diameter / 10;
        context.beginPath();
        context.moveTo(this.x - this.diameter / 5, this.y - this.diameter / 5);
        context.lineTo(this.x, this.y);
        context.lineTo(this.x + this.diameter / 3, this.y - this.diameter / 2);
        context.stroke();
        context.strokeStyle = "#70b570";
        context.lineWidth = this.diameter / 15;
        context.beginPath();
        context.moveTo(this.x - this.diameter / 5, this.y - this.diameter / 5);
        context.lineTo(this.x, this.y);
        context.lineTo(this.x + this.diameter / 3, this.y - this.diameter / 2);
        context.stroke();
    };
}