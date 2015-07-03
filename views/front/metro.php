<div class="wp-author-info <?php echo $theme, ' ',$color, ' text-',$dir, ' social-',$social_style;?>">
	<div class="avatar"><?php echo $avatar;?></div>
	<div class="con">
		<div class="author-title"><?php echo $display_name;?>
			<div class="author-icons"><?php
			foreach ($userFields as $id=>$val) {
				if ($val != '') {
					echo '<a href="'.$val.'" class="icn-'.$id.'"  target="_blank"></a>';
				}
			}
			?></div>
		</div>
		<div class="author-desc"><?php echo $description;?></div>
	</div>
	<div class="gclear"></div>
</div>