<?php
  get_header();
  require_once('college_data.php');
?>
<style>
  :root {
    --green: #1B3B4C;
    --yellow: #FFC000;
  }
  /* Header Card */
  .college-header {
    /*max-width: 900px;*/
    margin: 0 auto 50px;
    background: #fff;
    /*border-radius: 16px;*/
    box-shadow: 0 8px 20px rgb(27 59 76 / 0.15);
    padding: 40px 30px;
    text-align: center;
  }
  .college-name {
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    font-size: 3.4rem;
    color: var(--green);
    margin-bottom: 12px;
    letter-spacing: 2px;
  }
  .naac-badge {
    font-weight: 700;
    color: var(--yellow);
    font-size: 1.4rem;
    letter-spacing: 1.5px;
    margin-bottom: 6px;
  }
  .tagline {
    font-style: italic;
    color: var(--green);
    font-weight: 600;
    letter-spacing: 0.5px;
  }

  /* Section Titles */
  .section-title {
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    font-size: 1.8rem;
    margin-top: 50px;
    margin-bottom: 25px;
    position: relative;
    padding-bottom: 8px;
    max-width: 280px;
    border-bottom: 5px solid var(--yellow);
    color: var(--green);
  }

  /* Info Boxes */
  .info-box {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgb(27 59 76 / 0.1);
    padding: 25px 30px;
    margin-bottom: 28px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: default;
  }
  .info-box:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgb(27 59 76 / 0.18);
  }
  .info-box h5 {
    font-weight: 700;
    font-size: 1.25rem;
    margin-bottom: 14px;
    color: var(--green);
    letter-spacing: 0.7px;
  }

  /* Contact links */
  a {
    color: var(--green);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.25s ease;
  }
  a:hover {
    color: var(--yellow);
    text-decoration: underline;
  }

  /* Responsive */
  @media (max-width: 576px) {
    .college-header {
      padding: 30px 20px;
    }
    .college-name {
      font-size: 2.4rem;
    }
    .section-title {
      max-width: 100%;
    }
  }
</style>

<!-- College Header -->
<div class="college-header">
  <div class="college-name"><?= bp_get_current_group_name(); ?></div>
  <?php /*<div class="naac-badge">NAAC Accredited: A+ Grade</div>
  <div class="tagline">Established 1995 | Affiliated to Delhi University</div>*/ ?>
</div>

<!-- About -->
<section id="about" class="container">
  <h2 class="section-title">About Us</h2>
  <p><?= $group->description ?></p>
</section>

<!-- Academic Programs -->
<section id="academics" class="container">
  <h2 class="section-title">Academic Programs</h2>
  <div class="row">
    <div class="col-md-6">
      <div class="info-box">
        <h5>Coming soon</h5>
        <p>Coming soon</p>
      </div>
    </div>
    <?php /*<div class="col-md-6">
      <div class="info-box">
        <h5>B.A / B.Com</h5>
        <p>English, History, Psychology, Economics, Commerce (General & Honours)</p>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info-box">
        <h5>M.Sc</h5>
        <p>Data Science, Environmental Studies</p>
      </div>
    </div>
    <div class="col-md-6">
      <div class="info-box">
        <h5>Ph.D Programs</h5>
        <p>Life Sciences, Interdisciplinary Research Areas</p>
      </div>
    </div>*/ ?>
  </div>
</section>

<!-- Facilities -->
<section id="facilities" class="container">
  <h2 class="section-title">Facilities & Quick Facts</h2>
  <div class="row text-center">
    <div class="col-md-4">
      <div class="info-box">
        <strong>📍 Location</strong>
        <p><?= !empty($group_address) ? implode(", ", $group_address) : '-' ?></p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-box">
        <strong>🎓 Students</strong>
        <p>Coming soon</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-box">
        <strong>🌿 Campus Area</strong>
        <p>Coming soon</p>
      </div>
    </div>
  </div>
</section>

<!-- Contact -->
<section id="contact" class="container">
  <h2 class="section-title">Contact Information</h2>
  <p><strong>Phone:</strong> <?= !empty($phone) ? $phone : '-' ?></p>
  <p>
  	<strong>Email:</strong>
  	<?php if(!empty($email)){ ?>
  	    <a href="mailto:<?= $email ?>"><?= $email ?></a>
  	<?php } else { echo "-"; } ?>
  </p>
  <p>
  	<strong>Website:</strong>
  	<?php if(!empty($grpwebsite)){ ?>
  	  <a href="<?= $grpwebsite ?>" target="_blank"><?= $grpwebsite ?></a>
  	<?php } else { echo "-"; } ?>
  </p>
</section>
<?php get_footer(); ?>