/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function($) {

  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {
        // JavaScript to be fired on all pages
        $('header .search-form').hide();
        $('#search-toggle').click(function(){
	       $('header .search-form').slideToggle(800);
        });

        //main mobile nav
        $('.navbar-toggle').click(function() {
          $('#sidebar-wrapper').toggleClass('on');
        });

        //hamburger nav animate
        $(".navbar-toggle").on("click", function () {
          $(this).toggleClass("active");
        });

        // this deploys the accordian functionality on the sub menu items in the offcanvas nav
        $('.submenuGroup').hide();

        $('.navmenu-fixed-right .fa').click(function() {
          $(this).siblings('.submenuGroup').slideToggle();
          $(this).toggleClass('is-open');
        });

        $('#page-nav .fa').click(function() {
          $(this).siblings('.submenuGroup').slideToggle();
          $(this).toggleClass('is-open');
        });

        // Navbar dropdown

        $('ul.nav > li.menu-item').mouseenter(function () {
          if ( $(this).find('.dropdown-menu' ).length ){
            $('.mainHeader').delay( 200 ).css({borderBottom: '0 solid rgba(63, 164, 164, .9)'}).animate({
                borderWidth: 45
            }, 250);
            $('.dropdown-menu').delay( 200 ).css({opacity: '0'}).animate({
              opacity: 1
            },450);
          }
        }).mouseleave(function () {
             $('.mainHeader').delay( 250 ).animate({
                borderWidth: 0
            }, 250);
            $('.dropdown-menu').delay( 250 ).animate({
               opacity: 0
           }, 150);
          });

      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
    // About us page, note the change from about-us to about_us.
    'about_us': {
      init: function() {
        // JavaScript to be fired on the about us page
      }
    }
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }

  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
