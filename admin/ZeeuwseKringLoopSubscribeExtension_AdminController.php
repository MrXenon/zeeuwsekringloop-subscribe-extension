<?php

/**
 * This Admin controller file provide functionality for the Admin section of the 
 * My event organiser.
 * 
 * @author Jorinde Dekker
 * @version 0.1
 * 
 * Version history
 * 0.1 Jorinde Dekker Initial version
 */

 class ZeeuwseKringLoopSubscribeExtension_AdminController {

    /**
     * This function will prepare all Admin functionality for the plugin
     */
    static function prepare() {
        
        // Check that we are in the admin area
        if ( is_admin() ) :

            // Add the sidebar Menu structure
            add_action( 'admin_menu', array('ZeeuwseKringLoopSubscribeExtension_AdminController', 'addMenus' ) );

        endif;
    }

    /**
     * Add the Menu structure to the Admin sidebar
     */
    static function addMenus()
    {

        add_menu_page(
        //string $page_title The text to be displayed in the title tags
        // of the page when the menu is selected
            __('ZKL subscription extension', 'zeeuwsekringloop-subscription-extenstion'),
            // string $menu_title The text to be used for the menu
            __('ZKL subscription extension', 'zeeuwsekringloop-subscription-extenstion'),
            // string $capability The capability required for this menu to be
            //displayed to the user.
            '',
            //string $menu_slug THe slug name to refer to this menu by (should
            //be unique for this menu)
            'zeeuwsekringloop-subscription-extenstion-admin',

            // callback $function The function to be called to output the content for this page
            array('ZeeuwseKringLoopSubscribeExtension_AdminController', 'adminMenuPage'),

            'dashicons-download'

        // int $position The position in the menu order this one should appear
        );

        add_submenu_page(
        // string $parent_slug The slug name for the parent menu
        // (or the file name of a standard Wordpress admin page)
            'zeeuwsekringloop-subscription-extenstion-admin',

            // string $page_title The text to be displayed in the title tags of
            // the page when the menu is selected
            __('dashboard', 'zeeuwsekringloop-subscription-extenstion'),

            // string $menu_title The text to be used for the menu
            __('Dashboard', 'zeeuwsekringloop-subscription-extenstion'),

            // string $capability The capability required for this menu to be
            // displayed to the user
            'manage_options',

            // string $menu_slug The slug name to refer to this menu by (should be
            // unique for this menu)
            'zkl_se_dashboard',

            // callback $function The function to be called to output the content for this page
            array('ZeeuwseKringLoopSubscribeExtension_AdminController', 'adminSubMenuDashboard')
        );

        //Opdracht 3
        add_submenu_page(
            'zeeuwsekringloop-subscription-extenstion-admin',

            __('overview', 'zeeuwsekringloop-subscription-extenstion'),

            __('Overview', 'zeeuwsekringloop-subscription-extenstion'),

            'manage_options',

            'zkl_se_overview',

            array('ZeeuwseKringLoopSubscribeExtension_AdminController', 'adminSubMenuOverview')

        );

        //Opdracht 3
        add_submenu_page(
            'zeeuwsekringloop-subscription-extenstion-admin',

            __('Events', 'zeeuwsekringloop-subscription-extenstion'),

            __('Events', 'zeeuwsekringloop-subscription-extenstion'),

            'manage_options',

            'zkl_se_events',

            array('ZeeuwseKringLoopSubscribeExtension_AdminController', 'adminSubMenuEvents')

        );

        //Opdracht 3
        add_submenu_page(
            'zeeuwsekringloop-subscription-extenstion-admin',

            __('Event View', 'zeeuwsekringloop-subscription-extenstion'),

            __('Event View', 'zeeuwsekringloop-subscription-extenstion'),

            'manage_options',

            'zkl_se_eventsview',

            array('ZeeuwseKringLoopSubscribeExtension_AdminController', 'adminSubMenuEventsView')

        );

    }

        /**
        * The main menu page
         */
            static function adminMenuPage() {

                //Include the view for this menu page.
                include ZKL_SE_ADMIN_VIEWS_DIR . '/admin_main.php';
            }

     /**
      * the submenu page for the event categories
      */
     static function adminSubMenuDashboard()
     {
         //include the view for this submenu page.
         include ZKL_SE_ADMIN_VIEWS_DIR . '/zkl_se_dashboard.php';
     }

            //The Submenu page for the event types Opdr3
            static function adminSubMenuOverview (){
            include ZKL_SE_ADMIN_VIEWS_DIR . '/zkl_se_overview.php';
            }

     //The Submenu page for the event types Opdr3
     static function adminSubMenuEvents (){
         include ZKL_SE_ADMIN_VIEWS_DIR . '/zkl_se_events.php';
     }
     //The Submenu page for the event types Opdr3
     static function adminSubMenuEventsView (){
         include ZKL_SE_ADMIN_VIEWS_DIR . '/zkl_se_eventsView.php';
     }
    }
?>