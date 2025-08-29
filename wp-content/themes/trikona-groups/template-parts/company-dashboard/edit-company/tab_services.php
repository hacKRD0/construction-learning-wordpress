<?php
    $obj = new Trikona();

    global $wpdb;
    $msg =false;
    switch_to_blog(1);
    $current_user = wp_get_current_user();
    $active_user_group_id = get_query_var( 'active_user_group_id' );

    $service_tab_accessible = false;
    $current_user_plan = $obj->getLoggedInUserMembership($current_user->ID);

    if (!empty($current_user_plan)) {
        if ($current_user_plan->membership_id == $obj->corporate_basic_mem_id) {
            $service_tab_accessible = false;
        } else if ($current_user_plan->membership_id == $obj->corporate_prime_mem_id) {
            $service_tab_accessible = false;
        } else if ($current_user_plan->membership_id == $obj->corporate_elite_mem_id) {
            $service_tab_accessible = true;
        }
    }

    $allowed_roles = $obj->checkIsAccessibleForCurrentUser($current_user);

    if (!$service_tab_accessible && empty($allowed_roles)) { ?>
        <div class="card user-card-full mt-4 text-center">
            <div class="row m-l-0 m-r-0">
                <div class="col-sm-12">
                    <div class="card-block">
                        You are not authorized to access this page.
                    </div>
                </div>
            </div>
        </div>
    <?php } else {
        $group_id = isset($_GET['groupId']) ? base64_decode($_GET['groupId']) : "";
        restore_current_blog();
        if (empty($group_id) && !empty($active_user_group_id)) {
            $group_id = $active_user_group_id;
        }

        if(isset($_POST['services']) && $_POST['services']!=""){
           groups_update_groupmeta( $group_id, $obj->services_meta,$_POST['services']);
           $msg =true;
        }
        if($group_id > 0){
        $services  = groups_get_groupmeta( $group_id, $obj->services_meta ,true );

        $term_obj_list = get_the_terms($current_user->ID, 'bp_member_type' );
            $termArr = array();
                if(!empty($term_obj_list)){
               foreach ($term_obj_list as $key => $term_obj_value) {
                  $termArr[$term_obj_value->name]= $term_obj_value->name;
                    }
                  
                    unset($termArr['student']);
                    unset($termArr['professional']);
                     //$memberType = implode(', ', $termArr);
                  } 


        ?>

        <div class="col-lg-12 well">
        <div class="row">
            <div class="col-sm-12">
            	<?php if($msg==true){ ?>
            	<div class="alert alert-success">
                    <strong>Success!</strong> Record Inserted successfully...
                </div>
        <?php } 
            $term_list = wp_get_post_terms( $current_user->ID, 'bp_member_type', array( 'fields' => 'slugs' ) );
            $optionName =  get_post_meta($obj->service_post_id, "group_fileds_optionName", true);
            if (is_array($optionName))
                $all_items = array_chunk($optionName,3);
        ?>
        	<div class="row">
        	    <div class="col-md-12">
        	    	<form class="form"  id="updateServices" name="updateServices" method="POST">
        	            <div class="card" style="margin:50px 0">
                            <div class="card-header" style="background-color:#1b3b4c;color: #fff;">Select Company Services</div>            
                            <!-- <ul class="list-group list-group-flush">  --> 
                            <div class="row p-3">
                                <?php
                                    foreach ($optionName as $key => $value) { 
                                  	    $checked = "";
                                      	if(!empty($services)){
                    		              if(in_array($value,$services)){
                    		                   $checked = "checked";
                    		              }else{
                    		              	 $checked = "";
                    		              }
                		                }
            		                if( $value!=""){
                                ?>                  
                                    <!-- <li class="list-group-item"> -->
                                    <div class="col-md-4 pb-3">
                                       <?= $value;?>
                                        <label class="checkbox">
                                            <input <?= $checked;?> type="checkbox" name="services[]" value="<?= $value;?>" class="inputDisabled" disabled/><span class="warning"></span>
                                        </label>
                                    </div>
                                    <!-- </li> -->
                                <?php } } ?>
                            </div>
                            <!-- </ul> -->
                            <br/><br/>
                            <!-- <button type="submit" class="btn btn-lg btn-info">Update</button> -->
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-lg btn-info inputDisabled d-none" disabled>Update</button>
                                <button type="button" id="removeDisable2" class="btn btn-lg btn-info">Edit</button>
                            </div>
                        </form> 
                    </div> 
                    </div>
                    </div>

        	</div>
        </div></div>

        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <style> 

         @keyframes check {0% {height: 0;width: 0;}
            25% {height: 0;width: 10px;}
            50% {height: 20px;width: 10px;}
          }
          .checkbox{background-color:#fff;display:inline-block;height:28px;margin:0 .25em;width:28px;border-radius:4px;border:1px solid #ccc;float:right}
          .checkbox span{display:block;height:28px;position:relative;width:28px;padding:0}
          .checkbox span:after{-moz-transform:scaleX(-1) rotate(135deg);-ms-transform:scaleX(-1) rotate(135deg);-webkit-transform:scaleX(-1) rotate(135deg);transform:scaleX(-1) rotate(135deg);-moz-transform-origin:left top;-ms-transform-origin:left top;-webkit-transform-origin:left top;transform-origin:left top;border-right:4px solid #fff;border-top:4px solid #fff;content:'';display:block;height:20px;left:3px;position:absolute;top:15px;width:10px}
          .checkbox span:hover:after{border-color:#999}
          .checkbox input{display:none}
          .checkbox input:checked + span:after{-webkit-animation:check .8s;-moz-animation:check .8s;-o-animation:check .8s;animation:check .8s;border-color:#555}
        .checkbox input:checked + .default:after{border-color:#444}
        .checkbox input:checked + .primary:after{border-color:#2196F3}
        .checkbox input:checked + .success:after{border-color:#8bc34a}
        .checkbox input:checked + .info:after{border-color:#3de0f5}
        .checkbox input:checked + .warning:after{border-color:#FFC107}
        .checkbox input:checked + .danger:after{border-color:#f44336}

        </style>

        <?php } ?>
<?php } ?>