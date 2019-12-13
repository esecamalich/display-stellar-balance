<?php
/**
 * Plugin Name: Display Stellar Balance
 * Plugin URI: https://github.com/esecamalich/display-stellar-balance
 * Description: This plugin displays the balance of any Stellar wallet.
 * Version: 1.0
 * Author: Sergio Camalich
 * Author URI: https://www.camali.ch
 */

 class DisplayStellarBalance
 {
     /**
      * Holds the values to be used in the fields callbacks
      */
     private $options;

     /**
      * Start up
      */
     public function __construct()
     {
         add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
         add_action( 'admin_init', array( $this, 'page_init' ) );
     }

     /**
      * Add options page
      */
     public function add_plugin_page()
     {
         // This page will be under "Settings"
         add_options_page(
             'Settings Admin',
             'Display Stellar Balance',
             'manage_options',
             'display-stellar-balance',
             array( $this, 'create_admin_page' )
         );
     }

     /**
      * Options page callback
      */
     public function create_admin_page()
     {
         // Set class property
         $this->options = get_option( 'display_stellar_balance_option_name' );
         ?>
         <div class="wrap">
             <h1>Display Stellar Balance</h1>
             <form method="post" action="options.php">
             <?php
                 // This prints out all hidden setting fields
                 settings_fields( 'display_stellar_balance_option_group' );
                 do_settings_sections( 'display-stellar-balance' );
                 submit_button();
             ?>
             </form>
         </div>
         <?php
     }

     /**
      * Register and add settings
      */
     public function page_init()
     {
         register_setting(
             'display_stellar_balance_option_group', // Option group
             'display_stellar_balance_option_name', // Option name
             array( $this, 'sanitize' ) // Sanitize
         );

         add_settings_section(
             'setting_section_id', // ID
             'My Custom Settings', // Title
             array( $this, 'print_section_info' ), // Callback
             'display-stellar-balance' // Page
         );

         add_settings_field(
             'public_key', // ID
             'Wallet Public Key:', // Title
             array( $this, 'public_key_callback' ), // Callback
             'display-stellar-balance', // Page
             'setting_section_id' // Section
         );

         /*add_settings_field(
             'title',
             'Title',
             array( $this, 'title_callback' ),
             'display-stellar-balance',
             'setting_section_id'
         );*/
     }

     /**
      * Sanitize each setting field as needed
      *
      * @param array $input Contains all settings fields as array keys
      */
     public function sanitize( $input )
     {
         $new_input = array();
         if( isset( $input['public_key'] ) )
             $new_input['public_key'] = absint( $input['public_key'] );

         if( isset( $input['title'] ) )
             $new_input['title'] = sanitize_text_field( $input['title'] );

         return $new_input;
     }

     /**
      * Print the Section text
      */
     public function print_section_info()
     {
         print 'Enter your settings below:';
     }

     /**
      * Get the settings option array and print one of its values
      */
     public function public_key_callback()
     {
         printf(
             '<input type="text" id="public_key" name="display_stellar_balance_option_name[public_key]" value="%s" />',
             isset( $this->options['public_key'] ) ? esc_attr( $this->options['public_key']) : ''
         );
     }

     /**
      * Get the settings option array and print one of its values
      */
     public function title_callback()
     {
         printf(
             '<input type="text" id="title" name="display_stellar_balance_option_name[title]" value="%s" />',
             isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
         );
     }
 }

 if( is_admin() )
     $my_settings_page = new DisplayStellarBalance();

?>
