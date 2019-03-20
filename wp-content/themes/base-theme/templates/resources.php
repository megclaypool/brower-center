	<section class="res-resources">
		<div class="res-resources-inner">
		<div class="section-title"> 
			<h2>Resources</h2>
		</div>
		<?php 
		//check for the repeater first. We dont want to do a while loop if there are none!
		if(have_rows('resources')){ ?>
		<?php $i = 1; ?>
		<div class="res-resources-content">
			
			<?php //Loops through the repeater
			while (have_rows('resources')) {
				the_row();
				
				//fetches the post object field. Assumes this is a single value, if they can select more than one you have to do a foreach loop here like the one in the example above
				$service_object = get_sub_field('resources');
			    $title = get_sub_field('resource_title');
				
				//get additional subfields
			    $byline = get_sub_field('resource_byline');
			    $blurb = get_sub_field('resource_text');
			    $link = get_sub_field('resource_link');
			    
			    ?>
				<div class="resource-item row-<?php echo $i; $i++; ?>">
			    	<h3 class="resource-title"><a href="<?php echo $link ?>" target="_blank" title="link to <?php echo $title ?>"><?php echo $title ?></a></h3>
			    	<div class="resource-byline"><?php echo $byline ?></div>
			    	<div class="resource-blurb"><?php echo $blurb ?> 
			    	<?php if($link !='') { ?>
			    	<a href="<?php echo $link ?>" title="link to <?php echo $title ?>" target="_blank">DOWNLOAD</a>
			    	<?php } ?>
			    	</div>
		    	</div>
			<?php }	
			
		} 
		?>
        </div><!-- /.resource-content-->	         
        </div><!-- /.resource-inner-->	         
	</section>