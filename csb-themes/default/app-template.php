<?php
/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/4/19
 * Time: 1:18 PM
 */
?>
<div class="container mt-3">
    <div class="row">

        <!-- Left block --------------------------------------------------------------- -->
        <div id="app-left" class="col-md-3 p-4">
            <?php $txt = $lang['app_page']['text-boxes']['app-left']; ?>
            <h2><?php echo $txt['title']; ?></h2>
            <p> <?php echo $txt['fact1-title']; ?><br/>
                <?php echo $txt['fact1-content']; ?></p>
            <p> <?php echo $txt['fact2-title']; ?><br/>
                <?php echo $txt['fact2-content']; ?></p>
            <p> <?php echo $txt['fact3-title']; ?><br/>
                <?php echo $txt['fact3-content']; ?></p>
            <p> <?php echo $txt['completed']; ?><br/>
                #####</p>
            <p><?php echo $txt['remaining']; ?><br/>
                #####</p>
            <p><?php echo $txt['dueDate']; ?><br/>
                <?php echo $txt['dueDateValue']; ?></p>

            <div id="app-examples">
                <h4><?php echo $txt['examples']; ?></h4>
                <?php
                $n = 0;

                foreach ($exampleSets as $exampleSet) {

                    if ($exampleSet['name'] == $defaultButton) $status = "";
                    else $status = ' hide';

                    echo "<div class='" . $exampleSet['name'] . $status . "''>";
                    foreach ($exampleSet['examples'] as $example) {
                        echo "<img class='example-image left' src='" . $example . "' alt='Example " . $exampleSet['name'] . " " . $n . "'>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <!-- main block --------------------------------------------------------------- -->
        <?php $txt = $lang['app_page']['text-boxes']['app-main']; ?>
        <div id="app-main" class="col-md-6 p-4">
            <div class="row">
                <button id="app-launcher" type="button" class="btn btn-primary"><?php echo $txt['app-button']; ?></button>
            </div>
        </div>

        <!-- Right block ---------------------------------------------------------------->
        <?php $txt = $lang['app_page']['text-boxes']['app-right']; ?>
        <div id="app-right" class="col-md-3 p-4">
            <h1><?php echo $txt['title']; ?></h1>
            <p><?php echo $txt['blurb']; ?></p>
            <p><?php echo $txt['footer']; ?></p>
            <input type="button" value="Discord"><input type="button" value="Twitch"><br/>
            <!-- <iframe src="https://titanembeds.com/embed/443490369443856384" height="245" width="350"></iframe> -->
        </div>
    </div>
</div>

<div id="cq-mapping-tool" style="display: none;">
    <div id="invisible-app-cover" style="display: none;"></div>
    <div id="science-application">
        <div id="app-header">
            <div id="app-title">CosmoQuest Mapping Tool</div>
            <div id="x-button" style="float: right;"><img src="/csb-content/images/applications/x-button.png" style="width: 18px; height: 18px;" /></div>
            <div id="tutorial-steps-complete">
                <div id="tutorial-steps-complete-background">
                    <div id="tutorial-steps-complete-foreground"></div>
                    <div id="tutorial-steps-complete-text">0% Complete</div>
                </div>
            </div>
        </div>
        <div id="science-application-inner">
            <div class="app-left-side">
                <div class="app-sidebar-title">Tools</div>
                <div id="app-tool-buttons-box">
                    <table class="app-tool-buttons-table">
                        <tr>
                            <td id="circle-button" class="app-tool-button selected muted-button" title="Mark a Crater"><img src="/csb-content//images/applications/buttons/circle-button.png" /></td>
                            <td id="eraser-button" class="app-tool-button not-selected muted-button" title="Erase a Mistake"><img src="/csb-content//images/applications/buttons/eraser-button.png" /></td>
                        </tr><tr>
                            <td id="ejecta-button" class="app-tool-button not-selected muted-button" title="Mark Crater Ejecta"><img src="/csb-content/images/applications/buttons/ejecta-button.png" /></td>
                            <!--<td id="concentric-crater-button" class="app-tool-button not-selected muted-button" title="Mark a Concentric Crater"><img src="/csb-content/images/applications/buttons/concentric-crater-button.png" /></td>-->
                            <td id="crater-chain-button" class="app-tool-button not-selected muted-button" title="Mark a Crater Chain"><img src="/csb-content/images/applications/buttons/crater-chain-button.png" /></td>
                        </tr><tr>
                            <td id="boulder-button" class="app-tool-button not-selected muted-button" title="Mark a Boulder"><img src="/csb-content/images/applications/buttons/boulder-button.png" /></td>
                            <td id="rock-button" class="app-tool-button not-selected muted-button" title="Rocks Tool"><img src="/csb-content/images/applications/buttons/rocks-button.png" /></td>
                        </tr>
                    </table>

                    <br />
                    <!--
                        <div id="app-tool-help-button" class="app-tool-button selected" style="width: 50px;" title="Mark a Crater"><img src="/csb-content/images/applications/buttons/help-button.png" /></div>
                        <br />
                    -->
                    <hr />
                    <p>Hide Your Marks?</p>
                    <table class="app-tool-buttons-table">
                        <tr>
                            <td id="show-marks-toggle" class="app-tool-button selected small-td muted-button">Show</td>
                            <td id="hide-marks-toggle" class="app-tool-button not-selected small-td muted-button">Hide</td>
                        </tr>
                    </table>
                    <hr />
                </div>
                <div id="app-bottom-left-box">
                    <div id="app-tutorial-button" class="app-bottom-left-button">Skip Tutorial</div>
                    <div id="app-login-popup-button" class="app-bottom-left-button">Login</div>
                </div>
            </div>
            <div class='app-middle'>
                <div id="app-image-display">
                    <canvas class="canvas" id="project_canvas">
                        <p>Your browser is too old to use canvas. Here are links to update your browser.</p>
                        <a href="http://windows.microsoft.com/en-us/internet-explorer/products/ie/home"><img src="/csb-content/images/browsers/ie.png"></a>
                        <a href="http://www.mozilla.org/en-US/firefox/new/"><img src="/csb-content/images/browsers/firefox.png"></a>
                        <a href="https://www.google.com/chrome"><img src="/csb-content/images/browsers/chrome.png"></a>
                        <a href="http://www.apple.com/safari/download/"><img src="/csb-content/images/browsers/safari.png"></a>
                        <a href="http://www.opera.com/download/"><img src="/csb-content/images/browsers/opera.png"></a>
                    </canvas>
                </div>
            </div>

            <div class="app-right-side">
                <div class="app-sidebar-title">Examples</div>
                <img id="app-example-up-arrow" src="/csb-content/images/applications/buttons/up-arrow.png" />
                <div id="example-images">
                    <img class="example-image" src="/csb-content/images/applications/mercury_mappers/examples/craters/crater1.png" />
                    <img class="example-image" src="/csb-content/images/applications/mercury_mappers/examples/craters/crater2.png" />
                    <img class="example-image" src="/csb-content/images/applications/mercury_mappers/examples/craters/crater3.png" />
                </div>
                <img id="app-example-down-arrow" src="/csb-content/images/applications/buttons/down-arrow.png" />
                <hr />
                <div id="submit-button" class="submit-button submit-pressed">
                    Image Done!
                </div>
            </div>
            <div id='marker-type'>
                <div id='marker-name'></div>
            </div>
            <div id="flying_image_div"></div>
            <div id="mask"><div id="message_box"></div></div>
        </div>
        <div id="app-examples-container">
            <div id="app-login-register-window" class="app-example">
                <div id="app-login-side">
                    <h2>Login</h2>
                    <ul>
                        <li>Username <input id="app-login-username" type="text" /></li>
                        <li>Password <input id="app-login-password" type="password" /></li>
                        <li id="app-forgot-password"><a href="/password/reset">I forgot my password</a></li>
                        <li id="app-remember-me"><input type="checkbox"> Remember Me</li>
                        <li id="app-login-error-text"></li>
                    </ul>
                    <div id="app-login-button-container">
                        <div id="app-login-button" class="app-button">Login</div>
                    </div>
                </div>
                <div id="app-register-side">
                    <h2>Register</h2>
                    <ul>
                        <li>Username <input id="app-register-username" type="text" /></li>
                        <li>Email <input id="app-register-email" type="text" /></li>
                        <li>Password <input id="app-register-password" type="password" placeholder=""/></li>
                        <li style="margin-top: 0"><p class="help-block" style="float: right;margin-bottom: 0; font-size: .7em;">Must be at least 8 characters.</p></li>
                        <li style="margin-top: 0">Password (again) <input id="app-register-password-confirm" type="password" /></li>
                        <li id="app-register-error-text"></li>
                    </ul>
                    <div id="app-register-button-container">
                        <div id="app-register-button" class="app-button">Register</div>
                    </div>
                </div>
                <div>
                    <p class="help-block">Login with Social Media</p>
                    <a href="/auth/login/facebook" class="app-button"><span class="link-facebook-icon"><i class="fa fa-facebook" aria-hidden="true"></i></span>Facebook</a>
                    <a href="/auth/login/google" class="app-button"><span class="link-google-icon"><i class="fa fa-google" aria-hidden="true"></i></span>Google</a>
                    <a href="/auth/login/twitch" class="app-button"><span class="link-twitch-icon"><i class="fa fa-twitch" aria-hidden="true"></i></span>Twitch</a>
                    <a href="/auth/login/twitter" class="app-button"><span class="link-twitter-icon"><i class="fa fa-twitter" aria-hidden="true"></i></span>Twitter</a>
                    <a href="/auth/login/github" class="app-button"><span class="link-github-icon"><i class="fa fa-github" aria-hidden="true"></i></span>Github</a>
                    <a href="/auth/login/scistarter" class="app-button">SciStarter</a>
                </div>
                <div id="app-dont-want-to-login-button" class="app-button">I Don't Want to Login</div>
            </div>
            <div id="app-example-circle-button" class="app-example">
                <h2>Craters</h2>
                <ul>
                    <li>Craters are bowl-shaped holes made by asteroids colliding with the surface.</li>
                    <li>Because of a crater's shape and the angle of the sun, a crater...
                        <ul>
                            <li>will be roughly <b>circular</b>.</li>
                            <li>will usually have <b>light</b> on the left side.</li>
                            <li>will have a <b>shadow</b> on the right side.</li>
                        </ul>
                    </li>
                </ul>
                <video controls preload='metadata' id="mercury-crater-video" class='example-video'><source src='/csb-content/images/applications/tutorials/mercury/crater-video.mp4' type='video/mp4'>You need to update your browser to view animations. </video>
                <div class="app-button okay-button">Got it</div>
            </div>
            <div id="app-example-eraser-button" class="app-example">
                <h2>Eraser Tool</h2>
                <h3>Clicking on a mark deletes it when using the eraser tool.</h3>
                <video></video>
                <div class="app-button okay-button">Got it</div>
            </div>
            <div id="app-mobile-window" class="app-example">
                <h2>You need to use a computer for Image Detective</h2>
                <h3>Because of the limited screen space available on your mobile devices, it's difficult to see details in photos, so we're currently requiring that people use a computer. We're working on support for tablets, and are testing different phones as well, so stay tuned!</h3>
                <div class="app-button okay-button" style="width: 15em; margin-left: -7.5em;">Close Image Detective</div>
            </div>
            <div id="app-tutorial-overlay" style="display: none">
                <h1 id="app-tutorial-title">Welcome to Image Detective!</h1>
                <h2 id="app-tutorial-text"></h2>
                <div id='app-tutorial-register-button' class='app-button app-tutorial-last-page-button' style='display: none'>Register</div>
                <div id='app-tutorial-login-button' class='app-button app-tutorial-last-page-button' style='display: none'>Login</div>
                <div id='app-tutorial-keep-practicing' class='app-button app-tutorial-last-page-button' style='display: none'>Keep practicing</div>
                <div id="app-tutorial-okay-button" class="app-button app-large-button">Start</div>
            </div>
        </div>
    </div>
    <div id="text-bubble-arrow" style="display: none"></div>
    <div id="text-bubble-blob" style="display: none">
        <h4></h4>
        <p></p>
        <div id="text-bubble-okay-button" style="display: none">Okay</div>
    </div>
    <canvas id='zoom-canvas' width='100' height='100'></canvas>
</div>