<?php

use Roots\Sage\Config;
use Roots\Sage\Wrapper;

?>

<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
  <?php get_template_part('templates/head'); ?>  
  <body <?php body_class(); ?>>   
    <?php get_template_part('templates/off-canvas'); ?>
    <!--[if lt IE 9]>
      <div class="alert alert-warning">
        <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage'); ?>
      </div>
    <![endif]-->
    <?php
      do_action('get_header');
      get_template_part('templates/header'); 

      if( !is_front_page() ) {
        get_template_part('templates/page-header');
      }

    ?>
    <div class="wrap container-fluid" role="document">
      <div class="content">
        <div class="row">
        <?php if ( is_active_sidebar( 'sidebar-primary' )) { ?>
          <main class="main col-sm-9 col-sm-push-3" role="main">
            <?php include Wrapper\template_path(); ?>
          </main><!-- /.main -->            
          <aside class="col-sm-3 col-sm-pull-9 sidebar" role="complementary">
            <?php include Wrapper\sidebar_path(); ?>
          </aside><!-- /.sidebar -->                  
        <?php } else { ?>
          <main class="main col-xs-12" role="main">
            <?php include Wrapper\template_path(); ?>
          </main><!-- /.main -->
        <?php } ?>        
        </div><!--/.row-->
      </div><!-- /.content -->
    </div><!-- /.wrap -->
    <?php
      do_action('get_footer');
      get_template_part('templates/footer');
      wp_footer();
    ?>  
  </body>
</html>
