/**
 * Image Array Sorter - Sorts arrays of images
 *
 * Created by Ryan Owens
 *
 */
export default class ImageArraySorter {
    constructor(images) {
        this.images = images;
    }

    sortByIncreasingZoom() {
        return this.images.sort((A, B) = > {
            console.log(A);
        console.log(B);
        let A_zoom = parseInt(A.image_user.details.location.zoom);
        let B_zoom = parseInt(B.image_user.details.location.zoom);
        if (A_zoom < B_zoom) {
            return -1;
        } else if (B_zoom > A_zoom) {
            return 1;
        }
        return 0;
    })
        ;
    }

    sortByDecreasingZoom() {
        return this.images.sort((A, B) = > {
            let A_zoom = parseInt(A.image_user.details.location.zoom);
        let B_zoom = parseInt(B.image_user.details.location.zoom);
        if (A_zoom > B_zoom) {
            return -1;
        } else if (B_zoom < A_zoom) {
            return 1;
        }
        return 0;
    })
        ;
    }

    sortByClosestZoom(zoom) {
        return this.images.sort((A, B) = > {
            let A_zoom = Math.abs(zoom - parseInt(A.image_user.details.location.zoom));
        let B_zoom = Math.abs(zoom - parseInt(B.image_user.details.location.zoom));
        if (A_zoom < B_zoom) {
            return -1;
        } else if (B_zoom > A_zoom) {
            return 1;
        }
        return 0;
    })
        ;
    }
}
