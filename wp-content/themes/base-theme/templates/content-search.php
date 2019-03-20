<article <?php post_class(); ?>>
  <div class="inner-container">
    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <div class="entry-summary">
      <?php echo excerpt(50).'&hellip;'; ?>
    </div>
    </div>
</article>