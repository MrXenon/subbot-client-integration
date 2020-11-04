<?php

defined( 'ABSPATH' ) OR exit;

/**
 * Plugin Name: Subbot client integration
 * Plugin URI: https://github.com/MrXenon/subbot-client-integration
 * Description: This plug-in will assist the admin in handling discord user names for the subbot to manage.
 * Author: Kevin Schuit
 * Author URI: https://www.3dynamisch.nl
 * Version: 1.0.0
 * Text Domain: subbot-client-integration
 * Domain Path: languages
 * 
 * This is distributed in hte hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even teh implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
 * GNU General Public License for more details.
 * 
 * You should have received a cpoy of the GNU General Publilc License 
 * along with your plugin. If not, see <http://www.gnu.org/licenses/>.
 */

 //Define the plugin name
 //Activeren en deactiveren
 define ( 'SUBBOT_CLIENT_INTEGRATION_PLUGIN', __FILE__ );

 //Inculde the general defenition file:
 require_once plugin_dir_path ( __FILE__ ) . 'includes/defs.php';

/* Register the hooks */
    register_activation_hook( __FILE__, array( 'SubBotClientIntegration', 'on_activation' ) );
    register_deactivation_hook( __FILE__, array( 'SubBotClientIntegration', 'on_deactivation' ) );
 
 class SubBotClientIntegration
 {
     public function __construct()
     {

         //Fire a hook before the class is setup.
         do_action('subbot_client_integration_pre_init');

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
         SubBotClientIntegration::createDb();

     }
     public static function on_deactivation()
     {
         if ( ! current_user_can( 'activate_plugins' ) )
             return;
         $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
         check_admin_referer( "deactivate-plugin_{$plugin}" );

     }

     /**
      * Loads the plugin into Wordpress
      *
      * @since 1.0.0
      */
     public function init()
     {

         // Run hook once Plugin has been initialized
         do_action('subbot_client_integration_init');

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
         require_once SUBBOT_CLIENT_INTEGRATION_PLUGIN_ADMIN_DIR . '/SubBotClientIntegration_AdminController.php';
     }

     /**
      * Admin controller functionality
      */
     public function createAdmin()
     {
         SubBotClientIntegration_AdminController::prepare();
     }

     /**
      * Load the view shortcodes:
      */
     public function loadViews()
     {
         include SUBBOT_CLIENT_INTEGRATION_PLUGIN_INCLUDES_VIEWS_DIR . '/view_shortcodes.php';
     }

     /**
      * Generate database tables
      */

     public static function createDb()
     {

         require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

         //Calling $wpdb;
         global $wpdb;

         $charset_collate = $wpdb->get_charset_collate();

         //Name of the table that will be added to the db
         $clients = $wpdb->prefix . "clients";

         //Create the clients table
         $sql = "CREATE TABLE IF NOT EXISTS $clients (
            id INT NOT NULL AUTO_INCREMENT,
            discordId VARCHAR(150) NOT NULL UNIQUE,
            expiration DATE  NOT NULL,
            type VARCHAR(150)  NOT NULL,
            PRIMARY KEY  (id))
            ENGINE = InnoDB $charset_collate";
         dbDelta($sql);

     }

 }

 // Instantiate the class
 $subbot_client_integration = new SubBotClientIntegration();
 ?>