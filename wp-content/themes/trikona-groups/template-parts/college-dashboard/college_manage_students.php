<?php
	$obj = new Trikona();
	global $wpdb, $bp;

	switch_to_blog(1);
	$current_user = wp_get_current_user();

	$args = array(  
	    'post_type' => 'studentcourses',
	    'post_status' => 'publish',
	    'posts_per_page' => -1, 
	    'orderby' => 'title', 
	    'order' => 'ASC', 
	);
	$loop = new WP_Query( $args );

	$results = $obj->getGroupMembers(['user_id' => $current_user->ID], $is_single_record = true);
	restore_current_blog();
	$passOutYears = $obj->getYearsOfPassout();
	$educations = $obj->getEdications();

	$active_user_group_id = get_query_var( 'active_user_group_id' );
?>
<div class="table-responsive">
	<div class="row" id="customFilter">
	    <div class="col-sm-1">
	        <label for="bio">Filters:</label>
	    </div>
	    <div class="col-sm-3">
	        <select class="select filtersData" name="clgcourses" id="clgcourses">
	            <option value="">College Courses</option>
	            <?php switch_to_blog(1); while ( $loop->have_posts() ) : $loop->the_post();
	            	$title= get_the_title(get_the_ID());  ?>
	               <option value="<?= get_the_ID() ?>"><?= $title ?></option>
	            <?php endwhile; restore_current_blog();?>
	        </select>
	    </div>
	    <div class="col-sm-2">
	        <select class="select filtersData" name="yearpss" id="yearpss">
	            <option value="">Year Of Passout</option>
	            <?php foreach ($passOutYears as $passOutYear) { ?>
	            	<option value="<?= $passOutYear->name;?>"><?= $passOutYear->name;?></option>
	            <?php }; ?>
	        </select>
	    </div>
	    <div class="col-sm-2">
	    	<select class="select filtersData" name="edus" id="edus">
	            <option value=""> Highest Education </option>
	            <?php foreach ($educations as $education) { ?>
	            	<option value="<?= $education->id;?>"><?= $education->name;?></option>
	            <?php } ?>
	        </select>
	   	</div>
	    <div class="col-sm-2">
	        <select class="select filtersData" name="status" id="status">
	            <option value=""> Status </option>
	            <option value="0">Active</option>
	            <option value="1">Inactive</option>
	        </select>
	    </div>
	    <div class="col-sm-2"> <button onclick="clearFilters()" id="clearFilters">CLEAR FILTERS</button></div>
	</div>

	<br />
	<div id="alert_message"></div>
	<button type="button" name="edit" id="edit" class="btn btn-info">EDIT TABLE DATA</button>
	<input type="hidden" value="<?= base64_encode($active_user_group_id) ?>" name="group_id">
	<table id="student_user_data" class="table table-bordered table-striped">
	    <div id="loader" class="loader" style="display:none;"></div>
	    <thead>
	        <tr>
	            <th>Status</th>
	            <th>First Name</th>
	            <th>Last Name</th>
	            <th>Total year of study</th>
	            <th>Current year of study</th>
	            <th>Year of passout</th>
	        </tr>
	    </thead>
	</table>
</div>

<script> 
	function clearFilters() {
	    jQuery('#clgcourses').val('');
	    jQuery('#yearpss').val('');
	    jQuery('#edus').val('');
	    jQuery('#status').val('');
	    var dataTable = jQuery('#student_user_data').DataTable();
	    dataTable.draw();
	}
</script>