<?php

/**
 * This Admin controller file provide functionality for the Admin section of the 
 * SubBot client integration
 * 
 * @author Kevin Schuit
 * @version 1.0
 * 
 * Version history
 * 1.0 Kevin Schuit Final Version
 */

 class SubBotClientIntegration_AdminController {

    /**
     * This function will prepare all Admin functionality for the plugin
     */
    static function prepare() {
        
        // Check that we are in the admin area
        if ( is_admin() ) :

            // Add the sidebar Menu structure
            add_action( 'admin_menu', array('SubBotClientIntegration_AdminController', 'addMenus' ) );

        endif;
    }

    /**
     * Add the Menu structure to the Admin sidebar
     */
    static function addMenus() {

        add_menu_page(
            __( 'Subbot Client Integration Admin', 'subbot-client-integration'),
            __( 'Subbot Client Integration', 'subbot-client-integration' ),
            '',
            'subbot-client-integration-admin',
            array( 'SubBotClientIntegration_AdminController', 'adminMenuPage'),
            'dashicons-chart-area'
        );

    //Settings page
        add_submenu_page (
            'subbot-client-integration-admin',
            __( 'subbot_client_integration_settings', 'subbot-client-integration' ),
            __( 'Settings', 'subbot-client-integration'),
            'manage_options',
            'subbot_client_integration_settings',
            array('SubBotClientIntegration_AdminController', 'adminSubMenuSettings')
        );



        //Client page
        add_submenu_page (
            'subbot-client-integration-admin',
            __( 'subbot_client_integration_client', 'subbot-client-integration' ),
            __( 'Clients toevoegen', 'subbot-client-integration'),
            'manage_options',
            'subbot_client_integration_client',
            array('SubBotClientIntegration_AdminController', 'adminSubMenuClients')
        );
    }

        /**
        * The main menu page
         */
            static function adminMenuPage() {
                //Include the view for this menu page.
                include SUBBOT_CLIENT_INTEGRATION_PLUGIN_ADMIN_VIEWS_DIR . '/subbot_client_integration_instellingen.php';
            }

     /**
      * the submenu page for the settings page
      */
            static function adminSubMenuSettings (){
                //include the view for this submenu page.
            include SUBBOT_CLIENT_INTEGRATION_PLUGIN_ADMIN_VIEWS_DIR . '/subbot_client_integration_instellingen.php';
            }

        /**
        * the submenu page for the clients page
        */
            static function adminSubMenuClients(){
            //include the view for this submenu page.
            include SUBBOT_CLIENT_INTEGRATION_PLUGIN_ADMIN_VIEWS_DIR . '/subbot_client_integration_client.php';
        }
    }
?>