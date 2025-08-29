<?php if($_GET['updateExpereince']=='true'){ ?>
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/icon?family=Material+Icons">
 <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8 pr-0">
                    <h5 class="cs-label-1">Expereince Details</h5>
                    </div>
                    <div class="col-sm-4 cs-label-1">
                        <button type="button" class="btn btn-info add-new" data-id="<?= base64_encode($current_user->ID) ?>"><i class="fa fa-plus"></i> Add New</button>
                    </div>
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>From(dd/mm/yy)</th>
                       <th>To(dd/mm/yy)</th>
                        <th>Organization</th>
                        <th>Position</th>
                        <th>Experience</th>
                         <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                    <tbody id="userData">

                   <?php 
        // Load and initialize database class 

 
        // Get members data from database 

        $members_experience = $wpdb->get_results("SELECT * from ".$wpdb->prefix."user_expriances where user_id='$current_user->ID' ORDER By id DESC");

        if($wpdb->num_rows > 0){ 
        	$rowid= 1;
           foreach ($members_experience as $key => $experiences) {   
        ?>
            <tr id="<?php echo $experiences->id; ?>">
                
                <td>
                    <span class="editSpan first_name"><?php echo $experiences->fromdate; ?></span>
                    <input class="form-control editInput formdate StartDate" type="text" name="formdate" value="<?php echo  $experiences->fromdate; ?>" style="display: none;">
                </td>
                <td>
                    <span class="editSpan last_name"><?php echo  $experiences->todate; ?></span>
                    <input class="form-control editInput todate StartDate" type="text" name="todate" value="<?php echo $experiences->todate; ?>" style="display: none;">
                </td>
                <td>
                    <span class="editSpan email"><?php echo $experiences->organization;  ?></span>
                    <input class="form-control editInput Organization" type="text" name="email" value="<?php echo $experiences->organization; ?>" style="display: none;">
                </td>
                <td>
                    <span class="editSpan "><?php echo $experiences->position; ?></span>
                    <input class="form-control editInput position" type="text" name="position" value="<?php echo $experiences->position; ?>" style="display: none;">
                </td>
                <td>
                    <span class="editSpan "><?php echo $experiences->experience; ?></span>
                    <select class="form-control editInput " name="experience" style="display: none;">
                       <option value="0-1">0-1</option>
					            <option value="1-5">1-5</option>
					            <option value="5-10">5-10</option>
					            <option value="15-20">15-20</option>
					            <option value="10-15">10-15</option>
					            <option value="20+">20+</option>
                    </select>
                </td>
                <td>
                    <span class="editSpan email"><?php echo $experiences->responsibility; ?></span>
                    <input class="form-control editInput responsibility" type="text" name="responsibility" value="<?php echo $experiences->responsibility; ?>" style="display: none;">
                </td>
                <td class="d-flex">
                    <a href="javascript:void(0);" class="editBtn edit"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="javascript:void(0);" class="deleteBtn"><i class="fa-solid fa-circle-xmark"></i></a>
                    
                    <button type="button" class="btn btn-success saveBtn" style="display: none;"><i class="fa-solid fa-circle-check"></i></button>
                    <button type="button" class="btn btn-danger confirmBtn" style="display: none;">Confirm</button>
                    <button type="button" class="btn btn-secondary cancelBtn" style="display: none;"><i class="fa-solid fa-circle-xmark"></i></button>
                </td>
            </tr>
        <?php 
            $rowid ++; } 
        }else{ 
           echo '<tr><td colspan="7">No record(s) found...</td></tr>'; 
        } 
    ?>
                </tbody>
            </table>
        </div>

   <?php } ?>