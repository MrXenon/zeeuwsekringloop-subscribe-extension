<?php

//	Add	the	main view shortcode
add_shortcode('subscriptions','load_subscription_view');

function load_subscription_view( $atts, $content = NULL){
    //Include the main view
        include ZKL_SE_INCLUDES_VIEWS_DIR.
            '/subscriptions.php';
}

