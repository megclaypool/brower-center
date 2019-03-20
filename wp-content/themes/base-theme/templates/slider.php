<?php 
//check for the repeater first. We dont want to do a while loop if there are none!
if(have_rows('slider')){ ?>
<div class="flexslider-wrapper">
	<div class="flexslider">
		<ul class="slides">
			<?php //Loops through the repeater
			while (have_rows('slider')) {
				the_row();
				
				//fetches the post object field. Assumes this is a single value, if they can select more than one you have to do a foreach loop here like the one in the example above
				$slide_object = get_sub_field('slider');
			    $title = get_sub_field('title');
				
				//Fetch the image field
				//Make sure image field is set to Image ID
				$slide_image = get_sub_field('image');
				$slide_image_size = "slider";
				$slide_thumb = wp_get_attachment_image_src( $slide_image, $slide_image_size );

				//Mobile Image
				$mobile_slide_image = get_sub_field('image');
				$mobile_slide_image_size = "mobile-slider";
				$mobile_slide_thumb = wp_get_attachment_image_src( $mobile_slide_image, $mobile_slide_image_size );

				//get additional subfields
			    $blurb = get_sub_field('blurb');
			    $link = get_sub_field('url');
			    
			    ?>
			<li class="slide">
				<div class="slide-img">
					<a href="<?php echo $link ?>" >
						<?php if( is_mobile() ) {?> 
							<img src="<?php echo $mobile_slide_thumb[0] ?>" alt="<?php echo $title ?> link" />
						<?php } else { ?>
							<img src="<?php echo $slide_thumb[0] ?>" alt="<?php echo $title ?> link" />
						<?php } ?>
					</a>
					<div class="slide-text">
						<h2 class="slide-title">
							<a href="<?php echo $link ?>" ><?php echo $title ?></a>
						</h2>
						<div class="slide-blurb hidden-xs">
							<a href="<?php echo $link ?>" ><?php echo $blurb ?></a>
						</div>	
						<div class="slide-link hidden-xs">
							<a href="<?php echo $link ?>" class="btn pink">Read More</a>
						</div>	

					</div>
				</div>
				
			</li>
			<?php
				}
			?>
		</ul>
	</div>
</div>
<script>
(function($) 
{
	$(window).ready(function() {
	   //slider
      $('.flexslider').flexslider({
        animation: "fade",
        controlsContainer: '.slider',
        controlNav: true
      });

    });
  })(jQuery);
</script>
<?php } ?>