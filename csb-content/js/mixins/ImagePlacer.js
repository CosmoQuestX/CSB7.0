/**
 * Image Placer
 *
 * Created by Ryan Owens
 */

import DetectivesImage from './DetectivesImage.js';

export default class ImagePlacer {
  /**
   * Image Placer Contructor
   * @param  {Canvas} canvas HTML canvas reference
   * @param  {Map}    map    Google Map Object reference
   * @return {void}
   */
  constructor(canvas, map) {
    this.canvas = canvas;
    this.map = map;
  }

  /**
   * Place: Draws images on the canvas
   * @param  {array} images array of images to draw on the canvas
   * @return {boolean} whether an image has been placed
   */
  place(images) {
    // Get the Maps dimensions
    let map_dimensions = this.getDimensionsOfMap(this.map);
    // Clear the canvas
    this.clearCanvas(this.canvas, map_dimensions);
    // image has been placed bolean
    let image_placed = false;
    // Google map zoom level
    let map_zoom = this.map.getZoom();
    // Run through the images
    images.forEach((elem) => {
      let image = new DetectivesImage(elem);
      if(image.getUserZoom() == map_zoom) {
        // image is going to be placed
        image_placed = true;
        // Get the Image's XY Location
        image = this.convertLatLongIntoXY(map_dimensions, image);
        // Load the image to get its width and height
        let img = new Image();
        // When the image is loaded, continue placing it
        img.onload = () => {
          // Scale coordinates for image center and image scale
          image = this.scaleImageCoordinates(img, image);
          console.log("Image Coordinates: ");
          console.log(image.getCoordinates());
          console.log("Image Lat/Lng :");
          console.log(image.getUserLatitude());
          console.log(image.getUserLongitude());
          // Place on the canvas
          this.placeImageOnCanvas(this.canvas, img, image);
        }
        img.src = image.getSource();
      }
    });
    return image_placed;
  }

  /**
   * Scale Image Coordinates - Given a set of coordinates to an image center,
   *                           scale them to the image top left corner
   * @param  {Image} img                   Image element
   * @param  {DetectivesImage} image       Image data
   * @return {DetectivesImage}             Image with scaled inputs
   */
  scaleImageCoordinates(img, image) {
    let scale = image.getUserScale();
    let width = img.naturalWidth;
    let height = img.naturalHeight;
    let coordinates = image.getCoordinates();
    console.log("Image Scale: " + scale);
    console.log("Old Width: " + width);
    console.log("Old Height: " + height);
    // Calculate new image Width and Heights
    width = ((scale / 100) * width);
    height = ((scale / 100) * height);
    console.log("New Height: " + height);
    console.log("New Width: " + width);
    image.setWidth(width);
    image.setHeight(height);
    // Calculate new Coordinates
    coordinates[0] = coordinates[0] - (width / 2);
    coordinates[1] = coordinates[1] - (height / 2);
    image.setCoordinates(coordinates[0], coordinates[1]);
    // Set the values
    return image;
  }

  /**
   * Gets the Latitude and Longitude of the Map's four corners
   * @param  {Map} map Google Map object
   * @return {array}     Latitude and Longitude Pairs
   */
  getLatitudeLongitudeCornersOfMap(map) {
    let bounds = map.getBounds().toJSON();
    let northEast = [bounds.north, bounds.east];
    let northWest = [bounds.north, bounds.west];
    let southEast = [bounds.south, bounds.east];
    let southWest = [bounds.south, bounds.west];
    return [northEast, northWest, southEast, southWest];
  }

  /**
   * Gets the Dimensions of a Map in pixels
   * @param  {Map} map Google Map reference
   * @return {array}   Dimensions of the Map
   */
  getDimensionsOfMap(map) {
    let elem = map.getDiv();
    return [elem.offsetWidth, elem.offsetHeight];
  }

  /**
   * Convert Latitude, Longitude into canvas X,Y coordinates
   * @param  {Array} dimensions      Google Map Dimensions
   * @param  {DetectivesImage} image Image Data
   * @return {DetectivesImage}       Image with coordinates
   */
  convertLatLongIntoXY(dimensions, image) {
    let latitude = image.getUserLatitude();
    let longitude = image.getUserLongitude();
    let width = dimensions[0];
    let height = dimensions[1];
    let topRight = this.map.getProjection().fromLatLngToPoint(this.map.getBounds().getNorthEast());
    let bottomLeft = this.map.getProjection().fromLatLngToPoint(this.map.getBounds().getSouthWest());
    let scale = Math.pow(2, this.map.getZoom());
    let worldPoint = this.map.getProjection().fromLatLngToPoint(new google.maps.LatLng(latitude, longitude));
    let point = new google.maps.Point((worldPoint.x - bottomLeft.x) * scale, (worldPoint.y - topRight.y) * scale);
    image.setCoordinates(point.x, point.y);
    return image;
  }

  /**
   * Place an image on the cavas given its XY location
   * @param  {Canvas} canvas            HTML Canvas reference
   * @param  {Image}  img               Image Object
   * @param  {DetectivesImage}  image   Image Data
   * @return {void}
   */
  placeImageOnCanvas(canvas, img, image) {
    let ctx = canvas.getContext("2d");
    let x =  image.getCoordinates()[0];
    let y = image.getCoordinates()[1];
    let width = image.getWidth();
    let height = image.getHeight();
    let rotation = image.getUserRotation();
    ctx.save();
    ctx.translate(x + width / 2, y + height / 2);
    ctx.rotate(rotation);
    ctx.drawImage(img, -width / 2, -height / 2, width, height);
    ctx.restore();
  }

  /**
   * Clear Canvas - Clear all content on the screen
   * @param  {Canvas} canvas The Canvas Element
   * @param  {array}  dimensions The dimensions of the canvas
   * @return {void}
   */
  clearCanvas(canvas, dimensions) {
    let ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, dimensions[0], dimensions[1]);
  }
}
