<?php use Roots\Sage\Titles; ?>

<div class="page-header">
<div class="imgWrap">
	<?php if ( !is_mobile() && !is_page('resident-log-in') ) {//desktop image
		if ( has_post_thumbnail( ) ) {
			the_post_thumbnail('single-banner');
		}
	} if (get_post_type() == 'news') { 
		$news_image = wp_get_attachment_image_src( get_post_thumbnail_id('13'), 'single-banner' ); ?>
		<img src="<?php echo $news_image[0]; ?>"/>

	<?php } if (is_tree('1177')) {
		echo do_shortcode("[metaslider id=1293]");

	} if (is_page('resident-log-in')) {
		if ( has_post_thumbnail( ) ) {
			the_post_thumbnail('single-banner',array( 'class' => 'login-background' ));
		}
	 } ?>
	<?php if (is_404()){ ?>
		<img src="/wp-content/uploads/2016/02/about.jpg" />
	<?php } ?>
 </div>
<?php if (!is_page('resident-log-in')) { ?>

	<?php if(get_post_type() == 'news') { ?>
		<h1>News</h1>
	<?php } else { ?>
		<?php if(!get_the_title()) {?>
		<h1 style="background:0 none !important"></h1>
		<?php } ?>		
		<?php if(get_the_title()) {?>
		<h1><?= Titles\title(); ?></h1>
		<?php } ?>
	<?php } ?>
<?php } ?>
</div>
