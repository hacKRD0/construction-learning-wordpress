<?php 
	require_once('profile_data.php');

	$resume_file_name = $current_user->user_firstname." ".$current_user->user_lastname." Profile-1.pdf";
?>
<link rel="stylesheet" type="text/css" href="<?= $main_theme_url . '/dashboard/assets/css/style-dashboard.css'; ?>">
<link rel="stylesheet" type="text/css" href="<?= $main_theme_url . '/dashboard/assets/css/style-dashboard-addon.css'; ?>">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script type="text/javascript">
	function downloadPDF() {
		var opt = {
			pagebreak: {
		        mode: ['avoid-all', 'css', 'legacy']
		    },
			filename: '<?php echo $resume_file_name; ?>',
		  	jsPDF: {format: 'A4', orientation: 'portrait' }
		};
		var element = document.querySelector(".resume-container");
		html2pdf().set(opt).from(element).save();
	}
</script>
<style type="text/css">
	.template-type-1 {
	  &.resume-container {
	    background-color: #fff !important;
	    max-width: 1000px;

	    .logostyle {
	      text-align: center;
	    }
	  }

	  a {
	  	color: #fff !important;
	  }

	  .left-section {
	    background-color: #002A4D;
	    color: #fff;
	  }

	  .hr-line {
	    border-bottom: 4px solid #125B88;
	    padding-bottom: 1rem;
	    margin-bottom: 1rem;
	  }

	  .contact-details p, .basic-details p, .social-media-details p, .left-section h2 {
	    word-wrap: break-word;
	    width: 100%;
	  }

	  .right-hr-line {
	    border-bottom: 4px solid #DFDFDF;
	    padding-bottom: 1rem;
	    margin-bottom: 1rem;
	  }

	  .heading-title {
	    color: #002B4D;
	    font-weight: bold;
	  }
	}
  	.pl-0 {
  		padding-left: 0 !important;
  	}
  	.pr-0 {
  		padding-right: 0 !important;
  	}
</style>
<link rel="stylesheet" type="text/css" href="https://fontawesome.com/v4/assets/font-awesome/css/font-awesome.css">
<div class="container-lg pb-3">
	<div class="col-md-12 text-end"><button type="button" onclick="downloadPDF()">Download PDF</button></div>
</div>
<div class="container-lg resume-container template-type-1 pl-0 pr-0">
	<div class="row">
		<div class="col-md-3 left-section">
			<div class="col-md-12 logo logostyle">
				<?= $user_avatar ?>
			</div>
			<h2 class="text-center"><?= $current_user->user_firstname." ".$current_user->user_lastname; ?></h2>
			<div class="hr-line"></div>

			<?php if(!empty($address) || !empty($phone_nos) || !empty($email) || !empty($memberDob)){ ?>
			<di class="col-md-12 contact-details">
				<h4>Cotnact</h4>
				<?php if(!empty($address)){ ?>
				<div class="d-flex">
					<i class="fa fa-map-marker mb-auto mt-auto"></i>&nbsp;&nbsp;&nbsp;
					<p class="mb-auto mt-auto"><?= $address ?></p>
				</div>
				<?php } ?>
				<?php if(!empty($phone_nos)){ ?>
				<div class="d-flex pt-2">
					<i class="fa fa-mobile mb-auto mt-auto"></i>&nbsp;&nbsp;&nbsp;
					<p class="mb-auto mt-auto"><?= implode(", ", $phone_nos) ?></p>
				</div>
				<?php } ?>
				<?php if(!empty($memberDob)){ ?>
				<div class="d-flex pt-2">
					<i class="fa fa-calendar mb-auto mt-auto"></i>&nbsp;&nbsp;&nbsp;
					<p class="mb-auto mt-auto"><?= $memberDob ?></p>
				</div>
				<?php } ?>
				<?php if(!empty($email)){ ?>
					<div class="d-flex pt-2">
						<i class="fa fa-envelope mb-auto mt-auto"></i>&nbsp;&nbsp;&nbsp;
						<p class="mb-auto mt-auto"><?= $email ?></p>
					</div>
				<?php } ?>
			</di>
			<div class="hr-line"></div>
			<?php } ?>

			<?php if(!empty($gender)){ ?>
			<di class="col-md-12 basic-details">
				<h4>Basic Details</h4>
				<div class="d-flex pt-2">
					<i class="fa fa-user-md mb-auto mt-auto"></i>&nbsp;&nbsp;&nbsp;
					<p class="mb-auto mt-auto"><?= $gender ?></p>
				</div>
			</di>
			<div class="hr-line"></div>
			<?php } ?>

			<?php if(!empty($linkedinProfile)){ ?>
			<di class="col-md-12 basic-details">
				<h4>Social Media</h4>
				<div class="d-flex pt-2 linkedin-info">
					<i class="fa fa-linkedin-square mb-auto mt-auto"></i>&nbsp;&nbsp;&nbsp;
					<p class="mb-auto mt-auto"><?= $linkedinProfile ?></p>
				</div>
			</di>
			<div class="hr-line"></div>
			<?php } ?>
		</div>
		<div class="col-md-9">
			<?php if (!empty($member_bio)) { ?>
				<h1 class="heading-title">About Me</h1>
				<p><?= $member_bio ?></p>
				<div class="right-hr-line"></div>
			<?php } ?>

			<?php if(!empty($members_experience)){ ?>
				<h1 class="heading-title">Work & Experience</h1>
				<?php 
					foreach ($members_experience as $key => $experiences) {
						$date = [];
						if (!empty($experiences->fromdate))
							$date[] = $experiences->fromdate;
						if (!empty($experiences->todate))
							$date[] = $experiences->todate;
				?>
					<h4 class="heading-title mt-4"><?= $experiences->organization ?></h4>
					<div class="d-flex pt-2">
						<p class="mb-0"><b>Designation:</b></p>&nbsp;&nbsp;
						<p class="mb-0"><?= $experiences->position ?></p>
					</div>
					<div class="d-flex pt-2">
						<p class="mb-0"><b>Work Experience:</b></p>&nbsp;&nbsp;
						<p class="mb-0"><?= $experiences->experience ?></p>
					</div>
					<div class="d-flex pt-2">
						<p class="mb-0"><b>Description:</b></p>&nbsp;&nbsp;
						<p class="mb-0"><?= $experiences->responsibility ?></p>
					</div>
					<div class="d-flex pt-2">
						<p class="mb-0"><b>Time Period:</b></p>&nbsp;&nbsp;
						<p class="mb-0"><?= implode(' - ', $date) ?></p>
					</div>
					<div class="right-hr-line"></div>
				<?php } ?>
			<?php } ?>

			<?php if(!empty($members_education)){ ?>
				<h1 class="heading-title">Education</h1>

				<?php foreach ($members_education as $education) { ?>
					<h4 class="heading-title mt-4"><?= $education->institute_name ?></h4>
					<div class="d-flex pt-2">
						<p class="mb-0"><b>Qualification:</b></p>&nbsp;&nbsp;
						<p class="mb-0"><?= $education->qualification ?></p>
					</div>
					<div class="d-flex pt-2">
						<p class="mb-0"><b>Course:</b></p>&nbsp;&nbsp;
						<p class="mb-0"><?= $education->courseofStudy ?></p>
					</div>
					<div class="d-flex pt-2">
						<p class="mb-0"><b>Duration:</b></p>&nbsp;&nbsp;
						<p class="mb-0"><?= $education->duration ?></p>
					</div>
					<div class="d-flex pt-2">
						<p class="mb-0"><b>Start Date:</b></p>&nbsp;&nbsp;
						<p class="mb-0"><?= $education->startDate ?></p>
					</div>
					<div class="d-flex pt-2">
						<p class="mb-0"><b>Year of Passout:</b></p>&nbsp;&nbsp;
						<p class="mb-0"><?= $education->year_passout ?></p>
					</div>
					<div class="d-flex pt-2">
						<p class="mb-0"><b>Description:</b></p>&nbsp;&nbsp;
						<p class="mb-0"><?= $education->description ?></p>
					</div>

					<div class="right-hr-line"></div>
				<?php } ?>
			<?php } ?>

			<?php if(!empty($skill)){ ?>
				<h1 class="heading-title">My skills</h1>
				<?php 
					foreach ($skill['skill'] as $key => $skill_title) {
						$skill_star = '';
						if ($skill['scores'][$key] == 1) {
							$skill_star = '<i class="fa fa-star"></i>&nbsp;';
						} else if ($skill['scores'][$key] == 2) {
							$skill_star = '<i class="fa fa-star"></i>&nbsp;<i class="fa fa-star"></i>&nbsp;';
						} else if ($skill['scores'][$key] == 3) {
							$skill_star = '<i class="fa fa-star"></i>&nbsp;<i class="fa fa-star"></i>&nbsp;<i class="fa fa-star"></i>&nbsp;';
						} else if ($skill['scores'][$key] == 4) {
							$skill_star = '<i class="fa fa-star"></i>&nbsp;<i class="fa fa-star"></i>&nbsp;<i class="fa fa-star"></i>&nbsp;<i class="fa fa-star"></i>&nbsp;';
						} else if ($skill['scores'][$key] == 5) {
							$skill_star = '<i class="fa fa-star"></i>&nbsp;<i class="fa fa-star"></i>&nbsp;<i class="fa fa-star"></i>&nbsp;<i class="fa fa-star"></i>&nbsp;<i class="fa fa-star"></i>&nbsp;';
						}
				?>
					<div class="right-hr-line"></div>
					<div class="row">
						<div class="col-md-6"><?= $skill_title ?></div>
						<div class="col-md-6"><?= $skill_star ?></div>
					</div>
				<?php } ?>
				<div class="mb-4 pb-4"></div>
			<?php } ?>
		</div>
	</div>
</div>