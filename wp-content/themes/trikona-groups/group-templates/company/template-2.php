<?php
  get_header();
  require_once('company_data.php');
?>
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    /* Root colors */
    :root {
      --green: #1B3B4C;
      --yellow: #FFC000;
      --yellow-dark: #cca300;
      --bg: #f0f3f5;
      --white: #fff;
      --shadow: rgba(27, 59, 76, 0.15);
    }

    /* Reset & base */
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      font-family: 'Open Sans', sans-serif;
      background: var(--bg);
      color: var(--green);
    }

    /* Banner: full width */
    .banner {
      position: relative;
      width: 100%;
      height: 280px;
      background: url('<?= !empty($bp_group_banner) ? $bp_group_banner[0] : "https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1500&q=80";?>') no-repeat center/cover;
      border-radius: 0 0 20px 20px;
      overflow: hidden;
      box-shadow: 0 12px 25px var(--shadow);
      margin-bottom: 40px;
    }
    .banner::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, var(--green)cc 40%, var(--yellow)cc 90%);
      mix-blend-mode: multiply;
    }
    .banner-content {
      position: relative;
      z-index: 1;
      height: 100%;
      max-width: 900px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: var(--white);
      text-align: center;
      padding: 0 15px;
    }
    .banner-content h1 {
      font-family: 'Montserrat', sans-serif;
      font-weight: 700;
      font-size: 3rem;
      margin-bottom: 0.2rem;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      text-shadow: 2px 3px 10px rgba(0,0,0,0.6);
    }
    .banner-content p {
      font-size: 1.3rem;
      font-weight: 600;
      text-shadow: 1px 2px 6px rgba(0,0,0,0.5);
    }

    /* Container */
    .container {
      max-width: 900px;
      margin: 0 auto 50px;
      padding: 0 20px;
    }

    /* Section cards */
    .card {
      background: var(--white);
      margin-top: 40px;
      padding: 30px 30px 40px;
      border-radius: 20px;
      box-shadow: 0 10px 20px var(--shadow);
      display: flex;
      gap: 25px;
      align-items: center;
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.7s ease, transform 0.7s ease;
      flex-direction: row;
    }
    .card.visible {
      opacity: 1;
      transform: translateY(0);
    }
    .card-icon {
      flex-shrink: 0;
      width: 85px;
      height: 85px;
      background: var(--yellow);
      border-radius: 50%;
      color: var(--green);
      font-size: 42px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 5px 15px rgba(255,192,0,0.5);
      user-select: none;
    }
    .card-icon:hover {
      background: var(--yellow-dark);
      box-shadow: 0 8px 20px rgba(204,163,0,0.7);
      cursor: default;
    }
    .card-content {
      flex: 1;
    }
    .card-content h2 {
      font-family: 'Montserrat', sans-serif;
      font-weight: 700;
      font-size: 1.8rem;
      margin-bottom: 10px;
      color: var(--green);
    }
    .card-content p, .card-content ul {
      font-size: 1.1rem;
      color: var(--green);
      line-height: 1.5;
    }
    .card-content ul {
      margin: 8px 0 0 20px;
    }
    .card-content ul li {
      margin-bottom: 8px;
    }

    /* Contact links */
    .contact-list p {
      margin: 8px 0;
    }
    .contact-list i {
      margin-right: 10px;
      color: var(--yellow);
      vertical-align: middle;
    }
    .contact-list i.bi-envelope-paper-fill {
      color: var(--green) !important;
    }
    .contact-list a {
      color: var(--green);
      font-weight: 600;
      text-decoration: none;
      border-bottom: 2px solid transparent;
      transition: border-color 0.3s ease;
    }
    .contact-list a:hover {
      border-color: var(--yellow);
      color: var(--yellow-dark);
    }

    /* Responsive */
    @media (max-width: 700px) {
      .card {
        flex-direction: column;
        text-align: center;
        padding: 25px 20px 30px;
      }
      .card-icon {
        margin-bottom: 20px;
      }
    }
  </style>

  <!-- Banner outside container for full width -->
  <section class="banner" aria-label="Company Banner">
    <div class="banner-content">
      <h1><?= bp_get_current_group_name(); ?></h1>
      <?php /*<p>Innovating Sustainability Through Technology</p> */?>
    </div>
  </section>

  <div class="container" role="main">
    <!-- About -->
    <section class="card" tabindex="0" aria-labelledby="about-title">
      <div class="card-icon" aria-hidden="true"><i class="bi bi-info-circle-fill"></i></div>
      <div class="card-content">
        <h2 id="about-title">About Us</h2>
        <p><?= $group->description ?></p>
      </div>
    </section>

    <!-- Services -->
    <section class="card" tabindex="0" aria-labelledby="services-title">
      <div class="card-icon" aria-hidden="true"><i class="bi bi-gear-fill"></i></div>
      <div class="card-content">
        <h2 id="services-title">Our Services</h2>
        <ul>
          <li>Coming Soon</li>
          <?php /*<li>Smart IoT Devices</li>
          <li>Energy Management Software</li>
          <li>Green Data Center Solutions</li>
          <li>Environmental Impact Audits</li>*/ ?>
        </ul>
      </div>
    </section>

    <!-- Clients -->
    <section class="card" tabindex="0" aria-labelledby="clients-title">
      <div class="card-icon" aria-hidden="true"><i class="bi bi-people-fill"></i></div>
      <div class="card-content">
        <h2 id="clients-title">Our Clients</h2>
        <p>Coming Soon</p>
      </div>
    </section>

    <!-- Contact -->
    <section class="card contact-list" tabindex="0" aria-labelledby="contact-title">
      <div class="card-icon" aria-hidden="true"><i class="bi bi-envelope-paper-fill"></i></div>
      <div class="card-content">
        <h2 id="contact-title">Get In Touch</h2>
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
  </div>

  <script>
    // Scroll animation: fade and slide up
    document.addEventListener("DOMContentLoaded", () => {
      const cards = document.querySelectorAll('.card');

      const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            obs.unobserve(entry.target);
          }
        });
      }, { threshold: 0.3 });

      cards.forEach(card => observer.observe(card));
    });
  </script>
<?php get_footer(); ?>