<?php

/**
 * Plugin Name: WP Author Info
 * Plugin URI: http://ghuwad.com/
 * Description: You can attach author info with any post types via WP Author Info.
 * Version: 1.0.0
 * Author: Ghuwad.com
 * Author URI: http://ghuwad.com/about-us
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
		}

		static public function init() {
	        // Named global variable to make access for other scripts easier.
	        $GLOBALS[ __CLASS__ ] = new self;
	    }

	    static public function get_author_info($theme = 'default', $authorId = '') {
	    	if (!$authorId) {
	    		$authorId = get_the_author_meta('ID');
	    	}
	    	ob_start();
	    	$theme_file = WpAuthorInfo::getVar('front_view','path').$theme.'.php';
	    	if (file_exists($theme_file)) {
	            include($theme_file);
	    	}
            $ret = ob_get_contents();
			ob_end_clean();
			return $ret;
        }

		private function define_constants() {
			$arr = array();
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
			WpAuthorInfo::$_var = $arr;
		}

		private function includes() {
			require_once WpAuthorInfo::getVar('base','path').'lib/Helper.php';

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