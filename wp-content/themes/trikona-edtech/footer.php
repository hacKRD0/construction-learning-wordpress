<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<footer class="footer">
   <div class="container">
      <div class="footer-row">
         <div class="footer-col">
            <ul>
               <li><a href="/about-us/">About us</a></li>
               <li><a href="/contact-us/">Contact Us</a></li>
               <li><a href="/privacy-policy/"> Privacy Policy</a></li>
               <li><a href="/terms-and-conditions/">Terms & Conditions</a></li>
            </ul>
         </div>
      </div>
      <div class="footer-row">
         <div class="footer-col">
            <p>Construction World &copy; All Rights Reserved 2015-2023</p>
         </div>
      </div>
   </div>
</footer>

<?php wp_footer(); ?>
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

</body>
</html>