/**
 * Created by Ryan Owens on 6/14/16.
 */
function Segment(startingX, startingY, type, appInterface) {
    Mark.call(this, type, startingX, startingY, appInterface);
    this.points = [{x: startingX, y: startingY}, {x: startingX, y: startingY}];

    this.draw = function (context) {
        var mousePosition = this.appInterface.getSavedMousePosition();
        context.globalAlpha = this.alpha;
        context.lineWidth = 5;
        context.beginPath();
        if (this.points.length < 0)
            return;

        context.strokeStyle = "#FFFFFF";
        context.fillStyle = "#FFFFFF";
        context.moveTo(this.points[0].x, this.points[0].y);
        context.lineTo(this.points[1].x, this.points[1].y);
        if (this.isSelected)
            context.lineTo(Math.round(mousePosition.x), Math.round(mousePosition.y));
        context.stroke();

        if (this.status == "not_great") {
            context.fillStyle = "#C09030";
            context.strokeStyle = "#C09030";
        } else if (this.status == "way_too_far") {
            context.strokeStyle = "#920000";
            context.fillStyle = "#920000";
        } else {
            context.strokeStyle = "#70b570";
            context.fillStyle = "#70b570";
        }
        context.lineWidth -= 2;
        context.moveTo(this.points[0].x, this.points[0].y);
        context.lineTo(this.points[1].x, this.points[1].y);
        if (this.isSelected)
            context.lineTo(Math.round(mousePosition.x), Math.round(mousePosition.y));
        context.stroke();
    };

    this.length = function () {
        if (this.points.length < 2)
            return 0;
        else {
            var dx = this.points[0].x - this.points[1].x;
            var dy = this.points[0].y - this.points[1].y;
            return Math.sqrt(dx * dx + dy * dy);
        }
    };

    this.getSubmitData = function () {
        let x = (this.points[0].x + this.points[1].x) / 2;
        let y = (this.points[0].y + this.points[1].y) / 2;
        return {
            x: x,
            y: y,
            diameter: this.length(),
            owner: this.owner,
            type: this.type,
            details: {points: this.points}
        };
    };
}
