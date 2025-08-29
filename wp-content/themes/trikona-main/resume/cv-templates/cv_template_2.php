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
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
  }

  /* PDF container */
  .pdf-page {
    background-color: #f8f9fa;
    padding: 20px;
  }

  /* Left sidebar */
  .sidebar {
    background-color: #ffffff;
    padding: 20px;
    border-right: 3px solid rgba(0,0,0,0.05);
  }

  /* Profile image */
  .profile-img {
    width: 140px;
    height: 140px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #0d6efd;
    display: block;
    margin: 0 auto 15px;
  }

  /* Section titles */
  .section-title {
    font-size: 1.1rem;
    font-weight: bold;
    color: #0d6efd;
    border-bottom: 2px solid #0d6efd;
    margin-bottom: 10px;
    padding-bottom: 3px;
  }

  /* Progress bars */
  .progress {
    height: 16px;
  }

  /* Right content area */
  .content {
    background-color: #ffffff;
    padding: 20px;
  }

  /* Section spacing */
  .resume-section {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid rgba(0,0,0,0.05);
  }
</style>
<div class="container-lg pb-3 pt-3">
	<div class="col-md-12 text-right">
		<button type="button" class="btn" onclick="downloadPDF()">Download Resume</button>
	</div>
</div>
<div class="container pdf-page resume-container">
  <div class="row g-0">

    <!-- LEFT SIDEBAR -->
    <div class="col-md-4 sidebar">
      <?= str_replace('width="150"', 'width="150" class="profile-img"', $user_avatar)  ?>
      <h3 class="text-center"><?= $current_user->user_firstname." ".$current_user->user_lastname ?></h3>

      <div class="resume-section">
        <h4 class="section-title">Contact</h4>
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
    		<p><strong>LinkedIn:</strong> <a href="<?= $linkedinProfile ?>" target="_blank"><?= $linkedinProfile ?></a></p>
		<?php } ?>
      </div>

      <?php if(!empty($skill)){ ?>
      <div class="resume-section">
        <h4 class="section-title">Skills</h4>
        <?php 
     		foreach ($skill['skill'] as $key => $skill_title) {
	     		$percentage = ($skill['scores'][$key] * 100 / 5);

	     		$progress_class = getSkillProgressClass($percentage);
    	?>
        <p><?= $skill_title ?></p>
        <div class="progress mb-2">
          <div class="progress-bar <?= $progress_class ?>" style="width: <?= $percentage ?>%"><?= $percentage ?>%</div>
        </div>
        <?php } ?>
      </div>
      <?php } ?>
    </div>

    <!-- RIGHT CONTENT -->
    <div class="col-md-8 content">
    	<?php if (!empty($member_bio)) { ?>
	      <div class="resume-section">
	        <h4 class="section-title">About Me</h4>
	        <p><?= $member_bio ?></p>
	      </div>
	    <?php } ?>
	    <?php if (!empty($members_experience)) { ?>
	      <div class="resume-section">
	        <h4 class="section-title">Work & Experience</h4>
	        <?php 
	        	foreach ($members_experience as $key => $experiences) {
	        		$date = [];
	        		if (!empty($experiences->fromdate))
	        			$date[] = $experiences->fromdate;
	        		if (!empty($experiences->todate))
	        			$date[] = $experiences->todate;
	        ?>
	        <div>
	          <h5><?= $experiences->organization ?> - <small><?= $experiences->position ?></small></h5>
	          <p class="text-muted"><?= implode(' - ', $date)." (".$experiences->experience.")" ?></p>
	          <p><?= $experiences->responsibility ?></p>
	        </div>
	    	<?php } ?>    
	      </div>
	    <?php } ?>
	    <?php if(!empty($members_education)){ ?>
	      <div class="resume-section">
	        <h4 class="section-title">Education</h4>
	        <?php foreach ($members_education as $education) { ?>
		        <div>
		          <h5><?= $education->institute_name ?> - <?= $education->qualification ?></h5>
		          <p class="text-muted"><?= $education->courseofStudy ?> | <?= $education->startDate ?> - <?= $education->duration ?> | Passed Out: <?= $education->year_passout ?></p>
		          <p><?= $education->description ?></p>
		        </div>
		   	<?php } ?>
	      </div>
	    <?php } ?>

    </div>
  </div>
</div>