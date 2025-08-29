<?php
  get_header();
  require_once('college_data.php');
?>
<style>
  :root {
    --gv‑green: #1B3B4C;
    --gv‑yellow: #FFC000;
  }
  body {
    font-family: 'Lato', sans-serif;
    background-color: #fafafa;
    color: var(--gv‑green);
  }
  .nav-menu {
    background: var(--gv‑green);
  }
  .nav-menu .nav-link {
    color: #fff;
  }
  .nav-menu .nav-link:hover {
    color: var(--gv‑yellow);
  }
  header {
    text-align: center;
    padding: 80px 0;
    background: var(--gv‑green);
    color: #fff;
  }
  header h1 {
    font-size: 3rem;
    font-weight: 700;
  }
  header p {
    font-size: 1.1rem;
    margin-bottom: 20px;
  }
  header a.btn {
    background: var(--gv‑yellow);
    color: var(--gv‑green);
    font-weight: 600;
  }
  header a.btn:hover {
    background: #e0aa00;
    color: var(--gv‑green);
  }
  section {
    padding: 60px 0;
  }
  .section-title {
    font-size: 1.8rem;
    font-weight: 600;
    border-left: 6px solid var(--gv‑yellow);
    padding-left: 12px;
    margin-bottom: 1rem;
  }
  .info-box {
    border: 2px solid var(--gv‑yellow);
    border-radius: 8px;
    padding: 20px;
    background: #fff;
    margin-bottom: 20px;
    transition: transform .2s;
  }
  .info-box:hover {
    transform: translateY(-3px);
  }
  .footer {
    background: var(--gv‑green);
    color: #fff;
    padding: 30px 0;
    text-align: center;
  }
  a {
    color: var(--gv‑green);
  }
  a:hover {
    color: var(--gv‑yellow);
  }
</style>

<!-- HERO HEADER -->
<header>
  <div class="container">
    <h1><?= bp_get_current_group_name(); ?></h1>
    <a href="#about" class="btn btn-lg">Learn More</a>
  </div>
</header>

<div class="container">
  <!-- ABOUT -->
  <section id="about">
    <h2 class="section-title">About The College</h2>
    <p><?= $group->description ?></p>
  </section>

  <!-- ACADEMICS -->
  <section id="academics">
    <h2 class="section-title">Academics & Programs</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="info-box">
          <h5>Comming soon</h5>
          <p>Comming soon</p>
        </div>
      </div>
      <?php /*<div class="col-md-4">
        <div class="info-box">
          <h5>B.A</h5>
          <p>English • History • Psychology</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="info-box">
          <h5>B.Com</h5>
          <p>General & Honours</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="info-box">
          <h5>M.Sc</h5>
          <p>Data Science • Environmental Studies</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="info-box">
          <h5>Ph.D</h5>
          <p>Life Sciences</p>
        </div>
      </div>*/ ?>
    </div>
  </section>

  <!-- FACILITIES OR FACTS -->
  <section id="facilities">
    <h2 class="section-title">Facilities & Quick Facts</h2>
    <div class="row text-center">
      <div class="col-sm-4 mb-4">
        <div class="info-box">
          <strong>📍 Location</strong>
          <p><?= !empty($group_address) ? implode(", ", $group_address) : '-' ?></p>
        </div>
      </div>
      <div class="col-sm-4 mb-4">
        <div class="info-box">
          <strong>🌿 Campus</strong>
          <p>comming soon</p>
        </div>
      </div>
      <div class="col-sm-4 mb-4">
        <div class="info-box">
          <strong>🎓 Students</strong>
          <p>comming soon</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CONTACT -->
  <section id="contact">
    <h2 class="section-title">Get In Touch</h2>
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
</div>
<?php get_footer(); ?>