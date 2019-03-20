<div class="entry-content">
<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no results were found.', 'sage'); ?>
  </div>
<?php endif; ?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'search'); ?>
<?php endwhile; ?>
</div>

<?php if ($wp_query->max_num_pages > 1) : ?>
  <div class="pagination-container">
  <nav class="post-nav pull-right">
    <?php
      $args = array(
        'mid_size'           => 3,
        'next_text'          => __('More &raquo;'),
      );
      echo paginate_links( $args );
    ?>
  </nav>
</div>
<?php endif; ?>
