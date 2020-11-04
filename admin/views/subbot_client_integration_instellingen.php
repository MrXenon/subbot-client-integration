<?php
/**
 * Created by PhpStorm.
 * User: Kevin Schuit
 * Date: 4-11-2020
 * Time: 14:03
 */

// Add bootstrap.
include_once SUBBOT_CLIENT_INTEGRATION_PLUGIN_INCLUDES_BOOTSTRAP_DIR . '/bootstrap.php';
// include stylesheet
wp_enqueue_style('style', '/wp-content/plugins/subbot-client-integration/includes/bootstrap/style.css');
?>

<div class="container-fluid">
    <h1 class="text-center">Subbot client integration | Information & support</h1>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12 col-xsm-12">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="text-center">Features</h3>
                    <div>
                        <p>
                            - Create new clients<br>
                            - Update clients<br>
                            - Remove clients<br>
                            - View client list<br>
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
                            If you've run into problems, we're here to support you. We're available from Monday to Friday from 8.30 am till 17.00 pm GMT+1.<br>
                            <br>
                            Your project comes first!
                        </p>
                        <img style="width: 100%;" src="<?= plugins_url('/subbot-client-integration/includes/img/3dynamischLogo.png'); ?>" alt="3Dynamisch logo"><br>
                        <a href="tel:+31636409507" class="btn btn-primary">Contact us</a>
                        <a href="mailto:support@3dynamisch.nl" class="btn btn-primary">Mail with support</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xsm-12">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="text-center">Manual</h3>
                    <div>
                        <p>
                            If you require help with certain steps, please read our manual before contacting our support.
                        </p>
                        <a target="_blank" href="<?= plugins_url('/subbot-client-integration/includes/handleiding/Handleiding.pdf'); ?>" class="btn btn-primary">Download manual</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
