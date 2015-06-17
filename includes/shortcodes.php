<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
Class WpAuthorInfoShortCodes{

    static public function init(){
        add_shortcode( 'wp-author-info', __CLASS__ . '::wp_author_info' );
    }

    public static function wp_author_info($atts) {
        $theme = isset($atts['theme']) ? $atts['theme'] : WpAuthorInfo::getVar('theme', 'settings');
        $authorId = isset($atts['author_id']) ? $atts['author_id'] : '';
        return WpAuthorInfo::get_author_info($theme, $authorId);
    }
}
WpAuthorInfoShortCodes::init();