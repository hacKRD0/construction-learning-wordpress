<?php
/**
 * Template Name: theme temp
 * Description: Page template with Sidebar on the left side.
 *
 */


get_header();

?>
<div class="content-fluid cs-directory-page" style="padding:15px; background-color: #f7f7f7;">
<div class="row" style="padding:50px 0px">
<?php

	if ( have_posts() ) {

		while ( have_posts() ) {
			the_post();

			the_content();
		}
	}

	?>
</div>
</div>
<?php
get_footer();
