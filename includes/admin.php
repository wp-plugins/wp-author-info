<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
Class WpAuthorInfoAdmin{
    public function __construct() {
        add_action( 'admin_init', array( &$this, 'admin_init') );
        add_action( 'admin_menu', array( &$this, 'admin_menu' ), 9 );

        // show user fields
        add_action( 'edit_user_profile', array( &$this, 'show_user_fields' ) );
        add_action( 'show_user_profile', array( &$this, 'show_user_fields' ) );
        // update user profile field
        add_action( 'personal_options_update', array( &$this, 'save_user_fields' ) );
        add_action( 'edit_user_profile_update', array( &$this, 'save_user_fields' ) );
    }
    function admin_init() {
        wp_register_style( 'wp-author-info-css-admin', WpAuthorInfo::getVar('css','url') . 'admin/admin-styles.css');

        $this->registersetting();
        global $ginit ;
        $ginit = 1;

        if(isset($_REQUEST['g_request'])){
            switch($_REQUEST['g_request']){
                case 'g-wai-option':
                case 'g-wai-contact-us':
                    include WpAuthorInfo::getVar('admin_view','path').'default.php';
                    break;
            }
        }
        $ginit = 0;
    }

    public function registersetting() {
        register_setting( 'wp-author-info-settings', 'wp-author-info-settings' );
    }

    function admin_enqueue() {
        wp_enqueue_style( 'wp-author-info-css-admin' );

        wp_enqueue_script( 'wp-author-info-jquery-extend-script', WpAuthorInfo::getVar('js','url') . 'jquery-extend-ghuwad.js', array('jquery')  );
        wp_enqueue_script( 'wp-author-info-admin-script', WpAuthorInfo::getVar('js','url') . 'admin/admin-scripts.js', array('jquery')  );
    }

    public function admin_menu() {
        global $menu;
        $lang = WpAuthorInfo::getVar('lang');
        $main_page = add_menu_page( __( 'WpAuthorInfo' , $lang ), __( 'WP Author', $lang ), 'administrator', $lang, array(&$this,'router'), WpAuthorInfo::getVar('images','url').'icon.png', 55 );

        add_action('admin_print_scripts-' . $main_page, array( &$this,'admin_enqueue' ));
    }

    public function router() {
        include(WpAuthorInfo::getVar('admin_view','path').'default.php');
    }

    function show_user_fields( $user ) {
        if ( user_can( $user, 'edit_posts') ) {
            include WpAuthorInfo::getVar('base','path').'views/admin/user-fields.php';
        }
    }

    function save_user_fields( $user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) )
            return false;

        $availFields = WpAuthorInfo::getVar('fields');
        foreach ($availFields as $field) {
            $fn = 'wpai_'.$field['field'];
            if (isset($_POST[$fn])){
                update_user_meta( $user_id, $fn, strip_tags( $_POST[$fn] ) );
            }
        }
    }
}

return new WpAuthorInfoAdmin;