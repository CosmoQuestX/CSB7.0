/*
 *
 * Created by Ryan Owens 10/2/2017
 *
 */

/**
 * Mark Drawer: Draws marks on a canvas
 * @type Mixin
 */
export default class MarkDrawer {
  /**
   * Constructor: Constructs a Mark Drawer
   * @param  {Context} ctx   2d canvas context
   * @param  {map} icons Map of mark icons
   * @return MarkDrawer
   */
  constructor(ctx, icons) {
    this.ctx = ctx;
    this.icons = icons;
    this.original_image_size = {
      width: 450,
      height: 450
    };
  }

  /**
   * Draw a set of marks
   * @param  {array} marks array of all marks to draw
   * @return void
   */
  draw(marks) {
    let segments = [];
    this.scale = this.calculateImageScale();
    this.clearCanvas();
    marks.forEach((mark) => {
      switch (mark.type) {
        case 'crater':
          if (mark.confirmed == null) {
            this.drawEmptyCrater(mark);
          } else {
            this.drawFilledCrater(mark);
          }
          break;
        case 'feature':
          this.drawFeature(mark);
          break;
        case 'boulder':
          this.drawBoulder(mark);
          break;
        case 'blanket':
          this.drawBlanket(mark);
          break;
        case 'segment':
          segments.push(mark);
          break;
        case 'paint_mark':
          this.drawPaintMark(mark);
          break;
        default:
          console.log("Unknown Mark Type");
      }
    });
    if (segments.length > 1) {
      for (var i = 1; i < segments.length; i += 2) {
        this.drawSegment(segments[i - 1], segments[i]);
      }
    }
  }

  /**
   * Calculate Image Scale: Gets the scale factor between the current image
   *                        and the original size
   * @return {map} Maps width and height scales
   */
  calculateImageScale() {
    let width = this.ctx.canvas.width;
    let height = this.ctx.canvas.height;
    return {
      width_scale: width / this.original_image_size.width,
      height_scale: height / this.original_image_size.height
    };
  }

  /**
   * Draw Paint Mark: Draws a paint mark on the canvas
   * @return void
   */
  drawPaintMark() {
    console.error("Paint Mark Type not implemented");
  }

  /**
   * Draw Segment: Draws a line segment on the canvas
   * @param  {Mark} mark1 starting mark for the segment
   * @param  {Mark} mark2 ending mark for the segment
   * @return void
   */
  drawSegment(mark1, mark2) {
    let context = this.ctx;
    context.lineWidth = 5;
    context.beginPath();
    context.strokeStyle = "#FFFFFF";
    context.fillStyle = "#FFFFFF";
    context.moveTo(mark1.x * this.scale.width_scale, mark1.y * this.scale.height_scale);
    context.lineTo(mark2.x * this.scale.width_scale, mark2.y * this.scale.height_scale);
    context.stroke();
  }

  /**
   * Draw Blanket: Draws a blanket mark on the canvas
   * @param  {Mark} mark the blanket
   * @return void
   */
  drawBlanket(mark) {
    console.error("Blanket Type not implemented");
  }

  /**
   * Draw Feature: Draws one of several different Feature marks on the canvas
   * @param  {Mark} mark the mark to draw
   * @return void
   */
  drawFeature(mark) {
    switch (mark.sub_type) {
      case 'Crater Chain':
        this.drawCraterChain(mark);
        break;
      case 'Bullseye (Concentric) Crater':
        console.error("Bullseye Crater Type not implemented");
        break;
      case 'Dark Albedo Feature':
        console.error("Dark Albedo Type not implemented");
        break;
      case 'Odd shaped feature':
        console.error("Odd shaped Type not implemented");
        break;
      case 'Light Albedo Feature':
        console.error("Light Albedo Type not implemented");
        break;
      case 'Odd albedo feature':
        console.error("Odd albedo Type not implemented");
        break;
      case 'Boulder Field':
        console.error("Boulder Field Type not implemented");
        break;
      default:
        console.log("Unknown Feature Type");
    }
  }

  /**
   * Draw Boulder: Draws a boulder mark on the canvas
   * @param  {Mark} mark the boulder mark
   * @return void
   */
  drawBoulder(mark) {
    this.ctx.globalAlpha = 1.0;
    let context = this.ctx;
    let boulderImage = this.icons['boulderImage'];
    context.globalAlpha = this.alpha;
    context.drawImage(boulderImage, (mark.x * this.scale.width_scale) - boulderImage.width / 2, (mark.y * this.scale.height_scale) - boulderImage.height / 2, boulderImage.width, boulderImage.height);
  }

  /**
   * Draw Crater Chain: Draws a crater chain on the canvas
   * @param  {Mark} mark the crater chain to draw
   * @return void
   */
  drawCraterChain(mark) {
    console.error("Crater Chain Type not implemented");
  }

  /**
   *
   * Draw Empty Crater: Draws a circle which represents a crater
   * @param  {Mark} mark the crater to draw
   * @return void
   */
  drawEmptyCrater(mark) {
    let context = this.ctx;
    context.globalAlpha = 0.25 * 1;
    context.fillStyle = "#39b54a";
    context.strokeStyle = "#39b54a";

    context.beginPath();
    context.arc((mark.x * this.scale.width_scale), (mark.y * this.scale.height_scale), mark.diamater / 2, 0, Math.PI * 2, false);
    context.fillStyle = "#39b54a";
    context.closePath();
    context.fill()

    context.globalAlpha = 1;
    context.arc((mark.x * this.scale.width_scale), (mark.y * this.scale.height_scale), mark.diameter / 2, 0, Math.PI * 2, true);
    context.strokeStyle = "#70b570";
    context.stroke();
    context.closePath();
  }

  /**
   *
   * Draw Filled Crater: Draws a filled circle which represents a confirmed crater
   * @param  {Mark} mark the crater to draw
   * @return void
   */
  drawFilledCrater(mark) {
    let context = this.ctx;
    context.globalAlpha = 0.25 * 1;
    let alpha = 0.6;
    context.fillStyle = "#39b54a";
    context.strokeStyle = "#39b54a";
    context.beginPath();
    context.arc((mark.x * this.scale.width_scale), (mark.y * this.scale.height_scale), mark.diameter / 2 * this.scale.width_scale, 0, Math.PI * 2, true);
    context.closePath();
    context.fill();

    context.globalAlpha = alpha;
    context.beginPath();
    context.arc((mark.x * this.scale.width_scale), (mark.y * this.scale.height_scale), mark.diameter / 2 * this.scale.width_scale, 0, Math.PI * 2, true);
    context.closePath();
    context.stroke();
    context.closePath();
  }

  /**
   * Clear Canvas: Clear all items from the canvas before drawing
   * @return void
   */
  clearCanvas() {
    console.log("Clearing Canvas");
    console.log(this.ctx.canvas.width);
    console.log(this.ctx.canvas.height);
    this.ctx.clearRect(0, 0, this.ctx.canvas.width, this.ctx.canvas.height);
  }
}
