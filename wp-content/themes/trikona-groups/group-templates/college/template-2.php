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
    font-family: 'Segoe UI', sans-serif;
    background-color: #f9fafb;
  }

  .college-card {
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    padding: 35px;
    margin-top: 50px;
  }

  .logo-img {
    width: 80px;
    height: 80px;
    object-fit: contain;
    border-radius: 10px;
    border: 3px solid var(--college-yellow);
  }

  .section-title {
    color: var(--college-green);
    border-left: 5px solid var(--college-yellow);
    padding-left: 12px;
    margin-top: 40px;
    margin-bottom: 20px;
    font-weight: 600;
  }

  .card-style {
    background-color: #eaf3f6;
    border-left: 5px solid var(--college-green);
    border-radius: 10px;
    padding: 15px;
  }

  .btn-custom {
    background-color: var(--college-yellow);
    color: #000;
    font-weight: 600;
    border: none;
  }

  .btn-custom:hover {
    background-color: #e0aa00;
    color: #000;
  }

  a {
    color: var(--college-green);
    text-decoration: none;
  }

  .footer {
    text-align: center;
    font-size: 14px;
    color: #6c757d;
    margin-top: 60px;
    padding-bottom: 20px;
  }

  .list-group-item {
    border: none;
    background: #f4f7f9;
  }
</style>
<div class="container college-card">
  <!-- Header -->
  <div class="d-flex align-items-center gap-4 border-bottom pb-3">
    <?php if(!empty($bp_group_banner)){ ?>
        <img src="<?= $bp_group_banner[0];?>" alt="College Logo" class="logo-img">
    <?php } ?>
    <div>
      <h2 class="mb-0" style="color: var(--college-green);"><?= bp_get_current_group_name(); ?></h2>
    </div>
  </div>

  <!-- About Section -->
  <h4 class="section-title">About the College</h4>
  <p><?= $group->description ?></p>

  <!-- Quick Facts -->
  <h4 class="section-title">Quick Facts</h4>
  <div class="row g-3">
    <div class="col-md-3 col-sm-6">
      <div class="card-style text-center">
        <strong>📍 Location</strong><br> <?= !empty($group_address) ? implode(", ", $group_address) : '-' ?>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card-style text-center">
        <strong>🏛 Type</strong><br> <?= !empty($group->status) ? ucfirst($group->status) : '-' ?>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card-style text-center">
        <strong>🌳 Campus</strong><br> comming soon
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card-style text-center">
        <strong>🎓 Students</strong><br> comming soon
      </div>
    </div>
  </div>

  <!-- Academic Programs -->
  <h4 class="section-title">Academic Programs</h4>
  <ul class="list-group mb-4">
    <li class="list-group-item">comming soon</li>
  </ul>
  <?php /*<ul class="list-group mb-4">
    <li class="list-group-item">B.Sc – Computer Science, Physics, Chemistry</li>
    <li class="list-group-item">B.A – English, History, Psychology</li>
    <li class="list-group-item">B.Com – General & Hons.</li>
    <li class="list-group-item">M.Sc – Data Science, Environmental Studies</li>
    <li class="list-group-item">Ph.D. – Life Sciences</li>
  </ul>*/ ?>

  <!-- Contact Info -->
  <h4 class="section-title">Contact Information</h4>
  <div class="row">
    <div class="col-md-6 mb-2">
      <strong>📞 Phone:</strong> <?= !empty($phone) ? $phone : '-' ?>
    </div>
    <div class="col-md-6 mb-2">
      <strong>✉️ Email:</strong>
      <?php if(!empty($email)){ ?>
          <a href="mailto:<?= $email ?>"><?= $email ?></a>
      <?php } else { echo "-"; } ?>
    </div>
    <div class="col-12">
      <strong>🌐 Website:</strong>
      <?php if(!empty($grpwebsite)){ ?>
        <a href="<?= $grpwebsite ?>" target="_blank"><?= $grpwebsite ?></a>
      <?php } else { echo "-"; } ?>
    </div>
    <?php if(!empty($grpwebsite)){ ?>
    <div class="col-12 mt-3">
      <a href="<?= $grpwebsite ?>" class="btn btn-custom">Visit Official Site</a>
    </div>
    <?php } ?>
  </div>
</div>
<?php get_footer(); ?>