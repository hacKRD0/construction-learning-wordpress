<?php
  get_header();
  require_once('college_data.php');
?>
<style>
  :root {
    --green: #1B3B4C;
    --yellow: #FFC000;
    --light-green: #e9f0f3;
  }
  body {
    background-color: var(--light-green);
    margin: 0;
    color: var(--green);
  }
  .container {
    max-width: 900px;
    margin: 0 auto;
  }
  /* College Title Card */
  .title-card {
    background: var(--green);
    color: white;
    padding: 40px 30px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgb(27 59 76 / 0.25);
    text-align: center;
    margin-bottom: 40px;
    font-family: 'Poppins', sans-serif;
  }
  .title-card h1 {
    font-size: 3.4rem;
    margin-bottom: 8px;
    letter-spacing: 2px;
  }
  .title-card .naac {
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--yellow);
    margin-bottom: 6px;
  }
  .title-card .tagline {
    font-size: 1.1rem;
    font-style: italic;
    opacity: 0.85;
  }

  /* Card style for sections */
  .info-card {
    background: white;
    border-radius: 14px;
    box-shadow: 0 6px 15px rgb(27 59 76 / 0.12);
    padding: 28px 30px;
    margin-bottom: 30px;
    transition: box-shadow 0.3s ease;
  }
  .info-card:hover {
    box-shadow: 0 10px 30px rgb(27 59 76 / 0.20);
  }
  .info-card h3 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 1.8rem;
    margin-bottom: 20px;
    border-bottom: 3px solid var(--yellow);
    padding-bottom: 6px;
    color: var(--green);
    letter-spacing: 1px;
  }
  .info-card p, .info-card ul {
    font-size: 1rem;
    line-height: 1.5;
  }
  .info-card ul {
    padding-left: 20px;
  }
  .info-card ul li {
    margin-bottom: 8px;
  }

  /* Contact links */
  a {
    color: var(--green);
    font-weight: 600;
    text-decoration: none;
  }
  a:hover {
    color: var(--yellow);
    text-decoration: underline;
  }

  /* Responsive adjustments */
  @media (max-width: 575.98px) {
    .title-card h1 {
      font-size: 2.5rem;
    }
    .info-card h3 {
      font-size: 1.5rem;
    }
  }
</style>

<div class="container">
  <!-- College Title Card -->
  <div class="title-card mt-2">
    <h1><?= bp_get_current_group_name(); ?></h1>
    <?php /*<div class="naac">NAAC Accredited: A+ Grade</div>
    <div class="tagline">Established 1995 | Affiliated to Delhi University</div>*/ ?>
  </div>

  <!-- About Section -->
  <div class="info-card" id="about">
    <h3>About Us</h3>
    <p><?= $group->description ?></p>
  </div>

  <!-- Academic Programs -->
  <div class="info-card" id="academics">
    <h3>Academic Programs</h3>
    <ul>
      <li><strong>Coming Soon:</strong> Coming Soon</li>
      <?php /*<li><strong>B.A / B.Com:</strong> English, History, Psychology, Economics, Commerce (General & Honours)</li>
      <li><strong>M.Sc Programs:</strong> Data Science, Environmental Studies</li>
      <li><strong>Ph.D Programs:</strong> Life Sciences, Interdisciplinary Research Areas</li>*/ ?>
    </ul>
  </div>

  <!-- Facilities -->
  <div class="info-card" id="facilities">
    <h3>Facilities & Quick Facts</h3>
    <ul>
      <li><strong>Location:</strong> <?= !empty($group_address) ? implode(", ", $group_address) : '-' ?></li>
      <li><strong>Students Enrolled:</strong> Coming Soon</li>
      <li><strong>Campus Area:</strong> Coming Soon</li>
    </ul>
  </div>

  <!-- Contact Information -->
  <div class="info-card" id="contact">
    <h3>Contact Information</h3>
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
  </div>
</div>
<?php get_footer(); ?>