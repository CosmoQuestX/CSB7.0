/**
 * Created by Ryan Owens on 6/14/16.
 */
function Feature(x, y, appInterface) {
    Mark.call(this, 'feature', x, y, appInterface);
    this.draw = function (context, appInterface) {
        var featureImage = appInterface.icons['featureImage'];
        var featureSelectedImage = appInterface.icons['featureSelectedImage'];
        var scale = 1;
        context.globalAlpha = this.alpha;
        if (this == appInterface.markUnderMouse)
            context.drawImage(featureSelectedImage, (this.x - featureSelectedImage.width / 2) * scale, (this.y - featureSelectedImage.height) * scale, featureSelectedImage.width * scale, featureSelectedImage.height * scale);
        else
            context.drawImage(featureImage, (this.x - featureImage.width / 2) * scale, (this.y - featureImage.height) * scale, featureImage.width * scale, featureImage.height * scale);
    };
}