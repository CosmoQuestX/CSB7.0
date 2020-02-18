/**
 * Created by Ryan Owens on 6/14/16.
 */
function Transient(x, y, appInterface) {
    Mark.call(this, 'transient', x, y, appInterface);
    this.draw = function (context, appInterface) {
        var transientImage = appInterface.icons['transientImage'];
        context.globalAlpha = this.alpha;
        context.drawImage(transientImage, this.x - transientImage.width / 2, this.y - transientImage.height / 2, transientImage.width, transientImage.height);
    };
}