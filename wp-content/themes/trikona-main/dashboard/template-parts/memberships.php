
<?php  if($_GET['memberships']=='true'){

   global $wpdb;
   $memberships_users  = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."pmpro_memberships_users WHERE user_id = $current_user->ID" );
 ?>

       <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8">
                    <h5 class="cs-label-1">Memberships Details</h5>
                    </div>
                    <div class="col-sm-4">
                       
                    </div>
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Plan Name</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Start Date</th>
                        <th style="width: 20%;">End Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                 <tbody>
                 	<?php foreach ($memberships_users as $key => $memberships_users_data) {

                   $memberships_level  = $wpdb->get_row( "SELECT name FROM ".$wpdb->prefix."pmpro_membership_levels WHERE id = $memberships_users_data->membership_id" );
                 		
                 	?>
                 	<tr> 
                    <td><?php echo $memberships_level->name;?></td>
                     <td><span class="plan<?php echo $memberships_users_data->status;?>"><?php echo $memberships_users_data->status;?></span></td>
                      <td>₹ <?php echo number_format($memberships_users_data->initial_payment,2);?></td>
                      <td><?php echo $memberships_users_data->startdate;?></td>
                       <td><?php echo $memberships_users_data->enddate;?></td>
                       <td>
                       	<?php if($memberships_users_data->status=='active'){ ?>

                                 <a class="btn plans btn-danger" target="_blank" href="https://www.constructionlearning.org/membership-cancel/">Cancel </a>
                            	<?php }?></td>

                 	</tr>
                 	<?php } ?>
                 </tbody>
        
              </table>
               <ul> <li><a class="btn plans btn-color-1" target="_blank" href="<?php echo home_url()?>/membership-details">Membership Plans </span></a></li></ul>
            </div>
      <?php //echo do_shortcode('[pmpro_account]'); ?>

<?php } ?>