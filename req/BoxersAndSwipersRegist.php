<?php
/**
 * Boxers and Swipers
 * 
 * @package    Boxers and Swipers
 * @subpackage BoxersAndSwipersRegist registered in the database
    Copyright (c) 2014- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; version 2 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

class BoxersAndSwipersRegist {

	/* ==================================================
	 * Settings register
	 * @since	1.0
	 */
	function register_settings(){

		$plugin_datas = get_file_data( BOXERSANDSWIPERS_PLUGIN_BASE_DIR.'/boxersandswipers.php', array('version' => 'Version') );
		$plugin_version = floatval($plugin_datas['version']);

		// version 2.37 -> 2.38
		global $wpdb;
		$exclude_meta = 'boxersandswipers_exclude';
		$change_meta = $wpdb->get_results("
						SELECT	post_id, meta_value
						FROM	$wpdb->postmeta
						WHERE	meta_key
						LIKE	'%%$exclude_meta%%'
						");
		if ( $change_meta ) {
			foreach ($change_meta as $value) {
				if ( $value->meta_value === 'true' ) {
					update_post_meta( $value->post_id, $exclude_meta, 1 );
				}
			}
		}

		include_once( BOXERSANDSWIPERS_PLUGIN_BASE_DIR.'/req/BoxersAndSwipersAdmin.php' );
		$boxersandswipersadmin = new BoxersAndSwipersAdmin();
		$applytypes = $boxersandswipersadmin->apply_type();
		unset($boxersandswipersadmin);
		$apply_tbl = array();
		if ( !get_option('boxersandswipers_apply') ) {
			foreach ( $applytypes as $key => $value ) {
				$apply_tbl[$key]['pc'] = NULL;
				$apply_tbl[$key]['tb'] = NULL;
				$apply_tbl[$key]['sp'] = NULL;
			}
			unset($applytypes[$key]);
		} else { // Version 3.00 or later
			$boxersandswipers_apply = get_option('boxersandswipers_apply');
			foreach ( $applytypes as $key => $value ) {
				if ( array_key_exists( $key, $boxersandswipers_apply ) ) {
					$apply_tbl[$key]['pc'] = $boxersandswipers_apply[$key]['pc'];
					$apply_tbl[$key]['tb'] = $boxersandswipers_apply[$key]['tb'];
					$apply_tbl[$key]['sp'] = $boxersandswipers_apply[$key]['sp'];
				} else {
					$apply_tbl[$key]['pc'] = NULL;
					$apply_tbl[$key]['tb'] = NULL;
					$apply_tbl[$key]['sp'] = NULL;
				}
			}
			unset($applytypes[$key]);
		}
		update_option( 'boxersandswipers_apply', $apply_tbl );

		if ( !get_option('boxersandswipers_effect') ) {
			$effect_tbl = array(
							'pc' => 'colorbox',
							'tb' => 'nivolightbox',
							'sp' => 'photoswipe'
							);
			update_option( 'boxersandswipers_effect', $effect_tbl );
		}

		if ( !get_option('boxersandswipers_useragent') ) {
			$useragent_tbl = array(
								'tb' => 'iPad|^.*Android.*Nexus(((?:(?!Mobile))|(?:(\s(7|10).+))).)*$|Kindle|Silk.*Accelerated|Sony.*Tablet|Xperia Tablet|Sony Tablet S|SAMSUNG.*Tablet|Galaxy.*Tab|SC-01C|SC-01D|SC-01E|SC-02D',
								'sp' => 'iPhone|iPod|Android.*Mobile|BlackBerry|IEMobile'
							);
			update_option( 'boxersandswipers_useragent', $useragent_tbl );
		}

		if ( !get_option('boxersandswipers_colorbox') ) {
			$colorbox_tbl = array(
								'rel' => 'boxersandswipers',
								'transition' => 'elastic',
								'speed' => 350,
								'title' => NULL,
								'scalePhotos' => 'true',
								'scrolling' => 'true',
								'opacity' => 0.85,
								'open' => NULL,
								'returnFocus' => 'true',
								'trapFocus' => 'true',
								'fastIframe' => 'true',
								'preloading' => 'true',
								'overlayClose' => 'true',
								'escKey' => 'true',
								'arrowKey' => 'true',
								'loop' => 'true',
								'fadeOut' => 300,
								'closeButton' => 'true',
								'current' => 'image {current} of {total}',
								'previous' => 'previous',
								'next' => 'next',
								'close' => 'close',
								'width' => NULL,
								'height' => NULL,
								'innerWidth' => NULL,
								'innerHeight' => NULL,
								'initialWidth' => 300,
								'initialHeight' => 100,
								'maxWidth' => NULL,
								'maxHeight' => NULL,
								'slideshow' => 'true',
								'slideshowSpeed' => 2500,
								'slideshowAuto' => NULL,
								'slideshowStart' => 'start slideshow',
								'slideshowStop' => 'stop slideshow',
								'fixed' => NULL,
								'top' => NULL,
								'bottom' => NULL,
								'left' => NULL,
								'right' => NULL,
								'reposition' => 'true',
								'retinaImage' => NULL
							);
			update_option( 'boxersandswipers_colorbox', $colorbox_tbl );
		} else {
			$settings_tbl = get_option('boxersandswipers_colorbox');
			foreach ($settings_tbl as $key => $value) {
				if ( $value === 'false' ) {
					$settings_tbl[$key] = NULL;
				}
			}
			update_option( 'boxersandswipers_colorbox', $settings_tbl );
		}

		if ( !get_option('boxersandswipers_slimbox') ) {
			$slimbox_tbl = array(
								'loop' => NULL,
								'overlayOpacity' => 0.8,
								'overlayFadeDuration' => 400,
								'resizeDuration' => 400,
								'resizeEasing' => 'swing',
								'initialWidth' => 250,
								'initialHeight' => 250,
								'imageFadeDuration' => 400,
								'captionAnimationDuration' => 400,
								'counterText' => 'Image {x} of {y}',
								'closeKeys' => '[27, 88, 67]',
								'previousKeys' => '[37, 80]',
								'nextKeys' => '[39, 78]'
							);
			update_option( 'boxersandswipers_slimbox', $slimbox_tbl );
		} else {
			$settings_tbl = get_option('boxersandswipers_slimbox');
			foreach ($settings_tbl as $key => $value) {
				if ( $value === 'false' ) {
					$settings_tbl[$key] = NULL;
				}
			}
			update_option( 'boxersandswipers_slimbox', $settings_tbl );
		}

		if ( !get_option('boxersandswipers_nivolightbox') ) {
			$nivolightbox_tbl = array(
								'effect' => 'fade',
								'keyboardNav' => 'true',
								'clickOverlayToClose' => 'true'
							);
			update_option( 'boxersandswipers_nivolightbox', $nivolightbox_tbl );
		} else {
			$settings_tbl = get_option('boxersandswipers_nivolightbox');
			foreach ($settings_tbl as $key => $value) {
				if ( $value === 'false' ) {
					$settings_tbl[$key] = NULL;
				}
			}
			update_option( 'boxersandswipers_nivolightbox', $settings_tbl );
		}

		if ( !get_option('boxersandswipers_imagelightbox') ) {
			$imagelightbox_tbl = array(
								'animationSpeed' => 250,
								'preloadNext' => 'true',
								'enableKeyboard' => 'true',
								'quitOnEnd' => NULL,
								'quitOnImgClick' => NULL,
								'quitOnDocClick' => NULL
							);
			update_option( 'boxersandswipers_imagelightbox', $imagelightbox_tbl );
		} else {
			$settings_tbl = get_option('boxersandswipers_imagelightbox');
			foreach ($settings_tbl as $key => $value) {
				if ( $value === 'false' ) {
					$settings_tbl[$key] = NULL;
				}
			}
			update_option( 'boxersandswipers_imagelightbox', $settings_tbl );
		}

		if ( !get_option('boxersandswipers_photoswipe') ) {
			$photoswipe_tbl = array(
									'bgOpacity' => 1,
									'captionArea' => 'true',
									'shareButton' => 'true',
									'fullScreenButton' => 'true',
									'zoomButton' => 'true',
									'preloaderButton' => 'true',
									'tapToClose' => NULL,
									'tapToToggleControls' => 'true',
									'animationDuration' => 333,
									'maxSpreadZoom' => 2,
									'history' => 'true'
								);
			update_option( 'boxersandswipers_photoswipe', $photoswipe_tbl );
		} else {
			$boxersandswipers_photoswipe = get_option('boxersandswipers_photoswipe');
			if ( $plugin_version < 2.30 ) {
				$photoswipe_tbl = array(
										'fadeInSpeed' => $boxersandswipers_photoswipe['fadeInSpeed'],
										'fadeOutSpeed' => $boxersandswipers_photoswipe['fadeOutSpeed'],
										'slideSpeed' => $boxersandswipers_photoswipe['slideSpeed'],
										'swipeThreshold' => $boxersandswipers_photoswipe['swipeThreshold'],
										'swipeTimeThreshold' => $boxersandswipers_photoswipe['swipeTimeThreshold'],
										'loop' => $boxersandswipers_photoswipe['loop'],
										'slideshowDelay' => $boxersandswipers_photoswipe['slideshowDelay'],
										'imageScaleMethod' => $boxersandswipers_photoswipe['imageScaleMethod'],
										'preventHide' => $boxersandswipers_photoswipe['preventHide'],
										'backButtonHideEnabled' => $boxersandswipers_photoswipe['backButtonHideEnabled'],
										'captionAndToolbarHide' => $boxersandswipers_photoswipe['captionAndToolbarHide'],
										'captionAndToolbarHideOnSwipe' => $boxersandswipers_photoswipe['captionAndToolbarHideOnSwipe'],
										'captionAndToolbarFlipPosition' => $boxersandswipers_photoswipe['captionAndToolbarFlipPosition'],
										'captionAndToolbarAutoHideDelay' => $boxersandswipers_photoswipe['captionAndToolbarAutoHideDelay'],
										'captionAndToolbarOpacity' => $boxersandswipers_photoswipe['captionAndToolbarOpacity'],
										'captionAndToolbarShowEmptyCaptions' => $boxersandswipers_photoswipe['captionAndToolbarShowEmptyCaptions']
									);
			} else if ( $plugin_version >= 2.30 ) {
				$photoswipe_tbl = array(
										'bgOpacity' => $boxersandswipers_photoswipe['bgOpacity'],
										'captionArea' => $boxersandswipers_photoswipe['captionArea'],
										'shareButton' => $boxersandswipers_photoswipe['shareButton'],
										'fullScreenButton' => $boxersandswipers_photoswipe['fullScreenButton'],
										'zoomButton' => $boxersandswipers_photoswipe['zoomButton'],
										'preloaderButton' => $boxersandswipers_photoswipe['preloaderButton'],
										'tapToClose' => $boxersandswipers_photoswipe['tapToClose'],
										'tapToToggleControls' => $boxersandswipers_photoswipe['tapToToggleControls'],
										'animationDuration' => $boxersandswipers_photoswipe['animationDuration'],
										'maxSpreadZoom' => $boxersandswipers_photoswipe['maxSpreadZoom'],
										'history' => $boxersandswipers_photoswipe['history']
									);
				foreach ($photoswipe_tbl as $key => $value) {
					if ( $value === 'false' ) {
						$photoswipe_tbl[$key] = NULL;
					}
				}
			}
			update_option( 'boxersandswipers_photoswipe', $photoswipe_tbl );
		}

		if ( !get_option('boxersandswipers_swipebox') ) {
			$swipebox_tbl = array(
								'hideBarsDelay' => 3000,
								'loopAtEnd' => NULL
							);
			update_option( 'boxersandswipers_swipebox', $swipebox_tbl );
		} else {
			$boxersandswipers_swipebox = get_option('boxersandswipers_swipebox');
			if ( $plugin_version >= 2.22 ) {
				foreach ($boxersandswipers_swipebox as $key => $value) {
					if ( $value === 'false' ) {
						$boxersandswipers_swipebox[$key] = NULL;
					}
				}
				if ( array_key_exists( "loopAtEnd", $boxersandswipers_swipebox ) ) {
					$loopatend = $boxersandswipers_swipebox['loopAtEnd'];
				} else {
					$loopatend = NULL;
				}
				$swipebox_tbl = array(
									'hideBarsDelay' => $boxersandswipers_swipebox['hideBarsDelay'],
									'loopAtEnd' => $loopatend
								);
				update_option( 'boxersandswipers_swipebox', $swipebox_tbl );
			}
		}

	}

}

?>