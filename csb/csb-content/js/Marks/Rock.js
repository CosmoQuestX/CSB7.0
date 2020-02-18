/**
 * Created by Ryan Owens on 6/14/16.
 */
function Rock(x, y, appInterface) {
    Mark.call(this, 'rock', x, y, appInterface);
    this.diameter = 6;
    this.draw = function (context, appInterface) {
        var rockImage = appInterface.icons['rockImage'];
        context.globalAlpha = this.alpha;
        context.drawImage(rockImage, this.x - rockImage.width / 2, this.y - rockImage.height / 2, rockImage.width, rockImage.height);
    };
}