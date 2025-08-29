<?php  if($_GET['myaccout']=='true'){ ?>
<?php 
	$active_user_id = get_current_user_id();

	if (isset($_GET['id'])) {
	    $active_user_id = base64_decode($_GET['id']);
	}
    $billing_first_name =get_user_meta( $active_user_id, 'billing_first_name', true );
    $billing_last_name = get_user_meta( $active_user_id, 'billing_last_name',true );
    $billing_company = get_user_meta( $active_user_id, 'billing_company',true );
    $billing_country = get_user_meta( $active_user_id, 'billing_country', true);
    $billing_address_1 = get_user_meta( $active_user_id, 'billing_address_1', true );
    $billing_address_2 =  get_user_meta( $active_user_id, 'billing_address_2', true );
    $billing_city =   get_user_meta( $active_user_id, 'billing_city', true );
    $billing_postcode = get_user_meta( $active_user_id, 'billing_postcode', true );
    $billing_phone = get_user_meta( $active_user_id, 'billing_phone', true );
    $billing_email = get_user_meta( $active_user_id, 'billing_email', true );
    $billing_state = get_user_meta( $active_user_id, 'billing_state', true );
            $user_id = $active_user_id;
                  $customer_orders = get_posts( array(
                       'meta_key'    => '_customer_user',
                       'meta_value'  => $user_id,
                       'post_type'   => 'wc-orders',
                       'post_status' => array_keys( wc_get_order_statuses() ),
                       'numberposts' => -1
                   ));
    
        ?>
<ul class="nav nav-tabs my-orders">
    <li class="active"><a data-toggle="tab" href="#tab1">Orders</a></li>
    <li><a data-toggle="tab" href="#tab2">Billing Address</a></li>
    <li><a data-toggle="tab" href="#tab3">Update Password</a></li>
</ul>
<div class="tab-content">
    <div id="tab1" class="tab-pane  in active">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="cs-label-1">Order Details</h5>
                    </div>
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customer_orders as $key => $orders) {
                        $order = wc_get_order( $orders->ID );
                            $currency      = $order->get_currency();
                            $order_data = $order->get_data();
                        ?>
                    <tr>
                        <td><a target="_blank" href="<?php home_url();?>/my-account/view-order/<?php echo $orders->ID?>/"><?php echo $orders->ID;?></a></td>
                        <td><?php echo $order_data['date_created']->date('M-d-Y');?></td>
                        <td><?php echo $order->get_status();?></td>
                        <td><?php echo '₹'.$order_data['total'];?></td>
                        <td><a target="_blank" href="<?php home_url();?>/my-account/view-order/<?php echo $orders->ID?>/" class="woocommerce-button wp-element-button button view">View</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="tab2" class="tab-pane ">
    	<div class="table-wrapper">
	        <h3>Billing address</h3>
	        <p>
	        <div class="col-lg-12 well">
	            <div class="row">
	                <div class="col-sm-12">
	                    <form class="form"  id="updateBillingAddress" name="updateBillingAddress" method="POST">
	                        <div class="row">
	                            <div class="col-sm-6 form-group">
	                                <label>First Name *</label>
	                                <input name="billing_first_name" type="text"  class="form-control required" value="<?php echo  $billing_first_name; ?>">
	                            </div>
	                            <div class="col-sm-6 form-group">
	                                <label>Last Name *</label>
	                                <input name="billing_last_name" type="text" class="form-control required" value="<?php echo  $billing_last_name;?>">
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-sm-6 form-group">
	                                <label>Company name (optional)</label>
	                                <input name="billing_company" type="text" class="form-control required" value="<?php echo  $billing_company;?>">
	                            </div>
	                            <div class="col-sm-6 form-group">
	                                <label>Country / Region *</label>
	                                <select name="billing_country" class="form-control required">
	                                    <option value="">Select Country</option>
	                                    <option value="IN" <?php if($billing_country == 'IN'): ?> selected="selected"<?php endif; ?>>India</option>
	                                </select>
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-sm-4 form-group">
	                                <label>Street address *</label>
	                                <input type="text" placeholder="House number and street name" class="form-control required"  name="billing_address_1"  value="<?php echo $billing_address_1;?>">
	                            </div>
	                            <div class="col-sm-4 form-group">
	                                <label>Street address *</label>
	                                <input type="text" placeholder="Apartment, suite, unit, etc. (optional)" class="form-control required"  name="billing_address_2"  value="<?php echo $billing_address_2;?>">
	                            </div>
	                            <div class="col-sm-4 form-group">
	                                <label>State *</label>
	                                <select name="billing_state" id="billing_state" class="form-control required" >
	                                    <option value="">Select State</option>
	                                    <option value="AP" <?php if($billing_state == 'AP'): ?> selected="selected"<?php endif; ?>>Andhra Pradesh</option>
	                                    <option value="AR" <?php if($billing_state == 'AR'): ?> selected="selected"<?php endif; ?>>Arunachal Pradesh</option>
	                                    <option value="AS" <?php if($billing_state == 'AS'): ?> selected="selected"<?php endif; ?>>Assam</option>
	                                    <option value="BR" <?php if($billing_state == 'BR'): ?> selected="selected"<?php endif; ?>>Bihar</option>
	                                    <option value="CT" <?php if($billing_state == 'CT'): ?> selected="selected"<?php endif; ?>>Chhattisgarh</option>
	                                    <option value="GA" <?php if($billing_state == 'GA'): ?> selected="selected"<?php endif; ?>>Goa</option>
	                                    <option value="GJ" <?php if($billing_state == 'GJ'): ?> selected="selected"<?php endif; ?>>Gujarat</option>
	                                    <option value="HR" <?php if($billing_state == 'HR'): ?> selected="selected"<?php endif; ?>>Haryana</option>
	                                    <option value="HP" <?php if($billing_state == 'HP'): ?> selected="selected"<?php endif; ?>>Himachal Pradesh</option>
	                                    <option value="JK" <?php if($billing_state == 'JK'): ?> selected="selected"<?php endif; ?>>Jammu and Kashmir</option>
	                                    <option value="JH" <?php if($billing_state == 'JH'): ?> selected="selected"<?php endif; ?>>Jharkhand</option>
	                                    <option value="KA" <?php if($billing_state == 'KA'): ?> selected="selected"<?php endif; ?>>Karnataka</option>
	                                    <option value="KL" <?php if($billing_state == 'KL'): ?> selected="selected"<?php endif; ?>>Kerala</option>
	                                    <option value="LA" <?php if($billing_state == 'LA'): ?> selected="selected"<?php endif; ?>>Ladakh</option>
	                                    <option value="MP" <?php if($billing_state == 'MP'): ?> selected="selected"<?php endif; ?>>Madhya Pradesh</option>
	                                    <option value="MH" <?php if($billing_state == 'MH'): ?> selected="selected"<?php endif; ?>>Maharashtra</option>
	                                    <option value="MN" <?php if($billing_state == 'MN'): ?> selected="selected"<?php endif; ?>>Manipur</option>
	                                    <option value="ML" <?php if($billing_state == 'ML'): ?> selected="selected"<?php endif; ?>>Meghalaya</option>
	                                    <option value="MZ" <?php if($billing_state == 'MZ'): ?> selected="selected"<?php endif; ?>>Mizoram</option>
	                                    <option value="NL" <?php if($billing_state == 'NL'): ?> selected="selected"<?php endif; ?>>Nagaland</option>
	                                    <option value="OR" <?php if($billing_state == 'OR'): ?> selected="selected"<?php endif; ?>>Odisha</option>
	                                    <option value="PB" <?php if($billing_state == 'PB'): ?> selected="selected"<?php endif; ?>>Punjab</option>
	                                    <option value="RJ" <?php if($billing_state == 'RJ'): ?> selected="selected"<?php endif; ?>>Rajasthan</option>
	                                    <option value="SK" <?php if($billing_state == 'SK'): ?> selected="selected"<?php endif; ?>>Sikkim</option>
	                                    <option value="TN" <?php if($billing_state == 'TN'): ?> selected="selected"<?php endif; ?>>Tamil Nadu</option>
	                                    <option value="TS" <?php if($billing_state == 'TS'): ?> selected="selected"<?php endif; ?>>Telangana</option>
	                                    <option value="TR" <?php if($billing_state == 'TR'): ?> selected="selected"<?php endif; ?>>Tripura</option>
	                                    <option value="UK" <?php if($billing_state == 'UK'): ?> selected="selected"<?php endif; ?>>Uttarakhand</option>
	                                    <option value="UP" <?php if($billing_state == 'UP'): ?> selected="selected"<?php endif; ?>>Uttar Pradesh</option>
	                                    <option value="WB" <?php if($billing_state == 'WB'): ?> selected="selected"<?php endif; ?>>West Bengal</option>
	                                    <option value="AN" <?php if($billing_state == 'AN'): ?> selected="selected"<?php endif; ?>>Andaman and Nicobar Islands</option>
	                                    <option value="CH" <?php if($billing_state == 'CH'): ?> selected="selected"<?php endif; ?>>Chandigarh</option>
	                                    <option value="DN" <?php if($billing_state == 'DN'): ?> selected="selected"<?php endif; ?>>Dadra and Nagar Haveli</option>
	                                    <option value="DD" <?php if($billing_state == 'DD'): ?> selected="selected"<?php endif; ?>>Daman and Diu</option>
	                                    <option value="DL" <?php if($billing_state == 'DL'): ?> selected="selected"<?php endif; ?>>Delhi</option>
	                                    <option value="LD" <?php if($billing_state == 'LD'): ?> selected="selected"<?php endif; ?>>Lakshadeep</option>
	                                    <option value="PY" <?php if($billing_state == 'PY'): ?> selected="selected"<?php endif; ?>>Pondicherry (Puducherry)</option>
	                                </select>
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-sm-6 form-group">
	                                <label>Town / City *</label>
	                                <input type="text" name="billing_city" class="form-control required " value="<?php echo  $billing_city;?>">
	                            </div>
	                            <div class="col-sm-6 form-group">
	                                <label>PIN Code *</label>
	                                <input name="billing_postcode" type="text" class="form-control required " value="<?php echo  $billing_postcode;?>">
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-sm-6 form-group">
	                                <label>Email Address *</label>
	                                <input type="text" name="billing_email" class="form-control required email" value="<?php echo  $billing_email;?>">
	                            </div>
	                            <div class="col-sm-6 form-group">
	                                <label>Phone *</label>
	                                <input name="billing_phone" type="text"  class="form-control required " value="<?php echo  $billing_phone;?>">
	                            </div>
	                        </div>
	                        <div style="display:none;" class="alert alert-success" id="updateproInfo1" role="alert">Your address has been Updated successfully.</div>
	                        <button type="submit" class="btn btn-lg btn-info">Update Address</button>				
	                    </form>
	                </div>
	            </div>
	        </div>
	        </p>
	    </div>
    </div>
    <div id="tab3" class="tab-pane">
    	<div class="table-wrapper">
	        <div class="change-password-messages">
	            <p>You must change your password before you can access any part of our website.</p>
	        </div>
	        <div class = "change-password-form">
	            <div class="row" >
	                <div class="col-sm-8 form-group">
	                    <label for="password"><?php _e('New Password'); ?></label>
	                    <input name="password" type="password" id="password" class="form-control password1" value="">
	                    <div id="password-strength-status"></div>
	                </div>
	                <div class="col-sm-8 form-group">
	                    <label for="password2"><?php _e('Re-enter New Password'); ?></label>
	                    <input name="password2" type="password" id="password2"  class="form-control password2" value="">
	                </div>
	            </div>
	            <div class="row">
	                <div class="col-sm-6 form-group">
	                    <input type="submit" name="change-password" value="<?php _e('Change Password'); ?>" class="btn-change-pass" /> 
	                </div>
	            </div>
	        </div>
	    </div>
    </div>
</div>
<?php } ?>