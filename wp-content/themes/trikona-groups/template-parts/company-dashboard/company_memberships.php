<?php
    $obj = new Trikona();

	global $wpdb;
    // Switch to the main site context (ID 1) in a multisite network
    switch_to_blog(1);

	$current_user = wp_get_current_user();
    $memberships_users = $obj->getMembershipPlans(['user_id' => $current_user->ID]);

    // Restore original blog context (important for multisite)
    restore_current_blog();
?>

<div class="table-wrapper">
    <div class="table-title">
        <div class="row">
            <div class="col-sm-8">
                <h2>Memberships <b>Details</b></h2>
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
                <th>End Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($memberships_users as $key => $memberships_users_data) {
         		$memberships_level  = $obj->getMembershipLevel($memberships_users_data->membership_id);
         	?>
            <tr>
                <td><?= $memberships_level->name; ?></td>
                <td><span
                        class="plan<?= $memberships_users_data->status; ?>"><?= $memberships_users_data->status; ?></span>
                </td>
                <td><?= $memberships_users_data->billing_amount; ?></td>
                <td><?= $memberships_users_data->startdate; ?></td>
                <td><?= $memberships_users_data->enddate; ?></td>
                	<td><?php if($memberships_users_data->status=='active'){ ?>

                         <a class="btn plans" target="_blank" href="<?= get_site_url(1) ?>/membership-cancel/">Cancel </a>
                    	<?php }?></td>
            </tr>
            <?php } ?>
        </tbody>

    </table>
    <ul>
        <li><a class="btn plans" target="_blank" href="<?= get_site_url(1) ?>/membership-details">Membership
                Plans </span></a></li>
    </ul>
</div>