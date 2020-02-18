function MercuryMappers(csbApp) {
    var segment1 = new Segment(80, 423, csbApp.appInterface);
    segment1.points = [{x: 80, y: 423}, {x: 112, y: 373}];
    var segment2 = new Segment(278, 189, csbApp.appInterface);
    segment2.points = [{x: 278, y: 189}, {x: 362, y: 131}];
    var segment3 = new Segment(355, 119, csbApp.appInterface);
    segment3.points = [{x: 355, y: 119}, {x: 410, y: 428}];

    Application.call(this,
        "Mercury Mappers",
        {
            "circle-button": [
                "/csb-content/images/applications/mercury_mappers/examples/craters/crater2.png",
                "/csb-content/images/applications/mercury_mappers/examples/craters/crater3.png",
                "/csb-content/images/applications/mercury_mappers/examples/craters/crater5.png",
                "/csb-content/images/applications/mercury_mappers/examples/craters/crater4.png",
                "/csb-content/images/applications/mercury_mappers/examples/craters/crater1.png",
                "/csb-content/images/applications/mercury_mappers/examples/craters/crater6.png",
                "/csb-content/images/applications/mercury_mappers/examples/craters/crater7.png",
                "/csb-content/images/applications/mercury_mappers/examples/craters/crater8.png",
                "/csb-content/images/applications/mercury_mappers/examples/craters/crater9.png",
                "/csb-content/images/applications/mercury_mappers/examples/craters/crater10.png",
                "/csb-content/images/applications/mercury_mappers/examples/craters/crater11.png",
                "/csb-content/images/applications/mercury_mappers/examples/craters/crater12.png"
            ],
            "eraser-button": []
        },
        [
            {
                "type": "set-image",
                "image-location": "/csb-content/images/applications/tutorials/mercury/mercury-tutorial-2.png",
                "correct-marks": [
                    new Crater(238, 116, 62, csbApp.appInterface),
                    new Crater(230, 392, 22, csbApp.appInterface),
                    new Crater(401, 361, 33, csbApp.appInterface)
                ],
                "existing-marks": [
                    new Crater(150, 180, 75, csbApp.appInterface)
                ],
                "required-score": 3,
                "active-tools": ["circle-button", "eraser-button", "marks-toggle"],
                "tutorial-percent": 0
            },
            {
                "type": "dropdown",
                "title": "Tutorial",
                "text": "Welcome to Mars Mappers! To start,<br />  we'll quickly explain how to do the following: <ol><li><img src='/csb-content/images/applications/tutorials/icons/eraser.png' /> Erase a mistake</li><li><img src='/csb-content/images/applications/tutorials/icons/crater.png' /> Find a crater</li><li><img src='/csb-content/images/applications/tutorials/icons/easy.png' /> Complete an easy image</li><li><img src='/csb-content/images/applications/tutorials/icons/hard.png' /> Complete a hard image</li>",
                "ok-button": {}, // Should have text like "Okay, let's start!",
                "goal": "GOAL: Learn About Craters"
            },
            {
                "type": "text-bubble",
                "title": "Erasing a Mistake",
                "text": "So first... we accidentally made a mistake here, and we need you to <b>erase</b> it.",
                "delay": 1000
            },
            {
                "type": "click",
                "jquery-id": "#eraser-button",
                "text": "First, click on the Eraser Tool to select it."
            },
            {
                "type": "erase-marks",
                "text": "Now click on this crater mark to delete it."
            },
            {
                "type": "text-bubble",
                "title": "Marking Craters",
                "text": "Nice! Now you need to find the <b>craters</b> in this image.<br /><br /> Craters are <b>circular holes</b> in the ground with</br /><b>bright sunlight</b> on one side and a <b>dark shadow</b> on the other.",
                "delay": 500,
                "tutorial-percent": 10
            },
            {
                "type": "text-bubble",
                "text": "I'll show you how to mark the first two, then you can get the third!",
                "delay": 500
            },
            {
                "type": "auto-create-mark",
                "marks": [
                    new Crater(238, 116, 62, csbApp.appInterface),
                    new Crater(230, 392, 22, csbApp.appInterface)
                ]
            },
            {
                "type": "click",
                "jquery-id": "#circle-button",
                "text": "Now you can mark the <b>third crater</b>!<br />Select the Crater Tool.",
                "tutorial-percent": 20
            },
            {
                "type": "click",
                "jquery-id": "#app-example-down-arrow",
                "text": "You can see some examples of <b>craters</b> over here.<br />Click on these arrows to see more.",
                "delay": 500
            },
            {
                "type": "click",
                "jquery-id": "#hide-marks-toggle",
                "text": "If you need a better view of the image,<br /> you can <b>hide</b> the circles that you've made by clicking here."
            },
            {
                "type": "click",
                "jquery-id": "#hide-marks-toggle,#show-marks-toggle",
                "text": "Click here to <b>show</b> the marks again.",
                "delay": 250
            },
            {
                "type": "find-marks",
                "text": "Mark the last crater in the image by clicking and dragging the mouse."
            },
            {
                "type": "click",
                "jquery-id": "#submit-button",
                "text": "Awesome job! Now click here to go to the next image!"
            },
            {
                "type": "set-image",
                "image-location": "/csb-content/images/applications/tutorials/mercury/mercury-tutorial-3.png",
                "correct-marks": [
                    new Crater(81, 354, 62, csbApp.appInterface),
                    new Crater(167, 285, 67, csbApp.appInterface),
                    new Crater(137, 141, 42, csbApp.appInterface),
                    new Crater(91, 141, 95, csbApp.appInterface),
                    new Crater(207, 37, 28, csbApp.appInterface),
                    new Crater(260, 90, 26, csbApp.appInterface),
                    new Crater(349, 99, 32, csbApp.appInterface),
                    new Crater(333, 86, 18, csbApp.appInterface),
                    new Crater(253, 53, 20, csbApp.appInterface),
                    new Crater(24, 108, 24, csbApp.appInterface),
                    new Crater(4, 330, 65, csbApp.appInterface)
                ],
                "required-score": 6,
                "tutorial-percent": 40
            },
            {
                "type": "text-bubble",
                "title": "Complete an Easy Image",
                "text": "Some images have more craters than others. Let's start with an easier image.",
                "delay": 1000
            },
            {
                "type": "find-marks",
                "text": "Try to mark at least 6 craters.",
                "delay": 500
            },
            {
                "type": "click",
                "jquery-id": "#submit-button",
                "text": "Great job! Now move to the last image."
            },
            {
                "type": "set-image",
                "image-location": "/csb-content/images/applications/tutorials/mercury/mercury-tutorial-4.png",
                "correct-marks": [
                    new Crater(305, 149, 124, csbApp.appInterface),
                    new Crater(406, 200, 25, csbApp.appInterface),
                    new Crater(395, 173, 19, csbApp.appInterface),
                    new Crater(371, 285, 19, csbApp.appInterface),
                    new Crater(344, 220, 182, csbApp.appInterface),
                    new Crater(386, 409, 20, csbApp.appInterface),
                    new Crater(350, 410, 23, csbApp.appInterface),
                    new Crater(308, 444, 21, csbApp.appInterface),
                    new Crater(288, 354, 30, csbApp.appInterface),
                    new Crater(252, 442, 21, csbApp.appInterface),
                    new Crater(230, 408, 28, csbApp.appInterface),
                    new Crater(194, 427, 37, csbApp.appInterface),
                    new Crater(156, 418, 27, csbApp.appInterface),
                    new Crater(216, 432, 22, csbApp.appInterface),
                    new Crater(109, 392, 20, csbApp.appInterface),
                    new Crater(81, 373, 19, csbApp.appInterface),
                    new Crater(119, 315, 21, csbApp.appInterface),
                    new Crater(194, 335, 18, csbApp.appInterface),
                    new Crater(112, 270, 21, csbApp.appInterface),
                    new Crater(77, 252, 23, csbApp.appInterface),
                    new Crater(76, 232, 26, csbApp.appInterface),
                    new Crater(154, 99, 23, csbApp.appInterface),
                    new Crater(235, 20, 21, csbApp.appInterface),
                    new Crater(254, 79, 19, csbApp.appInterface),
                    new Crater(243, 119, 25, csbApp.appInterface),
                    new Crater(243, 98, 26, csbApp.appInterface),
                    new Crater(383, 88, 26, csbApp.appInterface),
                    new Crater(382, 60, 34, csbApp.appInterface),
                    new Crater(409, 28, 22, csbApp.appInterface),
                    new Crater(433, 113, 21, csbApp.appInterface),
                    new Crater(63, 273, 23, csbApp.appInterface),
                    new Crater(178, 383, 31, csbApp.appInterface),
                    new Crater(128, 374, 35, csbApp.appInterface),
                    new Crater(192, 296, 23, csbApp.appInterface),
                    new Crater(175, 208, 20, csbApp.appInterface),
                    new Crater(31, 4, 32, csbApp.appInterface),
                    new Crater(85, 122, 43, csbApp.appInterface),
                    new Crater(111, 92, 20, csbApp.appInterface),
                    new Crater(398, 97, 22, csbApp.appInterface),
                    new Crater(111, 180, 19, csbApp.appInterface),
                    new Crater(140, 75, 18, csbApp.appInterface),
                    new Crater(193, 45, 18, csbApp.appInterface),
                    new Crater(18, 164, 19, csbApp.appInterface),
                    new Crater(16, 236, 23, csbApp.appInterface),
                    new Crater(8, 261, 22, csbApp.appInterface),
                    new Crater(5, 120, 18, csbApp.appInterface),
                    new Crater(18, 31, 36, csbApp.appInterface),
                    new Crater(144, 170, 19, csbApp.appInterface),
                    new Crater(86, 51, 21, csbApp.appInterface),
                    new Crater(252, 353, 36, csbApp.appInterface),
                    new Crater(255, 382, 25, csbApp.appInterface),
                    new Crater(47, 332, 23, csbApp.appInterface),
                    new Crater(292, 1, 22, csbApp.appInterface),
                    new Crater(383, 22, 19, csbApp.appInterface),
                    new Crater(377, 132, 19, csbApp.appInterface),
                    new Crater(442, 59, 19, csbApp.appInterface),
                    new Crater(38, 424, 20, csbApp.appInterface),
                    new Crater(66, 429, 18, csbApp.appInterface)
                ],
                "active-tools": ["circle-button", "eraser-button", "crater-chain-button", "marks-toggle"],
                "required-score": 12,
                "tutorial-percent": 70
            },
            {
                "type": "text-bubble",
                "title": "Completing a Hard Image",
                "text": "It can be very difficult to find every crater in an image.<br />The goal is to find as many as you can, not all of them.",
                "delay": 500
            },
            {
                "type": "find-marks",
                "text": "Try to mark <b>12 craters</b>. We're only interested in craters larger than this: <img src='/csb-content/images/applications/tutorials/red-circle.png' style='width: 16px; height: 16px;' /><br /><b>TIP:</b> If a crater is too small, your circle will be <b style='color: red'>red</b>",
                "delay": 500
            },
            {
                "type": "click",
                "jquery-id": "#submit-button",
                "text": "Great job! Click here to finish the tutorial."
            },
            {
                "type": "dropdown",
                "title": "Tutorial Complete",
                "text": "Great job! We're going to start the real thing now. <br /><br />Log in, mark as many craters in each image as you can, and contribute to real NASA science! <br /><br />Or if you want to keep practicing, continue without logging in.<br /><br /><br />",
                "is-end": true,
                "tutorial-percent": 100
            }
        ]
    )
}