<?php
  get_header();
  require_once('college_data.php');
?>
<style>
  :root {
    --college-green: #1B3B4C;
    --college-yellow: #FFC000;
  }
  body {
    background-color: #f5f7fa;
    font-family: 'Segoe UI', sans-serif;
  }
  .hero {
    background: var(--college-green);
    color: #fff;
    padding: 100px 0;
    text-align: center;
  }
  .hero h1 {
    font-size: 3rem;
    font-weight: 700;
  }
  .hero p {
    font-size: 1.2rem;
  }
  .section-title {
    color: var(--college-green);
    border-left: 6px solid var(--college-yellow);
    padding-left: 12px;
    margin-bottom: 1rem;
  }
  .card-highlight {
    border-left: 4px solid var(--college-green);
    background: #eaf0f2;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 1rem;
  }
  .footer {
    background-color: var(--college-green);
    color: #fff;
    padding: 20px 0;
    text-align: center;
    margin-top: 40px;
  }
  a.btn-visit {
    background-color: var(--college-yellow);
    color: var(--college-green);
    font-weight: bold;
  }
  a.btn-visit:hover {
    background-color: #e0aa00;
    color: var(--college-green);
  }
</style>

<!-- HERO SECTION -->
<section class="hero">
  <div class="container">
    <h1><?= bp_get_current_group_name(); ?></h1>
    <a href="#contact" class="btn btn-visit mt-3">Contact Us</a>
  </div>
</section>

<div class="container my-5">

  <!-- ABOUT -->
  <section id="about">
    <h2 class="section-title">About the College</h2>
    <p><?= $group->description ?></p>
  </section>

  <!-- QUICK FACTS -->
  <section id="facts" class="mt-5">
    <h2 class="section-title">Quick Facts</h2>
    <div class="row">
      <div class="col-md-3">
        <div class="card-highlight text-center">
          <strong>Location</strong><br><?= !empty($group_address) ? implode(", ", $group_address) : '-' ?>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card-highlight text-center">
          <strong>Type</strong><br><?= !empty($group->status) ? ucfirst($group->status) : '-' ?>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card-highlight text-center">
          <strong>Campus Area</strong><br>comming soon
        </div>
      </div>
      <div class="col-md-3">
        <div class="card-highlight text-center">
          <strong>Students</strong><br>comming soon
        </div>
      </div>
    </div>
  </section>

  <!-- PROGRAMS -->
  <section id="programs" class="mt-5">
    <h2 class="section-title">Academic Programs</h2>
    <ul class="list-group mb-4">
      <li class="list-group-item">comming soon</li>
    </ul>
    <?php /*
    <ul class="list-group list-group-flush">
      <li class="list-group-item">B.Sc – Computer Science, Physics, Chemistry</li>
      <li class="list-group-item">B.A – English, History, Psychology</li>
      <li class="list-group-item">B.Com – General & Honours</li>
      <li class="list-group-item">M.Sc – Data Science, Environmental Studies</li>
      <li class="list-group-item">Ph.D – Life Sciences</li>
    </ul>*/ ?>
  </section>

  <!-- CONTACT -->
  <section id="contact" class="mt-5">
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
    <?php if(!empty($grpwebsite)){ ?>
      <a href="https://www.gvcollege.edu.in" class="btn btn-visit">Visit Official Site</a>
    <?php } ?>
  </section>

</div>
<?php get_footer(); ?>