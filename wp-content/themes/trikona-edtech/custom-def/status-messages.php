<?php
	/**
	 * This class contains the centralize status messages and its code.
	 *
	 * Author: Gsuswami Mahendragiri
	 * Author URI: https://in.linkedin.com/in/mpgauswami-86a825254
	 *
	 */

	class TrikonaStatusMessages {
		public $sucess = [
			'1' => 'You have sufficient access for dashboard',
		];
		public $error = [
			// Authorization & Permission Errors
			'101' => 'Access to this dashboard is restricted. Please contact your administrator for further assistance.', // 1
			'102' => 'The group has not yet been assigned to you, which means you will not be able to access this dashboard.', // 3
			'103' => 'You do not have the necessary permissions to access this dashboard.', // 4
			'104' => 'You are not authorized to access this page.', // 5

			// Configuration Errors
			'201' => 'Multiple groups are assigned from same group type.', // 2

			// No Data / Empty Result Errors
			'301' => 'Unfortunately, no records were found that match the selected filter criteria.', // 6
		];
	}