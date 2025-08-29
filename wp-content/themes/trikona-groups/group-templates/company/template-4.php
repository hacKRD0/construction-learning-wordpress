<?php
  get_header();
  require_once('company_data.php');
?>
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    :root {
      --green: #1B3B4C;
      --yellow: #FFC000;
      --yellow-dark: #cca300;
      --bg: #f9fafb;
      --white: #fff;
      --shadow: rgba(27, 59, 76, 0.12);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Open Sans', sans-serif;
      background: var(--bg);
      color: var(--green);
      overflow-x: hidden;
      line-height: 1.6;
    }

    .banner {
      width: 100%;
      height: 320px;
      background: url('<?= !empty($bp_group_banner) ? $bp_group_banner[0] : "https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1500&q=80";?>') center center/cover no-repeat;
      display: flex;
      justify-content: center;
      align-items: center;
      color: var(--white);
      text-align: center;
      padding: 0 20px;
      box-shadow: inset 0 0 0 2000px rgba(27, 59, 76, 0.6);
    }

    .banner h1 {
      font-family: 'Montserrat', sans-serif;
      font-size: 3.8rem;
      letter-spacing: 0.14em;
      text-transform: uppercase;
      text-shadow: 2px 3px 8px rgba(0, 0, 0, 0.7);
      margin-bottom: 10px;
    }

    .banner p {
      font-weight: 600;
      font-size: 1.3rem;
      text-shadow: 1px 2px 6px rgba(0, 0, 0, 0.6);
    }

    .container {
      max-width: 900px;
      margin: 60px auto 80px;
      padding: 0 20px;
    }

    .timeline {
      position: relative;
      margin-left: 40px;
      border-left: 3px solid var(--yellow);
      padding-left: 40px;
    }

    .card {
      position: relative;
      margin-bottom: 50px;
      opacity: 0;
      transform: translateX(-30px);
      transition: opacity 0.7s ease, transform 0.7s ease;
      background-color: transparent;
    }

    .card.visible {
      opacity: 1;
      transform: translateX(0);
    }

    .card-icon {
      position: absolute;
      left: -66px;
      top: 8px;
      width: 56px;
      height: 56px;
      background-color: var(--yellow);
      border-radius: 50%;
      color: var(--green);
      font-size: 28px;
      display: flex;
      justify-content: center;
      align-items: center;
      box-shadow: 0 4px 12px rgba(255, 192, 0, 0.5);
    }

    .card-icon:hover {
      background-color: var(--yellow-dark);
      box-shadow: 0 6px 20px rgba(204, 163, 0, 0.7);
      cursor: default;
    }

    .card-content h2 {
      font-family: 'Montserrat', sans-serif;
      font-size: 1.8rem;
      margin-bottom: 10px;
      color: var(--green);
      letter-spacing: 0.03em;
    }

    .card-content p {
      font-size: 1.12rem;
      margin-bottom: 8px;
    }

    .card-content ul {
      margin-left: 20px;
      list-style-type: disc;
      font-size: 1.1rem;
    }

    .card-content ul li {
      margin-bottom: 6px;
    }

    .contact-info p {
      display: flex;
      align-items: center;
      font-size: 1.12rem;
      margin: 10px 0;
      color: var(--green);
    }

    .contact-info .card-content i {
      margin-right: 14px;
      color: var(--yellow);
      font-size: 1.4rem;
      min-width: 28px;
      text-align: center;
    }

    .contact-info a {
      color: var(--green);
      font-weight: 600;
      text-decoration: none;
      border-bottom: 2px solid transparent;
      transition: border-color 0.3s ease;
    }

    .contact-info a:hover {
      border-color: var(--yellow);
      color: var(--yellow-dark);
    }

    @media (max-width: 720px) {
      .container {
        margin: 40px auto 60px;
      }

      .timeline {
        margin-left: 20px;
        padding-left: 30px;
        border-left-width: 2px;
      }

      .card-icon {
        left: -46px;
        width: 48px;
        height: 48px;
        font-size: 24px;
        top: 12px;
      }
    }
  </style>

  <!-- Banner -->
  <header class="banner" role="banner" aria-label="Company Banner">
    <div>
      <h1><?= bp_get_current_group_name(); ?></h1>
      <?php /*<p>Innovating Sustainability Through Technology</p>*/ ?>
    </div>
  </header>

  <!-- Content -->
  <main class="container" role="main" tabindex="-1">
    <section class="timeline" aria-label="Company Profile Timeline">

      <!-- About -->
      <article class="card" tabindex="0" aria-labelledby="about-title">
        <div class="card-icon"><i class="bi bi-info-circle-fill"></i></div>
        <div class="card-content">
          <h2 id="about-title">About Us</h2>
          <p><?= $group->description ?></p>
        </div>
      </article>

      <!-- Services -->
      <article class="card" tabindex="0" aria-labelledby="services-title">
        <div class="card-icon"><i class="bi bi-gear-fill"></i></div>
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
      </article>

      <!-- Clients -->
      <article class="card" tabindex="0" aria-labelledby="clients-title">
        <div class="card-icon"><i class="bi bi-people-fill"></i></div>
        <div class="card-content">
          <h2 id="clients-title">Our Clients</h2>
          <p>Coming Soon</p>
        </div>
      </article>

      <!-- Contact -->
      <article class="card contact-info" tabindex="0" aria-labelledby="contact-title">
        <div class="card-icon"><i class="bi bi-envelope-fill"></i></div>
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
      </article>

    </section>
  </main>

  <!-- Scroll animation -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const cards = document.querySelectorAll('.card');
      const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            obs.unobserve(entry.target);
          }
        });
      }, { threshold: 0.2 });
      cards.forEach(card => observer.observe(card));
    });
  </script>
<?php get_footer(); ?>