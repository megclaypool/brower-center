<div class="mobile-banner visible-md visible-sm visible-xs col-xs-12" role="banner">

  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mobileNavbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
</div>
<header class="mainHeader" role="banner">
<div class="top-header hidden-xs hidden-sm">
  <div class="container">
  <div class="top-header-item header-social">
    <a href="https://www.facebook.com/davidbrowercenter/" target="_blank"><span class="fa-stack"><span class="fa fa-circle fa-stack-2x"></span><span class="fa fa-facebook fa-stack-1x fa-inverse"></span></span></a><a href="https://twitter.com/browercenter" target="_blank"><span class="fa-stack"><span class="fa fa-circle fa-stack-2x"></span><span class="fa fa-twitter fa-stack-1x fa-inverse"></span></span></a><a href="https://www.instagram.com/davidbrowercenter/" target="_blank"><span class="fa-stack"><span class="fa fa-circle fa-stack-2x"></span><span class="fa fa-instagram fa-stack-1x fa-inverse"></span></span></a>
  </div>          
  <div class="top-header-item header-newsletter">
    <a class="btn btn-primary" role="button" data-toggle="collapse" href="#desktopnl" aria-expanded="false" aria-controls="desktopnl">
      Newsletter Sign-up <span class="fa fa-chevron-down"></span>
    </a>
    <div class="collapse" id="desktopnl">
      <div id="mc_embed_signup">
        <form action="//browercenter.us9.list-manage.com/subscribe/post?u=1ba551556fa6f8d20e1143b76&amp;id=bc578d4fff" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="" _lpchecked="1">
            <div id="mc_embed_signup_scroll">

          <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required="">
            <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
            <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_1ba551556fa6f8d20e1143b76_bc578d4fff" tabindex="-1" value=""></div>
            <div class="signup"><input type="submit" value="signup" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
            </div>
        </form>
      </div>
    </div>
  </div><!--/.top-header-item-->
  <div class="top-header-item header-donate">
    <a href="https://4agc.com/donation_pages/4da28627-3921-44b2-81b0-83facfe9c902" class="btn btn-teal btn-short" target="_blank">DONATE</a>
  </div><!--/.top-header-item-->
</div>
</div>       
  <div class="header-top">
    <div class="container"> 
	    <div class="col-md-3">
      <a href="<?php bloginfo('url'); ?>"><h1><?php bloginfo('name'); ?><span><?php bloginfo('description'); ?></span></h1></a>
	    </div> <!-- end 4 -->

	    <div class="col-md-9 hidden-xs hidden-sm">

        <nav role="navigation" class="navbar">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(array(
                          'theme_location' => 'primary_navigation',
                          'walker'      => new Roots_Nav_Walker,
                          'menu_class'  => 'nav nav-pills',
                          'link_before' => '<span>',
                          'link_after'  => '</span>')
          );
      endif;
      ?>
    </nav>
	    </div> <!-- end 8 -->
    </div>
</header>
<div class="clearfix"></div>
