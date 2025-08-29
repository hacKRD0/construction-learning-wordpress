<?php
  get_header();
  require_once('college_data.php');
?>
<style>
  :root {
    --green: #1B3B4C;
    --yellow: #FFC000;
    --white: #fff;
    --shadow-color: rgba(27, 59, 76, 0.15);
  }

  * {
    box-sizing: border-box;
  }

  body {
    margin: 0;
    font-family: 'Roboto', sans-serif;
    background: #f9fafb;
    color: var(--green);
    overflow-x: hidden;
  }

  /* Banner */
  .banner {
    position: relative;
    width: 100%;
    height: 320px;
    background: url('<?= !empty($bp_group_banner) ? $bp_group_banner[0] : "https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1470&q=80";?> ') no-repeat center/cover;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    text-align: center;
  }

  .banner::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(27,59,76,0.85) 0%, rgba(27,59,76,0.5) 70%, transparent 100%);
    z-index: 0;
  }

  .banner-content {
    position: relative;
    z-index: 1;
    max-width: 900px;
    padding: 0 15px;
    animation: fadeInUp 1s ease forwards;
  }

  .banner-content h1 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 3rem;
    letter-spacing: 3px;
    margin-bottom: 0.3rem;
    text-shadow: 0 3px 6px rgba(0,0,0,0.4);
  }

  .banner-content p {
    font-size: 1.2rem;
    font-weight: 500;
    letter-spacing: 1.2px;
    text-shadow: 0 2px 5px rgba(0,0,0,0.3);
  }

  /* NAAC Ribbon */
  .naac-ribbon {
    position: absolute;
    top: 20px;
    right: 20px;
    background: var(--yellow);
    color: var(--green);
    font-weight: 700;
    padding: 10px 25px;
    border-radius: 50px;
    font-size: 1rem;
    box-shadow: 0 4px 12px rgba(255,192,0,0.6);
    letter-spacing: 1.2px;
    user-select: none;
    z-index: 2;
    animation: fadeIn 2s ease forwards 0.5s;
  }

  /* Content area */
  main {
    max-width: 900px;
    margin: 40px auto 60px;
    padding: 0 15px;
  }

  .section-card {
    background: var(--white);
    border-radius: 16px;
    box-shadow: 0 8px 20px var(--shadow-color);
    padding: 30px 35px;
    margin-bottom: 30px;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.7s ease forwards;
  }
  .section-card:nth-child(1) { animation-delay: 0.3s; }
  .section-card:nth-child(2) { animation-delay: 0.6s; }
  .section-card:nth-child(3) { animation-delay: 0.9s; }
  .section-card:nth-child(4) { animation-delay: 1.2s; }

  .section-card:hover {
    box-shadow: 0 15px 40px rgba(27,59,76,0.3);
    transform: translateY(0);
    transition: all 0.3s ease;
  }

  .section-card h2 {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 1.9rem;
    color: var(--green);
    margin-bottom: 15px;
    position: relative;
    padding-left: 18px;
  }
  .section-card h2::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 6px;
    height: 30px;
    background: var(--yellow);
    border-radius: 3px;
  }

  .section-card p, .section-card ul {
    font-size: 1.1rem;
    line-height: 1.6;
    color: var(--green);
  }

  ul {
    padding-left: 22px;
    margin-top: 10px;
  }

  ul li {
    margin-bottom: 12px;
  }

  a {
    color: var(--green);
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s ease;
  }
  a:hover {
    color: var(--yellow);
    text-decoration: underline;
  }

  /* Animations */
  @keyframes fadeInUp {
    0% {
      opacity: 0;
      transform: translateY(20px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes fadeIn {
    from {opacity:0;}
    to {opacity:1;}
  }

  /* Responsive */
  @media (max-width: 576px) {
    .banner-content h1 {
      font-size: 2.3rem;
    }
    .banner-content p {
      font-size: 1rem;
    }
    main {
      margin: 25px auto 40px;
    }
    .section-card {
      padding: 20px 25px;
    }
    .section-card h2 {
      font-size: 1.5rem;
    }
  }
</style>

<header class="banner" role="banner" aria-label="College Banner Image">
  <div class="banner-content">
    <h1><?= bp_get_current_group_name(); ?></h1>
    <?php /*<p>Empowering Minds Since 1995 | Affiliated to Delhi University</p>*/ ?>
  </div>
  <div class="naac-ribbon" aria-label="<?= !empty($group->status) ? ucfirst($group->status) : '-' ?>"><?= !empty($group->status) ? ucfirst($group->status) : '-' ?></div>
</header>

<main role="main" aria-label="College Profile Information">
  <section class="section-card" tabindex="0" aria-labelledby="about-heading">
    <h2 id="about-heading">About Us</h2>
    <p><?= $group->description ?></p>
  </section>

  <section class="section-card" tabindex="0" aria-labelledby="programs-heading">
    <h2 id="programs-heading">Academic Programs</h2>
    <ul>
      <li><strong>Coming Soon:</strong> Coming Soon</li>
      <?php /*<li><strong>B.A / B.Com:</strong> English, History, Psychology, Economics, Commerce (General & Honours)</li>
      <li><strong>M.Sc Programs:</strong> Data Science, Environmental Studies</li>
      <li><strong>Ph.D Programs:</strong> Life Sciences, Interdisciplinary Research Areas</li>*/ ?>
    </ul>
  </section>

  <section class="section-card" tabindex="0" aria-labelledby="facilities-heading">
    <h2 id="facilities-heading">Facilities & Quick Facts</h2>
    <ul>
      <li><strong>Location:</strong> <?= !empty($group_address) ? implode(", ", $group_address) : '-' ?></li>
      <li><strong>Students Enrolled:</strong> Coming Soon</li>
      <li><strong>Campus Area:</strong> Coming Soon</li>
    </ul>
  </section>

  <section class="section-card" tabindex="0" aria-labelledby="contact-heading">
    <h2 id="contact-heading">Contact Information</h2>
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
</main>

<?php get_footer(); ?>