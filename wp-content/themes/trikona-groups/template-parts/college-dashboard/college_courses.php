<?php
	global $trikona_obj, $status_messages;
	$courses = $trikona_obj->getStudentCourses();
	$active_user_group_id = get_query_var( 'active_user_group_id' );

	$group_id = isset($_GET['groupId']) ? base64_decode($_GET['groupId']) : "";
	restore_current_blog();
	if (empty($group_id) && !empty($active_user_group_id)) {
	    $group_id = $active_user_group_id;
	}

	switch_to_blog(1);

	if(isset($_POST['courses']) && !empty($_POST['courses'])){
	   	groups_update_groupmeta( $group_id, $trikona_obj->courses_meta, $_POST['courses']);
	   	$msg =true;
	}
	if($group_id > 0){
		$active_courses  = groups_get_groupmeta( $group_id, $trikona_obj->courses_meta ,true );
		restore_current_blog();
?>
		<div class="col-lg-12 p-0">
			<div class="row">
				<div class="col-sm-12">
					<?php if($msg==true){ ?>
						<div class="alert alert-success">
					        <strong>Success!</strong> Record Inserted successfully...
					    </div>
					<?php } ?>
					<div class="row">
					    <div class="col-md-12">
		    		    	<form class="form"  id="updateServices" name="updateServices" method="POST">
		    		            <div class="card">
		    	                    <div class="card-header" style="background-color:#1b3b4c;color: #fff;">Courses</div>            
		    	                    <!-- <ul class="list-group list-group-flush">  --> 
		    	                    <div class="row p-3">
		    	                    	<?php if(!empty($courses)){ ?>
		    	                    		<?php foreach ($courses as $course) {
		    	                    			$checked = "";
		                    		            if(!empty($active_courses) && in_array($course['ID'], $active_courses)){
		                    		                $checked = "checked";
		                    		            }else{
		                    		              	$checked = "";
		                    		            }
		    	                    		?>
		    	                    			<div class="col-md-4 pb-3">
		    	                    			   <?= $course['title'] ?>
		    	                    			    <label class="checkbox">
		    	                    			        <input <?= $checked;?> type="checkbox" name="courses[]" value="<?= $course['ID'] ?>" class="inputDisabled" disabled/><span class="warning"></span>
		    	                    			    </label>
		    	                    			</div>
		    	                    		<?php } ?>
		    	                    	<?php } else { ?>
		    	                    		<div class="col-sm-12 text-center">
		    	                    			<h4>No Courses Available yet.</h4>
		    	                    		</div>
		    	                    	<?php } ?>
		    	                    </div>

		    	                    <div class="col-md-12 text-right">
		    	                        <button type="submit" class="btn btn-lg btn-info inputDisabled d-none" disabled>Update</button>
		    	                        <button type="button" class="btn btn-lg btn-info removeDisable2">Edit</button>
		    	                    </div>
		    	                </div>
		    	            </form>
					    </div>
					</div>
				</div>
			</div>
		</div>

		<style>
			@keyframes check {
			    0% {
			        height: 0;
			        width: 0;
			    }

			    25% {
			        height: 0;
			        width: 10px;
			    }

			    50% {
			        height: 20px;
			        width: 10px;
			    }
			}

			.checkbox {
			    background-color: #fff;
			    display: inline-block;
			    height: 28px;
			    margin: 0 .25em;
			    width: 28px;
			    border-radius: 4px;
			    border: 1px solid #ccc;
			    float: right
			}

			.checkbox span {
			    display: block;
			    height: 28px;
			    position: relative;
			    width: 28px;
			    padding: 0
			}

			.checkbox span:after {
			    -moz-transform: scaleX(-1) rotate(135deg);
			    -ms-transform: scaleX(-1) rotate(135deg);
			    -webkit-transform: scaleX(-1) rotate(135deg);
			    transform: scaleX(-1) rotate(135deg);
			    -moz-transform-origin: left top;
			    -ms-transform-origin: left top;
			    -webkit-transform-origin: left top;
			    transform-origin: left top;
			    border-right: 4px solid #fff;
			    border-top: 4px solid #fff;
			    content: '';
			    display: block;
			    height: 20px;
			    left: 3px;
			    position: absolute;
			    top: 15px;
			    width: 10px
			}

			.checkbox span:hover:after {
			    border-color: #999
			}

			.checkbox input {
			    display: none
			}

			.checkbox input:checked+span:after {
			    -webkit-animation: check .8s;
			    -moz-animation: check .8s;
			    -o-animation: check .8s;
			    animation: check .8s;
			    border-color: #555
			}

			.checkbox input:checked+.default:after {
			    border-color: #444
			}

			.checkbox input:checked+.primary:after {
			    border-color: #2196F3
			}

			.checkbox input:checked+.success:after {
			    border-color: #8bc34a
			}

			.checkbox input:checked+.info:after {
			    border-color: #3de0f5
			}

			.checkbox input:checked+.warning:after {
			    border-color: #FFC107
			}

			.checkbox input:checked+.danger:after {
			    border-color: #f44336
			}
		</style>
<?php } else { ?>
	<div class="card user-card-full mt-4 text-center">
	    <div class="row m-l-0 m-r-0">
	        <div class="col-sm-12">
	            <div class="card-block">
	                <?= $status_messages->error[104] ?>
	            </div>
	        </div>
	    </div>
	</div>
<?php } ?>