<?php
/**
 * Boxers and Swipers
 * 
 * @package    Boxers and Swipers
 * @subpackage BoxersAndSwipersAdmin Management screen
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

class BoxersAndSwipersAdmin {

	/* ==================================================
	 * Add a "Settings" link to the plugins page
	 * @since	1.0
	 */
	function settings_link( $links, $file ) {
		static $this_plugin;
		if ( empty($this_plugin) ) {
			$this_plugin = BOXERSANDSWIPERS_PLUGIN_BASE_FILE;
		}
		if ( $file == $this_plugin ) {
			$links[] = '<a href="'.admin_url('options-general.php?page=boxersandswipers').'">'.__( 'Settings').'</a>';
		}
			return $links;
	}

	/* ==================================================
	 * Settings page
	 * @since	1.0
	 */
	function plugin_menu() {
		add_options_page( 'Boxers and Swipers Options', 'Boxers and Swipers', 'manage_options', 'boxersandswipers', array($this, 'plugin_options') );
	}

	/* ==================================================
	 * Add Css and Script
	 * @since	1.26
	 */
	function load_custom_wp_admin_style() {
		if ($this->is_my_plugin_screen()) {
			$boxersandswipers_plugin_url = plugins_url($path='boxers-and-swipers',$scheme=null);
			wp_enqueue_style( 'jquery-responsiveTabs', $boxersandswipers_plugin_url.'/css/responsive-tabs.css' );
			wp_enqueue_style( 'jquery-responsiveTabs-style', $boxersandswipers_plugin_url.'/css/style.css' );
			wp_enqueue_style( 'boxersandswipers-style', $boxersandswipers_plugin_url.'/css/boxersandswipers.css' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-responsiveTabs', $boxersandswipers_plugin_url.'/js/jquery.responsiveTabs.min.js' );
			wp_enqueue_script( 'boxersandswipers-js', $boxersandswipers_plugin_url.'/js/jquery.boxersandswipers.admin.js', array('jquery') );

		}
	}

	/* ==================================================
	 * For only admin style
	 * @since	2.28
	 */
	function is_my_plugin_screen() {
		$screen = get_current_screen();
		if (is_object($screen) && $screen->id == 'settings_page_boxersandswipers') {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/* ==================================================
	 * Settings page
	 * @since	1.0
	 */
	function plugin_options() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		if( !empty($_POST) ) {
			$settings_tabs = intval($_POST['boxersandswipers_admin_tabs']);
			$post_nonce_field = 'boxersandswipers_tabs'.$settings_tabs;
			if ( isset($_POST[$post_nonce_field]) && $_POST[$post_nonce_field] ) {
				if ( check_admin_referer( 'bxsw_settings'.$settings_tabs, $post_nonce_field ) ) {
					$this->options_updated($settings_tabs);
				}
			}
		}

		$scriptname = admin_url('options-general.php?page=boxersandswipers');

		$boxersandswipers_apply = get_option('boxersandswipers_apply');
		$boxersandswipers_effect = get_option('boxersandswipers_effect');
		$boxersandswipers_useragent = get_option('boxersandswipers_useragent');
		$boxersandswipers_colorbox = get_option('boxersandswipers_colorbox');
		$boxersandswipers_slimbox = get_option('boxersandswipers_slimbox');
		$boxersandswipers_nivolightbox = get_option('boxersandswipers_nivolightbox');
		$boxersandswipers_imagelightbox = get_option('boxersandswipers_imagelightbox');
		$boxersandswipers_photoswipe = get_option('boxersandswipers_photoswipe');
		$boxersandswipers_slideshow = get_option('boxersandswipers_slideshow');
		$boxersandswipers_swipebox = get_option('boxersandswipers_swipebox');

		$applytypes = $this->apply_type();

		?>

		<div class="wrap">
		<h2>Boxers and Swipers</h2>

	<div id="boxersandswipers_admin_tabs">
	  <ul>
	    <li><a href="#boxersandswipers_admin_tabs-1"><?php _e('Device Settings', 'boxers-and-swipers') ?></a></li>
		<li><a href="#boxersandswipers_admin_tabs-2">Colorbox&nbsp<?php _e('Settings'); ?></a></li>
		<li><a href="#boxersandswipers_admin_tabs-3">Slimbox&nbsp<?php _e('Settings'); ?></a></li>
		<li><a href="#boxersandswipers_admin_tabs-4">Nivo Lightbox&nbsp<?php _e('Settings'); ?></a></li>
		<li><a href="#boxersandswipers_admin_tabs-5">Image Lightbox&nbsp<?php _e('Settings'); ?></a></li>
		<li><a href="#boxersandswipers_admin_tabs-6">PhotoSwipe&nbsp<?php _e('Settings'); ?></a></li>
		<li><a href="#boxersandswipers_admin_tabs-7">Swipebox&nbsp<?php _e('Settings'); ?></a></li>
		<li><a href="#boxersandswipers_admin_tabs-8"><?php _e('Exclude', 'boxers-and-swipers'); ?></a></li>
		<li><a href="#boxersandswipers_admin_tabs-9"><?php _e('Donate to this plugin &#187;'); ?></a></li>
	<!--
		<li><a href="#boxersandswipers_admin_tabs-10">FAQ</a></li>
	 -->
	  </ul>

	  <div id="boxersandswipers_admin_tabs-1">
		<div class="wrap">

			<form method="post" action="<?php echo $scriptname; ?>">
			<?php wp_nonce_field('bxsw_settings1', 'boxersandswipers_tabs1'); ?>

			<h2><?php _e('Device Settings', 'boxers-and-swipers') ?></h2>	

			<p class="submit">
			<?php submit_button( __('Save Changes'), 'large', 'Submit', FALSE ); ?>
			<?php submit_button( __('Default'), 'large', 'Default', FALSE ); ?>
			</p>

			<div>
				<div style="padding:10px;border:#CCC 2px solid; margin:0 0 20px 0">
					<div style="display:block"><b>PC</b></div>
					<div style="display:block;padding:20px 0">
					<?php $target_effect_pc = $boxersandswipers_effect['pc']; ?>
					<select id="boxersandswipers_effect_pc" name="boxersandswipers_effect_pc">
						<option value='colorbox' <?php if ('colorbox' == $target_effect_pc)echo 'selected="selected"'; ?>>Colorbox</option>
						<option value='slimbox' <?php if ('slimbox' == $target_effect_pc)echo 'selected="selected"'; ?>>Slimbox</option>
						<option value='nivolightbox' <?php if ('nivolightbox' == $target_effect_pc)echo 'selected="selected"'; ?>>Nivo Lightbox</option>
						<option value='imagelightbox' <?php if ('imagelightbox' == $target_effect_pc)echo 'selected="selected"'; ?>>Image Lightbox</option>
						<option value='photoswipe' <?php if ('photoswipe' == $target_effect_pc)echo 'selected="selected"'; ?>>PhotoSwipe</option>
						<option value='swipebox' <?php if ('swipebox' == $target_effect_pc)echo 'selected="selected"'; ?>>Swipebox</option>
					</select>
					</div>
					<div>
					<?php
					foreach ( $applytypes as $key => $value ) {
						?>
						<div style="float:left;margin:7px">
						<input name="boxersandswipers_apply_pc[<?php echo $key; ?>]" type="checkbox" value="true" <?php if ($boxersandswipers_apply[$key]['pc']) echo 'checked'; ?>><?php echo $value; ?></div>&nbsp;&nbsp;
						<?php
					}
					?>
					<div style="clear:both"></div>
					</div>
				</div>

				<div style="padding:10px; border:#CCC 2px solid">
					<div style="display:block"><b>Tablet</b></div>
					<div style="display:block;padding:20px 0">
					<?php $target_effect_tb = $boxersandswipers_effect['tb']; ?>
					<select id="boxersandswipers_effect_tb" name="boxersandswipers_effect_tb">
						<option value='colorbox' <?php if ('colorbox' == $target_effect_tb)echo 'selected="selected"'; ?>>Colorbox</option>
						<option value='slimbox' <?php if ('slimbox' == $target_effect_tb)echo 'selected="selected"'; ?>>Slimbox</option>
						<option value='nivolightbox' <?php if ('nivolightbox' == $target_effect_tb)echo 'selected="selected"'; ?>>Nivo Lightbox</option>
						<option value='imagelightbox' <?php if ('imagelightbox' == $target_effect_tb)echo 'selected="selected"'; ?>>Image Lightbox</option>
						<option value='photoswipe' <?php if ('photoswipe' == $target_effect_tb)echo 'selected="selected"'; ?>>PhotoSwipe</option>
						<option value='swipebox' <?php if ('swipebox' == $target_effect_tb)echo 'selected="selected"'; ?>>Swipebox</option>
					</select>
					</div>
					<div style="padding:0 0 20px">
					<?php
					foreach ( $applytypes as $key => $value ) {
						?>
						<div style="float:left;margin:7px">
						<input name="boxersandswipers_apply_tb[<?php echo $key; ?>]" type="checkbox" value="true" <?php if ($boxersandswipers_apply[$key]['tb']) echo 'checked'; ?>><?php echo $value; ?></div>&nbsp;&nbsp;
						<?php
					}
					?>
					<div style="clear:both"></div>
					</div>
					<div style="display:box">
						<p>
						<?php _e('User Agent (Regular Expressions are Possible)', 'boxers-and-swipers'); ?>
						</p>
						<p>
						<textarea id="boxersandswipers_useragent_tb" name="boxersandswipers_useragent_tb" rows="5" style="width: 100%;"><?php echo $boxersandswipers_useragent['tb'] ?></textarea>
						</p>
					</div>
					<div style="clear:both"></div>
				</div>

				<div style="margin:20px 0; padding:10px; border:#CCC 2px solid">
					<div style="display:block"><b>Smartphone</b></div>
					<div style="display:block;margin:20px 0">
					<?php $target_effect_sp = $boxersandswipers_effect['sp']; ?>
					<select id="boxersandswipers_effect_sp" name="boxersandswipers_effect_sp">
						<option value='colorbox' <?php if ('colorbox' == $target_effect_sp)echo 'selected="selected"'; ?>>Colorbox</option>
						<option value='slimbox' <?php if ('slimbox' == $target_effect_sp)echo 'selected="selected"'; ?>>Slimbox</option>
						<option value='nivolightbox' <?php if ('nivolightbox' == $target_effect_sp)echo 'selected="selected"'; ?>>Nivo Lightbox</option>
						<option value='imagelightbox' <?php if ('imagelightbox' == $target_effect_sp)echo 'selected="selected"'; ?>>Image Lightbox</option>
						<option value='photoswipe' <?php if ('photoswipe' == $target_effect_sp)echo 'selected="selected"'; ?>>PhotoSwipe</option>
						<option value='swipebox' <?php if ('swipebox' == $target_effect_sp)echo 'selected="selected"'; ?>>Swipebox</option>
					</select>
					</div>
					<div style="padding:0 0 20px">
					<?php
					foreach ( $applytypes as $key => $value ) {
						?>
						<div style="float:left;margin:7px">
						<input name="boxersandswipers_apply_sp[<?php echo $key; ?>]" type="checkbox" value="true" <?php if ($boxersandswipers_apply[$key]['sp']) echo 'checked'; ?>><?php echo $value; ?></div>&nbsp;&nbsp;
						<?php
					}
					?>
					<div style="clear:both"></div>
					</div>
					<div style="display:block">
						<p>
						<?php _e('User Agent (Regular Expressions are Possible)', 'boxers-and-swipers'); ?>
						</p>
						<p>
						<textarea id="boxersandswipers_useragent_sp" name="boxersandswipers_useragent_sp" rows="5" style="width: 100%;"><?php echo $boxersandswipers_useragent['sp'] ?></textarea>
						</p>
					</div>
					<div style="clear:both"></div>
				</div>

			</div>
			<div style="clear:both"></div>

			<input type="hidden" name="boxersandswipers_admin_tabs" value="1" />
			<?php submit_button( __('Save Changes'), 'large', 'Submit', TRUE ); ?>

			</form>

			<div align="right"><a href="http://a4jp.com" target="_blank">Mr. Glen Charles Rowell. Thank you for a nice design.</a></div>

		</div>
	  </div>

	  <div id="boxersandswipers_admin_tabs-2">
		<div class="wrap">
			<h2>Colorbox <?php _e('Settings'); ?> (<a href="http://www.jacklmoore.com/colorbox/" target="_blank"><font color="red">Colorbox <?php _e('Website'); ?></font></a>)</h2>	

			<form method="post" action="<?php echo $scriptname.'#boxersandswipers_admin_tabs-2'; ?>">
			<?php wp_nonce_field('bxsw_settings2', 'boxersandswipers_tabs2'); ?>

			<p class="submit">
			<?php submit_button( __('Save Changes'), 'large', 'Submit', FALSE ); ?>
			<?php submit_button( __('Default'), 'large', 'Default', FALSE ); ?>
			</p>

			<div id="container-colorbox">

				<div class="item-boxersandswipers-settings">
					<div>transition</div>
					<div><?php _e('Default') ?>&nbsp(elastic)</div>
					<div>
					<?php $target_colorbox_transition = $boxersandswipers_colorbox['transition']; ?>
					<select id="boxersandswipers_colorbox_transition" name="boxersandswipers_colorbox_transition">
						<option <?php if ('elastic' == $target_colorbox_transition) echo 'selected="selected"'; ?>>elastic</option>
						<option <?php if ('fade' == $target_colorbox_transition) echo 'selected="selected"'; ?>>fade</option>
						<option <?php if ('none' == $target_colorbox_transition) echo 'selected="selected"'; ?>>none</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>speed</div>
					<div><?php _e('Default') ?>&nbsp(350)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_speed" name="boxersandswipers_colorbox_speed" value="<?php echo $boxersandswipers_colorbox['speed'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>title</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_title" name="boxersandswipers_colorbox_title" value="<?php echo $boxersandswipers_colorbox['title'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>scalePhotos</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_colorbox_scalePhotos = $boxersandswipers_colorbox['scalePhotos']; ?>
					<select id="boxersandswipers_colorbox_scalePhotos" name="boxersandswipers_colorbox_scalePhotos">
						<option <?php if ('true' == $target_colorbox_scalePhotos) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_scalePhotos) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>scrolling</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_colorbox_scrolling = $boxersandswipers_colorbox['scrolling']; ?>
					<select id="boxersandswipers_colorbox_scrolling" name="boxersandswipers_colorbox_scrolling">
						<option <?php if ('true' == $target_colorbox_scrolling) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_scrolling) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>opacity</div>
					<div><?php _e('Default') ?>&nbsp(0.85)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_opacity" name="boxersandswipers_colorbox_opacity" value="<?php echo $boxersandswipers_colorbox['opacity'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>open</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
					<?php $target_colorbox_open = $boxersandswipers_colorbox['open']; ?>
					<select id="boxersandswipers_colorbox_open" name="boxersandswipers_colorbox_open">
						<option <?php if ('true' == $target_colorbox_open) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_open) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>returnFocus</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_colorbox_returnFocus = $boxersandswipers_colorbox['returnFocus']; ?>
					<select id="boxersandswipers_colorbox_returnFocus" name="boxersandswipers_colorbox_returnFocus">
						<option <?php if ('true' == $target_colorbox_returnFocus) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_returnFocus) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>trapFocus</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_colorbox_trapFocus = $boxersandswipers_colorbox['trapFocus']; ?>
					<select id="boxersandswipers_colorbox_trapFocus" name="boxersandswipers_colorbox_trapFocus">
						<option <?php if ('true' == $target_colorbox_trapFocus) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_trapFocus) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>fastIframe</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_colorbox_fastIframe = $boxersandswipers_colorbox['fastIframe']; ?>
					<select id="boxersandswipers_colorbox_fastIframe" name="boxersandswipers_colorbox_fastIframe">
						<option <?php if ('true' == $target_colorbox_fastIframe) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_fastIframe) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>preloading</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_colorbox_preloading = $boxersandswipers_colorbox['preloading']; ?>
					<select id="boxersandswipers_colorbox_preloading" name="boxersandswipers_colorbox_preloading">
						<option <?php if ('true' == $target_colorbox_preloading) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_preloading) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>overlayClose</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_colorbox_overlayClose = $boxersandswipers_colorbox['overlayClose']; ?>
					<select id="boxersandswipers_colorbox_overlayClose" name="boxersandswipers_colorbox_overlayClose">
						<option <?php if ('true' == $target_colorbox_overlayClose) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_overlayClose) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>escKey</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_colorbox_escKey = $boxersandswipers_colorbox['escKey']; ?>
					<select id="boxersandswipers_colorbox_escKey" name="boxersandswipers_colorbox_escKey">
						<option <?php if ('true' == $target_colorbox_escKey) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_escKey) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>arrowKey</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_colorbox_arrowKey = $boxersandswipers_colorbox['arrowKey']; ?>
					<select id="boxersandswipers_colorbox_arrowKey" name="boxersandswipers_colorbox_arrowKey">
						<option <?php if ('true' == $target_colorbox_arrowKey) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_arrowKey) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>loop</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_colorbox_loop = $boxersandswipers_colorbox['loop']; ?>
					<select id="boxersandswipers_colorbox_loop" name="boxersandswipers_colorbox_loop">
						<option <?php if ('true' == $target_colorbox_loop) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_loop) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>fadeOut</div>
					<div><?php _e('Default') ?>&nbsp(300)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_fadeOut" name="boxersandswipers_colorbox_fadeOut" value="<?php echo $boxersandswipers_colorbox['fadeOut'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>closeButton</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_colorbox_closeButton = $boxersandswipers_colorbox['closeButton']; ?>
					<select id="boxersandswipers_colorbox_closeButton" name="boxersandswipers_colorbox_closeButton">
						<option <?php if ('true' == $target_colorbox_closeButton) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_closeButton) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>current</div>
					<div><?php _e('Default') ?>&nbsp(image {current} of {total})</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_current" name="boxersandswipers_colorbox_current" value="<?php echo $boxersandswipers_colorbox['current'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>previous</div>
					<div><?php _e('Default') ?>&nbsp(previous)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_previous" name="boxersandswipers_colorbox_previous" value="<?php echo $boxersandswipers_colorbox['previous'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>next</div>
					<div><?php _e('Default') ?>&nbsp(next)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_next" name="boxersandswipers_colorbox_next" value="<?php echo $boxersandswipers_colorbox['next'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>close</div>
					<div><?php _e('Default') ?>&nbsp(close)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_close" name="boxersandswipers_colorbox_close" value="<?php echo $boxersandswipers_colorbox['close'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>width</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_width" name="boxersandswipers_colorbox_width" value="<?php echo $boxersandswipers_colorbox['width'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>height</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_height" name="boxersandswipers_colorbox_height" value="<?php echo $boxersandswipers_colorbox['height'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>innerWidth</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_innerWidth" name="boxersandswipers_colorbox_innerWidth" value="<?php echo $boxersandswipers_colorbox['innerWidth'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>innerHeight</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_innerHeight" name="boxersandswipers_colorbox_innerHeight" value="<?php echo $boxersandswipers_colorbox['innerHeight'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>initialWidth</div>
					<div><?php _e('Default') ?>&nbsp(300)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_initialWidth" name="boxersandswipers_colorbox_initialWidth" value="<?php echo $boxersandswipers_colorbox['initialWidth'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>initialHeight</div>
					<div><?php _e('Default') ?>&nbsp(100)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_initialHeight" name="boxersandswipers_colorbox_initialHeight" value="<?php echo $boxersandswipers_colorbox['initialHeight'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>maxWidth</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_maxWidth" name="boxersandswipers_colorbox_maxWidth" value="<?php echo $boxersandswipers_colorbox['maxWidth'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>maxHeight</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_maxHeight" name="boxersandswipers_colorbox_maxHeight" value="<?php echo $boxersandswipers_colorbox['maxHeight'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>slideshow</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_colorbox_slideshow = $boxersandswipers_colorbox['slideshow']; ?>
					<select id="boxersandswipers_colorbox_slideshow" name="boxersandswipers_colorbox_slideshow">
						<option <?php if ('true' == $target_colorbox_slideshow) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_slideshow) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>slideshowSpeed</div>
					<div><?php _e('Default') ?>&nbsp(2500)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_slideshowSpeed" name="boxersandswipers_colorbox_slideshowSpeed" value="<?php echo $boxersandswipers_colorbox['slideshowSpeed'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>slideshowAuto</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
					<?php $target_colorbox_slideshowAuto = $boxersandswipers_colorbox['slideshowAuto']; ?>
					<select id="boxersandswipers_colorbox_slideshowAuto" name="boxersandswipers_colorbox_slideshowAuto">
						<option <?php if ('true' == $target_colorbox_slideshowAuto) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_slideshowAuto) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>slideshowStart</div>
					<div><?php _e('Default') ?>&nbsp(start slideshow)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_slideshowStart" name="boxersandswipers_colorbox_slideshowStart" value="<?php echo $boxersandswipers_colorbox['slideshowStart'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>slideshowStop</div>
					<div><?php _e('Default') ?>&nbsp(stop slideshow)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_slideshowStop" name="boxersandswipers_colorbox_slideshowStop" value="<?php echo $boxersandswipers_colorbox['slideshowStop'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>fixed</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
					<?php $target_colorbox_fixed = $boxersandswipers_colorbox['fixed']; ?>
					<select id="boxersandswipers_colorbox_fixed" name="boxersandswipers_colorbox_fixed">
						<option <?php if ('true' == $target_colorbox_fixed) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_fixed) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>top</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_top" name="boxersandswipers_colorbox_top" value="<?php echo $boxersandswipers_colorbox['top'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>bottom</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_bottom" name="boxersandswipers_colorbox_bottom" value="<?php echo $boxersandswipers_colorbox['bottom'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>left</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_left" name="boxersandswipers_colorbox_left" value="<?php echo $boxersandswipers_colorbox['left'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>right</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
						<input type="text" id="boxersandswipers_colorbox_right" name="boxersandswipers_colorbox_right" value="<?php echo $boxersandswipers_colorbox['right'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>reposition</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_colorbox_reposition = $boxersandswipers_colorbox['reposition']; ?>
					<select id="boxersandswipers_colorbox_reposition" name="boxersandswipers_colorbox_reposition">
						<option <?php if ('true' == $target_colorbox_reposition) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_reposition) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>retinaImage</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
					<?php $target_colorbox_retinaImage = $boxersandswipers_colorbox['retinaImage']; ?>
					<select id="boxersandswipers_colorbox_retinaImage" name="boxersandswipers_colorbox_retinaImage">
						<option <?php if ('true' == $target_colorbox_retinaImage) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_colorbox_retinaImage) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>

			</div>
			<div style="clear:both"></div>

			<input type="hidden" name="boxersandswipers_admin_tabs" value="2" />
			<?php submit_button( __('Save Changes'), 'large', 'Submit', TRUE ); ?>

			</form>

		</div>
	  </div>

	  <div id="boxersandswipers_admin_tabs-3">
		<div class="wrap">
			<h2>Slimbox <?php _e('Settings'); ?> (<a href="http://www.digitalia.be/software/slimbox2" target="_blank"><font color="red">Slimbox <?php _e('Website'); ?></font></a>)</h2>	

			<form method="post" action="<?php echo $scriptname.'#boxersandswipers_admin_tabs-3'; ?>">
			<?php wp_nonce_field('bxsw_settings3', 'boxersandswipers_tabs3'); ?>

			<p class="submit">
			<?php submit_button( __('Save Changes'), 'large', 'Submit', FALSE ); ?>
			<?php submit_button( __('Default'), 'large', 'Default', FALSE ); ?>
			</p>

			<div id="container-slimbox">

				<div class="item-boxersandswipers-settings">
					<div>loop</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
					<?php $target_slimbox_loop = $boxersandswipers_slimbox['loop']; ?>
					<select id="boxersandswipers_slimbox_loop" name="boxersandswipers_slimbox_loop">
						<option <?php if ('true' == $target_slimbox_loop) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_slimbox_loop) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>overlayOpacity</div>
					<div><?php _e('Default') ?>&nbsp(0.8)</div>
					<div>
						<input type="text" id="boxersandswipers_slimbox_overlayOpacity" name="boxersandswipers_slimbox_overlayOpacity" value="<?php echo $boxersandswipers_slimbox['overlayOpacity'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>overlayFadeDuration</div>
					<div><?php _e('Default') ?>&nbsp(400)</div>
					<div>
						<input type="text" id="boxersandswipers_slimbox_overlayFadeDuration" name="boxersandswipers_slimbox_overlayFadeDuration" value="<?php echo $boxersandswipers_slimbox['overlayFadeDuration'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>resizeDuration</div>
					<div><?php _e('Default') ?>&nbsp(400)</div>
					<div>
						<input type="text" id="boxersandswipers_slimbox_resizeDuration" name="boxersandswipers_slimbox_resizeDuration" value="<?php echo $boxersandswipers_slimbox['resizeDuration'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>resizeEasing</div>
					<div><?php _e('Default') ?>&nbsp(swing)</div>
					<div>
					<?php $target_slimbox_resizeEasing = $boxersandswipers_slimbox['resizeEasing']; ?>
					<select id="boxersandswipers_slimbox_resizeEasing" name="boxersandswipers_slimbox_resizeEasing">
						<option <?php if ('swing' == $target_slimbox_resizeEasing) echo 'selected="selected"'; ?>>swing</option>
						<option <?php if ('linear' == $target_slimbox_resizeEasing) echo 'selected="selected"'; ?>>linear</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>initialWidth</div>
					<div><?php _e('Default') ?>&nbsp(250)</div>
					<div>
						<input type="text" id="boxersandswipers_slimbox_initialWidth" name="boxersandswipers_slimbox_initialWidth" value="<?php echo $boxersandswipers_slimbox['initialWidth'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>initialHeight</div>
					<div><?php _e('Default') ?>&nbsp(250)</div>
					<div>
						<input type="text" id="boxersandswipers_slimbox_initialHeight" name="boxersandswipers_slimbox_initialHeight" value="<?php echo $boxersandswipers_slimbox['initialHeight'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>imageFadeDuration</div>
					<div><?php _e('Default') ?>&nbsp(400)</div>
					<div>
						<input type="text" id="boxersandswipers_slimbox_imageFadeDuration" name="boxersandswipers_slimbox_imageFadeDuration" value="<?php echo $boxersandswipers_slimbox['imageFadeDuration'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>captionAnimationDuration</div>
					<div><?php _e('Default') ?>&nbsp(400)</div>
					<div>
						<input type="text" id="boxersandswipers_slimbox_captionAnimationDuration" name="boxersandswipers_slimbox_captionAnimationDuration" value="<?php echo $boxersandswipers_slimbox['captionAnimationDuration'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>counterText</div>
					<div><?php _e('Default') ?>&nbsp(Image {x} of {y})</div>
					<div>
						<input type="text" id="boxersandswipers_slimbox_counterText" name="boxersandswipers_slimbox_counterText" value="<?php echo $boxersandswipers_slimbox['counterText'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>closeKeys</div>
					<div><?php _e('Default') ?>&nbsp([27, 88, 67])</div>
					<div>
						<input type="text" id="boxersandswipers_slimbox_closeKeys" name="boxersandswipers_slimbox_closeKeys" value="<?php echo $boxersandswipers_slimbox['closeKeys'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>previousKeys</div>
					<div><?php _e('Default') ?>&nbsp([37, 80])</div>
					<div>
						<input type="text" id="boxersandswipers_slimbox_previousKeys" name="boxersandswipers_slimbox_previousKeys" value="<?php echo $boxersandswipers_slimbox['previousKeys'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>nextKeys</div>
					<div><?php _e('Default') ?>&nbsp([39, 78])</div>
					<div>
						<input type="text" id="boxersandswipers_slimbox_nextKeys" name="boxersandswipers_slimbox_nextKeys" value="<?php echo $boxersandswipers_slimbox['nextKeys'] ?>" />
					</div>
				</div>

			</div>
			<div style="clear:both"></div>

			<input type="hidden" name="boxersandswipers_admin_tabs" value="3" />
			<?php submit_button( __('Save Changes'), 'large', 'Submit', TRUE ); ?>

			</form>

		</div>
	  </div>
	  <div id="boxersandswipers_admin_tabs-4">
		<div class="wrap">
			<h2>Nivo Lightbox <?php _e('Settings'); ?> (<a href="https://github.com/Codeinwp/Nivo-Lightbox-jQuery" target="_blank"><font color="red">Nivo Lightbox <?php _e('Website'); ?></font></a>)</h2>	

			<form method="post" action="<?php echo $scriptname.'#boxersandswipers_admin_tabs-4'; ?>">
			<?php wp_nonce_field('bxsw_settings4', 'boxersandswipers_tabs4'); ?>

			<p class="submit">
			<?php submit_button( __('Save Changes'), 'large', 'Submit', FALSE ); ?>
			<?php submit_button( __('Default'), 'large', 'Default', FALSE ); ?>
			</p>

			<div id="container-nivolightbox">

				<div class="item-boxersandswipers-settings">
					<div>effect</div>
					<div><?php _e('Default') ?>&nbsp(fade)</div>
					<div>
					<?php $target_nivolightbox_effect = $boxersandswipers_nivolightbox['effect']; ?>
					<select id="boxersandswipers_nivolightbox_effect" name="boxersandswipers_nivolightbox_effect">
						<option <?php if ('fade' == $target_nivolightbox_effect) echo 'selected="selected"'; ?>>fade</option>
						<option <?php if ('fadeScale' == $target_nivolightbox_effect) echo 'selected="selected"'; ?>>fadeScale</option>
						<option <?php if ('slideLeft' == $target_nivolightbox_effect) echo 'selected="selected"'; ?>>slideLeft</option>
						<option <?php if ('slideRight' == $target_nivolightbox_effect) echo 'selected="selected"'; ?>>slideRight</option>
						<option <?php if ('slideUp' == $target_nivolightbox_effect) echo 'selected="selected"'; ?>>slideUp</option>
						<option <?php if ('slideDown' == $target_nivolightbox_effect) echo 'selected="selected"'; ?>>slideDown</option>
						<option <?php if ('fall' == $target_nivolightbox_effect) echo 'selected="selected"'; ?>>fall</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>keyboardNav</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_nivolightbox_keyboardNav = $boxersandswipers_nivolightbox['keyboardNav']; ?>
					<select id="boxersandswipers_nivolightbox_keyboardNav" name="boxersandswipers_nivolightbox_keyboardNav">
						<option <?php if ('true' == $target_nivolightbox_keyboardNav) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_nivolightbox_keyboardNav) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>clickOverlayToClose</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_nivolightbox_clickOverlayToClose = $boxersandswipers_nivolightbox['clickOverlayToClose']; ?>
					<select id="boxersandswipers_nivolightbox_clickOverlayToClose" name="boxersandswipers_nivolightbox_clickOverlayToClose">
						<option <?php if ('true' == $target_nivolightbox_clickOverlayToClose) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_nivolightbox_clickOverlayToClose) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>

			</div>
			<div style="clear:both"></div>

			<input type="hidden" name="boxersandswipers_admin_tabs" value="4" />
			<?php submit_button( __('Save Changes'), 'large', 'Submit', TRUE ); ?>

			</form>

		</div>
	  </div>
	  <div id="boxersandswipers_admin_tabs-5">
		<div class="wrap">
			<h2>Image Lightbox <?php _e('Settings'); ?> (<a href="http://osvaldas.info/image-lightbox-responsive-touch-friendly" target="_blank"><font color="red">Image Lightbox <?php _e('Website'); ?></font></a>)</h2>	

			<form method="post" action="<?php echo $scriptname.'#boxersandswipers_admin_tabs-5'; ?>">
			<?php wp_nonce_field('bxsw_settings5', 'boxersandswipers_tabs5'); ?>

			<p class="submit">
			<?php submit_button( __('Save Changes'), 'large', 'Submit', FALSE ); ?>
			<?php submit_button( __('Default'), 'large', 'Default', FALSE ); ?>
			</p>

			<div id="container-imagelightbox">

				<div class="item-boxersandswipers-settings">
					<div>animationSpeed</div>
					<div><?php _e('Default') ?>&nbsp(250)</div>
					<div>
						<input type="text" id="boxersandswipers_imagelightbox_animationSpeed" name="boxersandswipers_imagelightbox_animationSpeed" value="<?php echo $boxersandswipers_imagelightbox['animationSpeed'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>preloadNext</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_imagelightbox_preloadNext = $boxersandswipers_imagelightbox['preloadNext']; ?>
					<select id="boxersandswipers_imagelightbox_preloadNext" name="boxersandswipers_imagelightbox_preloadNext">
						<option <?php if ('true' == $target_imagelightbox_preloadNext) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_imagelightbox_preloadNext) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>enableKeyboard</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_imagelightbox_enableKeyboard = $boxersandswipers_imagelightbox['enableKeyboard']; ?>
					<select id="boxersandswipers_imagelightbox_enableKeyboard" name="boxersandswipers_imagelightbox_enableKeyboard">
						<option <?php if ('true' == $target_imagelightbox_enableKeyboard) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_imagelightbox_enableKeyboard) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>quitOnEnd</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
					<?php $target_imagelightbox_quitOnEnd = $boxersandswipers_imagelightbox['quitOnEnd']; ?>
					<select id="boxersandswipers_imagelightbox_quitOnEnd" name="boxersandswipers_imagelightbox_quitOnEnd">
						<option <?php if ('true' == $target_imagelightbox_quitOnEnd) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_imagelightbox_quitOnEnd) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>quitOnImgClick</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
					<?php $target_imagelightbox_quitOnImgClick = $boxersandswipers_imagelightbox['quitOnImgClick']; ?>
					<select id="boxersandswipers_imagelightbox_quitOnImgClick" name="boxersandswipers_imagelightbox_quitOnImgClick">
						<option <?php if ('true' == $target_imagelightbox_quitOnImgClick) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_imagelightbox_quitOnImgClick) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>quitOnDocClick</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
					<?php $target_imagelightbox_quitOnDocClick = $boxersandswipers_imagelightbox['quitOnDocClick']; ?>
					<select id="boxersandswipers_imagelightbox_quitOnDocClick" name="boxersandswipers_imagelightbox_quitOnDocClick">
						<option <?php if ('true' == $target_imagelightbox_quitOnDocClick) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_imagelightbox_quitOnDocClick) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>

			</div>
			<div style="clear:both"></div>

			<input type="hidden" name="boxersandswipers_admin_tabs" value="5" />
			<?php submit_button( __('Save Changes'), 'large', 'Submit', TRUE ); ?>

			</form>

		</div>
	  </div>
	  <div id="boxersandswipers_admin_tabs-6">
		<div class="wrap">
			<h2>PhotoSwipe <?php _e('Settings'); ?> (<a href="http://photoswipe.com/" target="_blank"><font color="red">PhotoSwipe <?php _e('Website'); ?></font></a>)</h2>	

			<form method="post" action="<?php echo $scriptname.'#boxersandswipers_admin_tabs-6'; ?>">
			<?php wp_nonce_field('bxsw_settings6', 'boxersandswipers_tabs6'); ?>

			<p class="submit">
			<?php submit_button( __('Save Changes'), 'large', 'Submit', FALSE ); ?>
			<?php submit_button( __('Default'), 'large', 'Default', FALSE ); ?>
			</p>

			<div id="container-photoswipe">
				<div class="item-boxersandswipers-settings">
					<div>bgOpacity(0-1)</div>
					<div><?php _e('Default') ?>&nbsp(1)</div>
					<div>
						<input type="text" id="boxersandswipers_photoswipe_bgOpacity" name="boxersandswipers_photoswipe_bgOpacity" value="<?php echo $boxersandswipers_photoswipe['bgOpacity'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>captionEl(captionArea)</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<?php $target_photoswipe_captionArea = $boxersandswipers_photoswipe['captionArea']; ?>
					<div>
					<select id="boxersandswipers_photoswipe_captionArea" name="boxersandswipers_photoswipe_captionArea">
						<option <?php if ('true' == $target_photoswipe_captionArea) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_photoswipe_captionArea) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>shareEl(shareButton)</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_photoswipe_shareButton = $boxersandswipers_photoswipe['shareButton']; ?>
					<select id="boxersandswipers_photoswipe_shareButton" name="boxersandswipers_photoswipe_shareButton">
						<option <?php if ('true' == $target_photoswipe_shareButton) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_photoswipe_shareButton) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>fullscreenEl(fullScreenButton)</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_photoswipe_fullScreenButton = $boxersandswipers_photoswipe['fullScreenButton']; ?>
					<select id="boxersandswipers_photoswipe_fullScreenButton" name="boxersandswipers_photoswipe_fullScreenButton">
						<option <?php if ('true' == $target_photoswipe_fullScreenButton) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_photoswipe_fullScreenButton) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>zoomEl(zoomButton)</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_photoswipe_zoomButton = $boxersandswipers_photoswipe['zoomButton']; ?>
					<select id="boxersandswipers_photoswipe_zoomButton" name="boxersandswipers_photoswipe_zoomButton">
						<option <?php if ('true' == $target_photoswipe_zoomButton) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_photoswipe_zoomButton) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>preloaderEl(preloaderButton)</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_photoswipe_preloaderButton = $boxersandswipers_photoswipe['preloaderButton']; ?>
					<select id="boxersandswipers_photoswipe_preloaderButton" name="boxersandswipers_photoswipe_preloaderButton">
						<option <?php if ('true' == $target_photoswipe_preloaderButton) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_photoswipe_preloaderButton) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>tapToClose</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
					<?php $target_photoswipe_tapToClose = $boxersandswipers_photoswipe['tapToClose']; ?>
					<select id="boxersandswipers_photoswipe_tapToClose" name="boxersandswipers_photoswipe_tapToClose">
						<option <?php if ('true' == $target_photoswipe_tapToClose) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_photoswipe_tapToClose) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>tapToToggleControls</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_photoswipe_tapToToggleControls = $boxersandswipers_photoswipe['tapToToggleControls']; ?>
					<select id="boxersandswipers_photoswipe_tapToToggleControls" name="boxersandswipers_photoswipe_tapToToggleControls">
						<option <?php if ('true' == $target_photoswipe_tapToToggleControls) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_photoswipe_tapToToggleControls) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>animationDuration</div>
					<div><?php _e('Default') ?>&nbsp(333)</div>
					<div>
						<input type="text" id="boxersandswipers_photoswipe_animationDuration" name="boxersandswipers_photoswipe_animationDuration" value="<?php echo $boxersandswipers_photoswipe['animationDuration'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>maxSpreadZoom</div>
					<div><?php _e('Default') ?>&nbsp(2)</div>
					<div>
						<input type="text" id="boxersandswipers_photoswipe_maxSpreadZoom" name="boxersandswipers_photoswipe_maxSpreadZoom" value="<?php echo $boxersandswipers_photoswipe['maxSpreadZoom'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>history</div>
					<div><?php _e('Default') ?>&nbsp(true)</div>
					<div>
					<?php $target_photoswipe_history = $boxersandswipers_photoswipe['history']; ?>
					<select id="boxersandswipers_photoswipe_history" name="boxersandswipers_photoswipe_history">
						<option <?php if ('true' == $target_photoswipe_history) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_photoswipe_history) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>
			</div>
			<div style="clear:both"></div>

			<input type="hidden" name="boxersandswipers_admin_tabs" value="6" />
			<?php submit_button( __('Save Changes'), 'large', 'Submit', TRUE ); ?>

			</form>

		</div>
	  </div>
	  <div id="boxersandswipers_admin_tabs-7">
		<div class="wrap">
			<h2>Swipebox <?php _e('Settings'); ?> (<a href="http://brutaldesign.github.io/swipebox/" target="_blank"><font color="red">Swipebox <?php _e('Website'); ?></font></a>)</h2>	

			<form method="post" action="<?php echo $scriptname.'#boxersandswipers_admin_tabs-7'; ?>">
			<?php wp_nonce_field('bxsw_settings7', 'boxersandswipers_tabs7'); ?>

			<p class="submit">
			<?php submit_button( __('Save Changes'), 'large', 'Submit', FALSE ); ?>
			<?php submit_button( __('Default'), 'large', 'Default', FALSE ); ?>
			</p>

			<div id="container-swipebox">

				<div class="item-boxersandswipers-settings">
					<div>hideBarsDelay</div>
					<div><?php _e('Default') ?>&nbsp(3000)</div>
					<div>
						<input type="text" id="boxersandswipers_swipebox_hideBarsDelay" name="boxersandswipers_swipebox_hideBarsDelay" value="<?php echo $boxersandswipers_swipebox['hideBarsDelay'] ?>" />
					</div>
				</div>
				<div class="item-boxersandswipers-settings">
					<div>loopAtEnd</div>
					<div><?php _e('Default') ?>&nbsp(false)</div>
					<div>
					<?php $target_swipebox_loopAtEnd = $boxersandswipers_swipebox['loopAtEnd']; ?>
					<select id="boxersandswipers_swipebox_loopAtEnd" name="boxersandswipers_swipebox_loopAtEnd">
						<option <?php if ('true' == $target_swipebox_loopAtEnd) echo 'selected="selected"'; ?>>true</option>
						<option value="" <?php if (!$target_swipebox_loopAtEnd) echo 'selected="selected"'; ?>>false</option>
					</select>
					</div>
				</div>

			</div>
			<div style="clear:both"></div>

			<input type="hidden" name="boxersandswipers_admin_tabs" value="7" />
			<?php submit_button( __('Save Changes'), 'large', 'Submit', TRUE ); ?>

			</form>

		</div>
	  </div>

	  <div id="boxersandswipers_admin_tabs-8">
		<div class="wrap">
		<h2><?php _e('Exclude', 'boxers-and-swipers'); ?></h2>

				<div style="margin:20px 0; padding:10px; border:#CCC 2px solid">
					<?php _e('You can disable this plugin on each page or post as you like, by editing the page or post and then selecting the option on that page meta box of Boxers And Swipers. And Quick Edit too. If you can not see the options anywhere make sure the Screen Options check box is on. Click Screen Options &gt; Show on screen &gt; Boxers And Swipers at the top right of your screen.', 'boxers-and-swipers'); ?>

					<div style="clear:both"></div>
				</div>

		</div>
	  </div>

		<div id="boxersandswipers_admin_tabs-9">
			<div class="wrap">
				<?php
				$plugin_datas = get_file_data( BOXERSANDSWIPERS_PLUGIN_BASE_DIR.'/boxersandswipers.php', array('version' => 'Version') );
				$plugin_version = __('Version:').' '.$plugin_datas['version'];
				?>
				<h4 style="margin: 5px; padding: 5px;">
				<?php echo $plugin_version; ?> |
				<a style="text-decoration: none;" href="https://wordpress.org/support/plugin/boxers-and-swipers" target="_blank"><?php _e('Support Forums') ?></a> |
				<a style="text-decoration: none;" href="https://wordpress.org/support/view/plugin-reviews/boxers-and-swipers" target="_blank"><?php _e('Reviews', 'boxers-and-swipers') ?></a>
				</h4>
				<div style="width: 250px; height: 180px; margin: 5px; padding: 5px; border: #CCC 2px solid;">
				<h3><?php _e('Please make a donation if you like my work or would like to further the development of this plugin.', 'boxers-and-swipers'); ?></h3>
				<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
		<a style="margin: 5px; padding: 5px;" href='https://pledgie.com/campaigns/28307' target="_blank"><img alt='Click here to lend your support to: Various Plugins for WordPress and make a donation at pledgie.com !' src='https://pledgie.com/campaigns/28307.png?skin_name=chrome' border='0' ></a>
				</div>
			</div>
		</div>

	<!--
	  <div id="boxersandswipers_admin_tabs-10">
		<div class="wrap">
		<h2>FAQ</h2>

		</div>
	  </div>
	-->

	</div>

		</div>
		<?php
	}

	/* ==================================================
	 * Update wp_options table.
	 * @param	string	$tabs
	 * @since	1.0
	 */
	function options_updated($tabs){

		switch ($tabs) {
			case 1:
				$applytypes = $this->apply_type();
				$apply_tbl = array();
				if ( !empty($_POST['Default']) ) {
					foreach ( $applytypes as $key => $value ) {
						$apply_tbl[$key]['pc'] = NULL;
						$apply_tbl[$key]['tb'] = NULL;
						$apply_tbl[$key]['sp'] = NULL;
					}
					unset($applytypes[$key]);
					$effect_tbl = array(
									'pc' => 'colorbox',
									'tb' => 'nivolightbox',
									'sp' => 'photoswipe'
									);
					$useragent_tbl = array(
										'tb' => 'iPad|^.*Android.*Nexus(((?:(?!Mobile))|(?:(\s(7|10).+))).)*$|Kindle|Silk.*Accelerated|Sony.*Tablet|Xperia Tablet|Sony Tablet S|SAMSUNG.*Tablet|Galaxy.*Tab|SC-01C|SC-01D|SC-01E|SC-02D',
										'sp' => 'iPhone|iPod|Android.*Mobile|BlackBerry|IEMobile'
									);
				} else {
					foreach ( $applytypes as $key => $value ) {
						if ( !empty($_POST['boxersandswipers_apply_pc'][$key]) ) {
							$apply_tbl[$key]['pc'] = $_POST['boxersandswipers_apply_pc'][$key];
						} else {
							$apply_tbl[$key]['pc'] = NULL;
						}
						if ( !empty($_POST['boxersandswipers_apply_tb'][$key]) ) {
							$apply_tbl[$key]['tb'] = $_POST['boxersandswipers_apply_tb'][$key];
						} else {
							$apply_tbl[$key]['tb'] = NULL;
						}
						if ( !empty($_POST['boxersandswipers_apply_sp'][$key]) ) {
							$apply_tbl[$key]['sp'] = $_POST['boxersandswipers_apply_sp'][$key];
						} else {
							$apply_tbl[$key]['sp'] = NULL;
						}
					}
					unset($applytypes[$key]);
					$effect_tbl = array(
									'pc' => $_POST['boxersandswipers_effect_pc'],
									'tb' => $_POST['boxersandswipers_effect_tb'],
									'sp' => $_POST['boxersandswipers_effect_sp'],
									);
					$useragent_tbl = array(
									'tb' => stripslashes($_POST['boxersandswipers_useragent_tb']),
									'sp' => stripslashes($_POST['boxersandswipers_useragent_sp'])
									);
				}
				update_option( 'boxersandswipers_apply', $apply_tbl );
				update_option( 'boxersandswipers_effect', $effect_tbl );
				update_option( 'boxersandswipers_useragent', $useragent_tbl );
				echo '<div class="notice notice-success is-dismissible"><ul><li>'.__('Device Settings', 'boxers-and-swipers').' --> '.__('Settings saved.').'</li></ul></div>';
				break;
			case 2:
				if ( !empty($_POST['Default']) ) {
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
				} else {
					$colorbox_tbl = array(
									'rel' => 'boxersandswipers',
									'transition' => $_POST['boxersandswipers_colorbox_transition'],
									'speed' => intval($_POST['boxersandswipers_colorbox_speed']),
									'title' => $_POST['boxersandswipers_colorbox_title'],
									'scalePhotos' => $_POST['boxersandswipers_colorbox_scalePhotos'],
									'scrolling' => $_POST['boxersandswipers_colorbox_scrolling'],
									'opacity' => floatval($_POST['boxersandswipers_colorbox_opacity']),
									'open' => $_POST['boxersandswipers_colorbox_open'],
									'returnFocus' => $_POST['boxersandswipers_colorbox_returnFocus'],
									'trapFocus' => $_POST['boxersandswipers_colorbox_trapFocus'],
									'fastIframe' => $_POST['boxersandswipers_colorbox_fastIframe'],
									'preloading' => $_POST['boxersandswipers_colorbox_preloading'],
									'overlayClose' => $_POST['boxersandswipers_colorbox_overlayClose'],
									'escKey' => $_POST['boxersandswipers_colorbox_escKey'],
									'arrowKey' => $_POST['boxersandswipers_colorbox_arrowKey'],
									'loop' => $_POST['boxersandswipers_colorbox_loop'],
									'fadeOut' => intval($_POST['boxersandswipers_colorbox_fadeOut']),
									'closeButton' => $_POST['boxersandswipers_colorbox_closeButton'],
									'current' => $_POST['boxersandswipers_colorbox_current'],
									'previous' => $_POST['boxersandswipers_colorbox_previous'],
									'next' => $_POST['boxersandswipers_colorbox_next'],
									'close' => $_POST['boxersandswipers_colorbox_close'],
									'width' => $_POST['boxersandswipers_colorbox_width'],
									'height' => $_POST['boxersandswipers_colorbox_height'],
									'innerWidth' => $_POST['boxersandswipers_colorbox_innerWidth'],
									'innerHeight' => $_POST['boxersandswipers_colorbox_innerHeight'],
									'initialWidth' => intval($_POST['boxersandswipers_colorbox_initialWidth']),
									'initialHeight' => intval($_POST['boxersandswipers_colorbox_initialHeight']),
									'maxWidth' => $_POST['boxersandswipers_colorbox_maxWidth'],
									'maxHeight' => $_POST['boxersandswipers_colorbox_maxHeight'],
									'slideshow' => $_POST['boxersandswipers_colorbox_slideshow'],
									'slideshowSpeed' => intval($_POST['boxersandswipers_colorbox_slideshowSpeed']),
									'slideshowAuto' => $_POST['boxersandswipers_colorbox_slideshowAuto'],
									'slideshowStart' => $_POST['boxersandswipers_colorbox_slideshowStart'],
									'slideshowStop' => $_POST['boxersandswipers_colorbox_slideshowStop'],
									'fixed' => $_POST['boxersandswipers_colorbox_fixed'],
									'top' => $_POST['boxersandswipers_colorbox_top'],
									'bottom' => $_POST['boxersandswipers_colorbox_bottom'],
									'left' => $_POST['boxersandswipers_colorbox_left'],
									'right' => $_POST['boxersandswipers_colorbox_right'],
									'reposition' => $_POST['boxersandswipers_colorbox_reposition'],
									'retinaImage' => $_POST['boxersandswipers_colorbox_retinaImage']
									);
				}
				update_option( 'boxersandswipers_colorbox', $colorbox_tbl );
				echo '<div class="notice notice-success is-dismissible"><ul><li>'.'Colorbox'.' --> '.__('Settings saved.').'</li></ul></div>';
				break;
			case 3:
				if ( !empty($_POST['Default']) ) {
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
				} else {
					$slimbox_tbl = array(
									'loop' => $_POST['boxersandswipers_slimbox_loop'],
									'overlayOpacity' => floatval($_POST['boxersandswipers_slimbox_overlayOpacity']),
									'overlayFadeDuration' => intval($_POST['boxersandswipers_slimbox_overlayFadeDuration']),
									'resizeDuration' => intval($_POST['boxersandswipers_slimbox_resizeDuration']),
									'resizeEasing' => $_POST['boxersandswipers_slimbox_resizeEasing'],
									'initialWidth' => intval($_POST['boxersandswipers_slimbox_initialWidth']),
									'initialHeight' => intval($_POST['boxersandswipers_slimbox_initialHeight']),
									'imageFadeDuration' => intval($_POST['boxersandswipers_slimbox_imageFadeDuration']),
									'captionAnimationDuration' => intval($_POST['boxersandswipers_slimbox_captionAnimationDuration']),
									'counterText' => $_POST['boxersandswipers_slimbox_counterText'],
									'closeKeys' => $_POST['boxersandswipers_slimbox_closeKeys'],
									'previousKeys' => $_POST['boxersandswipers_slimbox_previousKeys'],
									'nextKeys' => $_POST['boxersandswipers_slimbox_nextKeys']
									);
				}
				update_option( 'boxersandswipers_slimbox', $slimbox_tbl );
				echo '<div class="notice notice-success is-dismissible"><ul><li>'.'Slimbox'.' --> '.__('Settings saved.').'</li></ul></div>';
				break;
			case 4:
				if ( !empty($_POST['Default']) ) {
					$nivolightbox_tbl = array(
										'effect' => 'fade',
										'keyboardNav' => 'true',
										'clickOverlayToClose' => 'true'
									);
				} else {
					$nivolightbox_tbl = array(
									'effect' => $_POST['boxersandswipers_nivolightbox_effect'],
									'keyboardNav' => $_POST['boxersandswipers_nivolightbox_keyboardNav'],
									'clickOverlayToClose' => $_POST['boxersandswipers_nivolightbox_clickOverlayToClose']
									);
				}
				update_option( 'boxersandswipers_nivolightbox', $nivolightbox_tbl );
				echo '<div class="notice notice-success is-dismissible"><ul><li>'.'Nivo Lightbox'.' --> '.__('Settings saved.').'</li></ul></div>';
				break;
			case 5:
				if ( !empty($_POST['Default']) ) {
					$imagelightbox_tbl = array(
										'animationSpeed' => 250,
										'preloadNext' => 'true',
										'enableKeyboard' => 'true',
										'quitOnEnd' => NULL,
										'quitOnImgClick' => NULL,
										'quitOnDocClick' => NULL
									);
				} else {
					$imagelightbox_tbl = array(
										'animationSpeed' => intval($_POST['boxersandswipers_imagelightbox_animationSpeed']),
										'preloadNext' => $_POST['boxersandswipers_imagelightbox_preloadNext'],
										'enableKeyboard' => $_POST['boxersandswipers_imagelightbox_enableKeyboard'],
										'quitOnEnd' => $_POST['boxersandswipers_imagelightbox_quitOnEnd'],
										'quitOnImgClick' => $_POST['boxersandswipers_imagelightbox_quitOnImgClick'],
										'quitOnDocClick' => $_POST['boxersandswipers_imagelightbox_quitOnDocClick']
									);
				}
				update_option( 'boxersandswipers_imagelightbox', $imagelightbox_tbl );
				echo '<div class="notice notice-success is-dismissible"><ul><li>'.'Image Lightbox'.' --> '.__('Settings saved.').'</li></ul></div>';
				break;
			case 6:
				if ( !empty($_POST['Default']) ) {
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
				} else {
					$photoswipe_tbl = array(
						                'bgOpacity' => floatval($_POST['boxersandswipers_photoswipe_bgOpacity']),
						                'captionArea' => $_POST['boxersandswipers_photoswipe_captionArea'],
						                'shareButton' => $_POST['boxersandswipers_photoswipe_shareButton'],
						                'fullScreenButton' => $_POST['boxersandswipers_photoswipe_fullScreenButton'],
						                'zoomButton' => $_POST['boxersandswipers_photoswipe_zoomButton'],
						                'preloaderButton' => $_POST['boxersandswipers_photoswipe_preloaderButton'],
						                'tapToClose' => $_POST['boxersandswipers_photoswipe_tapToClose'],
						                'tapToToggleControls' => $_POST['boxersandswipers_photoswipe_tapToToggleControls'],
						                'animationDuration' => intval($_POST['boxersandswipers_photoswipe_animationDuration']),
						                'maxSpreadZoom' => intval($_POST['boxersandswipers_photoswipe_maxSpreadZoom']),
						                'history' => $_POST['boxersandswipers_photoswipe_history']
									);
				}
				update_option( 'boxersandswipers_photoswipe', $photoswipe_tbl );
				echo '<div class="notice notice-success is-dismissible"><ul><li>'.'PhotoSwipe'.' --> '.__('Settings saved.').'</li></ul></div>';
				break;
			case 7:
				if ( !empty($_POST['Default']) ) {
					$swipebox_tbl = array(
									'hideBarsDelay' => 3000,
									'loopAtEnd' => NULL
									);
				} else {
					$swipebox_tbl = array(
									'hideBarsDelay' => intval($_POST['boxersandswipers_swipebox_hideBarsDelay']),
									'loopAtEnd' => $_POST['boxersandswipers_swipebox_loopAtEnd']
									);
				}
				update_option( 'boxersandswipers_swipebox', $swipebox_tbl );
				echo '<div class="notice notice-success is-dismissible"><ul><li>'.'Swipebox'.' --> '.__('Settings saved.').'</li></ul></div>';
				break;
		}

	}

	/* ==================================================
	 * Apply type.
	 * @since	3.00
	 */
	function apply_type(){

		$plugin_slug = 'yith-infinite-scrolling';
		$plugin_name = 'YITH Infinite Scrolling';
		$install_url = 'plugin-install.php?tab=plugin-information&plugin='.$plugin_slug.'&TB_iframe=true&width=600&height=550';

		if ( is_multisite() ) {
			$infinitescroll_install_url = esc_url(network_admin_url('plugin-install.php?tab=plugin-information&plugin=yith-infinite-scrolling'));
		} else {
			$infinitescroll_install_url = esc_url(admin_url('plugin-install.php?tab=plugin-information&plugin=yith-infinite-scrolling'));
		}
		$infinitescroll_install_html = '<a href="'.$infinitescroll_install_url.'" style="text-decoration: none; word-break: break-all;" title="'.__('Works with Infinite Scroll.', 'boxers-and-swipers').'">YITH Infinite Scrolling</a>';

		$applytypes['postthumbnails'] = __('Featured Images');
		$applytypes['infinitescroll'] = $infinitescroll_install_html;

		return $applytypes;

	}

	/* ==================================================
	 * Add custom box.
	 * @since	2.0
	 */
	function add_exclude_boxersandswipers_custom_box() {
	    add_meta_box('boxersandswipers_exclude', 'Boxers And Swipers', array(&$this,'exclude_boxersandswipers_custom_box'), 'page', 'side', 'high');
	    add_meta_box('boxersandswipers_exclude', 'Boxers And Swipers', array(&$this,'exclude_boxersandswipers_custom_box'), 'post', 'side', 'high');

		$args = array(
		   'public'   => true,
		   '_builtin' => false
		);
		$custom_post_types = get_post_types( $args, 'objects', 'and' ); 
		foreach ( $custom_post_types as $post_type ) {
		    add_meta_box('boxersandswipers_exclude', 'Boxers And Swipers', array(&$this,'exclude_boxersandswipers_custom_box'), $post_type->name, 'side', 'high');
		}

	}
	 
	/* ==================================================
	 * Custom box.
	 * @since	2.0
	 */
	function exclude_boxersandswipers_custom_box() {

		if ( isset($_GET['post']) ) {
			$get_post = $_GET['post'];
		} else {
			$get_post = NULL;
		}

		static $printNonce = TRUE;
		if ( $printNonce ) {
			$printNonce = FALSE;
			wp_nonce_field( plugin_basename( __FILE__ ), 'boxersandswipers_edit_nonce' );
		}

		$boxersandswipers_exclude = get_post_meta( $get_post, 'boxersandswipers_exclude' );

		?>
		<table>
		<tbody>
			<tr>
				<td>
					<div>
						<?php
						if (!empty($boxersandswipers_exclude)) {
							?>
							<input type="checkbox" name="boxersandswipers_exclude" value="1">
							<?php
						} else {
							?>
							<input type="checkbox" name="boxersandswipers_exclude" value="1" checked>
							<?php
						}
						_e('Apply', 'boxers-and-swipers');
						?>
					</div>
				</td>
			</tr>
		</tbody>
		</table>
		<?php

	}

	/* ==================================================
	 * Update wp_postmeta table.
	 * @since	2.0
	 */
	function save_exclude_boxersandswipers_postdata( $post_id ) {

		$slug = 'boxersandswipers';

		if ( !current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$_POST += array("{$slug}_edit_nonce" => '');
		if ( !wp_verify_nonce( $_POST["{$slug}_edit_nonce"], plugin_basename( __FILE__ ) ) ) {
			return;
		}

		if ( isset($_REQUEST['boxersandswipers_exclude']) ) {
			delete_post_meta( $post_id, 'boxersandswipers_exclude' );
		} else {
			add_post_meta( $post_id, 'boxersandswipers_exclude', 1, true );
		}

	}

	/* ==================================================
	 * Posts columns menu
	 * @since	2.0
	 */
	function posts_columns_boxersandswipers($columns){
	    $columns['column_boxersandswipers_exclude'] = __('Boxers And Swipers');
	    return $columns;
	}

	/* ==================================================
	 * Posts columns
	 * @since	2.0
	 */
	function posts_custom_columns_boxersandswipers($column_name, $post_id){
		if($column_name === 'column_boxersandswipers_exclude'){
			$boxersandswipers_exclude = get_post_meta( $post_id, 'boxersandswipers_exclude' );
			if (!empty($boxersandswipers_exclude)) {
				if ($boxersandswipers_exclude[0]){
					?>
					<div><span class="column-boxersandswipers_exclude" style="display: none;">1</span>
					<?php _e('Exclude', 'boxers-and-swipers');?></div>
					<?php
				} else {
					_e('Apply');
				}
			} else {
				_e('Apply');
			}
	    }
	}

	/* ==================================================
	 * Pages columns menu
	 * @since	2.0
	 */
	function pages_columns_boxersandswipers($columns){
	    $columns['column_boxersandswipers_exclude'] = __('Boxers And Swipers');
	    return $columns;
	}

	/* ==================================================
	 * Pages columns
	 * @since	2.0
	 */
	function pages_custom_columns_boxersandswipers($column_name, $post_id){
		if($column_name === 'column_boxersandswipers_exclude'){
			$boxersandswipers_exclude = get_post_meta( $post_id, 'boxersandswipers_exclude' );
			if (!empty($boxersandswipers_exclude)) {
				if ($boxersandswipers_exclude[0]){
					?>
					<div><span class="column-boxersandswipers_exclude" style="display: none;">1</span>
					<?php _e('Exclude', 'boxers-and-swipers');?></div>
					<?php
				} else {
					_e('Apply');
				}
			} else {
				_e('Apply');
			}
	    }
	}

	/* ==================================================
	 * Quick Edit Form
	 * @since	2.38
	 */
	function display_custom_quickedit_boxersandswipers( $column_name, $post_type ) {

		static $printNonce = TRUE;
		if ( $printNonce ) {
			$printNonce = FALSE;
			wp_nonce_field( plugin_basename( __FILE__ ), 'boxersandswipers_edit_nonce' );
		}

		?>
		<fieldset class="inline-edit-col-right inline-edit-<?php echo $column_name; ?>">
			<div class="inline-edit-col <?php echo $column_name; ?>">
				<label class="inline-edit-group">
				<?php 
				if ( $column_name === 'column_boxersandswipers_exclude' ) {
					?>
					<span class="title">Boxers and Swipers</span>
					<div>
					<span style="margin-right: 1em;"></span>
					<input type="checkbox" name="boxersandswipers_exclude" value="1" />
					<span class="checkbox-title"><?php _e('Apply'); ?></span>
					</div>
					<?php
				}
				?>
				</label>
			</div>
		</fieldset>
		<?php

	}

	/* ==================================================
	 * Load Quick Edit Script
	 * @since	2.38
	 */
	function wp_boxersandswipers_admin_enqueue_scripts( $hook ) {
		$boxersandswipers_plugin_url = plugins_url($path='boxers-and-swipers',$scheme=null);
		wp_enqueue_script( 'boxersandswipers_custom_script', $boxersandswipers_plugin_url.'/js/admin_edit.js', array('jquery','inline-edit-post') );
	}

}

?>