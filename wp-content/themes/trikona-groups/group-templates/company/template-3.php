<?php
  get_header();
  require_once('company_data.php');

  function getShortCode($text) {
      // Normalize whitespace
      $text = trim(preg_replace('/\s+/', ' ', $text));
      $words = explode(' ', $text);

      if (count($words) > 1) {
          // Get first character of first two words (no loop)
          $firstChar = isset($words[0][0]) ? $words[0][0] : '';
          $secondChar = isset($words[1][0]) ? $words[1][0] : '';
          $short = $firstChar . $secondChar;
      } else {
          // Get first two characters of single word
          $short = substr($words[0], 0, 2);
      }

      return strtoupper($short);
  }
?>
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    :root {
      --green: #1B3B4C;
      --yellow: #FFC000;
      --yellow-dark: #cca300;
      --white: #fff;
      --light-bg: #f9fafb;
      --shadow: rgba(27, 59, 76, 0.15);
    }

    /* Reset */
    * {
      margin: 0; padding: 0; box-sizing: border-box;
    }

    body {
      font-family: 'Roboto', sans-serif;
      background: var(--light-bg);
      color: var(--green);
      min-height: 100vh;
    }

    /* Hero Banner */
    .hero {
      position: relative;
      background: url('<?= !empty($bp_group_banner) ? $bp_group_banner[0] : "https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1500&q=80";?>') center/cover no-repeat;
      height: 320px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .hero::before {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(27, 59, 76, 0.75);
      z-index: 0;
    }
    .hero-content {
      position: relative;
      color: var(--yellow);
      text-align: center;
      z-index: 1;
      max-width: 90%;
    }
    .hero-content h1 {
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      font-size: 3.8rem;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      margin-bottom: 0.3rem;
      text-shadow: 1px 2px 8px rgba(0,0,0,0.7);
    }
    .hero-content p {
      font-size: 1.5rem;
      font-weight: 500;
      text-shadow: 1px 2px 6px rgba(0,0,0,0.6);
    }

    /* Logo circle overlapping hero */
    .logo-circle {
      position: relative;
      width: 140px;
      height: 140px;
      background: var(--yellow);
      border-radius: 50%;
      border: 8px solid var(--white);
      margin: -70px auto 40px;
      box-shadow: 0 10px 25px var(--shadow);
      display: flex;
      justify-content: center;
      align-items: center;
      color: var(--green);
      font-size: 4.5rem;
      font-weight: 700;
      font-family: 'Poppins', sans-serif;
      user-select: none;
    }

    /* Main content */
    .container {
      max-width: 720px;
      margin: 0 auto 70px;
      padding: 0 20px;
    }

    /* Section cards */
    section.card {
      background: var(--white);
      border-radius: 20px;
      padding: 28px 28px 36px;
      margin-bottom: 32px;
      box-shadow: 0 12px 30px var(--shadow);
      opacity: 0;
      transform: translateY(40px);
      transition: opacity 0.6s ease, transform 0.6s ease;
      cursor: default;
    }
    section.card.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* Icon bar */
    .icon-bar {
      width: 52px;
      height: 52px;
      background: var(--green);
      color: var(--yellow);
      font-size: 26px;
      border-radius: 12px;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      margin-right: 18px;
      vertical-align: middle;
      user-select: none;
    }

    /* Header in cards */
    .card h2 {
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      font-size: 1.9rem;
      color: var(--green);
      margin-bottom: 14px;
      display: flex;
      align-items: center;
    }

    /* Text content */
    .card p, .card ul {
      font-size: 1.12rem;
      line-height: 1.55;
      color: var(--green);
    }
    .card ul {
      margin-left: 22px;
      margin-top: 6px;
    }
    .card ul li {
      margin-bottom: 8px;
    }

    /* Contact list styling */
    .contact-list p {
      display: flex;
      align-items: center;
      margin: 10px 0;
      font-weight: 600;
    }
    .contact-list i {
      color: var(--yellow); /* change icon color to yellow */
      font-size: 1.4rem;
      margin-right: 12px;
      min-width: 26px;
      text-align: center;
    }
    .contact-list a {
      color: var(--green);
      text-decoration: none;
      border-bottom: 2px solid transparent;
      transition: border-color 0.3s ease;
    }
    .contact-list a:hover {
      border-color: var(--yellow);
      color: var(--yellow-dark);
    }

    /* Responsive */
    @media (max-width: 480px) {
      .hero-content h1 {
        font-size: 2.6rem;
      }
      .hero-content p {
        font-size: 1.2rem;
      }
      .logo-circle {
        width: 110px;
        height: 110px;
        font-size: 3.5rem;
        margin: -55px auto 32px;
      }
    }
  </style>

  <!-- Hero Banner -->
  <header class="hero" role="banner" aria-label="Company Banner">
    <div class="hero-content">
      <h1><?= bp_get_current_group_name(); ?></h1>
      <?php /*<p>Innovating Sustainability Through Technology</p>*/ ?>
    </div>
  </header>

  <!-- Logo Circle -->
  <div class="logo-circle" aria-hidden="true" title="Company Logo"><?= getShortCode(bp_get_current_group_name()) ?></div>

  <!-- Main Content -->
  <main class="container" role="main" tabindex="0">
    <!-- About Us -->
    <section class="card" aria-labelledby="about-title">
      <h2><span class="icon-bar"><i class="bi bi-info-circle-fill"></i></span>About Us</h2>
      <p><?= $group->description ?></p>
    </section>

    <!-- Services -->
    <section class="card" aria-labelledby="services-title">
      <h2><span class="icon-bar"><i class="bi bi-tools"></i></span>Our Services</h2>
      <ul>
        <li>Coming Soon</li>
        <?php /*<li>Cloud Infrastructure & DevOps</li>
        <li>Artificial Intelligence & Machine Learning</li>
        <li>IT Consulting & Strategy</li>
        <li>Cybersecurity Solutions</li>*/ ?>
      </ul>
    </section>

    <!-- Clients -->
    <section class="card" aria-labelledby="clients-title">
      <h2><span class="icon-bar"><i class="bi bi-people-fill"></i></span>Our Clients</h2>
      <p>Coming Soon</p>
    </section>

    <!-- Contact -->
    <section class="card contact-list" aria-labelledby="contact-title">
      <h2><span class="icon-bar"><i class="bi bi-envelope-fill mr-0"></i></span>Get In Touch</h2>
      <p><i class="bi bi-telephone-fill" aria-hidden="true"></i> <?= !empty($phone) ? $phone : '-' ?></p>
      <p>
        <i class="bi bi-envelope-fill" aria-hidden="true"></i>
        <?php if(!empty($email)){ ?>
          <a href="mailto:<?= $email ?>"><?= $email ?></a>
        <?php } else { echo "-"; } ?>
      </p>
      <p>
        <i class="bi bi-globe" aria-hidden="true"></i>
        <?php if(!empty($grpwebsite)){ ?>
          <a href="<?= $grpwebsite ?>" target="_blank"><?= $grpwebsite ?></a>
        <?php } else { echo "-"; } ?>
      </p>
      <p><i class="bi bi-geo-alt-fill" aria-hidden="true"></i> <?= !empty($group_address) ? implode(", ", $group_address) : '-' ?></p>
    </section>
  </main>

  <script>
    // Fade-in scroll animation
    document.addEventListener('DOMContentLoaded', () => {
      const cards = document.querySelectorAll('.card');
      const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
          if(entry.isIntersecting) {
            entry.target.classList.add('visible');
            obs.unobserve(entry.target);
          }
        });
      }, { threshold: 0.3 });
      cards.forEach(card => observer.observe(card));
    });
  </script>
<?php get_footer(); ?>