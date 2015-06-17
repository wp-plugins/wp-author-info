<?php
global $ginit;
$errMsg = array();
if (isset($_POST['contact'])) {
	if (!empty($_POST['your-name']) && !empty($_POST['your-email'])) {
		$name = sanitize_text_field($_POST['your-name']);
		$email = sanitize_email($_POST['your-email']);
		$subject = sanitize_text_field($_POST['subject']);
		$message = sanitize_text_field($_POST['message']);
		$message = array();
		$message[] = 'Date : '.date('Y-m-d H:i:s');
		$message[] = 'Name : '.$name;
		$message[] = 'Email : '.$email;
		$message[] = 'Subject : '.$subject;
		$message[] = 'Message : ';
		$message[] = $message;
		$message = implode('\n', $message);
		wp_mail( 'contact@ghuwad.com', 'WpAuthorInfo - Contact', $message );
		if ($ginit) {
			wp_redirect(admin_url( 'admin.php?page=wp_author_info&email_sent=1'));
			exit;
		}
	} else {
		$errMsg['contact'] = 'Please enter require field';
	}
}
$WpAuthorInfo = $GLOBALS[ 'WpAuthorInfo' ];
$lang = $WpAuthorInfo->getVar('lang');
$post_types = get_post_types(array(
   'public'   => true,
   '_builtin' => false
));
$post_types = array_merge(array('page'=>'page', 'post'=>'post'),$post_types);
$settings = WpAuthorInfo::getVar('settings');
?>
<div class="wrap">
	<h2></h2>
	<div class="ghuwad-panel">
		<h2 class="gtitle"><?php _e( 'WP Author Info', $lang ); ?> <span class="version">v1.0.0</span></h2>
		<div class="gnav">
			<a href="javascript:void(0)" class="active" data-tab="settings"><?php _e( 'Settings', $lang ); ?></a>
			<a href="javascript:void(0)" data-tab="help"><?php _e( 'Help', $lang ); ?></a>
			<a href="javascript:void(0)" data-tab="contact"><?php _e( 'Report Bug or Request New Feature', $lang ); ?></a>
			<a href="javascript:void(0)" data-tab="about-us"><?php _e( 'About Us', $lang ); ?></a>
		</div>
		<div class="gbody">
			<div id="gtab-settings" class="gtab-content open">
				<form method="post" id="frmOptions" action="options.php">
				<?php settings_fields('wp-author-info-settings');
					do_settings_sections('wp-author-info-settings'); ?>
					<div class="subheadd">
						<?php
							if(isset($_GET['settings-updated']) && $_GET['settings-updated']=='true'){
								echo '<div class="g-success-msg">'.__('Your settings successfully updated', $lang ).'</div>';
							}
						?>
					</div>
					<div class="nav-vertical">
						<div class="gnav">
							<a href="javascript:void(0)" class="active" data-tab="display"><?php _e('Visiblity', $lang ); ?></a>
							<a href="javascript:void(0)" data-tab="theme"><?php _e('Theme', $lang ); ?></a>
						</div>
						<div class="gtab-content-con">
							<div id="gtab-display" class="gtab-content open">
								<h3><?php _e('Visiblity', $lang ); ?></h3>
								<div class="gnote"><?php _e('Set where you want to show WP Author Info', $lang ); ?></div>
								<?php
								$c = 'first';
								foreach ($post_types as $key=>$post_type) {
								?>
								<div class="grow<?php echo ' ',$c; $c='';?>">
									<div class="glbl"><?php _e('Show in', $lang ); ?> <?php echo $post_type;?></div>
									<div class="gcon">
										<select name="wp-author-info-settings[post_types][<?php echo $key;?>]">
											<option value="no" <?php echo isset($settings['post_types'][$key]) && $settings['post_types'][$key] == 'no'?'selected="selected"':'';?>><?php _e('No', $lang ); ?></option>
											<option value="above" <?php echo isset($settings['post_types'][$key]) && $settings['post_types'][$key] == 'above'?'selected="selected"':'';?>><?php _e('Above', $lang ); ?></option>
											<option value="below" <?php echo isset($settings['post_types'][$key]) && $settings['post_types'][$key] == 'below'?'selected="selected"':'';?>><?php _e('Below', $lang ); ?></option>
										</select>
									</div><div class="gclear"></div>
								</div>
								<?php } ?>
								<div class="grow last">
									<div class="glbl">&nbsp;</div>
									<div class="gcon">
										<button type="submit" class="button-primary orange"><?php _e('Update', $lang ); ?></button>
									</div>
								</div>
							</div>
							<div id="gtab-theme" class="gtab-content">
								<h3><?php _e('Theme Settings', $lang ); ?></h3>
								<div class="gnote"><?php _e('Choose theme for Author Info section', $lang ); ?></div>
								<div class="grow first">
									<div class="glbl"><?php _e('Select any theme', $lang ); ?></div>
									<div class="gcon">
										<select name="wp-author-info-settings[theme]">
											<option value="default" <?php echo isset($settings['theme']) && $settings['theme'] == 'default' ? 'selected="selected"':'';?>><?php _e('Default', $lang ); ?></option>
											<option value="box" <?php echo isset($settings['theme']) && $settings['theme'] == 'box' ? 'selected="selected"':'';?>><?php _e('Box', $lang ); ?></option>
										</select>
									</div><div class="gclear"></div>
								</div>
								<div class="grow last">
									<div class="glbl">&nbsp;</div>
									<div class="gcon">
										<button type="submit" class="button-primary orange"><?php _e('Update', $lang ); ?></button>
									</div>
								</div>
							</div>
						</div>
						<div class="gclear"></div>
					</div>
					<div class="gclear"></div>
				</form>
			</div>
			<div id="gtab-help" class="gtab-content info">
				<div class="p">
					<strong>Take look for all features.</strong>
					<ul class="t">
						<li><span>You can prefix or sufix author detail on any post type (including page &amp; post).</span></li>
						<li><span>You can set theme for author info.</span></li>
						<li><span>You can also add author detail at widget or content using shortcode.</span></li>
					</ul>
				</div>
				<div class="p">
					<strong>Read about Shortcode:</strong>
					<code>[wp-author-info theme="default" author_id="1"]</code> has two arguments.
					<ul class="t">
						<li><span><strong>theme (optional)</strong> : set given theme for content otherwise take theme from settings</span></li>
						<li><span><strong>author_id (optional)</strong> : specified which author info is display wiht shortcode, if it blank or not pass then it display current user within wordpress loop.</span></li>
					</ul>
				</div>
				<div class="p">
					<strong>Read about Setting fields.</strong>
					<ul class="t">
						<li style="margin-bottom: 10px;"><strong>Visiblity:</strong> <span>Configure visibility of author detail on single page. There is three option:<br> <strong>No: </strong> Hide author detail on single page<br/><strong>Above: </strong>Show autho detail before content on single page<br/><strong>Below: </strong> Show autho detail after content on single page.</span></li>
						<li><strong>Theme:</strong> <span>Set look of author detail.There is two option:<br> <strong>Default: </strong> Only show info without any style &amp; color<br/><strong>Box: </strong> Show author detail into box.</span></li>
					</ul>
				</div>
			</div>
			<div id="gtab-contact" class="gtab-content gcontact-us info">
				<div class="p">
					<?php _e('You can contact us as below');?>
				</div>
				<?php 
				if(!empty($_GET['email_sent'])) {
					echo '<div class="g-success-msg">'.__('Thanks for contact us, We\'ll reply as soon as possible.', $lang ).'</div>';
				}else if(!empty($errMsg['contact'])) {
					echo '<div class="g-error-msg">',$errMsg['contact'],'</div>';
				}?>
				<form method="post" id="frmContact">
					<input type="hidden" name="g_request" value="contact-us"/>
					<div class="grow">
						<div class="glbl g-a-r"><?php _e('Your Name', $lang ); ?> <?php _e('(required)', $lang ); ?></div>
						<div class="gcon">
							<input type="text" name="your-name" class="regular-text"/>
						</div><div class="gclear"></div>
					</div>
					<div class="grow">
						<div class="glbl g-a-r"><?php _e('Your Email', $lang ); ?> <?php _e('(required)', $lang ); ?></div>
						<div class="gcon">
							<input type="text" name="your-email" class="regular-text"/>
						</div><div class="gclear"></div>
					</div>
					<div class="grow">
						<div class="glbl g-a-r"><?php _e('Subject', $lang ); ?></div>
						<div class="gcon">
							<input type="text" name="subject" class="regular-text"/>
						</div><div class="gclear"></div>
					</div>
					<div class="grow">
						<div class="glbl g-a-r"><?php _e('Purpose', $lang ); ?></div>
						<div class="gcon">
							<select name="purpose">
								<option><?php _e('Request new feature', $lang ); ?></option>
								<option><?php _e('Report Bug', $lang ); ?></option>
								<option><?php _e('Other', $lang ); ?></option>
							</select>
						</div><div class="gclear"></div>
					</div>
					<div class="grow">
						<div class="glbl g-a-r"><?php _e('Message', $lang ); ?></div>
						<div class="gcon">
							<textarea name="message" rows="3" cols="30"></textarea>
						</div><div class="gclear"></div>
					</div>
					<div class="grow last">
						<div class="glbl">&nbsp;</div>
						<div class="gcon">
							<button type="submit" name="contact" class="button-primary orange"><?php _e('Submit', $lang ); ?></button>
						</div>
					</div>
				</form>
			</div>
			<div id="gtab-about-us" class="gtab-content gabout-us info">
				<a href="http://ghuwad.com"><img src="<?php echo WpAuthorInfo::getVar('images','url');?>/ghuwad-logo.png" alt="Ghuwad.com"/></a><br/>
				<?php _e('Email', $lang ); ?> : <a href="mailto:contact@ghuwad.com">contact@ghuwad.com</a>
			</div>
			<div class="subheadd">
				<?php _e('Develop by', $lang ); ?> <a href="http://ghuwad.com" target="_blank">Ghuwad.com</a>
			</div>
		</div>
	</div>
</div>