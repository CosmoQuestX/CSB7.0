/*
 *
 * Created by Ryan Owens 10/6/2017
 *
 */

/**
 * Image Details Parser
 * @type Mixin
 */
export default class ImageDetailsParser {
    /**
     * Constructor: Creates an Image Detail Parser
     * @param  {Image} image the image with json to parse
     * @return {ImageDetailsParser}
     */
    constructor(image) {
        this.image = image;
        this.image_details = image.details;
    }

    /**
     * isJSON: Determines if the image details is JSON
     * @return {Boolean} true if the imate details is JSON
     */
    isJSON() {
        let is_json = true;
        try {
            let json = $.parseJSON(this.image_details);
        } catch (e) {
            is_json = false;
        }
        return is_json;
    }

    /**
     * Parse JSON: Generates an HTML representation of the image details
     * @return {String} HTML representation of the image details
     */
    parseJSON() {
        let html = `
     <strong>Image Details</strong>
     <ul class="gallery-image-details-ul">
     `;
        let json = $.parseJSON(this.image_details);
        for (var prop in json) {
            let value = json[prop];
            let name = prop.replace('_', ' ');
            html += `<li class="gallery-image-details-li">
        <p>
          <strong>${name}:</strong>
          <span>${value}</span>
        </p>
        </li>
      `;
        }
        // parse
        html += `</ul>`;
        return html;
    }

    /**
     * Is XML: Determines if the image details is XML
     * @return {Boolean} True if the image details are XML
     */
    isXML() {
        let is_xml = true;
        try {
            let xml = $.parseXML(`<xml>${this.image_details}</xml>`);
        } catch (e) {
            is_xml = false;
        }
        return is_xml;
    }

    /**
     * Parse XML: Returns an HTML representation of the image details
     * @return {String} HTML representation of the XML image details
     */
    parseXML() {
        let html = `
     <strong>Image Details</strong>
     <ul class="gallery-image-details-ul">
     `;
        if (window.DOMParser) {
            var parser = new DOMParser();
            var xml = parser.parseFromString(`<xml>${this.image_details}</xml>`, "text/xml");
        } else // Internet Explorer
        {
            var xml = new ActiveXObject("Microsoft.XMLDOM");
            xml.async = false;
            xml.loadXML(`<xml>${this.image_details}</xml>`);
        }
        let values = xml.getElementsByTagName("xml")[0].childNodes;
        for (var i = 0; i < values.length; i++) {
            let nodeName = values[i].nodeName.replace('_', ' ');
            for (var j = 0; j < values[i].childNodes.length; j++) {
                let nodeValue = values[i].childNodes[j].nodeValue;
                html += `<li class="gallery-image-details-li">
          <p>
            <strong>${nodeName}:</strong>
            <span>${nodeValue}</span>
          </p>
          </li>
        `;
            }
        }
        // parse
        html += `</ul>`;
        return html;
    }

    /**
     * Get Details String: Gets HTML from the image details
     * @return {String} HTML of the image details
     */
    getDetailsString() {
        if (this.image_details == "" || this.image_details == null) {
            return "<strong>This image does not have any details.</strong>"
        }

        if (this.isJSON()) {
            return this.parseJSON();
        } else if (this.isXML()) {
            return this.parseXML();
        } else {
            return "";
        }
    }
}
