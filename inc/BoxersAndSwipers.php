<?php
/**
 * Boxers and Swipers
 * 
 * @package    Boxers and Swipers
 * @subpackage BoxersAndSwipers Main Functions
/*  Copyright (c) 2014- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
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

class BoxersAndSwipers {

	public $effect;

	/* ==================================================
	* @param	string	$link
	* @return	string	$link
	* @since	1.0
	*/
	function add_anchor_tag($link) {

		$boxersandswipers_exclude = get_post_meta( get_the_ID(), 'boxersandswipers_exclude' );
		$simplemasonry_apply = get_post_meta( get_the_ID(), 'simplemasonry_apply' );
		$simplenivoslider_apply = get_post_meta( get_the_ID(), 'simplenivoslider_apply' );

		if ( !empty($boxersandswipers_exclude) && $boxersandswipers_exclude[0] ) {
			// Through
		} else if ( class_exists('SimpleMasonry') && get_post_gallery(get_the_ID()) && !empty($simplemasonry_apply) && $simplemasonry_apply[0] ) {
			// for Simple Masonry Gallery http://wordpress.org/plugins/simple-masonry-gallery/
			// for Gallery
			add_filter( 'post_gallery', array(&$this, 'gallery_filter') );
			$link = $this->add_anchor_tag_content($link);
		} else if ( class_exists('SimpleNivoSlider') && !empty($simplenivoslider_apply) && $simplenivoslider_apply[0] ) {
			// for Simple Nivo Slider http://wordpress.org/plugins/simple-nivoslider/
			// Through
		} else {
			if ( !is_attachment() ) {
				// for Gallery
				add_shortcode( 'gallery', array(&$this, 'file_gallery_shortcode') );
				add_filter( 'post_gallery', array(&$this, 'gallery_filter') );
				// for Insert Attachement
				$link = $this->add_anchor_tag_content($link);
			}
		}

		return $link;

	}

	/* ==================================================
	* @param	string	$link
	* @return	$link
	* @since	1.0
	*/
	function add_anchor_tag_content($link) {

		if(preg_match_all("/\s+href\s*=\s*([\"\']?)([^\s\"\'>]+)(\\1)/ims", $link, $result) !== false){
			global $post;
			$attachments = get_children(
				array(
					'post_parent' => $post->ID,
					'post_type' => 'attachment',
					'post_status' => 'any',
					'post_mime_type' => 'image'
				)
			);
	    	foreach ($result[0] as $value){
				$exts = explode('.', substr($value, 0, -1));
				$ext = end($exts);
				$ext2type = wp_ext2type($ext);
				if ( $ext2type === 'image' ) {
					if ( $this->effect === 'colorbox' ) {
						// colorbox
						$class_name = ' class="boxersandswipers"';
						$titlename = NULL;
						foreach ( $attachments as $attachment ) {
							if( strpos($value, get_post_meta( $attachment->ID, '_wp_attached_file', true )) ){
								$titlename = ' title="'.$attachment->post_title.'"';
							}
						}
						$link = str_replace($value, $class_name.$titlename.$value, $link);
					} else if ( $this->effect === 'slimbox' ) {
						//slimbox
						$rel_name = ' rel="boxersandswipers"';
						$titlename = NULL;
						foreach ( $attachments as $attachment ) {
							if( strpos($value, get_post_meta( $attachment->ID, '_wp_attached_file', true )) ){
								$titlename = ' title="'.$attachment->post_title.'"';
							}
						}
						$link = str_replace($value, $rel_name.$titlename.$value, $link);
					} else if ( $this->effect === 'nivolightbox' ) {
						//nivolightbox
						$rel_name = ' data-lightbox-gallery="boxersandswipers"';
						$titlename = NULL;
						foreach ( $attachments as $attachment ) {
							if( strpos($value, get_post_meta( $attachment->ID, '_wp_attached_file', true )) ){
								$titlename = ' title="'.$attachment->post_title.'"';
							}
						}
						$link = str_replace($value, $rel_name.$titlename.$value, $link);
					} else if ( $this->effect === 'imagelightbox' ) {
						//imagelightbox
						$rel_name = ' data-imagelightbox="boxersandswipers"';
						$titlename = NULL;
						foreach ( $attachments as $attachment ) {
							if( strpos($value, get_post_meta( $attachment->ID, '_wp_attached_file', true )) ){
								$titlename = ' title="'.$attachment->post_title.'"';
							}
						}
						$link = str_replace($value, $rel_name.$titlename.$value, $link);
					} else if ( $this->effect === 'photoswipe' || $this->effect === 'swipebox' ) {
						//photoswipe || swipebox
						$rel_name = ' rel="boxers-and-swipers"';
						$class_name = ' class="boxersandswipers"';
						$titlename = NULL;
						foreach ( $attachments as $attachment ) {
							if( strpos($value, get_post_meta( $attachment->ID, '_wp_attached_file', true )) ){
								$titlename = ' title="'.$attachment->post_title.'"';
							}
						}
						$link = str_replace($value, $rel_name.$class_name.$titlename.$value, $link);
					}
				}
	    	}
		}
		if(preg_match_all("/<img(.+?)>/i", $link, $result) !== false){
	    	foreach ($result[1] as $value){
				preg_match('/src=\"(.[^\"]*)\"/',$value,$src);
				if ( isset($src[1]) ) {
				$explode = explode("/" , $src[1]);
				$file_name = $explode[count($explode) - 1];
				$alt_name = preg_replace("/(.+)(\.[^.]+$)/", "$1", $file_name);
					if( !strpos($value, 'alt=') ) {
						$alt_name = ' alt="'.$alt_name.'" ';
						$link = str_replace($value, $alt_name.$value, $link);
					}
				}
			}
		}

		return $link;

	}

	/* ==================================================
	* @param	string	$link
	* @return	none
	* @since	1.0
	*/
	function gallery_filter($link) {
		add_filter( 'wp_get_attachment_link', array(&$this, 'add_anchor_tag_gallery'), 10, 6 );	
	}

	/* ==================================================
	* @param	string	$link
	* @return	string	$link
	* @since	1.0
	*/
	function add_anchor_tag_gallery($link, $id, $size, $permalink, $icon, $text) {

		if(preg_match_all("/\s+href\s*=\s*([\"\']?)([^\s\"\'>]+)(\\1)/ims", $link, $result) !== false){
	    	foreach ($result[0] as $value){
				$exts = explode('.', substr($value, 0, -1));
				$ext = end($exts);
				$ext2type = wp_ext2type($ext);
				if ( $ext2type === 'image' ) {
					$titlename = ' title="'.get_the_title($id).'"';
					if ( $this->effect === 'colorbox' ) {
						// colorbox
						$class_name = 'class="boxersandswipers"';
					    $link = str_replace( '<a', '<a '.$class_name.$titlename, $link );
					} else if ( $this->effect === 'slimbox' ) {
						//slimbox
						$rel_name = 'rel="boxersandswipers"';
					    $link = str_replace( '<a', '<a '.$rel_name.$titlename, $link );
					} else if ( $this->effect === 'nivolightbox' ) {
						//nivolightbox
						$rel_name = 'data-lightbox-gallery="boxersandswipers"';
					    $link = str_replace( '<a', '<a '.$rel_name.$titlename, $link );
					} else if ( $this->effect === 'imagelightbox' ) {
						//imagelightbox
						$rel_name = 'data-imagelightbox="boxersandswipers"';
					    $link = str_replace( '<a', '<a '.$rel_name.$titlename, $link );
					} else if ( $this->effect === 'photoswipe' ) {
						//photoswipe
						$rel_name = 'rel="boxers-and-swipers"';
						$class_name = ' class="boxersandswipers" ';
					    $link = str_replace( '<a', '<a '.$rel_name.$class_name, $link );
					} else if ( $this->effect === 'swipebox' ) {
						//swipebox
						$rel_name = 'rel="boxers-and-swipers"';
						$class_name = ' class="boxersandswipers"';
					    $link = str_replace( '<a', '<a '.$rel_name.$class_name.$titlename, $link );
					}
				}
			}
		}

		return $link;

	}

	/* ==================================================
	* Load Script
	* @param	none
	* @since	3.00
	*/
	function load_frontend_scripts(){

		$boxersandswipers_plugin_url = plugins_url($path='boxers-and-swipers',$scheme=null);
		$boxersandswipers_apply = get_option('boxersandswipers_apply');
		$device = $this->agent_check();
		$add_apply_set = array( 'infinitescroll' => $boxersandswipers_apply['infinitescroll'][$device]);

		wp_enqueue_script('jquery');

		if ($this->effect === 'colorbox'){
			// for COLORBOX
			wp_enqueue_script( 'colorbox', $boxersandswipers_plugin_url.'/colorbox/jquery.colorbox-min.js', null, '1.4.37');
			wp_enqueue_script( 'colorbox-jquery', $boxersandswipers_plugin_url.'/js/jquery.boxersandswipers.colorbox.js',array('jquery'));
			$settings_tbl = get_option('boxersandswipers_colorbox');
			$settings_tbl = array_merge( $settings_tbl, $add_apply_set );
			wp_localize_script( 'colorbox-jquery', 'colorbox_settings', $settings_tbl );
		} else if ($this->effect === 'slimbox'){
			// for slimbox
			wp_enqueue_script( 'slimbox', $boxersandswipers_plugin_url.'/slimbox/js/slimbox2.js', null, '2.05');
			wp_enqueue_script( 'slimbox-jquery', $boxersandswipers_plugin_url.'/js/jquery.boxersandswipers.slimbox.js',array('jquery'));
			$settings_tbl = get_option('boxersandswipers_slimbox');
			$settings_tbl = array_merge( $settings_tbl, $add_apply_set );
			wp_localize_script( 'slimbox-jquery', 'slimbox_settings', $settings_tbl );
		} else if ($this->effect === 'nivolightbox'){
			// for nivolightbox
			wp_enqueue_script( 'nivolightbox', $boxersandswipers_plugin_url.'/nivolightbox/nivo-lightbox.min.js', null, '1.2.0');
			wp_enqueue_script( 'nivolightbox-jquery', $boxersandswipers_plugin_url.'/js/jquery.boxersandswipers.nivolightbox.js',array('jquery'));
			$settings_tbl = get_option('boxersandswipers_nivolightbox');
			$settings_tbl = array_merge( $settings_tbl, $add_apply_set );
			wp_localize_script( 'nivolightbox-jquery', 'nivolightbox_settings', $settings_tbl );
		} else if ($this->effect === 'imagelightbox'){
			// for imagelightbox
			wp_enqueue_script( 'imagelightbox', $boxersandswipers_plugin_url.'/imagelightbox/imagelightbox.min.js');
			wp_enqueue_script( 'imagelightbox-jquery', $boxersandswipers_plugin_url.'/js/jquery.boxersandswipers.imagelightbox.js',array('jquery'));
			$settings_tbl = get_option('boxersandswipers_imagelightbox');
			$settings_tbl = array_merge( $settings_tbl, $add_apply_set );
			wp_localize_script( 'imagelightbox-jquery', 'imagelightbox_settings', $settings_tbl );
		} else if ($this->effect === 'photoswipe'){
			// for PhotoSwipe
			wp_enqueue_script( 'photoswipe' , $boxersandswipers_plugin_url.'/photoswipe/jquery.photoswipe.js', null, '4.1.1' );
			wp_enqueue_script( 'photoswipe-jquery', $boxersandswipers_plugin_url.'/js/jquery.boxersandswipers.photoswipe.js',array('jquery'));
			$settings_tbl = get_option('boxersandswipers_photoswipe');
			$settings_tbl = array_merge( $settings_tbl, $add_apply_set );
			wp_localize_script( 'photoswipe-jquery', 'photoswipe_settings', $settings_tbl );
		} else if ($this->effect === 'swipebox'){
			// for Swipebox
			wp_enqueue_script( 'swipebox' , $boxersandswipers_plugin_url.'/swipebox/js/jquery.swipebox.min.js', null, '1.3.0.1' );
			wp_enqueue_script( 'swipebox-jquery', $boxersandswipers_plugin_url.'/js/jquery.boxersandswipers.swipebox.js',array('jquery'));
			$settings_tbl = get_option('boxersandswipers_swipebox');
			$settings_tbl = array_merge( $settings_tbl, $add_apply_set );
			wp_localize_script( 'swipebox-jquery', 'swipebox_settings', $settings_tbl );
		}

	}

	/* ==================================================
	* Load CSS
	* @param	none
	* @since	3.00
	*/
	function load_frontend_styles() {

		$boxersandswipers_plugin_url = plugins_url($path='boxers-and-swipers',$scheme=null);

		if ($this->effect === 'colorbox'){
			// for COLORBOX
			wp_enqueue_style( 'colorbox',  $boxersandswipers_plugin_url.'/colorbox/colorbox.css' );
		} else if ($this->effect === 'slimbox'){
			// for slimbox
			wp_enqueue_style( 'slimbox',  $boxersandswipers_plugin_url.'/slimbox/css/slimbox2.css' );
		} else if ($this->effect === 'nivolightbox'){
			// for nivolightbox
			wp_enqueue_style( 'nivolightbox',  $boxersandswipers_plugin_url.'/nivolightbox/nivo-lightbox.css' );
			wp_enqueue_style( 'nivolightbox-themes',  $boxersandswipers_plugin_url.'/nivolightbox/themes/default/default.css' );
		} else if ($this->effect === 'imagelightbox'){
			// for imagelightbox
			wp_enqueue_style( 'imagelightbox',  $boxersandswipers_plugin_url.'/imagelightbox/imagelightbox.css' );
		} else if ($this->effect === 'photoswipe'){
			// for PhotoSwipe
			wp_enqueue_style( 'photoswipe',  $boxersandswipers_plugin_url.'/photoswipe/photoswipe.css' );
		} else if ($this->effect === 'swipebox'){
			// for Swipebox
			wp_enqueue_style( 'swipebox',  $boxersandswipers_plugin_url.'/swipebox/css/swipebox.min.css' );
		}

	}

	/* ==================================================
	* @param	none
	* @return	string	$device
	* @since	1.0
	*/
	function agent_check(){

		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		$boxersandswipers_useragent = get_option('boxersandswipers_useragent');

		if(preg_match("{".$boxersandswipers_useragent['tb']."}",$user_agent)){
			//Tablet
			$device = "tb"; 
		}else if(preg_match("{".$boxersandswipers_useragent['sp']."}",$user_agent)){
			//Smartphone
			$device = "sp";
		}else{
			$device = "pc"; 
		}

		return $device;

	}

	/* ==================================================
	* @param	Array	$atts
	* @return	Array	$atts
	* @since	1.5
	*/
	function file_gallery_shortcode( $atts ){

		if ( empty($atts['link']) ) {
		    $atts['link'] = 'file';
		} else if ( $atts['link'] === 'none' ) {
		    $atts['link'] = 'none';
		} else {
		    $atts['link'] = 'file';
		}

	    return gallery_shortcode( $atts );

	}

	/* ==================================================
	* @param	string	$html
	* @param	int		$post_id
	* @param	string	$post_thumbnail_id
	* @param	string	$size
	* @param	array	$attr
	* @return	string	$html
	* @since	2.40
	*/
	function add_post_thumbnail_tag($html, $post_id, $post_thumbnail_id, $size, $attr) {
		$device = $this->agent_check();
		$boxersandswipers_apply = get_option('boxersandswipers_apply');
		if ( has_post_thumbnail() && $boxersandswipers_apply['postthumbnails'][$device] ) {
			$large_image_url = wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
			$link = ' href="'.$large_image_url[0];

			if ( $this->effect === 'colorbox' ) {
				// colorbox
				$class_name = ' class="boxersandswipers" title="'.get_the_title($post_thumbnail_id).'"';
			} else if ( $this->effect === 'slimbox' ) {
				//slimbox
				$class_name = ' rel="boxersandswipers" title="'.get_the_title($post_thumbnail_id).'"';
			} else if ( $this->effect === 'nivolightbox' ) {
				//nivolightbox
				$class_name = ' data-lightbox-gallery="boxersandswipers" title="'.get_the_title($post_thumbnail_id).'"';
			} else if ( $this->effect === 'imagelightbox' ) {
				//imagelightbox
				$class_name = ' data-imagelightbox="boxersandswipers" title="'.get_the_title($post_thumbnail_id).'"';
			} else if ( $this->effect === 'photoswipe' || $this->effect === 'swipebox' ) {
				//photoswipe || swipebox
				$class_name = ' rel="boxers-and-swipers" class="boxersandswipers" title="'.get_the_title($post_thumbnail_id).'"';
			}

			$html2 = '<a '.$class_name.$link. '">';
			$html2 .= $html; 
			$html2 .= '</a>';
			return $html2;
		} else {
			return $html;
		}
	}

}

?>