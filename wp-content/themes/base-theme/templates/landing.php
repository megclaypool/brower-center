<?php

  $relationship = get_field('add_content');
  if ($relationship != '') {
  foreach ($relationship as $postID) {
    $title = get_the_title( $postID );
    $thumbnail = get_the_post_thumbnail( $postID, 'medium' );
    $link = get_permalink( $postID );
    $room_summary = get_field('room_summary',$postID);
    $excerpt = get_long_excerpt_by_id($postID);
    $posttype = get_post_type($postID);
    $content = get_post($postID);
    $content_string = $content->post_content;
    ?>
    <div class="row">
    <article <?php post_class(); ?>>
	<?php if( ($thumbnail != '') && (!is_mobile()) ) { ?>
	  <div class="col-xs-12 col-sm-3">
	  <div class="thumbnail-container">
	    <div class="entry-image">
	      <?php if($posttype != 'program') { ?>
	      <a href="<?php echo $link ?>" title="link to <?php echo $title ?>">
	      <?php
	        echo $thumbnail;
	      ?>
	    </a>
		<?php } else {
		    echo $thumbnail;	    
		} ?>
	    </div>

	    <?php if($posttype == 'exhibit' || $posttype == 'event_room') { ?>

	    	<div><a class="btn btn-default" href="<?php echo $link; ?>">LEARN MORE</a></div>

	    <?php } ?>
	  </div>
	  </div>
	  <div class="col-xs-12 col-sm-9">
	<?php } else { ?>
		<div class="col-xs-12">
	<?php } ?>
  <div class="inner-container">
	 	<?php if($posttype == 'program') { ?>
		 	<?php if (get_field('eventbrite_link', $postID)) { ?>
	    	    <h2 class="entry-title"><a target="_blank" href="<?php echo get_field('eventbrite_link', $postID); ?>"><?php echo $title; ?></a></h2>
	 		<?php } 
		} else { ?>
	 		<h2 class="entry-title"><a href="<?php echo $link ?>" title="link to <?php echo $title ?>"><?php echo $title; ?></a></h2>
	 	<?php } ?>
    <?php if($posttype == 'news') { ?>
		<time class="updated" datetime="<?= get_the_time('c'); ?>"><?= get_the_date(); ?></time>
		<div class="byline author vcard"><?= __('By', 'sage'); ?> <a href="<?= get_author_posts_url(get_the_author_meta('ID')); ?>" rel="author" class="fn"><?= get_the_author(); ?></a></div>
	<?php } ?>

	<?php if (get_field('publication', $postID)) { ?>
	    <div class="byline author vcard">Publication: <?php echo get_field('publication', $postID); ?></div>
	<?php } ?>
	<?php if (get_field('room_capacity', $postID)) { ?>
	    <div class="byline author vcard">Room Capacity: <?php echo get_field('room_capacity', $postID); ?></div>
	<?php } ?>

	<?php if($posttype == 'exhibit') { ?>
	<?php if (get_field('date', $postID)) {
		$date = get_field('date', $postID);
		?>
	    <div class="byline author vcard"><?php echo $date;  ?></div>
	<?php } ?><?php } ?>

	<?php if($posttype == 'program') { ?>
	<?php if (get_field('presented', $postID)) {
		$presented = get_field('presented', $postID);
		?>
	    <div class="byline author vcard">Presented by: <?php echo $presented;  ?></div>
	<?php } ?><?php } ?>

  	<?php if (get_field('date', $postID)) {
		$date = DateTime::createFromFormat('Ymd', get_field('date', $postID));
    	echo '<div class="program-date">'. $date->format('F j, Y'); ?>
    	
    	<?php if(get_field('end_date', $postID)) {
    		$end_date = DateTime::createFromFormat('Ymd', get_field('end_date', $postID));
        	echo ' - '. $end_date->format('F j, Y');
        	}?>
        	<br/>
    	<?php if (get_field('time', $postID)) { ?>
        	<?php echo get_field('time', $postID); ?>
        <?php } ?>
    	<?php if (get_field('end_time', $postID)) { 
        	echo ' - ';
        	echo get_field('end_time', $postID);
        	} ?>
    </div>
	<?php } //end date ?>
	<div class="price">
	<?php if (get_field('price', $postID)) { ?>
		<?php echo get_field('price', $postID); ?>
	<?php } //end price ?>

	<?php if (get_field('eventbrite_link', $postID)) { ?>
    	   | <a class="tickets" target="_blank" href="<?php echo get_field('eventbrite_link', $postID); ?>">
			<?php if(get_field('purchase_text',$postID) ) {
                the_field('purchase_text',$postID);
              } else {
                echo 'Purchase Tickets';
              } ?></a>
	<?php } //end eventbrite link ?>
	</div>
    <div class="entry-summary">
      <?php if($posttype == 'program') { 
	      	echo $content_string; 
	      } elseif($posttype == 'event_room') {
	      	echo $room_summary;
	      } else { 
      		echo $excerpt; 
		} ?>
    </div><!--/.entry-summary-->
    <?php if($posttype == 'program' || $posttype == 'exhibit' || $posttype == 'event_room') { ?>

	<?php } else { ?>

    	<div><a class="btn btn-default" href="<?php echo $link; ?>">LEARN MORE</a></div>

    <?php } ?>

    </div>
    </div> <!-- end col 9 -->
</article>
</div> <!-- end row -->

  <div class="clearfix"></div>

<?php } //endforeach
  } //endif
wp_reset_postdata(); //Restore original Post Data ?>
<div class="fix"></div>
