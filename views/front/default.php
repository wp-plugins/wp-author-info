<div class="wp-author-info <?php echo $theme;?>">
	<span class="avatar"><?php echo get_avatar($authorId);?></span>
	<span class="author-title"><?php echo ucfirst(get_the_author_meta('display_name', $authorId));?></span>
	<div class="author-desc"><?php echo get_the_author_meta('description', $authorId);?></div>
</div>