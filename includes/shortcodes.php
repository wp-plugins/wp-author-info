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
        $color = isset($atts['color']) ? $atts['color'] : WpAuthorInfo::getVar('color', 'settings');
        $dir = isset($atts['dir']) ? $atts['dir'] : WpAuthorInfo::getVar('dir', 'settings');
        $social_style = isset($atts['social_style']) ? $atts['social_style'] : WpAuthorInfo::getVar('social_style', 'settings');
        $authorId = isset($atts['author_id']) ? $atts['author_id'] : '';
        return WpAuthorInfo::get_author_info($authorId, $theme, $color, $dir, $social_style);
    }
}
WpAuthorInfoShortCodes::init();