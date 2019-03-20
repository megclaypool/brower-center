<?php
/**
 * Template Name: Homepage Template
 */
?>
<?php //get_template_part('templates/slider'); ?>
<?php while (have_posts()) : the_post(); ?>

	<div class="homeBlock first" style="background-image: url(<?php the_field('image_1'); ?>);">

		<?php if (get_field('title_1')) { ?>
	    	<div>
				<h2><?php the_field('title_1'); ?></h2>
			</div>
		<?php } ?>

		<?php if (get_field('blurb_1')) { ?>
	    	<div>
				<?php the_field('blurb_1'); ?>
			</div>
		<?php } ?>

		<?php if (get_field('link_button_1')) { ?>
	    	<div>
			<a class="btn btn-teal" href="<?php the_field('link_button_1'); ?>"><?php if (get_field('link_text_1')) { ?><?php the_field('link_text_1'); ?><?php } ?></a>	
		</div>
		<?php } ?>
	</div>
	<div class="clearfix"></div>

	<div class="homeBlock second" style="background-image: url(<?php the_field('image_2'); ?>);">

		<?php if (get_field('title_2')) { ?>
	    	<div>
					<h2><?php the_field('title_2'); ?></h2>
				</div>
		<?php } ?>

		<?php if (get_field('blurb_2')) { ?>
	    	<div>
					<?php the_field('blurb_2'); ?>
				</div>
		<?php } ?>

		<?php if (get_field('link_button_2')) { ?>
	    	<div>
					<a class="btn" href="<?php the_field('link_button_2'); ?>"><?php if (get_field('link_text_2')) { ?><?php the_field('link_text_2'); ?><?php } ?></a>
				</div>
		<?php } ?>
	</div>
	<div class="clearfix"></div>

	<div class="homeBlock third" style="background-image: url(<?php the_field('image_3'); ?>);">

		<?php if (get_field('title_3')) { ?>
	    	<div>
					<h2><?php the_field('title_3'); ?></h2>
				</div>
		<?php } ?>

		<?php if (get_field('blurb_3')) { ?>
	    	<div>
					<?php the_field('blurb_3'); ?>
				</div>
		<?php } ?>

		<?php if (get_field('link_button_3')) { ?>
	    	<div>
					<a class="btn" href="<?php the_field('link_button_3'); ?>"><?php if (get_field('link_text_3')) { ?><?php the_field('link_text_3'); ?><?php } ?></a>
				</div>
		<?php } ?>
	</div>
	<div class="clearfix"></div>

	<div class="homeBlock fourth" style="background-image: url(<?php the_field('image_4'); ?>);">

		<?php if (get_field('title_4')) { ?>
	    	<div>
					<h2><?php the_field('title_4'); ?></h2>
				</div>
		<?php } ?>

		<?php if (get_field('blurb_4')) { ?>
	    	<div>
					<?php the_field('blurb_4'); ?>
				</div>
		<?php } ?>

		<?php if (get_field('link_button_4')) { ?>
	    	<div>
					<a class="btn" href="<?php the_field('link_button_4'); ?>"><?php if (get_field('link_text_4')) { ?><?php the_field('link_text_4'); ?><?php } ?></a>
				</div>
		<?php } ?>
	</div>
	<div class="clearfix"></div>

<?php endwhile; ?>
