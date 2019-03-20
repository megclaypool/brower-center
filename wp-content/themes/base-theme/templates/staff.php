<div class="leadership-inner">
    <div class="section-title"> 
      <h2>Leadership</h2>
    </div>
    <?php 
    //check for the repeater first. We dont want to do a while loop if there are none!
    if(have_rows('leadership')){ ?>
    <?php $i = 0; ?>
    <div class="leadership-content">
	  
	  <?php //Loops through the repeater
	  while (have_rows('leadership')) {
	    the_row(); $i++;
	    
	    //fetches the post object field. Assumes this is a single value, if they can select more than one you have to do a foreach loop here like the one in the example above
	      $leadership_object = get_sub_field('leadership');
	      $leadership_name = get_sub_field('leadership_name');
	      $leadership_title = get_sub_field('leadership_title');
	
	      //Image 
	      $leadership_image = get_sub_field('leadership_image');
	
	      //thumb full
	      $leadership_image_full = "thumbnail";
	      $leadership_thumb = wp_get_attachment_image_src( $leadership_image, $leadership_image_full );
	    
	      $leadership_bio = get_sub_field('leadership_bio');
	      
	      ?>
		  <div class="staff-item row-<?php echo $i; ?>">
	          <?php if( !is_mobile() ) { ?>
	          <div class="bio-image">
	            <img src="<?php echo $leadership_thumb[0] ?>" alt="<?php echo $leadership_name; ?> bio image"/>
	            
	            <p><?php if (get_sub_field('leadership_social')) { ?>
	                <a href="<?php the_sub_field('leadership_social'); ?>" target="_blank"><span class="fa-lg fa-stack"><span class="fa fa-circle fa-stack-2x"></span><span class="fa fa-linkedin fa-stack-1x fa-inverse"></span></span></a>
	            <?php } ?>
	            <?php if (get_sub_field('leadership_email')) { ?>
	                <a href="mailto:<?php the_sub_field('leadership_email'); ?>"><span class="fa-lg fa-stack"><span class="fa fa-circle fa-stack-2x"></span><span class="fa fa-envelope fa-stack-1x fa-inverse"></span></span></a>
	            <?php } ?></p>
	          </div>
	          <?php } ?>
	          <div class="staff-text">
	            <h3 class="staff-name">
	             <?php echo $leadership_name ?><br/>
	              <span><?php echo $leadership_title; ?></span>
	            </h3>
	            <?php if( is_mobile() ) { ?>
	           <?php if (get_sub_field('leadership_social')) { ?>
	                <a href="<?php the_sub_field('leadership_social'); ?>" target="_blank"><span class="fa-lg fa-stack"><span class="fa fa-circle fa-stack-2x"></span><span class="fa fa-linkedin fa-stack-1x fa-inverse"></span></span></a>
	            <?php } ?>
	            <?php if (get_sub_field('leadership_email')) { ?>
	                <a href="mailto:<?php the_sub_field('leadership_email'); ?>"><span class="fa-lg fa-stack"><span class="fa fa-circle fa-stack-2x"></span><span class="fa fa-envelope fa-stack-1x fa-inverse"></span></span></a>
	            <?php } ?>
	        	<?php } ?>
	            <div class="full-bio">
	              <?php echo $leadership_bio;?>
	            </div>                
	          </div><!--/.staff-text-->
	    </div><!--/.staff-item-->
	  <?php } ?>
	<?php  } ?>
    </div><!-- /.leadership-content-->       
</div><!-- /.leadership-inner-->


<div class="staff-inner">
    <div class="section-title"> 
      <h2>Staff</h2>
    </div>
    <?php 
    //check for the repeater first. We dont want to do a while loop if there are none!
    if(have_rows('staff_members')){ ?>
    <?php $i = 0; ?>
    <div class="staff-content">
      
      <?php //Loops through the repeater
      while (have_rows('staff_members')) {
        the_row(); $i++;
        
        //fetches the post object field. Assumes this is a single value, if they can select more than one you have to do a foreach loop here like the one in the example above
          $staff_object = get_sub_field('staff_members');
          $staff_name = get_sub_field('staff_name');
          $staff_title = get_sub_field('staff_title');

          //Image 
          $staff_image = get_sub_field('staff_image');

          //thumb full
          $staff_image_full = "thumbnail";
          $staff_thumb = wp_get_attachment_image_src( $staff_image, $staff_image_full );
        
          $staff_bio = get_sub_field('staff_bio');
          
          ?>
		  
          <div class="staff-item row-<?php echo $i; ?>">
            <?php if( !is_mobile() ) { ?>
            <div class="bio-image">
              <img src="<?php echo $staff_thumb[0] ?>" alt="<?php echo $staff_name; ?> bio image"/>
              
              <p><?php if (get_sub_field('staff_social')) { ?>
                  <a href="<?php the_sub_field('staff_social'); ?>" target="_blank"><span class="fa-lg fa-stack"><span class="fa fa-circle fa-stack-2x"></span><span class="fa fa-linkedin fa-stack-1x fa-inverse"></span></span></a>
              <?php } ?>
              <?php if (get_sub_field('staff_email')) { ?>
                  <a href="mailto:<?php the_sub_field('staff_email'); ?>"><span class="fa-lg fa-stack"><span class="fa fa-circle fa-stack-2x"></span><span class="fa fa-envelope fa-stack-1x fa-inverse"></span></span></a>
              <?php } ?></p>
            </div>
            <?php }?>            
            <div class="staff-text">
              <h3 class="staff-name">
                <?php echo $staff_name ?><br/>
                <span><?php echo $staff_title; ?></span>
              </h3>
	           <?php if( is_mobile() ) { ?>
	           <?php if (get_sub_field('staff_social')) { ?>
	                <a href="<?php the_sub_field('staff_social'); ?>" target="_blank"><span class="fa-lg fa-stack"><span class="fa fa-circle fa-stack-2x"></span><span class="fa fa-linkedin fa-stack-1x fa-inverse"></span></span></a>
	            <?php } ?>
	            <?php if (get_sub_field('staff_email')) { ?>
	                <a href="mailto:<?php the_sub_field('staff_email'); ?>"><span class="fa-lg fa-stack"><span class="fa fa-circle fa-stack-2x"></span><span class="fa fa-envelope fa-stack-1x fa-inverse"></span></span></a>
	            <?php } ?>
	        	<?php } ?>              
              <div class="full-bio">
                <?php echo $staff_bio;?>
              </div>                
            </div><!--/.staff-text-->
        </div><!--/.staff-item-->
      <?php } ?>
   <?php  } ?>
    </div><!-- /.staff-content-->       
</div><!-- /.staff-inner-->

<div class="events-inner">
    <div class="section-title"> 
      <h2>Events Team</h2>
    </div>
    <?php 
    //check for the repeater first. We dont want to do a while loop if there are none!
    if(have_rows('events')){ ?>
    <?php $i = 0; ?>
    <div class="events-content">
    
    <?php //Loops through the repeater
    while (have_rows('events')) {
      the_row(); $i++;
      
      //fetches the post object field. Assumes this is a single value, if they can select more than one you have to do a foreach loop here like the one in the example above
        $events_object = get_sub_field('events');
        $events_name = get_sub_field('events_name');
        $events_title = get_sub_field('events_title');
  
        //Image 
        $events_image = get_sub_field('events_image');
  
        //thumb full
        $events_image_full = "thumbnail";
        $events_thumb = wp_get_attachment_image_src( $events_image, $events_image_full );
      
        $events_bio = get_sub_field('events_bio');
        
        ?>
      <div class="staff-item row-<?php echo $i; ?>">
            <?php if( !is_mobile() ) { ?>
            <div class="bio-image">
              <img src="<?php echo $events_thumb[0] ?>" alt="<?php echo $events_name; ?> bio image"/>
              
              <p><?php if (get_sub_field('events_social')) { ?>
                  <a href="<?php the_sub_field('events_social'); ?>" target="_blank"><span class="fa-lg fa-stack"><span class="fa fa-circle fa-stack-2x"></span><span class="fa fa-linkedin fa-stack-1x fa-inverse"></span></span></a>
              <?php } ?>
              <?php if (get_sub_field('events_email')) { ?>
                  <a href="mailto:<?php the_sub_field('events_email'); ?>"><span class="fa-lg fa-stack"><span class="fa fa-circle fa-stack-2x"></span><span class="fa fa-envelope fa-stack-1x fa-inverse"></span></span></a>
              <?php } ?></p>
            </div>
            <?php } ?>
            <div class="staff-text">
              <h3 class="staff-name">
               <?php echo $events_name ?><br/>
                <span><?php echo $events_title; ?></span>
              </h3>
	           <?php if( is_mobile() ) { ?>
	           <?php if (get_sub_field('event_social')) { ?>
	                <a href="<?php the_sub_field('event_social'); ?>" target="_blank"><span class="fa-lg fa-stack"><span class="fa fa-circle fa-stack-2x"></span><span class="fa fa-linkedin fa-stack-1x fa-inverse"></span></span></a>
	            <?php } ?>
	            <?php if (get_sub_field('event_email')) { ?>
	                <a href="mailto:<?php the_sub_field('event_email'); ?>"><span class="fa-lg fa-stack"><span class="fa fa-circle fa-stack-2x"></span><span class="fa fa-envelope fa-stack-1x fa-inverse"></span></span></a>
	            <?php } ?>
	        	<?php } ?>               
              <div class="full-bio">
                <?php echo $events_bio;?>
              </div>                
            </div><!--/.staff-text-->
      </div><!--/.staff-item-->
    <?php } ?>
  <?php  } ?>
    </div><!-- /.events-content-->       
</div><!-- /.events-inner-->

<div class="board-inner">
    <div class="section-title"> 
      <h2>Board of Directors</h2>
    </div>
    <?php 
    //check for the repeater first. We dont want to do a while loop if there are none!
    if(have_rows('board_members')){ ?>
    <?php $i = 0; ?>
    <div class="board-content">
      
      <?php //Loops through the repeater
      while (have_rows('board_members')) {
        the_row(); $i++;
        
        //fetches the post object field. Assumes this is a single value, if they can select more than one you have to do a foreach loop here like the one in the example above
          $board_object = get_sub_field('board_members');
          $board_name = get_sub_field('board_name');
          $board_title = get_sub_field('board_title');
        
          $board_bio = get_sub_field('board_bio');
          
          ?>

          <div class="staff-item row-<?php echo $i; ?>">
            <h3 class="board-name">
              <?php echo $board_name ?><br/>
              <span><?php echo $board_title; ?></span>
            </h3>
            <div class="full-bio">
              <?php echo $board_bio;?>
            </div>                
          </div><!--/.bio-wrap-->
      <?php } ?>
    
   <?php  } ?>
        </div><!--/.board-content-->
    </div><!-- /.board-inner-->       