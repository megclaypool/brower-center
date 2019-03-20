	<section class="//section name">
		<div class="//section name-inner">
		<div class="section-title"> 
			<h2>TITLE</h2>
		</div>
		<?php 
		//check for the repeater first. We dont want to do a while loop if there are none!
		if(have_rows('//repeat field name')){ ?>
		<?php $i = 1; ?>
		<div class="//section name-content">
			
			<?php //Loops through the repeater
			while (have_rows('services')) {
				the_row();
				
				//fetches the post object field. Assumes this is a single value, if they can select more than one you have to do a foreach loop here like the one in the example above
				$service_object = get_sub_field('services');
			    $title = get_sub_field('service_title');
				
				//Fetch the image field
				//Make sure image field is set to Image ID
				$service_image = get_sub_field('//image subfield name');
				$service_image_size = "//image size";
				$service_thumb = wp_get_attachment_image_src( $service_image, $service_image_size );

				//get additional subfields
			    $blurb = get_sub_field('//additional field');
			    $link = get_sub_field('//additional field');
			    
			    ?>

				<div class="col-xs-12 col-sm-3">
					<div class="service-item row-<?php echo $i; $i++; ?>" data-wow-duration="1.5s" data-wow-offset="50">
						<a href="<?php echo $link ?>" title="link to <?php $title ?>"><img src="<?php echo $service_thumb[0] ?>" alt="<?php echo $title ?> services" /></a>
				    	<h3 class="service-title"><a href="<?php echo $link ?>" title="link to <?php echo $title ?>"><?php echo $title ?></a></h3>
				    	<div class="service-blurb"><?php echo $blurb ?></div>
			    	</div>
				</div>
			<?php }	
			
		} 
		?>
        </div><!-- /.services-content-->	         
        </div><!-- /.services-inner-->	         
	</section>