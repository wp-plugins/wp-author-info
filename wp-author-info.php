<?php

/**
 * Plugin Name: WP Author Info
 * Plugin URI: http://ghuwad.com/
 * Description: Show author detail or author bio on any post type at below or above the content (including page & post).
 * Version: 2.0.1
 * Author: Ghuwad.com
 * Author URI: http://www.ghuwad.com/wordpress-plugins/wp-author-info
 * License: GPL2 or later
 */
/*

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('WpAuthorInfo')){

	class WpAuthorInfo{
		static public $_var = array();
		public function __construct() {
			// Define constants
			$this->define_constants();

			// Include required files
			$this->includes();

			$this->update_check();
		}

		private function update_check() {
		    $v1 = WpAuthorInfo::getVar('version');
		    $v2 = WpAuthorInfo::getVar('version', 'settings');

		    if ($v2 == '') { //first time install
		    	$availFields = WpAuthorInfo::getVar('fields');
		    	array_walk($availFields, function(&$value, $key, $return) {
				  $value = $value[$return];
				}, 'field');
				$defaultSettings = array('version' => $v1, 'theme' => 'box', 'color' => 'black', 'dir' => 'ltr', 'visibile_fields' => $availFields);
		    	update_option('wp-author-info-settings', $defaultSettings);
		    	WpAuthorInfo::setVar('settings', $defaultSettings);
		    }
		    if ( version_compare( $v1, $v2 ) > 0 ) { //do upgrade
		    }
		}

		static public function init() {
	        // Named global variable to make access for other scripts easier.
	        $GLOBALS[ __CLASS__ ] = new self;
	    }

	    static public function get_author_info($authorId = '', $theme = '', $color = '', $dir = '', $social_style = '') {
	    	if (!$authorId) {
	    		$authorId = get_the_author_meta('ID');
	    	}
	    	$settings = WpAuthorInfo::getVar('settings');
	    	if ($theme == '') {
	    		$theme = $settings['theme'];
	    	}
	    	if ($color == '') {
	    		$color = $settings['color'];
	    	}
	    	if ($dir == '') {
	    		$dir = $settings['dir'] ? $settings['dir'] : 'ltr';
	    	}
	    	if ($social_style == '') {
	    		$social_style = $settings['social_style'] ? $settings['social_style'] : 'default';
	    	}
	    	$theme_file = WpAuthorInfo::getVar('front_view', 'path').$theme.'.php';
	    	if (file_exists($theme_file)) {
	    		$userFields = WpAuthorInfo::get_user_fields($authorId);
	    		$avatar = get_avatar($authorId);
	    		$display_name = ucfirst(get_the_author_meta('display_name', $authorId));
	    		$description = get_the_author_meta('description', $authorId);
		    	ob_start();
	            include($theme_file);
	            $ret = ob_get_contents();
				ob_end_clean();
				return $ret;
	    	}
	    	return '';
        }

	    static public function get_user_fields($userId = '') {
	    	if (!$userId) {
	    		$userId = get_the_author_meta('ID');
	    	}
	    	if (!$userId) {
	    		return array();
	    	}
	    	$settings = WpAuthorInfo::getVar('settings');
			$availFields = WpAuthorInfo::getVar('fields');
			$ret = array();
			foreach ($availFields as $field) {
		        if (!in_array($field['field'], $settings['visibile_fields'])){
		            continue;
		        }
		        $fn = 'wpai_'.$field['field'];
		        $ret[$field['field']] = esc_attr( get_the_author_meta( $fn, $userId ) );
		    }
			return $ret;
        }

		private function define_constants() {
			$arr = array();
			$arr['version'] = '2.0.1';
			$arr['unique'] = 'wp_author_info';
			$arr['plugin'] = __FILE__;

			//paths
			$arr['path']['base'] = plugin_dir_path(  __FILE__  );
			$arr['path']['inc'] = $arr['path']['base'].'/includes/';
			$arr['path']['admin_view'] = $arr['path']['base'].'/views/admin/';
			$arr['path']['front_view'] = $arr['path']['base'].'/views/front/';

			$arr['lang'] = $arr['unique'];

			//urls
			$arr['url']['home'] = trim(plugin_dir_url( __FILE__ ),'/').'/';
			$arr['url']['css'] = $arr['url']['home'].'assets/css/';
			$arr['url']['js'] = $arr['url']['home'].'assets/js/';
			$arr['url']['images'] = $arr['url']['home'].'assets/images/';
			$arr['url']['admin'] = get_option('siteurl').'/wp-admin/admin.php';
			$arr['settings'] = get_option('wp-author-info-settings');
			$arr['settings'] = $arr['settings'] && is_array($arr['settings']) ? $arr['settings'] : array();
			$arr['fields'] = array(
					array('name'  => 'Twitter',
						'field' => 'twitter',
						'help'  => 'Enter your Twitter acount profile URL.'),
					array('name'  => 'Facebook',
					   'field' => 'facebook',
					   'help'  => 'Enter your Facebook profile URL.'),
					array('name'  => 'Google+',
					   'field' => 'google_plus',
					   'help'  => 'Enter your Google+ profile URL.'),
					array('name'  => 'LinkedIn',
					   'field' => 'linked_in',
					   'help'  => 'Enter your LinkedIn profile URL.'),
					array('name'  => 'Instagram',
					   'field' => 'instagram',
					   'help'  => 'Enter your Instagram profile URL.'),
					array('name'  => 'Flickr',
					   'field' => 'flickr',
					   'help'  => 'Enter your Flickr profile URL.'),
					array('name'  => 'Pinterest',
					   'field' => 'pinterest',
					   'help'  => 'Enter your Pinterest profile URL.'),
					array('name'  => 'Tumblr',
					   'field' => 'tumblr',
					   'help'  => 'Enter your Tumblr profile URL.'),
					array('name'  => 'YouTube',
					   'field' => 'youtube',
					   'help'  => 'Enter your YouTube profile/channel URL.'),
					array('name'  => 'Vimeo',
					   'field' => 'vimeo',
					   'help'  => 'Enter your Vimeo profile URL.'),
					array('name'  => 'StumbleUpon',
					   'field' => 'stumbleupon',
					   'help'  => 'Enter your StumbleUpon URL.'),
					array('name'  => 'Delicious',
					   'field' => 'delicious',
					   'help'  => 'Enter your Delicious URL.'),
				);
			$arr['themes'] = array('default', 'box', 'border-box', 'smart', 'material', 'metro');
			$arr['colors'] = array('blue', 'orange', 'green', 'red', 'white', 'black');
			WpAuthorInfo::$_var = $arr;
		}

		private function includes() {
			require_once WpAuthorInfo::getVar('base','path').'includes/common-functions.php';

			require_once WpAuthorInfo::getVar('base','path').'/includes/shortcodes.php';
			if ( is_admin() ) {
				require_once WpAuthorInfo::getVar('base','path').'/includes/admin.php';
			} else {
				require_once WpAuthorInfo::getVar('base','path').'/includes/front.php';
			}
			if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {}
		}

		static public function getVar($key,$key1='') {
			if($key1=='' && isset(WpAuthorInfo::$_var[$key])){
				return WpAuthorInfo::$_var[$key];
			}else if(isset(WpAuthorInfo::$_var[$key1][$key])){
				return WpAuthorInfo::$_var[$key1][$key];
			}
			return '';
		}

		public function setVar($key,$value) {
			WpAuthorInfo::$_var[$key] = $value;
			return true;
		}
	}
	add_action( 'plugins_loaded', array ( 'WpAuthorInfo', 'init' ), 10 );
}