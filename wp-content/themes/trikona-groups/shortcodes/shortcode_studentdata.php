<?php
	
	add_shortcode('student-data', 'student_data');

	function student_data(){
	    $group_id = bp_get_current_group_id();
	    global $wpdb;
	    $gropData  = $wpdb->get_results( "SELECT * FROM wpcw_user_profile WHERE group_id = $group_id" );

	    if($wpdb->num_rows > 0){
	    ob_start();
	   ?>
	    <div class="row">
	        <div class="col-lg-12" style="padding:0px">
	            <div class="main-box no-header clearfix">
	                <div class="main-box-body clearfix">
	                    <div class="table-responsive">
	                        <table class="table user-list">
	                            <thead>
	                            	 <tr>
							        	<th>Courses Of Study Offered</th>        	  
							            <th>No Of Years</th>  
							            <th>Batch Passout</th>         
							            <th>No Of Students</th>
	        						</tr>
		                           <?php   foreach ($gropData as $key => $gropData_profile) { 
							          	$years = $wpdb->get_results( "SELECT DISTINCT total_year_of_study FROM wpcw_user_profile_data WHERE courseid ='".$gropData_profile->course_id."' AND bp_group_id='".$gropData_profile->group_id."'  ");

							          	$batch = $wpdb->get_results( "SELECT DISTINCT year_of_passout FROM wpcw_user_profile_data WHERE courseid ='".$gropData_profile->course_id."' AND bp_group_id='".$gropData_profile->group_id."'  ");  	
							        ?>	
							        <tr>
							            <td><?php echo get_the_title($gropData_profile->course_id);?></td>
							            <td><?php echo $years[0]->total_year_of_study;?></td>
							            <td> 
							             <?php 
							               foreach ($batch as $key => $year_of_passout) {

							                  echo $year_of_passout->year_of_passout.'<br/>';
							               }
							             ?>
							            </td>
							            <td> 
							             <?php 
							               foreach ($batch as $key => $year_of_passout) {

							                 $year_of_passout_count = $wpdb->get_results( "SELECT  COUNT(year_of_passout) as count  FROM wpcw_user_profile_data WHERE courseid ='".$gropData_profile->course_id."' AND group_id='".$gropData_profile->group_id."' AND year_of_passout='".$year_of_passout->year_of_passout."' ");
							               foreach ($year_of_passout_count as $key => $value) { ?>
							               	<a href="<?php home_url();?>/student-directory/"> <?php echo $value->count;?></a><br>
							              <?php  }


							               }
							             ?>
							   
							            </td>
							        </tr>
	       <?php } ?>
	                            </thead>
	                            <tbody>
	                               
	                                
	                            </tbody>
	                        </table>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	<?php 
	    $output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
		}else{ ?>
	        <div class="row">
	            <div class="col-lg-12" style="padding:0px">
	            <p> No Student data</p>
	            </div>
	        </div>
	    <?php } ?>
	    <style>
	        .table a.table-link.danger {
	            color: #e74c3c;
	        }
	        .label {
	            border-radius: 3px;
	            font-size: 0.875em;
	            font-weight: 600;
	        }
	        .user-list tbody td .user-subhead {
	            font-size: 0.875em;
	            font-style: italic;
	        }
	        .user-list tbody td .user-link {
	            display: block;
	            font-size: 1.25em;
	            padding-top: 3px;
	            margin-left: 60px;
	        }
	        a {
	            color: #3498db;
	            outline: none!important;
	        }
	        .user-list tbody td>img {
	            position: relative;
	            max-width: 50px;
	            float: left;
	            margin-right: 15px;
	        }

	        .table thead tr th {
	            text-transform: uppercase;
	            font-size: 0.875em;
	        }
	        .table thead tr th {
	            border-bottom: 2px solid #e7ebee;
	        }
	        .table tbody tr td:first-child {
	            font-size: 1.125em;
	            font-weight: 300;
	        }
	        .table tbody tr td {
	            font-size: 0.875em;
	            vertical-align: middle;
	            border-top: 1px solid #e7ebee;
	            padding: 12px 8px;
	        }
	        a:hover{
	        text-decoration:none;
	        }
	    </style>
	<?php }

?>