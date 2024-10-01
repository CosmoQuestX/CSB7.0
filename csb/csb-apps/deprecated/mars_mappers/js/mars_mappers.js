function mars_mappers(csbApp) {
    Application.call(this,
        "Mars Mappers",
        {
            "circle-button": [
                "/csb/csb-apps/mars_mappers/images/examples/crater/crater1.png",
                "/csb/csb-apps/mars_mappers/images/examples/crater/crater2.png",
                "/csb/csb-apps/mars_mappers/images/examples/crater/crater13.png",
                "/csb/csb-apps/mars_mappers/images/examples/crater/crater4.png",
                "/csb/csb-apps/mars_mappers/images/examples/crater/crater10.png",
                "/csb/csb-apps/mars_mappers/images/examples/crater/crater3.png",
                "/csb/csb-apps/mars_mappers/images/examples/crater/crater7.png",
                "/csb/csb-apps/mars_mappers/images/examples/crater/crater8.png",
                "/csb/csb-apps/mars_mappers/images/examples/crater/crater5.png",
                "/csb/csb-apps/mars_mappers/images/examples/crater/crater6.png",
                "/csb/csb-apps/mars_mappers/images/examples/crater/crater9.png",
                "/csb/csb-apps/mars_mappers/images/examples/crater/crater11.png",
                "/csb/csb-apps/mars_mappers/images/examples/crater/crater12.png"
            ],
            "eraser-button": [],
            "ejecta-button": [
                "/csb/csb-apps/mars_mappers/images/examples/ejecta/ejecta1.png",
                "/csb/csb-apps/mars_mappers/images/examples/ejecta/ejecta2.png",
                "/csb/csb-apps/mars_mappers/images/examples/ejecta/ejecta3.png",
                "/csb/csb-apps/mars_mappers/images/examples/ejecta/ejecta4.png",
                "/csb/csb-apps/mars_mappers/images/examples/ejecta/ejecta5.png"
            ]
        },
        [
            {
                "type": "set-image",
                "image-location": "/csb/csb-apps/mars_mappers/images/tutorial/new_mars-tutorial-1.png",
                "correct-marks": [
                    new Crater(255, 84, 140, csbApp.appInterface)
                ],
                "existing-marks": [
                    new Crater(150, 180, 75, csbApp.appInterface)
                ],
                "required-score": 1,
                "tutorial-percent": 0
            },
            {
                "type": "dropdown",
                "title": "Tutorial",
                "text": "Welcome to Mars Mappers! To start,<br />  we'll quickly explain how to do the following: <ol><li><img src='/csb/csb-apps/deprecated/mars_mappers/images/tutorial/eraser.png' /> Erase a mistake</li><li><img src='/csb/csb-apps/deprecated/mars_mappers/images/tutorial/crater.png' /> Find a crater</li><li><img src='/csb/csb-apps/deprecated/mars_mappers/images/tutorial/light-image.png' /> Complete an image</li><li><img src='/csb/csb-apps/deprecated/mars_mappers/images/tutorial/ejecta.png' /> Find crater ejecta</li>",
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
                "text": "Nice! Now I need you to find the <b>crater</b> in this image.<br /><br /> Craters are <b>circular holes</b> in the ground with</br /><b>bright sunlight</b> on one side and a <b>dark shadow</b> on the other.",
                "delay": 500,
                "tutorial-percent": 10
            },
            {
                "type": "click",
                "jquery-id": "#circle-button",
                "text": "To mark the <b>crater</b>!<br />Select the Crater Tool."
            },
            {
                "type": "click",
                "jquery-id": "#app-example-down-arrow",
                "text": "You can see some examples of <b>craters</b> over here.<br />Click on these arrows to see more.",
                "delay": 500,
                "tutorial-percent": 20
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
                "text": "Mark the big crater in the image by clicking and dragging the mouse."
            },
            {
                "type": "click",
                "jquery-id": "#submit-button",
                "text": "Awesome job! Now click here to go to the next image!"
            },
            {
                "type": "set-image",
                "image-location": "/csb/csb-apps/mars_mappers/images/tutorial/new_mars-tutorial-7.png",
                "correct-marks": [
                    new Crater(161, 189, 80, csbApp.appInterface),
                    new Crater(129, 137, 22, csbApp.appInterface),
                    new Crater(58, 237, 46, csbApp.appInterface),
                    new Crater(305, 338, 37, csbApp.appInterface),
                    new Crater(352, 407, 27, csbApp.appInterface),
                    new Crater(306, 289, 21, csbApp.appInterface),
                    new Crater(399, 101, 44, csbApp.appInterface),
                    new Crater(380, 71, 24, csbApp.appInterface),
                    new Crater(227, 61, 33, csbApp.appInterface),
                    new Crater(199, 48, 24, csbApp.appInterface),
                    new Crater(265, 3, 22, csbApp.appInterface),
                    new Crater(50, 115, 20, csbApp.appInterface),
                    new Crater(36, 98, 19, csbApp.appInterface),
                    new Crater(410, 150, 21, csbApp.appInterface)
                ],
                "required-score": 8,
                "active-tools": ["circle-button", "eraser-button"],
                "goal": "GOAL: Complete a Medium Image",
                "tutorial-percent": 40
            },
            {
                "type": "text-bubble",
                "title": "Goal: Complete a More Difficult Image",
                "text": "Some images have <b>a few craters</b>, some have <b>no craters</b>.<br />We're going to practice on one with <b>lots of craters</b>!"
            },
            {

                "type": "find-marks",
                "text": "Mark <b>8 craters</b> in this image. We're only interested in craters larger than this: <img src='/csb/csb-content/images/applications/tutorials/red-circle.png' style='width: 16px; height: 16px;' /><br /><b>TIP:</b> If a crater is too small, your circle will be <b style='color: red'>red</b>",
                "delay": 500
            },
            {
                "type": "click",
                "jquery-id": "#submit-button",
                "text": "Great job! Let's move to the last image."
            },
            {
                "type": "set-image",
                "image-location": "/csb/csb-apps/mars_mappers/images/tutorial/mars-tutorial-6.png",
                "correct-marks": [
                    new Crater(172, 38, 18, csbApp.appInterface),
                    new Crater(350, 205, 32, csbApp.appInterface),
                    new Crater(435, 181, 20, csbApp.appInterface),
                    new Crater(438, 230, 27, csbApp.appInterface),
                    new Crater(323, 374, 68, csbApp.appInterface),
                    new Crater(196, 242, 128, csbApp.appInterface, true)
                ],
                "required-score": 4,
                "active-tools": ["circle-button", "eraser-button", "ejecta-button"],
                "tutorial-percent": 60
            },
            {
                "type": "text-bubble",
                "title": "Goal: Find the Ejecta Crater",
                "text": "Sometimes craters throw <b>ejecta</b> out when they're formed.<br />This looks like a <b>splash</b> of material around it."
            },
            {
                "type": "click",
                "jquery-id": "#ejecta-button",
                "text": "Select the <b>Ejecta Crater</b> tool."
            },
            {
                "type": "click",
                "jquery-id": "#app-example-down-arrow",
                "text": "You can see some examples of <b>ejecta</b> over here.<br />Click on these arrows to see more."
            },
            {
                "type": "mark-ejecta",
                "text": "Now, find and mark the <b>crater</b> with ejecta around it.<br />This tool works just like the normal crater tool."
            },
            {
                "type": "text-bubble",
                "text": "Excellent! <b>Ejecta</b> craters are very rare.<br />You might never see another one, but mark it if you do!",
                "tutorial-percent": 80
            },
            {
                "type": "click",
                "jquery-id": "#circle-button",
                "text": "Switch back to the Crater Tool."
            },
            {
                "type": "find-marks",
                "text": "Now mark a few more craters in the image, and we'll be done."
            },
            {
                "type": "click",
                "jquery-id": "#submit-button",
                "text": "Great job! Click here to finish the tutorial."
            },
            {
                "type": "dropdown",
                "title": "Tutorial Complete",
                "text": "Great job! We're going to start the real thing now. <br /><br />Log in, find as many craters in each image as you can, and contribute to real NASA science! <br /><br />Or if you want to keep practicing, continue without logging in.<br /><br /><br />",
                "is-end": true,
                "tutorial-percent": 100
            }
        ]
    )
}
