<?php
/**
 * Created by PhpStorm.
 * User: black
 * Date: 14-12-2020
 * Time: 22:01
 */


// Add bootstrap.
include_once ZKL_SE_BOOTSTRAP_DIR . '/bootstrap.php';
// include stylesheet
wp_enqueue_style('style', '/wp-content/plugins/zeeuwsekringloop-subscribe-extension/includes/bootstrap/style.css');

?>

<div class="container-fluid">
    <h1 class="text-center">Zeeuwsekringloop Subscribe Extension Information</h1>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12 col-xsm-12">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="text-center">Features</h3>
                    <div>
                        <p>
                            - Add new first name, lastname & email through front-end.<br>
                            - View first name, last name & email in backend overview.<br>
                            - Remove record in overview.<br>
                            - Add front-end form through shortcode: <code>[subscriptions]</code>.<br>
                            - Event page is created on plug-in install, you may have to manually add the page to the menu.<br>
                            - Message instead of subscription form, when there are no events available.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xsm-12">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="text-center">Support</h3>
                    <div>
                        <p>
                            Mocht u problemen ondervinden met dit systeem, dan zijn wij van maandag t/m vrijdag van 8.30 tot 17.00 bereikbaar.<br>
                            <br>
                            Onze service staat bij u altijd voorop!
                        </p>
                        <img style="width:200px; height:auto;" src="<?= plugins_url('/zeeuwsekringloop-subscribe-extension/includes/img/logo-black.png'); ?>" alt="IVS logo"><br><br>
                        <a href="tel:+31115641723" class="btn btn-primary">Neem contact op</a>
                        <a href="mailto:support@stichtingivs.nl" class="btn btn-primary">Mail met de support</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xsm-12">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="text-center">Handleiding</h3>
                    <div>
                        <p>
                            Morbi sit amet lorem nec enim lacinia hendrerit. Donec lacinia aliquam sagittis. Mauris turpis nibh, venenatis non nunc sed, vestibulum eleifend ante.
                            Cras in erat lectus. Ut bibendum libero eu malesuada ultrices. In eget augue quam. Donec euismod metus ut nibh ornare laoreet.
                            Etiam iaculis tincidunt justo, tempus pharetra arcu tincidunt vitae.
                            Maecenas ut mi eget est interdum laoreet eu et nunc.
                            Sed auctor sapien non augue porta, at volutpat elit sollicitudin. Nullam sit amet consectetur neque.
                        </p>
                        <a target="_blank" href="<?= plugins_url('/zeeuwsekringloop-subscribe-extension/includes/handleiding/Handleiding.pdf'); ?>" class="btn btn-primary">Download de handleiding</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
