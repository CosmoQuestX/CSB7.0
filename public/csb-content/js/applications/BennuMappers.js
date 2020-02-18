function BennuMappers(csbApp) {
    var boulder1 = new Segment(122, 24, "boulder", csbApp.appInterface);
    boulder1.points = [{x: 122, y: 24}, {x: 136, y: 11}];
    var boulder2 = new Segment(104, 184, "boulder", csbApp.appInterface);
    boulder2.points = [{x: 104, y: 184}, {x: 114, y: 170}];
    var boulder3 = new Segment(123, 245, "boulder", csbApp.appInterface);
    boulder3.points = [{x: 123, y: 245}, {x: 131, y: 231}];


    Application.call(this,
        "Bennu Mappers",
        {
            "circle-button": [
                "/csb-content/images/applications/bennu/examples/Crater1.png",
                "/csb-content/images/applications/bennu/examples/Crater2.png",
                "/csb-content/images/applications/bennu/examples/Crater3.png"
            ],
            "eraser-button": [],
            "rock-button": [
                "/csb-content/images/applications/bennu/examples/rock1.png",
                "/csb-content/images/applications/bennu/examples/rock2.png",
                "/csb-content/images/applications/bennu/examples/rock3.png",
            ],
            "boulder-button": [
                "/csb-content/images/applications/bennu/examples/boulder1.png",
                "/csb-content/images/applications/bennu/examples/boulder2.png",
                "/csb-content/images/applications/bennu/examples/boulder3.png",
            ]
        },
        [
            {
                "type": "set-image",
                "image-location": "/csb-content/images/applications/bennu/bennu-tutorial-1.png",
                "correct-marks": [
                    new Crater(347, 132, 42, csbApp.appInterface),
                    new Crater(107, 53, 33, csbApp.appInterface),
                    boulder1,
                    boulder2,
                    boulder3,
                    new Rock(76, 60, 6, csbApp.appInterface),
                    new Rock(153, 76, 6, csbApp.appInterface),
                    new Rock(132, 37, 6, csbApp.appInterface),
                    new Rock(168, 36, 6, csbApp.appInterface),
                    new Rock(208, 60, 6, csbApp.appInterface),
                    new Rock(188, 113, 6, csbApp.appInterface),
                    new Rock(258, 145, 6, csbApp.appInterface),
                    new Rock(240, 136, 6, csbApp.appInterface),
                    new Rock(242, 147, 6, csbApp.appInterface),
                    new Rock(249, 160, 6, csbApp.appInterface),
                    new Rock(258, 200, 6, csbApp.appInterface),
                    new Rock(232, 179, 6, csbApp.appInterface),
                    new Rock(224, 197, 6, csbApp.appInterface),
                    new Rock(246, 212, 6, csbApp.appInterface),
                    new Rock(243, 235, 6, csbApp.appInterface),
                    new Rock(268, 232, 6, csbApp.appInterface),
                    new Rock(271, 220, 6, csbApp.appInterface),
                    new Rock(289, 216, 6, csbApp.appInterface),
                    new Rock(279, 180, 6, csbApp.appInterface),
                    new Rock(298, 178, 6, csbApp.appInterface),
                    new Rock(322, 180, 6, csbApp.appInterface),
                    new Rock(318, 206, 6, csbApp.appInterface),
                    new Rock(140, 129, 6, csbApp.appInterface),
                    new Rock(186, 163, 6, csbApp.appInterface),
                    new Rock(192, 199, 6, csbApp.appInterface),
                    new Rock(171, 213, 6, csbApp.appInterface),
                    new Rock(144, 196, 6, csbApp.appInterface),
                    new Rock(104, 154, 6, csbApp.appInterface),
                    new Rock(99, 140, 6, csbApp.appInterface),
                    new Rock(81, 135, 6, csbApp.appInterface),
                    new Rock(56, 145, 6, csbApp.appInterface),
                    new Rock(47, 112, 6, csbApp.appInterface),
                    new Rock(71, 96, 6, csbApp.appInterface),
                    new Rock(85, 101, 6, csbApp.appInterface),
                    new Rock(67, 116, 6, csbApp.appInterface),
                    new Rock(41, 216, 6, csbApp.appInterface),
                    new Rock(61, 191, 6, csbApp.appInterface),
                    new Rock(73, 197, 6, csbApp.appInterface),
                    new Rock(23, 211, 6, csbApp.appInterface),
                    new Rock(14, 227, 6, csbApp.appInterface),
                    new Rock(28, 231, 6, csbApp.appInterface),
                    new Rock(7, 261, 6, csbApp.appInterface),
                    new Rock(28, 258, 6, csbApp.appInterface),
                    new Rock(48, 238, 6, csbApp.appInterface),
                    new Rock(72, 228, 6, csbApp.appInterface),
                    new Rock(94, 229, 6, csbApp.appInterface),
                    new Rock(103, 208, 6, csbApp.appInterface),
                    new Rock(108, 225, 6, csbApp.appInterface),
                    new Rock(124, 226, 6, csbApp.appInterface),
                    new Rock(100, 253, 6, csbApp.appInterface),
                    new Rock(111, 271, 6, csbApp.appInterface),
                    new Rock(77, 264, 6, csbApp.appInterface),
                    new Rock(63, 250, 6, csbApp.appInterface),
                    new Rock(45, 271, 6, csbApp.appInterface),
                    new Rock(40, 286, 6, csbApp.appInterface),
                    new Rock(40, 300, 6, csbApp.appInterface),
                    new Rock(41, 314, 6, csbApp.appInterface),
                    new Rock(64, 298, 6, csbApp.appInterface),
                    new Rock(84, 331, 6, csbApp.appInterface),
                    new Rock(99, 358, 6, csbApp.appInterface),
                    new Rock(101, 374, 6, csbApp.appInterface),
                    new Rock(115, 325, 6, csbApp.appInterface),
                    new Rock(127, 343, 6, csbApp.appInterface),
                    new Rock(136, 333, 6, csbApp.appInterface),
                    new Rock(139, 300, 6, csbApp.appInterface),
                    new Rock(142, 293, 6, csbApp.appInterface),
                    new Rock(170, 315, 6, csbApp.appInterface),
                    new Rock(190, 306, 6, csbApp.appInterface),
                    new Rock(191, 329, 6, csbApp.appInterface),
                    new Rock(193, 269, 6, csbApp.appInterface),
                    new Rock(207, 285, 6, csbApp.appInterface),
                    new Rock(211, 275, 6, csbApp.appInterface),
                    new Rock(220, 291, 6, csbApp.appInterface),
                    new Rock(235, 312, 6, csbApp.appInterface),
                    new Rock(247, 307, 6, csbApp.appInterface),
                    new Rock(245, 316, 6, csbApp.appInterface),
                    new Rock(178, 370, 6, csbApp.appInterface),
                    new Rock(191, 375, 6, csbApp.appInterface),
                    new Rock(174, 381, 6, csbApp.appInterface),
                    new Rock(137, 400, 6, csbApp.appInterface),
                    new Rock(145, 406, 6, csbApp.appInterface),
                    new Rock(162, 411, 6, csbApp.appInterface),
                    new Rock(164, 421, 6, csbApp.appInterface),
                    new Rock(204, 442, 6, csbApp.appInterface),
                    new Rock(179, 439, 6, csbApp.appInterface),
                    new Rock(110, 445, 6, csbApp.appInterface),
                    new Rock(39, 444, 6, csbApp.appInterface),
                    new Rock(29, 431, 6, csbApp.appInterface),
                    new Rock(17, 427, 6, csbApp.appInterface),
                    new Rock(5, 412, 6, csbApp.appInterface),
                    new Rock(39, 378, 6, csbApp.appInterface),
                    new Rock(20, 367, 6, csbApp.appInterface),
                    new Rock(65, 376, 6, csbApp.appInterface),
                    new Rock(93, 390, 6, csbApp.appInterface),
                    new Rock(26, 331, 6, csbApp.appInterface),
                    new Rock(98, 289, 6, csbApp.appInterface),
                    new Rock(201, 412, 6, csbApp.appInterface),
                    new Rock(221, 402, 6, csbApp.appInterface),
                    new Rock(217, 396, 6, csbApp.appInterface),
                    new Rock(236, 416, 6, csbApp.appInterface),
                    new Rock(242, 382, 6, csbApp.appInterface),
                    new Rock(221, 361, 6, csbApp.appInterface),
                    new Rock(212, 325, 6, csbApp.appInterface),
                    new Rock(253, 364, 6, csbApp.appInterface),
                    new Rock(265, 399, 6, csbApp.appInterface),
                    new Rock(280, 441, 6, csbApp.appInterface),
                    new Rock(298, 427, 6, csbApp.appInterface),
                    new Rock(331, 433, 6, csbApp.appInterface),
                    new Rock(354, 430, 6, csbApp.appInterface),
                    new Rock(366, 415, 6, csbApp.appInterface),
                    new Rock(330, 409, 6, csbApp.appInterface),
                    new Rock(339, 396, 6, csbApp.appInterface),
                    new Rock(343, 381, 6, csbApp.appInterface),
                    new Rock(364, 388, 6, csbApp.appInterface),
                    new Rock(378, 369, 6, csbApp.appInterface),
                    new Rock(391, 371, 6, csbApp.appInterface),
                    new Rock(402, 361, 6, csbApp.appInterface),
                    new Rock(406, 357, 6, csbApp.appInterface),
                    new Rock(430, 341, 6, csbApp.appInterface),
                    new Rock(428, 327, 6, csbApp.appInterface),
                    new Rock(412, 330, 6, csbApp.appInterface),
                    new Rock(412, 318, 6, csbApp.appInterface),
                    new Rock(359, 332, 6, csbApp.appInterface),
                    new Rock(345, 321, 6, csbApp.appInterface),
                    new Rock(316, 332, 6, csbApp.appInterface),
                    new Rock(303, 342, 6, csbApp.appInterface),
                    new Rock(303, 354, 6, csbApp.appInterface),
                    new Rock(275, 334, 6, csbApp.appInterface),
                    new Rock(266, 288, 6, csbApp.appInterface),
                    new Rock(318, 306, 6, csbApp.appInterface),
                    new Rock(340, 290, 6, csbApp.appInterface),
                    new Rock(361, 294, 6, csbApp.appInterface),
                    new Rock(365, 309, 6, csbApp.appInterface),
                    new Rock(374, 287, 6, csbApp.appInterface),
                    new Rock(383, 305, 6, csbApp.appInterface),
                    new Rock(407, 271, 6, csbApp.appInterface),
                    new Rock(436, 268, 6, csbApp.appInterface),
                    new Rock(419, 250, 6, csbApp.appInterface),
                    new Rock(397, 253, 6, csbApp.appInterface),
                    new Rock(319, 243, 6, csbApp.appInterface),
                    new Rock(347, 226, 6, csbApp.appInterface),
                    new Rock(436, 235, 6, csbApp.appInterface),
                    new Rock(423, 221, 6, csbApp.appInterface),
                    new Rock(435, 182, 6, csbApp.appInterface),
                    new Rock(425, 165, 6, csbApp.appInterface),
                    new Rock(429, 158, 6, csbApp.appInterface),
                    new Rock(406, 154, 6, csbApp.appInterface),
                    new Rock(396, 148, 6, csbApp.appInterface),
                    new Rock(397, 139, 6, csbApp.appInterface),
                    new Rock(378, 161, 6, csbApp.appInterface),
                    new Rock(302, 116, 6, csbApp.appInterface),
                    new Rock(319, 124, 6, csbApp.appInterface),
                    new Rock(321, 133, 6, csbApp.appInterface),
                    new Rock(328, 144, 6, csbApp.appInterface),
                    new Rock(268, 93, 6, csbApp.appInterface),
                    new Rock(245, 107, 6, csbApp.appInterface),
                    new Rock(217, 84, 6, csbApp.appInterface),
                    new Rock(246, 68, 6, csbApp.appInterface),
                    new Rock(266, 61, 6, csbApp.appInterface),
                    new Rock(276, 59, 6, csbApp.appInterface),
                    new Rock(264, 45, 6, csbApp.appInterface),
                    new Rock(294, 81, 6, csbApp.appInterface),
                    new Rock(310, 83, 6, csbApp.appInterface),
                    new Rock(333, 91, 6, csbApp.appInterface),
                    new Rock(344, 97, 6, csbApp.appInterface),
                    new Rock(383, 73, 6, csbApp.appInterface),
                    new Rock(398, 59, 6, csbApp.appInterface),
                    new Rock(415, 82, 6, csbApp.appInterface),
                    new Rock(442, 97, 6, csbApp.appInterface),
                    new Rock(418, 108, 6, csbApp.appInterface),
                    new Rock(441, 28, 6, csbApp.appInterface),
                    new Rock(353, 16, 6, csbApp.appInterface),
                    new Rock(365, 10, 6, csbApp.appInterface),
                    new Rock(373, 3, 6, csbApp.appInterface),
                    new Rock(374, 14, 6, csbApp.appInterface),
                    new Rock(347, 52, 6, csbApp.appInterface),
                    new Rock(311, 28, 6, csbApp.appInterface),
                    new Rock(303, 46, 6, csbApp.appInterface),
                    new Rock(292, 9, 6, csbApp.appInterface),
                    new Rock(267, 4, 6, csbApp.appInterface),
                    new Rock(216, 13, 6, csbApp.appInterface),
                    new Rock(189, 5, 6, csbApp.appInterface),
                    new Rock(68, 24, 6, csbApp.appInterface),
                    new Rock(75, 35, 6, csbApp.appInterface),
                    new Rock(102, 7, 6, csbApp.appInterface),
                    new Rock(147, 17, 6, csbApp.appInterface),
                    new Rock(155, 28, 6, csbApp.appInterface),
                    new Rock(167, 354, 6, csbApp.appInterface),
                    new Rock(159, 340, 6, csbApp.appInterface),
                    new Rock(346, 189, 6, csbApp.appInterface),
                    new Rock(267, 265, 6, csbApp.appInterface),
                    new Rock(289, 258, 6, csbApp.appInterface),
                    new Rock(155, 255, 6, csbApp.appInterface),
                    new Rock(198, 242, 6, csbApp.appInterface),
                    new Rock(177, 154, 6, csbApp.appInterface),
                    new Rock(168, 153, 6, csbApp.appInterface),
                    new Rock(119, 308, 6, csbApp.appInterface),
                    new Rock(25, 118, 6, csbApp.appInterface),
                    new Rock(41, 67, 6, csbApp.appInterface),
                    new Rock(188, 78, 6, csbApp.appInterface),
                    new Rock(131, 67, 6, csbApp.appInterface),
                    new Rock(123, 71, 6, csbApp.appInterface),
                    new Rock(143, 92, 6, csbApp.appInterface),
                    new Rock(153, 52, 6, csbApp.appInterface),
                    new Rock(391, 319, 6, csbApp.appInterface),
                    new Rock(174, 107, 6, csbApp.appInterface),
                    new Rock(187, 100, 6, csbApp.appInterface)
                ],
                "existing-marks": [
                    new Crater(250, 172, 48, csbApp.appInterface)
                ],
                "required-score": 80,
                "tutorial-percent": 0
            },
            {
                "type": "dropdown",
                "title": "Tutorial",
                "text": "<div class='app-description'>Welcome to Bennu Mappers! To start,<br />  we'll quickly explain how to do the following:</div><ol><li><img src='/csb-content/images/buttons/Button-erase.png' /> Erase a mistake</li><li><img src='/csb-content/images/buttons/Button-crater.png' /> Find craters</li><li><img src='/csb-content/images/buttons/Button-boulder.png' /> Measure boulders</li><li><img src='/csb-content/images/buttons/Button-rocks.png' /> Mark rocks(!!)</li>",
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
                "text": "Nice! Now you need to find the <b>craters</b> in this image.<br />(Bennu's craters will be much harder to see - this is Asteroid Itokawa.)<br /> Craters are <b>circular holes</b> in the ground with</br /><b>bright sunlight</b> on one side and a <b>dark shadow</b> on the other.",
                "delay": 500,
                "tutorial-percent": 10
            },
            {
                "type": "click",
                "jquery-id": "#circle-button",
                "text": "Select the Crater Tool."
            },
            {
                "type": "click",
                "jquery-id": "#app-example-down-arrow",
                "text": "You can see some examples of <b>craters</b> over here.<br />Click on these arrows to see more.",
                "delay": 500,
                "tutorial-percent": 20
            },
            {
                "type": "text-bubble",
                "text": "I'll show you how to mark one, then you can get the other!",
                "delay": 500
            },
            {
                "type": "auto-create-mark",
                "marks": [
                    new Crater(107, 53, 33, csbApp.appInterface)
                ]
            },
            {
                "type": "find-marks",
                "mark-types": ["crater"],
                "text": "Mark the last large crater in the image by clicking and dragging the mouse."
            },
            {
                "type": "click",
                "jquery-id": "#hide-marks-toggle",
                "text": "Great job! If you ever need a better view of the image,<br /> you can <b>hide</b> the circles that you've made by clicking here.",
                "tutorial-percent": 30
            },
            {
                "type": "click",
                "jquery-id": "#hide-marks-toggle,#show-marks-toggle",
                "text": "Click here to <b>show</b> the marks again.",
                "delay": 250
            },
            {
                "type": "text-bubble",
                "title": "Marking Boulders",
                "text": "You might have noticed that there are a few smaller crater-like things in the image.<br />These are actually <b>boulders</b>.<br />The shadows are on the opposite side compared to the craters,<br /> the edges are sharper, and that they <i>usually</i> aren't circular. Bennu has dozens or more boulders in each image!",
                "delay": 500,
                "tutorial-percent": 40
            },
            {
                "type": "click",
                "jquery-id": "#boulder-button",
                "text": "Select the Boulder Tool."
            },
            {
                "type": "text-bubble",
                "text": "I'll mark two of them, and you can get the last one!",
                "delay": 500
            },
            {
                "type": "auto-create-mark",
                "marks": [
                    boulder3,
                    boulder1
                ]
            },
            {
                "type": "find-marks",
                "mark-types": ["crater", "boulder"],
                "text": "Mark the last boulder by clicking and dragging the mouse.<br />We want the line to go across the <b>longest</b> axis of the boulder.",
                "tutorial-percent": 50
            },
            {
                "type": "text-bubble",
                "title": "Marking Craters",
                "text": "Great job! Lastly, we're going to mark the rocks in the image.<br />Rocks are each of the tiny dots and circles in the image.<br />There are <b>over 100</b> of them in this image, and we want to mark as many as we can.",
                "delay": 500,
                "tutorial-percent": 60
            },
            {
                "type": "click",
                "jquery-id": "#rock-button",
                "text": "Select the Rock Tool."
            },
            {
                "type": "text-bubble",
                "text": "I'll mark a few of them, and you can get the rest!",
                "delay": 500
            },
            {
                "type": "auto-create-mark",
                "marks": [
                    new Rock(140, 129, 6, csbApp.appInterface),
                    new Rock(324, 180, 6, csbApp.appInterface),
                    new Rock(235, 416, 6, csbApp.appInterface)
                ]
            },
            {
                "type": "wait-until-marks-have-been-made",
                "text": "Now you try marking as many rocks as you can. We won't get all of them, but let's try for 80.",
                "mark-count": 80,
                "tutorial-percent": 70
            },
            {
                "type": "text-bubble",
                "title": "Marking Craters",
                "text": "Awesome! Remember: In a real image, you want to mark <b>all</b> of the rocks that you can find. <br />Every tiny dot. It will take a while, but it's very important!",
                "delay": 500,
                "tutorial-percent": 80
            },
            {
                "type": "click",
                "jquery-id": "#submit-button",
                "text": "Finally, click here to complete the image and move to the next one!",
                "force-image-complete": true,
                "tutorial-percent": 90
            },
            {
                "type": "dropdown",
                "title": "Tutorial Complete",
                "text": "Great job! We're going to start the real thing now. <br /><br />Remember: the tutorial showed you Asteroid Itokowa. Now you will see Bennu!<br /><br />Log in if needed, measure boulders & craters, mark rocks, and contribute to real NASA science! <br />",
                "is-end": true,
                "tutorial-percent": 100
            }
        ]
    )
}