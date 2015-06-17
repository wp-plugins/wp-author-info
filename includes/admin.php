<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
Class WpAuthorInfoAdmin{
    public function __construct() {
        add_action( 'admin_init', array( &$this, 'admin_init') );
        add_action( 'admin_menu', array( &$this, 'admin_menu' ), 9 );
    }
    function admin_init() {
        wp_register_style( 'wp-author-info-css-admin', WpAuthorInfo::getVar('css','url') . 'admin/admin-styles.css');

        $this->registersetting();
        global $ginit ;
        $ginit = 1;

        if(isset($_REQUEST['g_request'])){
            switch($_REQUEST['g_request']){
                case 'contact-us':
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
}

return new WpAuthorInfoAdmin;