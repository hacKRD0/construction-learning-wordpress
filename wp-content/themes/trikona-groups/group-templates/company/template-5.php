<?php
  get_header();
  require_once('company_data.php');
?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    :root {
      --green: #1B3B4C;
      --yellow: #FFC000;
      --yellow-dark: #cca300;
      --light-bg: #f5f7fa;
      --white: #fff;
      --shadow: rgba(0, 0, 0, 0.08);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Open Sans', sans-serif;
      background: var(--light-bg);
      color: var(--green);
      line-height: 1.6;
    }

    .hero {
      display: flex;
      flex-wrap: wrap;
      min-height: 300px;
    }

    .hero .left {
      flex: 1 1 300px;
      background: var(--green);
      color: var(--white);
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      padding: 40px 20px;
      text-align: center;
    }

    .hero .left h1 {
      font-family: 'Poppins', sans-serif;
      font-size: 3rem;
      margin-bottom: 10px;
      text-transform: uppercase;
    }

    .hero .left p {
      font-size: 1.2rem;
      font-weight: 500;
      max-width: 300px;
    }

    .hero .right {
      flex: 2 1 300px;
      background: url('<?= !empty($bp_group_banner) ? $bp_group_banner[0] : "https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1500&q=80";?>') center/cover no-repeat;
    }

    .container {
      max-width: 900px;
      margin: 60px auto;
      padding: 0 20px;
    }

    .card-section {
      background: var(--white);
      box-shadow: 0 6px 20px var(--shadow);
      border-radius: 16px;
      margin-bottom: 40px;
      padding: 30px;
      display: flex;
      gap: 20px;
      align-items: flex-start;
    }

    .card-icon {
      width: 70px;
      height: 70px;
      background: var(--yellow);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--green);
      font-size: 32px;
      flex-shrink: 0;
      box-shadow: 0 4px 12px rgba(255, 192, 0, 0.5);
    }

    .card-content h2 {
      font-family: 'Poppins', sans-serif;
      font-size: 1.8rem;
      margin-bottom: 10px;
    }

    .card-content p {
      font-size: 1.1rem;
    }

    .card-content ul {
      padding-left: 20px;
      list-style-type: disc;
      font-size: 1.1rem;
    }

    .card-content ul li {
      margin-bottom: 6px;
    }

    .contact {
      background: var(--white);
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 6px 20px var(--shadow);
    }

    .contact h2 {
      font-family: 'Poppins', sans-serif;
      font-size: 2rem;
      margin-bottom: 20px;
      text-align: center;
    }

    .contact-info p {
      display: flex;
      align-items: center;
      margin: 10px 0;
      font-size: 1.1rem;
    }

    .contact-info i {
      font-size: 1.4rem;
      color: var(--yellow);
      margin-right: 10px;
      min-width: 26px;
      text-align: center;
    }

    .contact-info a {
      color: var(--green);
      text-decoration: none;
      font-weight: 600;
      border-bottom: 2px solid transparent;
      transition: border-color 0.3s ease;
    }

    .contact-info a:hover {
      border-color: var(--yellow);
      color: var(--yellow-dark);
    }

    @media (max-width: 720px) {
      .card-section {
        flex-direction: column;
        text-align: center;
        align-items: center;
      }
      .card-content {
        text-align: center;
      }
    }
  </style>

  <!-- Hero Banner -->
  <header class="hero" role="banner">
    <div class="left">
      <h1><?= bp_get_current_group_name(); ?></h1>
      <?php /*<p>Innovating Sustainability Through Technology</p>*/ ?>
    </div>
    <div class="right"></div>
  </header>

  <main class="container" role="main">
    <!-- About -->
    <section class="card-section" aria-labelledby="about-title">
      <div class="card-icon"><i class="bi bi-info-circle-fill"></i></div>
      <div class="card-content">
        <h2 id="about-title">About Us</h2>
        <p><?= $group->description ?></p>
      </div>
    </section>

    <!-- Services -->
    <section class="card-section" aria-labelledby="services-title">
      <div class="card-icon"><i class="bi bi-gear-fill"></i></div>
      <div class="card-content">
        <h2 id="services-title">Our Services</h2>
        <ul>
          <li>Coming Soon</li>
          <?php /*<li>Cloud Infrastructure & DevOps</li>
          <li>Artificial Intelligence & Machine Learning</li>
          <li>IT Consulting & Strategy</li>
          <li>Cybersecurity Solutions</li>*/ ?>
        </ul>
      </div>
    </section>

    <!-- Clients -->
    <section class="card-section" aria-labelledby="clients-title">
      <div class="card-icon"><i class="bi bi-people-fill"></i></div>
      <div class="card-content">
        <h2 id="clients-title">Our Clients</h2>
        <p>Coming Soon</p>
      </div>
    </section>

    <!-- Contact -->
    <section class="contact" aria-labelledby="contact-title">
      <h2 id="contact-title">Get In Touch</h2>
      <div class="contact-info">
        <p><i class="bi bi-telephone-fill"></i> <?= !empty($phone) ? $phone : '-' ?></p>
        <p>
          <i class="bi bi-envelope-fill"></i>
          <?php if(!empty($email)){ ?>
            <a href="mailto:<?= $email ?>"><?= $email ?></a>
          <?php } else { echo "-"; } ?>
        </p>
        <p>
          <i class="bi bi-globe"></i>
          <?php if(!empty($grpwebsite)){ ?>
            <a href="<?= $grpwebsite ?>" target="_blank"><?= $grpwebsite ?></a>
          <?php } else { echo "-"; } ?>
        </p>
        <p><i class="bi bi-geo-alt-fill"></i> <?= !empty($group_address) ? implode(", ", $group_address) : '-' ?></p>
      </div>
    </section>
  </main>
<?php get_footer(); ?>