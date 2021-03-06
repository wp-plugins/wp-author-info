<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
Class WpAuthorInfoFront {
    public function __construct(){
        add_action( 'the_content', array( &$this, 'add_to_content' ) );
        add_action( 'wp_enqueue_scripts', array( &$this, 'style_enqueue' ) );
    }

    public function add_to_content($content) {
        if (is_singular()) {
            $post_type = get_post_type();
            $post_id = get_the_ID();
            $settings = WpAuthorInfo::getVar('settings');
            $notInPost = empty($settings['hide_on_post']) ? array() : explode(',', $settings['hide_on_post']);
            if (isset($settings['post_types'][$post_type]) && !in_array($post_id, $notInPost) ) {
                if ($settings['post_types'][$post_type] == 'above') {
                    $content = WpAuthorInfo::get_author_info().$content;
                } else if ($settings['post_types'][$post_type] == 'below') {
                    $content = $content.WpAuthorInfo::get_author_info();
                }
            }
        }
        return $content;
    }
    public function style_enqueue() {
        wp_enqueue_style( 'wp-author-info-css', WpAuthorInfo::getVar('css','url') . 'wp-author-info.css');
    }
}

return new WpAuthorInfoFront;