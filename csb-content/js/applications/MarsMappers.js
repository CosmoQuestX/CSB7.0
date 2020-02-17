function MarsMappers(csbApp) {
    Application.call(this,
        "Mars Mappers",
        {
            "circle-button": [
                "/csb-content/images/applications/mars_mappers/examples/mars-crater-1.png",
                "/csb-content/images/applications/mars_mappers/examples/mars-crater-2.png",
                "/csb-content/images/applications/mars_mappers/examples/mars-crater-13.png",
                "/csb-content/images/applications/mars_mappers/examples/mars-crater-4.png",
                "/csb-content/images/applications/mars_mappers/examples/mars-crater-10.png",
                "/csb-content/images/applications/mars_mappers/examples/mars-crater-3.png",
                "/csb-content/images/applications/mars_mappers/examples/mars-crater-7.png",
                "/csb-content/images/applications/mars_mappers/examples/mars-crater-8.png",
                "/csb-content/images/applications/mars_mappers/examples/mars-crater-5.png",
                "/csb-content/images/applications/mars_mappers/examples/mars-crater-6.png",
                "/csb-content/images/applications/mars_mappers/examples/mars-crater-9.png",
                "/csb-content/images/applications/mars_mappers/examples/mars-crater-11.png",
                "/csb-content/images/applications/mars_mappers/examples/mars-crater-12.png"
            ],
            "eraser-button": [],
            "ejecta-button": [
                "/csb-content/images/applications/mars_mappers/examples/mars-ejecta-1.png",
                "/csb-content/images/applications/mars_mappers/examples/mars-ejecta-2.png",
                "/csb-content/images/applications/mars_mappers/examples/mars-ejecta-3.png",
                "/csb-content/images/applications/mars_mappers/examples/mars-ejecta-4.png",
                "/csb-content/images/applications/mars_mappers/examples/mars-ejecta-5.png"
            ]
        },
        [
            {
                "type": "set-image",
                "image-location": "/csb-content/images/applications/tutorials/mars/mars-tutorial-1.png",
                "correct-marks": [
                    new Crater(145, 128, 27, csbApp.appInterface),
                    new Crater(161, 85, 25, csbApp.appInterface),
                    new Crater(278, 263, 80, csbApp.appInterface)
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
                "text": "Welcome to Mars Mappers! To start,<br />  we'll quickly explain how to do the following: <ol><li><img src='/csb-content/images/applications/tutorials/icons/eraser.png' /> Erase a mistake</li><li><img src='/csb-content/images/applications/tutorials/icons/crater.png' /> Find a crater</li><li><img src='/csb-content/images/applications/tutorials/icons/light-image.png' /> Complete an image</li><li><img src='/csb-content/images/applications/tutorials/icons/ejecta.png' /> Find crater ejecta</li>",
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
                    new Crater(278, 263, 80, csbApp.appInterface),
                    new Crater(161, 85, 25, csbApp.appInterface)
                ]
            },
            {
                "type": "click",
                "jquery-id": "#circle-button",
                "text": "Now you can mark the <b>third crater</b>!<br />Select the Crater Tool."
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
                "text": "Mark the last crater in the image by clicking and dragging the mouse."
            },
            {
                "type": "click",
                "jquery-id": "#submit-button",
                "text": "Awesome job! Now click here to go to the next image!"
            },
            {
                "type": "set-image",
                "image-location": "/csb-content/images/applications/tutorials/mars/mars-tutorial-2.png",
                "correct-marks": [
                    new Crater(60, 45, 60),
                    new Crater(148, 67, 27),
                    new Crater(45, 157, 32),
                    new Crater(129, 369, 32),
                    new Crater(224, 311, 25),
                    new Crater(388, 159, 34),
                    new Crater(409, 101, 32),
                    new Crater(365, 100, 66)
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
                "text": "Mark the <b>8 largest craters</b>. We're only interested in craters larger than this: <img src='/csb-content/images/applications/tutorials/red-circle.png' style='width: 16px; height: 16px;' /><br /><b>TIP:</b> If a crater is too small, your circle will be <b style='color: red'>red</b>",
                "delay": 500
            },
            {
                "type": "click",
                "jquery-id": "#submit-button",
                "text": "Great job! Let's move to the last image."
            },
            {
                "type": "set-image",
                "image-location": "/csb-content/images/applications/tutorials/mars/mars-tutorial-3.png",
                "correct-marks": [
                    new Crater(216, 44, 107, csbApp.appInterface),
                    new Crater(338, 12, 21, csbApp.appInterface),
                    new Crater(376, 140, 23, csbApp.appInterface),
                    new Crater(192, 192, 60, csbApp.appInterface),
                    new Crater(63, 280, 35, csbApp.appInterface),
                    new Crater(299, 256, 94, csbApp.appInterface, true)
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