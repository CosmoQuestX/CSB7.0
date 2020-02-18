/**
 * Created by Ryan Owens on 6/14/16.
 */
function LinearFeature(startingX, startingY, appInterface) {
    Mark.call(this, 'linear_feature', startingX, startingY, appInterface);
    this.points = [];

    this.draw = function (context) {
        context.globalAlpha = this.alpha;
        context.lineWidth = 5;
        context.beginPath();
        if (this.points.length < 0)
            return;

        context.strokeStyle = "#FFFFFF";
        context.fillStyle = "#FFFFFF";
        context.moveTo(this.points[0].x, this.points[0].y);
        for (var i = 1; i < this.points.length; i++) {
            context.lineTo(this.points[i].x, this.points[i].y);
        }

        context.strokeStyle = "#5555AA";
        context.fillStyle = "#5555AA";
        context.lineWidth -= 1;
        context.moveTo(this.points[0].x, this.points[0].y);
        for (var i = 1; i < this.points.length; i++) {
            context.lineTo(this.points[i].x, this.points[i].y);
        }

        if (this.isSelected) {
            var mousePosition = this.appInterface.getSavedMousePosition();
            context.lineTo(mousePosition.x, mousePosition.y);
        }

        context.stroke();
        for (i = 0; i < this.points.length; i++) {
            context.arc(this.points[i].x, this.points[i].y, 5, 0, 2 * Math.PI, false);
        }
    };
}