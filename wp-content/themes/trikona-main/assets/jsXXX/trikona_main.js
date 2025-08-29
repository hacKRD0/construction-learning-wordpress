(function ($) {
  "use strict";
  $(document).ready(function () {
    $('.nav-item').on('click', function () {
      $(this).find('.dropdown-menu').stop(true, true).fadeToggle(200);
    });

    $(document).on('click', function (e) {
      if (!$(e.target).closest('.nav-item').length) {
        $('.dropdown-menu').hide();
      }
    });

    // Show or hide submenus when menu-item-has-children is clicked
    if ($(window).width() < 767) {
      $(".menu-item-has-children > a").click(function (e) {
        e.preventDefault(); // Prevent the default action of the anchor tag
        var $subMenu = $(this).next('.sub-menu');

        $(".sub-menu").not($subMenu).hide(); // Hide other submenus
        if ($subMenu.is(":visible")) {
          $subMenu.hide(); // Hide the submenu if it's already visible
        } else {
          $subMenu.show(); // Show the submenu if it's hidden
        }

        // Toggle a class to track the open state of the submenu
        $(this).parent().toggleClass('submenu-open');
      });
    }

    // Additional logic to hide submenus initially below 767px
    if ($(window).width() < 767) {
      $(".site-navigation").hide();
      $(".sub-menu").hide();

    }

    // Toggle the visibility of site-navigation when site-navigation-toggle-holder is clicked
    $(".site-navigation-toggle-holder").click(function () {
      $(".site-navigation").toggle();
    });

  });

})(jQuery);


// jQuery(document).ready(function($) {
//     $('body').on('click', 'a.checkout-button, button.checkout', function(e) {
//         // Prevent the default action
//         e.preventDefault();
//         // Perform an AJAX request to check the cart
//         $.ajax({
//             url: '/wp-admin/admin-ajax.php',
//             type: 'POST',
//             data: {
//                 action: 'check_cart_before_checkout'
//             },
//             success: function(response) {
//                 if (response.can_checkout) {
//                     // If no conflict, proceed to the checkout page
//                     window.location.href = '/checkout/';
//                 } else {
//                     // If conflict, display the error and prevent checkout
//                     alert(response.message); // Replace this with a more user-friendly notification
//                 }
//             }
//         });
//     });
// });


