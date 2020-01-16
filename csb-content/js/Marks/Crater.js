/**
 * Created by Ryan Owens on 6/14/16.
 */

function Crater(x, y, diameter, appInterface, isEjecta) {
    Mark.call(this, 'crater', x, y, appInterface);
    this.diameter = diameter;
    this.isEjecta = typeof (isEjecta) == "undefined" ? false : isEjecta;
    this.alpha = .6;

    this.draw = function (context, appInterface, scale) {
        this.chooseColor(context);
        context.lineWidth = scale * 1.5;
        if (this.isEjecta) {
            if (this.isSelected) {
                context.strokeStyle = "#FFFFFF";
            }
            if (this.isSelected && !this.isBeingMade || this == appInterface.markUnderMouse) {
            } else
                context.globalAlpha = 2 / 3 * this.alpha;

            context.beginPath();
            context.arc(this.x * scale, this.y * scale, this.diameter / 2 * scale, 0, Math.PI * 2, true);
            context.closePath();
            context.globalAlpha = this.alpha;
            context.stroke();
            context.fill();

        } else {
            if (this.isSelected) {
                context.strokeStyle = "#FFFFFF";
            }
            if (this.isSelected && !this.isBeingMade || this == appInterface.markUnderMouse) {
                context.globalAlpha = this.alpha;
            } else
                context.globalAlpha = 0.666 * this.alpha;

            context.beginPath();
            context.arc(this.x * scale, this.y * scale, this.diameter / 2 * scale, 0, Math.PI * 2, true);
            context.closePath();
            context.fill();

            context.globalAlpha = this.alpha;
            context.beginPath();
            context.arc(this.x * scale, this.y * scale, this.diameter / 2 * scale, 0, Math.PI * 2, true);
            context.closePath();
            context.stroke();
        }

        if (this.isSelected && !this.isBeingMade)
            this.drawCircleResizeArrow(context, appInterface.getSavedMousePosition());
    };

    this.chooseColor = function (context) {
        if (this.diameter < 18) {
            // Too small => red mark
            context.fillStyle = "#BB0000";
            context.strokeStyle = "#BB0000";
        } else {
            if (this.isEjecta) {
                // Good => blue mark
                context.fillStyle = "#6699FF";
                context.strokeStyle = "#FFFFFF";
            } else {
                // Good => green mark
                context.fillStyle = "#39b54a";
                context.strokeStyle = "#FFFFFF";
            }
        }
    }


    this.getResizeArrowSize = function () {
        if (this.diameter < 200)
            return ((.3 - (this.diameter / 200 * .15)) * this.diameter);
        else
            return (.15 * this.diameter);
    };

    this.drawCircleResizeArrow = function (context, mousePosition) {
        var angle = Math.atan2(mousePosition.y - this.y, mousePosition.x - this.x);
        var arrowSize = this.getResizeArrowSize();
        var mouseDistanceFromEdge = Math.abs(distanceBetween(this, mousePosition) - this.diameter / 2);

        var alpha = Math.max(0, 1 - (mouseDistanceFromEdge - arrowSize / 2) / (Math.pow(arrowSize / 3, 1.5)));
        if (mouseDistanceFromEdge <= arrowSize / 2)
            alpha = 1;

        context.strokeStyle = "#FFFFFF";
        var radRatio = Math.max(1 - (this.diameter / 2 - 30) / 340, .5);
        //radRatio = Math.min(radRatio,.75);
        var arrowTip = {x: arrowSize, y: 0};
        var arrowSideTip = {x: arrowSize / 2, y: arrowSize / 2};
        var arrowSideHeight = arrowSize / 4;

        // Translate and rotate to the angled position
        context.translate(this.x, this.y);
        context.rotate(angle);
        context.translate(this.diameter / 2, 0);
        context.globalAlpha = alpha;
        context.beginPath();
        context.moveTo(arrowTip.x, arrowTip.y);
        context.lineTo(arrowSideTip.x, arrowSideTip.y);
        context.lineTo(arrowSideTip.x, arrowSideHeight);
        context.lineTo(-arrowSideTip.x, arrowSideHeight);
        context.lineTo(-arrowSideTip.x, arrowSideTip.y);
        context.lineTo(-arrowTip.x, arrowTip.y);
        context.lineTo(-arrowSideTip.x, -arrowSideTip.y);
        context.lineTo(-arrowSideTip.x, -arrowSideHeight);
        context.lineTo(arrowSideTip.x, -arrowSideHeight);
        context.lineTo(arrowSideTip.x, -arrowSideTip.y);
        context.moveTo(arrowTip.x, arrowTip.y);
        context.closePath();
        context.stroke();
        context.fill();
        // Untranslate and unrotate to return the context to normal
        context.translate(-this.diameter / 2, 0);
        context.rotate(-angle);
        context.translate(-this.x, -this.y);
    }
}
