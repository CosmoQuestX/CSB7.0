<?php

// 1) Get URL and meta data for a Master Image
// 2) Save image in temp directory & write meta data to DB
// 3) Display master image and overlay with squares marking proposed thumbnails with default overlap
//      a) sub images are 450x450 pixels
//      b) default overlap is 45 pixels
//      c) alternate default is to calculate how to space squares so they are distributed in height & width
// 4) Allow user to edit proposed chops by entering how many pixels overlap they want
// 5) Chop the image and add sub-images to DB