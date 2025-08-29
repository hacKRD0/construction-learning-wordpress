<?php
    $obj = new Trikona();
    global $wpdb;

    $active_user_group_id = get_query_var( 'active_user_group_id' );

    $students = $obj->get_students_in_buddypress_group(['group_id' => $active_user_group_id]);
    $active_students = $obj->get_students_in_buddypress_group(['status' => 0]);

    $total_students = !empty($students) ? sizeof($students['data']) : 0;
    $total_active_students = !empty($active_students) ? sizeof($active_students['data']) : 0;

?>
<div class="row">
    <div class="col-lg-4 col-sm-6">
        <div class="circle-tile ">
            <a href="#">
                <div class="circle-tile-heading" style="background-color: var(--cscolor1)">
                    <i class="fas fa-university fa-fw fa-3x"></i>
                </div>
            </a>
            <div class="circle-tile-content" style="background-color: var(--cscolor1)">
                <div class="circle-tile-description text-faded">Total Students</div>
                <div class="circle-tile-number text-faded "><?= $total_students ?></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-sm-6">
        <div class="circle-tile ">
            <a href="#">
                <div class="circle-tile-heading" style="background-color: var(--cscolor2)">
                    <i class="fas fa-graduation-cap fa-fw fa-3x"></i>
                </div>
            </a>
            <div class="circle-tile-content" style="background-color: var(--cscolor2)">
                <div class="circle-tile-description text-faded"> Active Students </div>
                <div class="circle-tile-number text-faded "><?= $total_active_students ?></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-6">
        <div class="circle-tile ">
            <a href="#">
                <div class="circle-tile-heading" style="background-color: var(--cscolor3)">
                    <i class="fas fa-money-bill-alt fa-fw fa-3x"></i>
                </div>
            </a>
            <div class="circle-tile-content" style="background-color: var(--cscolor3)">
                <div class="circle-tile-description text-faded"> My Credit Balance</div>
                <div class="circle-tile-number text-faded ">
                    <?= do_shortcode('[mycred_my_balance type=trikona_credit]');?>        
                </div>
            </div>
        </div>
    </div>
</div>