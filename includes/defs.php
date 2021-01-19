<?php

/**
 * Defenitions needed in the plugin
 * 
 * @author
 * @version 0.1
 * 
 * Version history
 * 0.1      Initial version
 */
// The version has to be equal to the version shown in wordpress inserted in the zeeuwsekringloop-subscribe-extenstion.php file.
define ( 'ZKL_SE_VERSION', '1.0.0' );

// Minimum required Wordpress version for this plugin
define ( 'ZKL_SE_REQUIRED_WP_VERSION', '4.0' );

define ( 'ZKL_SE_BASENAME', plugin_basename( ZKL_SE ) );

define ( 'ZKL_SE_NAME', trim( dirname ( ZKL_SE_BASENAME ), '/' ) );

// Folder Structure
define ( 'ZKL_SE_DIR', untrailingslashit( dirname ( ZKL_SE ) ) );

define ( 'ZKL_SE_INCLUDES_DIR', ZKL_SE_DIR . '/includes' );

define ( 'ZKL_SE_INCLUDES_VIEWS_DIR', ZKL_SE_INCLUDES_DIR	. '/views'	);

define('ZKL_SE_BOOTSTRAP_DIR', ZKL_SE_INCLUDES_DIR . '/bootstrap');

define ( 'ZKL_SE_MODEL_DIR', ZKL_SE_INCLUDES_DIR . '/model' );

define ( 'ZKL_SE_ADMIN_DIR', ZKL_SE_DIR . '/admin' );

define ( 'ZKL_SE_ADMIN_VIEWS_DIR', ZKL_SE_ADMIN_DIR . '/views' );

?>