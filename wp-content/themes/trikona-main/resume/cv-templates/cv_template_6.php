<?php 
	require_once('cv_data.php');

	$resume_file_name = $current_user->user_firstname." ".$current_user->user_lastname." resume.pdf";

	$address = '';
	$address_values = $wpdb->get_row("SELECT value FROM ".$wpdb->prefix."bp_xprofile_data AS pd INNER JOIN ".$wpdb->prefix."bp_xprofile_fields AS pf ON pd.field_id = pf.id WHERE pf.name = 'Address' AND pd.user_id=".$current_user->ID);
	if (!empty($address_values)) {
		$address = $address_values->value;
	}
	$memberDob = get_user_meta( $current_user->ID, 'memberDob', true );
	$gender = get_user_meta( $current_user->ID, 'gender', true );
?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script type="text/javascript">
	function downloadPDF() {
		var opt = {
			pagebreak: {
		        mode: ['avoid-all', 'css', 'legacy']
		    },
			filename: '<?php echo $resume_file_name; ?>',
			html2canvas: { scale: 2, backgroundColor: '#f8f9fa' },
		  	jsPDF: {format: 'A4', orientation: 'portrait' }
		};
		var element = document.querySelector(".resume-container");
		html2pdf().set(opt).from(element).save();
	}
</script>
<style>
    body {
        background-color: #eef1f5;
    }
    .resume-container {
        background: #fff;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden;
        margin: 30px auto;
        max-width: 1000px;
    }
    .sidebar {
        background: #0d6efd;
        color: white;
        padding: 30px;
    }
    .sidebar img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        margin-bottom: 20px;
    }
    .sidebar h2 {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .sidebar p {
        margin-bottom: 8px;
    }
    .main-content {
        padding: 30px;
    }
    .section-title {
        font-weight: bold;
        color: #0d6efd;
        border-bottom: 2px solid #0d6efd;
        margin-bottom: 15px;
        padding-bottom: 5px;
    }
    .progress {
        height: 15px;
    }
</style>
<div class="container-lg pb-3 pt-3">
	<div class="col-md-12 text-right">
		<button type="button" class="btn" onclick="downloadPDF()">Download Resume</button>
	</div>
</div>

<div class="resume-container row g-0">
    <!-- Sidebar -->
    <div class="col-md-4 sidebar text-center">
        <?= str_replace('width="150"', 'width="150" class="profile-pic"', $user_avatar)  ?>
        <h2><?= $current_user->user_firstname." ".$current_user->user_lastname ?></h2>
    	<?php if(!empty($address)){ ?>
    		<p><strong>Address:</strong> <?= $address ?></p>
		<?php } ?>
		<?php if(!empty($phone_nos)){ ?>
    		<p><strong>Phone:</strong> <?= implode(", ", $phone_nos) ?></p>
		<?php } ?>
    	<?php if(!empty($memberDob)){ ?>
    		<p><strong>Date of Birth:</strong> <?= $memberDob ?></p>
		<?php } ?>
    	<?php if(!empty($email)){ ?>
    		<p><strong>Email:</strong> <?= $email ?></p>
		<?php } ?>
    	<?php if(!empty($gender)){ ?>
    		<p><strong>Gender:</strong> <?= $gender ?></p>
		<?php } ?>
		<?php if(!empty($linkedinProfile)){ ?>
    		<p><strong>LinkedIn:</strong> <a href="<?= $linkedinProfile ?>" target="_blank" class="text-white"><?= $linkedinProfile ?></a></p>
		<?php } ?>
    </div>
    <!-- Main Content -->
    <div class="col-md-8 main-content">
    	<?php if (!empty($member_bio)) { ?>
	        <!-- About Me -->
	        <div class="mb-4">
	            <h4 class="section-title">About Me</h4>
	            <p><?= $member_bio ?></p>
	        </div>
	    <?php } ?>

	    <?php if (!empty($members_experience)) { ?>
	        <!-- Experience -->
	        <div class="mb-4">
	            <h4 class="section-title">Work & Experience</h4>
	            <?php 
	            	foreach ($members_experience as $key => $experiences) {
	            		$date = [];
	            		if (!empty($experiences->fromdate))
	            			$date[] = $experiences->fromdate;
	            		if (!empty($experiences->todate))
	            			$date[] = $experiences->todate;
	            ?>
		            <div class="mb-3">
		                <h5><?= $experiences->organization ?> - <small><?= $experiences->position ?></small></h5>
		                <p class="text-muted"><?= implode(' - ', $date)." (".$experiences->experience.")" ?></p>
		                <p><?= $experiences->responsibility ?></p>
		            </div>
		        <?php } ?>
	        </div>
	    <?php } ?>

	    <?php if(!empty($members_education)){ ?>
	        <!-- Education -->
	        <div class="mb-4">
	            <h4 class="section-title">Education</h4>
	            <?php foreach ($members_education as $education) { ?>
		            <div class="mb-3">
		                <h5><?= $education->institute_name ?> - <?= $education->qualification ?></h5>
		                <p class="text-muted"><?= $education->courseofStudy ?> | <?= $education->startDate ?> - <?= $education->duration ?> | Passed Out: <?= $education->year_passout ?></p>
		                <p><?= $education->description ?></p>
		            </div>
		        <?php } ?>
	        </div>
	    <?php } ?>

	    <?php if(!empty($skill)){ ?>
        <!-- Skills -->
        <div>
            <h4 class="section-title">My Skills</h4>
            <?php 
            	foreach ($skill['skill'] as $key => $skill_title) {
            		$percentage = ($skill['scores'][$key] * 100 / 5);

            		$progress_class = getSkillProgressClass($percentage);
            ?>
	            <div class="mb-2">
    		        <label><?= $skill_title ?></label>
    		        <div class="progress">
    		            <div class="progress-bar <?= $progress_class ?>" style="width: <?= $percentage ?>%"><?= $percentage ?>%</div>
    		        </div>
    		    </div>
	        <?php } ?>
        </div>
        <?php } ?>

    </div>
</div>