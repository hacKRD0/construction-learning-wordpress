<?php
	global $trikona_obj;
	$active_user_group_id = get_query_var( 'active_user_group_id' );

    $filter = [
        'role' => $trikona_obj->professional_role,
        'meta_query' => [
            [
                'key'     => 'group_id',
                'value'   => $active_user_group_id,
                'compare' => '='
            ]
        ]
    ];

    $current_url = '';
    $staffs = $trikona_obj->getUsers($filter);
    switch_to_blog($trikona_obj->directories_site_blog_id);
    $ajax_url = admin_url( 'admin-ajax.php' );
    $current_url = home_url()."/manage-group-dashboard";
    $params = [];
    if (isset($_GET['groupId'])){
        $params['groupId'] = $_GET['groupId'];
    }
    if (isset($_GET['groupType'])){
        $params['groupType'] = $_GET['groupType'];
    }
    if (isset($_GET['tab'])){
        $params['tab'] = $_GET['tab'];
    }

    if (!empty($params)) {
        $current_url .= "?";
        $i = 0;
        foreach ($params as $key => $value) {
            if ($i == 0) {
                $current_url .= $key."=".$value."";
            } else {
                $current_url .= "&".$key."=".$value."";
            }
            $i++;
        }
    }
    restore_current_blog();

    $departments         = $trikona_obj->getDepartments();
    $designations        = $trikona_obj->getDesignations();
    $publication_types   = $trikona_obj->getPubicationTypes();

    $staff_details = [];
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $current_staff_id = base64_decode($_GET['id']);
        $staff_details = get_user_by( 'ID', $current_staff_id);
    }
?>
<style>
    .stepwizard { display:flex; justify-content:space-between; margin-bottom:50px; position:relative; }
    .stepwizard::before { content:""; position:absolute; top:2rem; left:0; width:100%; height:3px; background:#ccc; }
    .progress-line { position:absolute; top:2rem; left:0; height:3px; background:#ffc000; width:0; }
    .stepwizard-step { /*text-align:center;*/ position:relative; flex:1; }
    .stepwizard-step p { margin-top:10px; font-weight:500; }
    .btn-circle {
      width:50px; height:50px; display:flex; align-items:center; justify-content:center;
      border-radius:50%; font-weight:600; color:#fff; transition: all 0.5s ease; position:relative;
    }
    .btn-primary { background-color:#1b3b4c !important; border-color:#1b3b4c !important; }
    .completed .btn-circle {
      background-color:#ffc000 !important; border-color:#ffc000 !important; color:#fff !important;
      animation: completedBounce 1s ease-in-out infinite alternate, completedGlow 1.5s ease-in-out infinite alternate;
    }
    .btn-light { background-color:#e9ecef !important; border-color:#ccc !important; color:#6c757d !important; }
    label { color:#1b3b4c; font-weight:500; }
    .btn-secondary { background-color:#ffc000 !important; border-color:#ffc000 !important; color:#1b3b4c !important; }
    .btn-success { background-color:#28a745 !important; border-color:#28a745 !important; }

    /* Glowing pulse animation for active step */
    @keyframes glowPulse { 0% { box-shadow: 0 0 5px #ffc000; } 50% { box-shadow: 0 0 15px #ffc000; } 100% { box-shadow: 0 0 5px #ffc000; } }
    .pulsing { animation: glowPulse 1.5s infinite ease-in-out; }

    /* Subtle bounce and glow for completed steps */
    @keyframes completedBounce { 0% { transform: scale(1); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }
    @keyframes completedGlow { 0% { box-shadow: 0 0 5px #ffc000; } 50% { box-shadow: 0 0 10px #ffc000; } 100% { box-shadow: 0 0 5px #ffc000; } }
    input[type=file] {
        display: block;
    }
    .select2-container {
        width: 100% !important;
    }
    .select2.select2-container .select2-selection {
        height: 2.5rem !important;
    }
    .select2.select2-container .select2-selection .select2-selection__arrow {
        height: 2.4rem !important;
    }
</style>
<div class="col-md-12 alert alert-danger manger-staff-error d-none"></div>
<div class="col-md-12 alert alert-success manger-staff-success d-none"></div>
<div class="table-wrapper staff-table-container <?php if(!empty($staff_details)){ echo "d-none"; } ?>">
    <div class="table-title">
        <div class="row">
            <div class="col-sm-8 pr-0 cs-label-1">
                <h5>Staff Details</h5>
            </div>
            <div class="col-sm-4 cs-label-1">
                <button type="button" class="btn btn-info add-new-staff add-new"><i class="fa fa-plus"></i> Add New</button>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Gender</th>
                <th>DOB</th>
                <th>Contact Number</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($staffs)){ ?>
                <?php foreach ($staffs as $staff) { ?>
                    <tr>
                        <th><?= get_user_meta($staff->ID, 'first_name', true)." ".get_user_meta($staff->ID, 'first_name', true) ?></th>
                        <th><?= get_user_meta($staff->ID, 'gender', true) ?></th>
                        <th><?= date('d-M-Y', strtotime(get_user_meta($staff->ID, 'dob', true))) ?></th>
                        <th><?= get_user_meta($staff->ID, 'billing_phone', true) ?></th>
                        <th>
                            <?php
                                $department_id = get_user_meta($staff->ID, 'department', true);
                                switch_to_blog($trikona_obj->directories_site_blog_id);
                                echo get_the_title($department_id);
                                restore_current_blog();
                            ?>
                        </th>
                        <th>
                            <?php
                                $edit_url = '';
                                if (strpos($current_url, "?") !== false) {
                                    $edit_url = $current_url."&id=".base64_encode($staff->ID);
                                } else {
                                    $edit_url = $current_url."?id=".base64_encode($staff->ID);
                                }
                            ?>
                            <a href="<?= $edit_url ?>" class="editBtn edit d-none"><i class="fa-solid fa-pen-to-square"></i></a>
                        </th>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <th class="text-center" colspan="6">No staff data found.</th>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div class="container mt-5 <?php if(!empty($staff_details)){ echo "d-none"; } ?> staff-form-container">
    <div class="stepwizard">
        <div class="progress-line"></div>
        <div class="stepwizard-step" data-step="1">
            <button type="button" class="btn btn-primary btn-circle">0%</button>
            <p>Personal Info</p>
        </div>
        <div class="stepwizard-step" data-step="2">
            <button type="button" class="btn btn-light btn-circle">2</button>
            <p>Identification</p>
        </div>
        <div class="stepwizard-step" data-step="3">
            <button type="button" class="btn btn-light btn-circle">3</button>
            <p>Job Details</p>
        </div>
        <div class="stepwizard-step" data-step="4">
            <button type="button" class="btn btn-light btn-circle">4</button>
            <p>Emergency Contact</p>
        </div>
        <div class="stepwizard-step" data-step="5">
            <button type="button" class="btn btn-light btn-circle">5</button>
            <p>Additional Info</p>
        </div>
    </div>
    <form id="staffForm" novalidate enctype="multipart/form-data" method="POST">
        <input type="hidden" name="action" value="manage_group_staff_data">
        <input type="hidden" name="group_id" value="<?=  base64_encode($active_user_group_id) ?>">
        <!-- Step 1 -->
        <div class="setup-content" id="step-1">
            <h4 style="color:#1b3b4c;">Personal Information</h4>
            <div class="form-group"><label>First Name</label><input type="text" class="form-control" name="first_name" required></div>
            <div class="form-group"><label>Last Name</label><input type="text" class="form-control" name="last_name" required></div>
            <div class="form-group"><label>User name</label><input type="text" class="form-control" name="user_name" required></div>
            <div class="form-group">
                <label>Gender</label>
                <select class="form-control" name="gender" required>
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group"><label>Date of Birth</label><input type="date" class="form-control" name="dob" required></div>
            <div class="form-group"><label>Contact Number</label><input type="text" class="form-control" name="contact" required></div>
            <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email_address" required></div>
            <button class="btn btn-secondary cancel-staff-add" type="button">Cancel</button>
            <button class="btn btn-primary nextBtn float-right" type="button">Next</button>
        </div>
        <!-- Step 2 -->
        <div class="setup-content d-none" id="step-2">
            <h4 style="color:#1b3b4c;">Identification & Documents</h4>
            <div class="form-group"><label>National ID / Passport No.</label><input type="text" class="form-control" name="national_id" required></div>
            <div class="form-group"><label>Upload Photo</label><input type="file" class="form-control" name="photo" required></div>
            <button class="btn btn-secondary prevBtn" type="button">Previous</button>
            <button class="btn btn-primary cancel-staff-add" type="button">Cancel</button>
            <button class="btn btn-primary nextBtn float-right" type="button">Next</button>
        </div>
        <!-- Step 3 -->
        <div class="setup-content d-none" id="step-3">
            <h4 style="color:#1b3b4c;">Job Details</h4>
            <div class="form-group">
                <label>Department</label>
                <select class="form-control select2-element" name="department" required>
                    <option value="">Select</option>
                    <?php if(!empty($departments)){ ?>
                        <?php foreach ($departments as $department) { ?>
                            <option value="<?= $department->ID ?>"><?= $department->post_title ?></option>
                        <?php } ?>        
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Designation</label>
                <select class="form-control select2-element" name="designation" required>
                    <option value="">Select</option>
                    <?php if(!empty($designations)){ ?>
                        <?php foreach ($designations as $designation) { ?>
                            <option value="<?= $designation->ID ?>"><?= $designation->post_title ?></option>
                        <?php } ?>        
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Publication type</label>
                <select class="form-control select2-element" name="publication_type" required>
                    <option value="">Select</option>
                    <?php if(!empty($publication_types)){ ?>
                        <?php foreach ($publication_types as $publication_type) { ?>
                            <option value="<?= $publication_type->ID ?>"><?= $publication_type->post_title ?></option>
                        <?php } ?>        
                    <?php } ?>
                </select>
            </div>
            <button class="btn btn-secondary prevBtn" type="button">Previous</button>
            <button class="btn btn-primary cancel-staff-add" type="button">Cancel</button>
            <button class="btn btn-primary nextBtn float-right" type="button">Next</button>
        </div>
        <!-- Step 4 -->
        <div class="setup-content d-none" id="step-4">
            <h4 style="color:#1b3b4c;">Emergency Contact</h4>
            <div class="form-group"><label>Contact Person</label><input type="text" class="form-control" name="emergency_name" required></div>
            <div class="form-group"><label>Relationship</label><input type="text" class="form-control" name="relationship" required></div>
            <div class="form-group"><label>Phone</label><input type="text" class="form-control" name="emergency_phone" required></div>
            <button class="btn btn-secondary prevBtn" type="button">Previous</button>
            <button class="btn btn-primary cancel-staff-add" type="button">Cancel</button>
            <button class="btn btn-primary nextBtn float-right" type="button">Next</button>
        </div>
        <!-- Step 5 -->
        <div class="setup-content d-none" id="step-5">
            <h4 style="color:#1b3b4c;">Additional Information</h4>
            <div class="form-group"><label>Education</label><input type="text" class="form-control" name="education" required></div>
            <div class="form-group"><label>Skills</label><input type="text" class="form-control" name="skills" required></div>
            <button class="btn btn-secondary prevBtn" type="button">Previous</button>
            <button class="btn btn-primary cancel-staff-add" type="button">Cancel</button>
            <button class="btn btn-success float-right" type="submit">Submit</button>
        </div>
    </form>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    jQuery(document).ready(function () {
        var allWells = jQuery('.setup-content');
        var steps = jQuery('.stepwizard-step');

        jQuery('.select2-element').select2();

        function easeOutQuad(t) {
            return t * (2 - t);
        }

        function animateNumberAndLine(el, start, end, lineEl, duration) {
            var startTime = null;
            el.addClass('pulsing');

            function animate(timestamp) {
                if (!startTime) startTime = timestamp;
                var progress = (timestamp - startTime) / duration;
                if (progress > 1) progress = 1;
                var easedProgress = easeOutQuad(progress);
                var current = start + (end - start) * easedProgress;
                el.text(Math.round(current) + '%');
                lineEl.css('width', current + '%');
                if (progress < 1) requestAnimationFrame(animate);
                else el.removeClass('pulsing');
            }
            requestAnimationFrame(animate);
        }

        function setStep(step) {
            var totalSteps = steps.length;
            steps.find('button').removeClass('btn-primary').addClass('btn-light');
            steps.removeClass('completed');
            steps.each(function (index) {
                var s = index + 1;
                var btn = jQuery(this).find('button');
                if (s < step) {
                    jQuery(this).addClass('completed');
                    btn.text(Math.round((s - 1) / (totalSteps - 1) * 100) + '%');
                } else btn.text(s);
            });
            var currentStep = steps.eq(step - 1);
            var currentBtn = currentStep.find('button');
            currentBtn.removeClass('btn-light').addClass('btn-primary');

            var prevPercent = step === 1 ? 0 : Math.round((step - 2) / (totalSteps - 1) * 100);
            var currPercent = Math.round((step - 1) / (totalSteps - 1) * 100);

            animateNumberAndLine(currentBtn, prevPercent, currPercent, jQuery('.progress-line'), 1000);

            allWells.addClass('d-none');
            jQuery('#step-' + step).removeClass('d-none');
        }

        jQuery('.nextBtn').click(function () {
            var curStep = jQuery(this).closest(".setup-content");
            var inputs = curStep.find("input,select");
            var valid = true;
            inputs.each(function () {
                if (!this.checkValidity()) {
                    jQuery(this).addClass("is-invalid");
                    valid = false;
                } else jQuery(this).removeClass("is-invalid");
            });
            if (valid) {
                setStep(parseInt(curStep.attr("id").split("-")[1]) + 1);
            }
        });

        jQuery('.prevBtn').click(function () {
            var curStep = jQuery(this).closest(".setup-content");
            setStep(parseInt(curStep.attr("id").split("-")[1]) - 1);
        });

        setStep(1);

        jQuery('.add-new-staff').click(function(){
            jQuery('.staff-table-container').addClass('d-none');
            jQuery('.staff-form-container').removeClass('d-none');
        });

        jQuery('.cancel-staff-add').click(function(){
            jQuery('.staff-table-container').removeClass('d-none');
            jQuery('.staff-form-container').addClass('d-none');

            jQuery('.stepwizard-step').removeClass('completed');
            jQuery('.setup-content').addClass('d-none');
            jQuery('#step-1').removeClass('d-none');
        });

        jQuery('#staffForm').submit(function(e){
            if (this.checkValidity() === false) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
            e.preventDefault();

            var formData = new FormData(this);
            jQuery('.loader').removeClass('d-none');

            jQuery.ajax({
                type: "POST",
                url : '<?= $ajax_url ?>',
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function(response){
                    if (response.success) {
                        jQuery('.manger-staff-success').html(response.message);
                        jQuery('.manger-staff-success').removeClass('d-none');
                        jQuery('.loader').addClass('d-none');
                        if (response.errors) {
                            jQuery('.manger-staff-error').html(response.errors);
                            jQuery('.manger-staff-error').removeClass('d-none');
                        }
                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                    } else {
                        jQuery('.manger-staff-error').html(response.message);
                        jQuery('.manger-staff-error').removeClass('d-none');
                        jQuery('.loader').addClass('d-none');
                        setTimeout(function() {
                            jQuery('.manger-staff-error').addClass('d-none');
                        }, 2500);
                    }
                }
            });
        })
    });
</script>