<!-- pretty list item page -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language; ?>" xml:lang="<?php print $language; ?>">
<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php if (($node) && ($node->field_page_style) && ( $node->field_page_style[0]['value'])): ?>
    <?php if ($subtheme_directory && file_exists($subtheme_directory .'/' . $node->field_page_style[0]['value'] . '.css')): ?>
      <link rel="stylesheet" href="<?php print $base_path . $subtheme_directory; ?>/<?php print $node->field_page_style[0]['value']; ?>.css" type="text/css">
    <?php endif; ?>
  <?php endif; ?>	
  <?php if (($g_bp) && ( $g_bp['hard_css'])): ?>
    <?php if ($subtheme_directory && file_exists($subtheme_directory .'/' . $g_bp['hard_css'] . '.css')): ?>
      <link rel="stylesheet" href="<?php print $base_path . $subtheme_directory; ?>/<?php print $g_bp['hard_css']; ?>.css" type="text/css">
    <?php endif; ?>
  <?php endif; ?>	
  <!--[if IE]>
    <link rel="stylesheet" href="<?php print $base_path . $directory; ?>/ie.css" type="text/css">
    <?php if ($subtheme_directory && file_exists($subtheme_directory .'/ie.css')): ?>
      <link rel="stylesheet" href="<?php print $base_path . $subtheme_directory; ?>/ie.css" type="text/css">
    <?php endif; ?>
  <![endif]-->
  <?php print $scripts; ?>
	<base href="http://d5.openwebgroup.ca<?php print $base_path; ?>" />
</head>

<body class="<?php print $body_classes; ?>">

  <div id="page"><div id="page-inner">

    <a name="top" id="navigation-top"></a>
    <?php if ($primary_links || $secondary_links || $navbar): ?>
      <div id="skip-to-nav"><a href="#navigation"><?php print t('Skip to Navigation'); ?></a></div>
    <?php endif; ?>
	<div id="mini-nav"><a href="donate">donate</a> / <a href="mailinglist">join mailing list</a> / <a href="contact-direct">contact</a></div>
    <div id="header"><div id="header-inner" class="clear-block">

      <?php if ($logo || $site_name || $site_slogan): ?>
        <div id="logo-title">

          <?php if ($logo): ?>
            <div id="logo"><a href="<?php print $base_path; ?>" title="<?php print t('Home'); ?>" rel="home"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" id="logo-image" /></a></div>
          <?php endif; ?>

          <?php if ($site_name): ?>
            <?php if ($title): ?>
              <div id="site-name"><strong>
                <a href="<?php print $base_path; ?>" title="<?php print t('Home'); ?>" rel="home">
                <?php print $site_name; ?>
                </a>
              </strong></div>
            <?php else: ?>
              <h1 id="site-name">
                <a href="<?php print $base_path; ?>" title="<?php print t('Home'); ?>" rel="home">
                <?php print $site_name; ?>
                </a>
              </h1>
            <?php endif; ?>
          <?php endif; ?>

          <?php if ($site_slogan): ?>
            <div id="site-slogan"><?php print $site_slogan; ?></div>
          <?php endif; ?>

        </div> <!-- /#logo-title -->
      <?php endif; ?>

      <?php if ($header): ?>
        <div id="header-blocks">
          <?php print $header; ?>
        </div> <!-- /#header-blocks -->
      <?php endif; ?>

    </div></div> <!-- /#header-inner, /#header -->

    <div id="main"><div id="main-inner" class="clear-block<?php if ($search_box || $primary_links || $secondary_links || $navbar) { print ' with-navbar'; } ?>">

	    <?php if ($search_box || $primary_links || $secondary_links || $navbar): ?>
	       <div id="navbar"><div id="navbar-inner">

	         <!--a name="navigation" id="navigation"></a-->

	         <?php if ($search_box): ?>
	           <div id="search-box">
	             <?php print $search_box; ?>
	           </div> <!-- /#search-box -->
	         <?php endif; ?>

	         <?php if ($primary_links): ?>
	           <div id="primary">
	             <?php print theme('links', $primary_links); ?>
	           </div> <!-- /#primary -->
	         <?php endif; ?>

	         <?php print $navbar; ?>

	       </div></div> <!-- /#navbar-inner, /#navbar -->
	     <?php endif; ?>

      <div id="content"><div id="content-inner">

        <?php if ($content_top): ?>
          <div id="content-top">
            <?php print $content_top; ?>
          </div> <!-- /#content-top -->
        <?php endif; ?>

        <?php if ($breadcrumb || $title || $tabs || $help || $messages): ?>
          <div id="content-header">
            <?php /* print $breadcrumb; */ ?>
            <?php if ($title): ?>
              <!-- h1 class="title"><?php print $title; ?></h1 -->
            <?php endif; ?>
            <?php print $messages; ?>
            <?php if ($tabs): ?>
              <div class="tabs"><?php print $tabs; ?></div>
            <?php endif; ?>
            <?php print $help; ?>
          </div> <!-- /#content-header -->
        <?php endif; ?>

        <div id="content-area"><?php
//  echo('NRL: ');
// echo($g_bp['list_type']);
// print_r( menu_get_item(menu_get_active_item()));
// echo('<br />');
?>
          <?php print $content; ?>
        </div>

        <?php if ($feed_icons): ?>
          <div class="feed-icons"><?php print $feed_icons; ?></div>
        <?php endif; ?>

        <?php if ($content_bottom): ?>
          <div id="content-bottom">
            <?php print $content_bottom; ?>
          </div> <!-- /#content-bottom -->
        <?php endif; ?>

      </div></div> <!-- /#content-inner, /#content -->

 
      <?php if ($sidebar_left || true): ?>
        <div id="sidebar-left"><div id="sidebar-left-inner">
	         <?php if ($secondary_links && false): ?>
	           <div id="secondary">
	             <?php print theme('links', $secondary_links); ?>
	           </div> <!-- /#secondary -->
	         <?php endif; ?>

          <?php print $sidebar_left; ?>
        </div></div> <!-- /#sidebar-left-inner, /#sidebar-left -->
      <?php endif; ?>

      <?php if ($sidebar_right): ?>
        <div id="sidebar-right"><div id="sidebar-right-inner">
          <?php print $sidebar_right; ?>
        </div></div> <!-- /#sidebar-right-inner, /#sidebar-right -->
      <?php endif; ?>

    </div></div> <!-- /#main-inner, /#main -->

    <div id="footer"><div id="footer-inner">

      <div id="footer-message"><?php print $footer_message; ?></div>

      <?php $mission = theme_get_setting('mission', false); if ($mission && false): ?>
        <div id="mission"><?php print $mission; ?></div>
      <?php endif; ?>

    </div></div> <!-- /#footer-inner, /#footer -->

    <?php if ($closure_region): ?>
      <div id="closure-blocks"><?php print $closure_region; ?></div>
    <?php endif; ?>

    <?php print $closure; ?>

  </div></div> <!-- /#page-inner, /#page -->
<!-- new google tracking code -->
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-1848936-1");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>
