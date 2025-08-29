  <?php if(in_array('student', $userRolesChk)){?>                                   
  <div class="row">
    <div class="col-lg-3 col-sm-6">
      <div class="circle-tile ">
        <a href="#"><div class="circle-tile-heading" style="background-color: var(--cscolor1)"><i class="fa fa-university fa-fw fa-3x"></i></div></a>
        <div class="circle-tile-content" style="background-color: var(--cscolor1)">
          <div class="circle-tile-description text-faded"> Year Of Passout</div>
          <div class="circle-tile-number text-faded "><?php echo $yearpass;?></div>
          <!-- <a class="circle-tile-footer" href="#"><i class="fa fa-chevron-circle-right"></i></a> -->
        </div>
      </div>
    </div>
     
    <div class="col-lg-3 col-sm-6">
      <div class="circle-tile ">
        <a href="#"><div class="circle-tile-heading" style="background-color: var(--cscolor2)"><i class="fa fa-graduation-cap fa-fw fa-3x"></i></div></a>
        <div class="circle-tile-content" style="background-color: var(--cscolor2)">
          <div class="circle-tile-description text-faded"> Total year of study </div>
          <div class="circle-tile-number text-faded "><?php echo $crstd;?></div>
          <!-- <a class="circle-tile-footer" href="#"><i class="fa fa-chevron-circle-right"></i></a> -->
        </div>
      </div>
    </div> 
    <div class="col-lg-3 col-sm-6">
      <div class="circle-tile ">
        <a href="#"><div class="circle-tile-heading" style="background-color: var(--cscolor3)"><i class="fa fa-calendar fa-fw fa-3x"></i></div></a>
        <div class="circle-tile-content" style="background-color: var(--cscolor3)">
          <div class="circle-tile-description text-faded"> Current Year Of Study</div>
          <div class="circle-tile-number text-faded "><?php echo $toatlStudy;?></div>
          <!-- <a class="circle-tile-footer" href="#"><i class="fa fa-chevron-circle-right"></i></a> -->
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="circle-tile ">
        <a href="#"><div class="circle-tile-heading" style="background-color: var(--cscolor4)"><i class="fa fa-money-bill-alt fa-fw fa-3x"></i></i></div></a>
        <div class="circle-tile-content" style="background-color: var(--cscolor4)">
          <div class="circle-tile-description text-faded">My Credit Balance </div>
          <div class="circle-tile-number text-faded "><?php echo do_shortcode('[mycred_my_balance type=trikona_credit]');?></div>
          <!-- <a class="circle-tile-footer" href="#"><i class="fa fa-chevron-circle-right"></i></a> -->
        </div>
      </div>
    </div>
   <?php }else{
/*$coursesData  = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."bp_activity WHERE user_id   ='".$current_user->ID."' AND type='course_evaluated'" );*/
      global $jobs_obj;

      $course_count = $jobs_obj->getCourses($count_only = true);
   	?>
<div class="row">
    <div class="col-lg-4 col-sm-6">
      <div class="circle-tile ">
        <a href="#"><div class="circle-tile-heading" style="background-color: var(--cscolor1)"><i class="fa fa-university fa-fw fa-3x"></i></div></a>
        <div class="circle-tile-content" style="background-color: var(--cscolor1)">
          <div class="circle-tile-description text-faded"> Courses </div>
          <div class="circle-tile-number text-faded "><?= $course_count ?></div>
          <!-- <a class="circle-tile-footer" href="#"><i class="fa fa-chevron-circle-right"></i></a> -->
        </div>
      </div>
    </div>
     <?php $memberships_users  = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix."pmpro_memberships_users WHERE user_id = $current_user->ID AND status='active'" ); 
            $memberships_level  = $wpdb->get_row( "SELECT name FROM ".$wpdb->prefix."pmpro_membership_levels WHERE id = $memberships_users->membership_id" );

     ?>
    <div class="col-lg-4 col-sm-6">
      <div class="circle-tile ">
        <a href="#"><div class="circle-tile-heading" style="background-color: var(--cscolor2)"><i class="fa fa-graduation-cap fa-fw fa-3x"></i></div></a>
        <div class="circle-tile-content" style="background-color: var(--cscolor2)">
          <div class="circle-tile-description text-faded"> Membership  </div>
          <div class="circle-tile-number text-faded "><?php echo $memberships_level->name;?></div>
          <!-- <a class="circle-tile-footer" href="#"><i class="fa fa-chevron-circle-right"></i></a> -->
        </div>
      </div>
    </div> 
    <div class="col-lg-4 col-sm-6">
      <div class="circle-tile ">
        <a href="#"><div class="circle-tile-heading" style="background-color: var(--cscolor3)"><i class="fa fa-calendar fa-fw fa-3x"></i></div></a>
        <div class="circle-tile-content" style="background-color: var(--cscolor3)">
          <div class="circle-tile-description text-faded"> Assessments</div>
          <div class="circle-tile-number text-faded ">0</div>
          <!-- <a class="circle-tile-footer" href="#"><i class="fa fa-chevron-circle-right"></i></a> -->
        </div>
      </div>
    </div>
    
 <?php } ?>
  </div> 