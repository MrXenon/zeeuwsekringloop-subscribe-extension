<?php

defined( 'ABSPATH' ) OR exit;

/**
 * Plugin Name: ZeeuweseKringLoop subscription extension
 * Plugin URI: https://github.com/MrXenon
 * Description: This add-on will show add a form in the front-end where your guests can enter their name and e-mail. On submussion this information will be stored in the database and shown in the backend. Additionally an e-mail will be send on submission with the send data.
 * Author: Kevin Schuit
 * Author URI: https://kevinschuit.com
 * Version: 1.0.0
 * Text Domain: zeeuwsekringloop-subscription-extenstion
 * Domain Path: languages
 * 
 * This is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even teh implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
 * GNU General Public License for more details.
 * 
 * You should have received a cpoy of the GNU General Publilc License 
 * along with your plugin. If not, see <http://www.gnu.org/licenses/>.
 */

 //Define the plugin name:
 //Activeren en deactiveren
 define ( 'ZKL_SE', __FILE__ );

 //Inculde the general defenition file:
 require_once plugin_dir_path ( __FILE__ ) . 'includes/defs.php';

/* Register the hooks */
    register_activation_hook( __FILE__, array( 'ZeeuwseKringLoopSubscribeExtension', 'on_activation' ) );
    register_deactivation_hook( __FILE__, array( 'ZeeuwseKringLoopSubscribeExtension', 'on_deactivation' ) );
 
 class ZeeuwseKringLoopSubscribeExtension
 {
     public function __construct()
     {

         //Fire a hook before the class is setup.
         do_action('zkl_se_pre_init');

         //Load the plugin
         add_action('init', array($this, 'init'), 1);
     }

     public static function on_activation()
     {
         if ( ! current_user_can( 'activate_plugins' ) )
             return;
         $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
         check_admin_referer( "activate-plugin_{$plugin}" );

         // Loop through the database tables, if table does not exist, create the table.
         ZeeuwseKringLoopSubscribeExtension::ZKLcreateDb2();
         ZeeuwseKringLoopSubscribeExtension::create_event_page();
     }
     public static function on_deactivation()
     {
         if ( ! current_user_can( 'activate_plugins' ) )
             return;
         $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
         check_admin_referer( "deactivate-plugin_{$plugin}" );


         # Uncomment the following line to see the function in action
         # exit( var_dump( $_GET ) );
     }

     /**
      * Loads the plugin into Wordpress
      *
      * @since 1.0.0
      */
     public function init()
     {

         // Run hook once Plugin has been initialized
         do_action('zkl_se_init');

         // Load admin only components.
         if (is_admin()) {

             //Load all admin specific includes
             $this->requireAdmin();

             //Setup admin page
             $this->createAdmin();
         } else {

         }

         // Load the view shortcodes
         $this->loadViews();
     }

     /**
      * Loads all admin related files into scope
      *
      * @since 1.0.0
      */
     public function requireAdmin()
     {

         //Admin controller file
         require_once ZKL_SE_ADMIN_DIR . '/ZeeuwseKringLoopSubscribeExtension_AdminController.php';
     }

     /**
      * Admin controller functionality
      */
     public function createAdmin()
     {
         ZeeuwseKringLoopSubscribeExtension_AdminController::prepare();
     }

     /**
      * Load the view shortcodes:
      */
     public function loadViews()
     {
         include ZKL_SE_INCLUDES_VIEWS_DIR . '/view_shortcodes.php';
     }

     public static function ZKLcreateDb2()
     {

         require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

         //Calling $wpdb;
         global $wpdb;

         $charset_collate = $wpdb->get_charset_collate();

         //Name of the table that will be added to the db
         $subscription = $wpdb->prefix . "zkl_subscriptions";
         $event = $wpdb->prefix . "zkl_events";

        /*Create Database*/
         //Create the subscription table
         $sql = "CREATE TABLE IF NOT EXISTS $subscription (
            zkl_subscription_id INT NOT NULL AUTO_INCREMENT,
            zkl_fname VARCHAR(150) NOT NULL,
            zkl_lname VARCHAR(150) NOT NULL,
            zkl_email VARCHAR(1024) NOT NULL,
            zkl_event INT(11) NOT NULL,
            PRIMARY KEY  (zkl_subscription_id))
            ENGINE = InnoDB $charset_collate";
         dbDelta($sql);

         //Create the event table
         $sql = "CREATE TABLE IF NOT EXISTS $event (
            zkl_event_id INT NOT NULL AUTO_INCREMENT,
            zkl_event VARCHAR(150) NOT NULL,
            zkl_host VARCHAR(150) NOT NULL,
            zkl_description VARCHAR(1024) NOT NULL,
            zkl_event_date  DATE NOT NULL,
            zkl_event_estimated_time TIME NOT NULL,
            PRIMARY KEY  (zkl_event_id))
            ENGINE = InnoDB $charset_collate";
         dbDelta($sql);
     }

     // Create a page with the shortcode when the plug-in gets activated
     public static function create_event_page()
     {
         if (!current_user_can('activate_plugins')) return;

         global $wpdb;
         // check if the page name exists, if not exists, create new page and add title,
         // content, set status to publish, add author related to the person that activated the plug-in & set type to page
         if (null === $wpdb->get_row("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'evenementen'", 'ARRAY_A')) {

             $current_user = wp_get_current_user();
             // Create post object
             $page = array(
                 'post_title' => __('Evenementen'),
                 'post_content' => '[subscriptions]',
                 'post_status' => 'publish',
                 'post_author' => $current_user->ID,
                 'post_type' => 'page',
             );

             // insert the post into the database
             wp_insert_post($page);
         }
     }

 }

 // Instantiate the class
 $zklse = new ZeeuwseKringLoopSubscribeExtension();
 ?>