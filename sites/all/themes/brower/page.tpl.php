<?php  $bp_base_url = 'http://d5.openwebgroup.ca';  ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!--
	<?php /*print_r( $primary_links); */ echo( bp_current_section($primary_links)); ?>
-->
  <head>
	<base href="http://d5.openwebgroup.ca/" />
    <title>The David Brower Center</title>
    <link rel="shortcut icon" href="favicon.ico"/>
    <meta name="description" content="A facility designed to inspire and nurture current generations of activists and to build a foundation for future generations"/>
    <meta name="keywords" content="David Brower, David R. Brower, LEED Platinum, Brower Center, environmental movement, green building, Dave Brower, Berkeley, multi-tenant nonprofit center, New Urbanism, Peter Buckley, Earth Island Institute, Center for Ecoliteracy, Build it Green, California League of Conservation Voters, "/>
    <meta name="rank" content="1-10"/>
    <meta name="author" content="info@browercenter.org"/>
    <meta name="copyright" content="Copyright 2006, David Brower Center"/>
    <meta name="classification" content="Berkeley, David Brower Center, "/>
    <meta name="robots" content="All"/>
    <meta name="revisit-after" content="14 days"/>
    <meta name="distribution" content="Global"/>
    <meta name="coverage" content="Worldwide"/>
    <meta name="geo.placename" content="Berkeley, California"/>
    <meta name="geo.country" content="US"/>
    <meta name="state" content="CA"/>
    <meta name="resource-type" content="Document"/>
    <meta http-equiv="content-language" content="en-us"/>
    <link rel="stylesheet" type="text/css" href="<?php /* echo $bp_base_url; */ ?>themes/brower/style.css" title="The David Brower Center Style Sheet"/>
    <?php #print $scripts ?>
  </head>
  <body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" rightmargin="0">
  	<div style="margin: 0 auto;width:775px;" id="center-container">
    <table border="0" cellspacing="0" cellpadding="0" width="775" align="left">
      <tr>
	<td colspan="4" align="left" valign="top">

	  <table border="0" cellspacing="0" cellpadding="0" width="775" background="<?php /* echo $bp_base_url; */ ?>files/images/back_header.gif">
	    <tr>
	      <td align="left" valign="top" width="65"><img src="<?php /* echo $bp_base_url; */ ?>files/images/pixel.gif" alt="" width="65" height="1" hspace="0" vspace="0" border="0"></td>
	      <td align="left" valign="top" width="90"><a href="http://www.browercenter.org/"><img src="<?php /* echo $bp_base_url; */ ?>files/images/icon_browercenter.gif" alt="Brower Center Icon" width="90" height="43" hspace="0" vspace="0" border="0"></a></td>
	      <td align="left" valign="top" width="100%">&nbsp;</td>
	    </tr>
	    <tr>
	      <td align="left" valign="top" colspan="3"><a href="http://www.browercenter.org/"><img src="<?php /* echo $bp_base_url; */ ?>files/images/title_browercenter.gif" alt="Brower Center" width="347" height="21" hspace="20" vspace="6" border="0"></a></td>
	    </tr>
	    <tr>
	      <td align="left" valign="top" colspan="3"><img src="<?php /* echo $bp_base_url; */ ?>files/images/pixel.gif" alt="" width="1" height="20" hspace="0" vspace="0" border="0"></td>
	    </tr>
	    <tr>
	      <td class="primary_links" align="right" valign="top" colspan="3">
		<?php print theme('primary_links', $primary_links); ?>
	      </td>
	    </tr>
	  </table>
	</td>
      </tr>
      <tr>
	<td colspan="4" align="left" valign="top">
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	    <tr>
	      <td align="top" valign="top">
		<?php if ($node->field_page_image_path != NULL && $node->field_page_image_path[0]['value'] != '') { ?>
		  <?php if(file_exists("files/images/" . $node->field_page_image_path[0]['value'])) { ?>
		    <img src="<?php /* echo $bp_base_url; */ ?>files/images/<?php print $node->field_page_image_path[0]['value']; ?>" alt="Brower Center photo montage" border="0" height="143" vspace="10" width="775">
		  <?php } ?>
		<?php } else { ?>
		  <img src="<?php /* echo $bp_base_url; */ ?>files/images/g6.jpg" alt="Brower Center photo montage" border="0" height="143" vspace="10" width="775">
		<?php } ?>
	      </td>
	    </tr>
	  </table>
	</td>
      </tr>
      <tr>
	<td align="right" valign="top" width="155">
	  <br/>
	  <br/>
	  <div class="secondary_menu">
	    <?php print theme('secondary_links', $secondary_links); ?>
	  </div>
	  <div class="sidebar_left">
	    <?php print $floater; ?>
	  </div>
	  <?php global $user; ?>
	  <?php if ($user->uid != 0) { ?>
	    <div class="page_menu">
	      <div class="title">
		<h3>
		  Page Menu
		</h3>
	      </div>
	      <?php if ($tabs != ""): ?>
		<div class="tabs"><?php print $tabs ?></div>
	      <?php endif; ?>
	      <?php print $sidebar_left; ?>
	    </div>
	  <?php } ?> 
	</td>
	<td align="left" valign="top" width="20">
	  <img src="<?php /* echo $bp_base_url; */ ?>files/images/pixel.gif" alt="" border="0" height="1" hspace="0" vspace="0" width="20"/>
	</td>
	<td align="left" valign="top" width="600">
	  <?php print $content; ?>
	</td>
      </tr>
      <tr>
	<td colspan="4" align="left" valign="top"><img src="<?php /* echo $bp_base_url; */ ?>files/images/pixel_ltgreen.gif" alt="" width="775" height="1" hspace="0" vspace="4" border="0"></td>
      </tr>
      <tr>
	<td colspan="4" align="left" valign="top" class="footer">
	  &copy; 2006-2008 The David Brower Center. All Rights Reserved | <a href="?q=node/70" class="flink">Contact</a> | <a href="?q=node/71" class="flink">Privacy Policy</a>
	</td>
      </tr>
    </table>
    </div><!-- center-container -->
<!-- new google analytics tracking code -->
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
