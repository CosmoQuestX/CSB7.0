function MoonMappers(csbApp) {
    Application.call(this,
        "Moon Mappers",
        {
            "circle-button": [
                "/csb-content/applications/moon_mappers/examples/crater_1.png",
                "/csb-content/applications/moon_mappers/examples/crater_2.png",
                "/csb-content/applications/moon_mappers/examples/crater_3.png",
                "/csb-content/applications/moon_mappers/examples/crater_4.png",
                "/csb-content/applications/moon_mappers/examples/crater_5.png",
                "/csb-content/applications/moon_mappers/examples/crater_6.png",
                "/csb-content/applications/moon_mappers/examples/crater_7.png",
                "/csb-content/applications/moon_mappers/examples/crater_8.png",
                "/csb-content/applications/moon_mappers/examples/crater_9.png",
                "/csb-content/applications/moon_mappers/examples/crater_10.png",
                "/csb-content/applications/moon_mappers/examples/crater_11.png"
            ],
            "eraser-button": []
        },
        [
            {
                "type": "set-image",
                "image-location": "/csb-content/images/applications/tutorials/moon/moon_1.png",
                "correct-marks": [
                    new Crater(282, 50, 119, csbApp.appInterface),
                    new Crater(192, 178, 72, csbApp.appInterface),
                    new Crater(306, 304, 54, csbApp.appInterface)
                ],
                "existing-marks": [
                    new Crater(150, 180, 75, csbApp.appInterface)
                ],
                "required-score": 3,
                "tutorial-percent": 0
            },
            {
                "type": "dropdown",
                "title": "Tutorial",
                "text": "Welcome to Mars Mappers! To start,<br />  we'll quickly explain how to do the following: <ol><li><img src='/csb-content/images/applications/tutorials/icons/eraser.png' /> Erase a mistake</li><li><img src='/csb-content/images/applications/tutorials/icons/crater.png' /> Find a crater</li><li><img src='/csb-content/images/applications/tutorials/icons/light-image.png' /> Complete a light image</li><li><img src='/csb-content/images/applications/tutorials/icons/dark-image.png' /> Complete a dark image</li>",
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
                "title": "Find a crater",
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
                    new Crater(306, 304, 54, csbApp.appInterface),
                    new Crater(282, 50, 119, csbApp.appInterface)
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
                "image-location": "/csb-content/images/applications/tutorials/moon/moon_2.png",
                "correct-marks": [
                    new Crater(256, 13, 146),
                    new Crater(385, 39, 65),
                    new Crater(217, 89, 28),
                    new Crater(245, 184, 35),
                    new Crater(165, 250, 27),
                    new Crater(145, 217, 29),
                    new Crater(113, 269, 49),
                    new Crater(29, 374, 47),
                    new Crater(239, 351, 32),
                    new Crater(277, 349, 41),
                    new Crater(296, 299, 24),
                    new Crater(233, 290, 23),
                    new Crater(362, 324, 33),
                    new Crater(371, 297, 56),
                    new Crater(382, 365, 28),
                    new Crater(71, 113, 24),
                    new Crater(416, 297, 21),
                    new Crater(133, 306, 22),
                    new Crater(100, 397, 24),
                    new Crater(14, 189, 20),
                    new Crater(291, 405, 18),
                    new Crater(239, 411, 19),
                    new Crater(224, 420, 19),
                    new Crater(356, 354, 19),
                    new Crater(432, 312, 18),
                    new Crater(88, 288, 23),
                    new Crater(10, 313, 18),
                    new Crater(145, 38, 45),
                    new Crater(132, 11, 41)
                ],
                "required-score": 10,
                "tutorial-percent": 40
            },
            {
                "type": "text-bubble",
                "title": "Complete a Light Image",
                "text": "The moon has <b>lighter</b> areas (highlands) and <b>darker</b> areas (maria).<br /> Let's start with a typical highlands image.",
                "delay": 500
            },
            {
                "type": "text-bubble",
                "text": "It can be very difficult to find every crater in an image.<br />The goal is to find as many as you can, not all of them!",
                "delay": 500
            },
            {
                "type": "find-marks",
                "text": "Try to mark at least <b>10 craters</b>. We're only interested in craters larger than this: <img src='/csb-content/images/applications/tutorials/red-circle.png' style='width: 16px; height: 16px;' /><br /><b>TIP:</b> If a crater is too small, your circle will be <b style='color: red'>red</b>",
                "delay": 500
            },
            {
                "type": "click",
                "jquery-id": "#submit-button",
                "text": "Great job! Now let's move on to the last image."
            },
            {
                "type": "set-image",
                "image-location": "/csb-content/images/applications/tutorials/moon/moon_3.png",
                "correct-marks": [
                    new Crater(122, 133, 35, csbApp.appInterface),
                    new Crater(211, 107, 57, csbApp.appInterface),
                    new Crater(181, 209, 21, csbApp.appInterface),
                    new Crater(297, 246, 23, csbApp.appInterface),
                    new Crater(357, 299, 21, csbApp.appInterface),
                    new Crater(267, 382, 22, csbApp.appInterface),
                    new Crater(129, 329, 40, csbApp.appInterface),
                    new Crater(79, 194, 33, csbApp.appInterface),
                    new Crater(43, 60, 18, csbApp.appInterface),
                    new Crater(433, 67, 30, csbApp.appInterface),
                    new Crater(443, 112, 22, csbApp.appInterface),
                    new Crater(419, 267, 20, csbApp.appInterface),
                    new Crater(210, 404, 19, csbApp.appInterface),
                    new Crater(33, 431, 22, csbApp.appInterface),
                    new Crater(157, 150, 20, csbApp.appInterface),
                    new Crater(253, 126, 21, csbApp.appInterface),
                    new Crater(29, 391, 19, csbApp.appInterface),
                    new Crater(280, 356, 19, csbApp.appInterface),
                    new Crater(7, 114, 18, csbApp.appInterface)
                ],
                "required-score": 8,
                "tutorial-percent": 70
            },
            {
                "type": "text-bubble",
                "title": "Complete a Dark Image",
                "text": "Some parts of the moon are formed by volcano eruptions long ago.<br /> These areas are called <b>lunar maria</b>. Let's try one!",
                "delay": 500
            },
            {
                "type": "find-marks",
                "text": "Try to mark <b>8 craters</b>."
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