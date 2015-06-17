<div class="wp-author-info <?php echo $theme;?>">
	<div class="avatar"><?php echo get_avatar($authorId);?></div>
	<div class="con">
		<div class="author-title"><?php echo ucfirst(get_the_author_meta('display_name', $authorId));?></div>
		<div class="author-desc"><?php echo get_the_author_meta('description', $authorId);?></div>
	</div>
	<div class="gclear"></div>
</div>