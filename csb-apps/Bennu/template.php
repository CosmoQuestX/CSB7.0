<?php

/* setup left bar */

/* setup buttons */
$buttons = array(
    0 => array(
        'img' => 'Button-erase.png',
        'name' => 'Erase'
    ),
    1 => array(
        'img' => 'Button-rocks.png',
        'name' => 'Rocks'
    ),
    2 => array(
        'img' => 'Button-boulder.png',
        'name' => 'Boulder'
    ),
    3 => array(
        'img' => 'Button-crater.png',
        'name' => 'Crater'
    )
);

$defaultButton = 'Boulder';

$exampleSets = array(
    array(
        'name' => 'Rock',
        'examples' => array(
            $BASE_URL . 'csb-apps/Bennu/images/examples/Rocks1.png',
            $BASE_URL . 'csb-apps/Bennu/images/examples/Rocks2.png',
            $BASE_URL . 'csb-apps/Bennu/images/examples/Rocks1.png'
        )
    ),
    array(
        'name' => 'Boulder',
        'examples' => array(
            $BASE_URL . 'csb-apps/Bennu/images/examples/Boulder1.png',
            $BASE_URL . 'csb-apps/Bennu/images/examples/Boulder2.png',
            $BASE_URL . 'csb-apps/Bennu/images/examples/Boulder3.png'
        )
    ),
    array(
        'name' => 'Crater',
        'examples' => array(
            $BASE_URL . 'csb-apps/Bennu/images/examples/Crater1.png',
            $BASE_URL . 'csb-apps/Bennu/images/examples/Crater2.png',
            $BASE_URL . 'csb-apps/Bennu/images/examples/Crater3.png'
        )
    )
);



/* setup right side */
