<?php
/*
 * Plugin Name: Alive5 Chat
 * Plugin URI: https://www.alive5.com/
 * Description: A Multi Channel Chat Bot Integration.
 * Version: 1.1
 * Stable tag: 1.1
 * Author: Alive5 Team
 * Tested up to: 5.5.1
 * Text Domain: alive5-chat
 * Author URI: https://alive5.com/
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
require_once( plugin_dir_path( __FILE__ ) . 'class.alive5.php' );

if ( !class_exists( 'Alive5' ) ) {

    class Alive5 {

        public $settings = array();

        public function __construct(){
            add_action( 'admin_menu', array( $this, 'add_alive5_menu' ) );
            add_action( 'admin_init', array( $this, 'alive5_settings_init' ) );
            add_filter('plugin_action_links', array($this,'alive5_action_link'), 10, 2);
            register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );
            register_deactivation_hook( __FILE__, array( $this, 'plugin_deactivation' ) );
            $this->settings = get_option( 'alive5_settings' );
        }

        public function alive5_action_link($links, $file) {
            if ($file == plugin_basename(__FILE__)) {
                $plugin_links = array('<a href="' . admin_url('tools.php?page=alive5-chat') . '">Settings</a>');
                $links = array_merge( $plugin_links,$links);
            }
            return $links;
        }

        public function plugin_activation(){}

        public function plugin_deactivation(){} 

        public function add_alive5_menu(){
            add_submenu_page(
                'tools.php',
                'Alive5 Chat Page',
                'Alive5 Chat',
                'manage_options',
                'alive5-chat',
                array($this,'alive5_chat_settings' )
            );
        }

        public function alive5_chat_settings(){
            ?>
            <div class="wrap">
                <h1>Alive5 Chat Settings</h1>
                <form action='options.php' method='post'>
                    <?php
                        settings_fields( 'alive5-plugin' );
                        do_settings_sections( 'alive5-plugin' );
                        submit_button();
                    ?>
                </form>
            </div>
            <?php
        }

        public function alive5_settings_init( ) {
            register_setting( 'alive5-plugin', 'alive5_settings',array( $this, 'sanitize_inputs' ) );
            add_settings_section(
                'alive5-plugin_section',
                __( '', 'wordpress' ),
                array($this,'alive5_section_callback'),
                'alive5-plugin'
            );

            add_settings_field(
                'widget_enabled_field',
                __( 'Enable Chatbot', 'wordpress'),
                array($this,'widget_enabled_field'),
                'alive5-plugin',
                'alive5-plugin_section'
            );
        
            add_settings_field(
                'widget_id_field',
                __( 'Chat Widget ID', 'wordpress'),
                array($this,'widget_id_field'),
                'alive5-plugin',
                'alive5-plugin_section'
            );
        }

        public function sanitize_inputs($input){
            if(isset($input['widget_id'])){
                $input['widget_id'] = sanitize_text_field( $input['widget_id'] );
            }
            return $input;
        }

        public function widget_enabled_field(){
            ?>
                <input name="alive5_settings[enable_chatbot]" type="checkbox" id="enable_chatbot" value="" <?php if(isset($this->settings['enable_chatbot'])){ echo "checked";}?>>
            <?php
        }

        public function widget_id_field( ) {
            ?>
                <input type='text' name='alive5_settings[widget_id]' class="regular-text" value='<?php echo $this->settings['widget_id']; ?>'>
            <?php
        }

        public function alive5_section_callback(  ) {
            echo __( 'Enter alive5 chat widget ID.', 'wordpress' );
        }
    }

    if( is_admin()){
        $alive5 = new Alive5();
    }
}

?>