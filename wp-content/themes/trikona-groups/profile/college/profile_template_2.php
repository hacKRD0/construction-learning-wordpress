<?php 
	require_once('profile_data.php');

	$resume_file_name = $current_user->user_firstname." ".$current_user->user_lastname." Profile-2.pdf";
?>
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri() . '/dashboard/assets/css/style-dashboard.css'; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri() . '/dashboard/assets/css/style-dashboard-addon.css'; ?>">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script type="text/javascript">
	function downloadPDF() {
		var opt = {
			/*pagebreak: {
		        mode: ['avoid-all', 'css', 'legacy']
		    },*/
			filename:     '<?php echo $resume_file_name; ?>',
		  	jsPDF: {format: 'A4', orientation: 'portrait' }
		};
		var element = document.querySelector(".resume-container");
		html2pdf().set(opt).from(element).save();
	}
</script>
<style>
	.template-type-2 {
	  &.resume-container {
	    background-color: #fff !important;
	    padding: 3rem  2rem 0.5rem;
	    max-width: 1000px;

	    .logostyle {
	      text-align: left;
	    }
	  }

	  a {
	  	color: #212529 !important;
	  }

	  .label-personal-info {
	    background-color: #4a718c;
	    font-weight: bold;
	    text-transform: uppercase;
	    font-size: 20px;
	    padding: 7px 10px;
	    /*min-width: 25rem;*/
	    width: 100%;
	  }

	  .personal-info-icon {
	    font-weight: bold;
	    text-transform: uppercase;
	    font-size: 2rem;
	    padding: 5px 10px;
	    color: #85C8F8;
	  }

	  .personal-info-container {
	    padding: 1rem;
	    font-size: 1.5rem;
	    font-weight: bold;
	  }

		.experience-info-container {
			padding: 1rem;

			ul{
			  list-style: none;
			  margin:0;
			  padding:0;
			  position: relative;
			  &:before{
			      content:"";
			      display: inline-block;
			      width: 2px;
			      background: #85C8F8;
			      position: absolute;
			      left:3px;
			      top:5px;
			      height: calc(100% - 10px );
			  }
			  li{
			    position: relative;
			    padding-left: 15px;
			    margin-bottom: 20px;
			    &:before{
			      content:"";
			      display: inline-block;
			      width: 8px;
			      height: 8px;
			      background: #85C8F8;
			      position: absolute;
			      left:0;
			      top:5px;
			      border-radius: 10px;
			    }
			  }
			}
		}

		.skills-info-container {
			padding-left: 1rem;
		}
	}
</style>
<link rel="stylesheet" type="text/css" href="https://fontawesome.com/v4/assets/font-awesome/css/font-awesome.css">
<div class="container-lg pb-3">
	<div class="col-md-12 text-end"><button type="button" onclick="downloadPDF()">Download Resume</button></div>
</div>
<div class="container-lg resume-container template-type-2">
	<div class="row">
		<div class="col-md-2 logo logostyle">
			<?php echo $user_avatar; ?>
		</div>
		<div class="col-md-9 mb-auto mt-auto offset-md-1">
			<h2><?php echo $current_user->user_firstname." ".$current_user->user_lastname; ?></h2>
			<?php
				if (!empty($member_bio)) {
					echo "<p>".esc_html( $member_bio )."</p>";
				}
			?>
		</div>
	</div>

	<div class="row pt-4">
		<div class="col-md-6 pt-4">
			<div class="d-flex">
				<i class="fa fa-user personal-info-icon mb-auto mt-auto"></i>
				<div class="label-personal-info text-white">PERSONAL INFORMATION:</div>
			</div>
			<div class="personal-info-container">
				<?php if(!empty($phone_nos)){ ?>
				<div class="d-flex">
					<i class="fa fa-mobile mb-auto mt-auto"></i>&nbsp;&nbsp;&nbsp;
					<p class="mb-auto mt-auto"><?php echo implode(", ", $phone_nos)?></p>
				</div>
				<?php } ?>
				<?php if(!empty($email)){ ?>
					<div class="d-flex pt-2">
						<i class="fa fa-envelope mb-auto mt-auto"></i>&nbsp;&nbsp;&nbsp;
						<p class="mb-auto mt-auto"><?php echo $email; ?></p>
					</div>
				<?php } ?>
				<?php if(!empty($linkedinProfile)){ ?>
					<div class="d-flex pt-2">
						<i class="fa fa-linkedin-square mb-auto mt-auto"></i>&nbsp;&nbsp;&nbsp;
						<p class="mb-auto mt-auto"><?php echo $linkedinProfile; ?></p>
					</div>
				<?php } ?>
			</div>

			<?php if(!empty($skill) && isset($skill['skill']) && !empty($skill['skill'])){ ?>
			<div class="d-flex">
				<i class="fa fa-lightbulb-o personal-info-icon mb-auto mt-auto"></i>
				<div class="label-personal-info text-white">SKILLS:</div>
			</div>
			<div class="skills-info-container pt-4">
				<h5><b><?php echo implode(", ", $skill['skill']); ?></b></h5>
			</div>
			<?php } ?>
		</div>
		<div class="col-md-6 pt-4">
			<?php if(!empty($members_education)){ ?>
			<div class="d-flex pt-2">
				<i class="fa fa-graduation-cap personal-info-icon mb-auto mt-auto"></i>
				<div class="label-personal-info text-white">EDUCATION:</div>
			</div>
			<div class="experience-info-container pt-4">
				<ul>
					<?php foreach ($members_education as $key => $education) { ?>
						<li><b><?php echo $education->qualification; ?></b> from <?php echo $education->institute_name; ?> in the year of <?php echo date('Y', strtotime($education->startDate)); ?>.</li>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>
		</div>
	</div>

	<?php if(!empty($members_experience)){ ?>
		<div class="row">
			<div class="col-md-12 pt-4">
				<div class="d-flex">
					<i class="fa fa-briefcase personal-info-icon mb-auto mt-auto"></i>
					<div class="label-personal-info text-white">WORK EXPERIENCE:</div>
				</div>
				<div class="experience-info-container pt-4">
					<ul>
						<?php 
							$rowid= 1;
							foreach ($members_experience as $key => $experiences) {
								$date = [];
								if (!empty($experiences->fromdate))
									$date[] = $experiences->fromdate;
								if (!empty($experiences->todate))
									$date[] = $experiences->todate;
						?>
							<li>
								Worked as a <?php echo $experiences->position; ?> in <b><?php echo $experiences->organization;  ?></b>. (<?php echo implode(' - ', $date); ?>)
								<?php if(!empty($experiences->responsibility)){ echo "<p class='pt-2'>".esc_html( $experiences->responsibility )."</p>"; } ?>
							</li>
						<?php $rowid++; } ?>
					</ul>
				</div>
			</div>
		</div>
	<?php } ?>
</div>