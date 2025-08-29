<?php
	/**
	 * This class contains the centralize logic for JOBS site.
	 *
	 * Author: Gsuswami Mahendragiri
	 * Author URI: https://in.linkedin.com/in/mpgauswami-86a825254
	 *
	 */

	class TrikonaJobs {
		public function getStudentCourses($filter = []) {
			global $wpdb, $trikona_obj;
			switch_to_blog($trikona_obj->jobs_site_blog_id);
			$post_type = 'course';
			$args = wp_parse_args(
				array(
					'orderby' => 'upm.updated_date',
					'order'   => 'DESC',
					'status'  => 'any', // Any, enrolled, cancelled, expired.
				)
			);

			// Sanitize order & orderby.
			$args['orderby'] = preg_replace( '/[^a-zA-Z_.]/', '', $args['orderby'] );
			$args['order']   = preg_replace( '/[^a-zA-Z_.]/', '', $args['order'] );

			// Allow "short" orderby's to be passed in without a table reference.
			switch ( $args['orderby'] ) {
				case 'date':
					$args['orderby'] = 'upm.updated_date';
					break;
				case 'order':
					$args['orderby'] = 'p.menu_order';
					break;
				case 'title':
					$args['orderby'] = 'p.post_title';
					break;
			}

			// Prepare additional status AND clauses.
			if ( 'any' !== $args['status'] ) {
				$status = $wpdb->prepare(
					"
					AND upm.meta_value = %s
					AND upm.updated_date = (
						SELECT MAX( upm2.updated_date )
						  FROM {$wpdb->prefix}lifterlms_user_postmeta AS upm2
						 WHERE upm2.meta_key = '_status'
						   AND upm2.user_id = %d
						   AND upm2.post_id = upm.post_id
						)",
					$args['status'],
					$filter['user_id']
				);
			} else {
				$status = '';
			}

			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$courses = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT SQL_CALC_FOUND_ROWS DISTINCT upm.post_id AS id, p.post_title AS course_title
				 FROM {$wpdb->prefix}lifterlms_user_postmeta AS upm
				 JOIN {$wpdb->posts} AS p ON p.ID = upm.post_id
				 WHERE p.post_type = %s
				   AND p.post_status = 'publish'
				   AND upm.meta_key = '_status'
				   AND upm.user_id = %d
				   {$status}
				 ORDER BY {$args['orderby']} {$args['order']};
				",
					array(
						$post_type,
						$filter['user_id'],
					)
				),
				'OBJECT_K'
			);

			$found = absint( $wpdb->get_var( 'SELECT FOUND_ROWS()' ) ); // db call ok; no-cache ok.
			restore_current_blog();
			return array(
				'found'   => $found,
				'results' => $courses,
			);
		}

		public function getCourses($count_only = false) {
			global $wpdb, $trikona_obj;
			switch_to_blog($trikona_obj->jobs_site_blog_id);

			$post_type = 'course';
			$args = [
			    'post_type' 	=> $post_type,
			    'posts_per_page' => -1,
			    'post_status'    => 'publish',
			    'fields'         => $count_only ? 'ids' : 'all',
			];

			$query = new WP_Query($args);
			restore_current_blog();

		    if ($count_only) {
		        return $query->found_posts;
		    }

		    return $query->have_posts() ? $query->posts : [];
		}

		public function getComletedCourses($filter = []) {
			global $wpdb, $trikona_obj;
			switch_to_blog($trikona_obj->jobs_site_blog_id);

			$sql = "SELECT * FROM {$wpdb->prefix}lifterlms_user_postmeta WHERE post_id IN (".implode(",", $filter['course_ids']).") AND user_id = %d AND meta_key = %s AND meta_value = %s";
			$courses = $wpdb->get_results($wpdb->prepare($sql, [$filter['user_id'], '_is_complete', 'yes']));

			$found = absint( $wpdb->get_var( 'SELECT FOUND_ROWS()' ) );
			restore_current_blog();
			return array(
				'found'   => $found,
				'results' => $courses,
			);
		}
	}