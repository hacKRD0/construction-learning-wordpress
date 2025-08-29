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
      --green-dark: #14303b;
      --yellow-dark: #cc9a00;
      --bg-light: #f9f9f9;
      --shadow-light: rgba(27, 59, 76, 0.15);
    }

    body {
      margin: 0;
      font-family: 'Roboto', sans-serif;
      background: var(--bg-light);
      color: var(--green-dark);
      scroll-behavior: smooth;
    }

    h1, h2 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      margin-bottom: 10px;
      letter-spacing: 2px;
    }

    p, ul {
      font-size: 1.1rem;
      line-height: 1.6;
      margin: 0;
    }

    ul {
      padding-left: 24px;
      margin-top: 8px;
    }

    ul li {
      margin-bottom: 8px;
    }

    a {
      color: var(--green);
      font-weight: 600;
      text-decoration: none;
      position: relative;
      transition: color 0.3s ease;
    }

    a::after {
      content: '';
      position: absolute;
      left: 0;
      bottom: -4px;
      width: 0;
      height: 2.5px;
      background: var(--yellow);
      border-radius: 2px;
      transition: width 0.35s ease;
    }

    a:hover {
      color: var(--yellow-dark);
    }

    a:hover::after {
      width: 100%;
    }

    /* Banner */
    .banner {
      position: relative;
      background: url('<?= !empty($bp_group_banner) ? $bp_group_banner[0] : "https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1500&q=80";?>') no-repeat center center/cover;
      color: var(--yellow);
      text-align: center;
      padding: 130px 20px 90px;
      overflow: hidden;
    }

    .banner::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(27,59,76,0.85), rgba(255,192,0,0.85));
      z-index: 0;
    }

    .banner h1 {
      font-size: 4rem;
      position: relative;
      z-index: 1;
      text-transform: uppercase;
      user-select: none;
      text-shadow: 0 3px 7px rgba(0,0,0,0.35);
    }

    .banner p {
      max-width: 700px;
      margin: 12px auto 0 auto;
      font-weight: 600;
      font-size: 1.45rem;
      position: relative;
      z-index: 1;
      text-shadow: 0 1px 4px rgba(0,0,0,0.35);
    }

    main {
      max-width: 920px;
      margin: 60px auto 90px;
      padding: 0 20px;
    }

    .info-block {
      background: white;
      border-radius: 20px;
      padding: 30px 40px;
      margin-bottom: 50px;
      box-shadow: 0 10px 25px var(--shadow-light);
      display: flex;
      gap: 28px;
      align-items: center;
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.6s cubic-bezier(0.35, 0, 0.25, 1);
    }

    .info-block.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .info-icon {
      flex-shrink: 0;
      width: 90px;
      height: 90px;
      font-size: 45px;
      line-height: 1;
      color: var(--yellow);
      background: linear-gradient(135deg, var(--green), var(--green-dark));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow:
        0 8px 18px rgba(0,0,0,0.12),
        inset 0 4px 9px rgba(255, 255, 255, 0.2);
      transition: background 0.3s ease, color 0.3s ease;
      user-select: none;
    }

    .info-block:hover .info-icon {
      background: linear-gradient(135deg, var(--green-dark), var(--green));
      color: var(--yellow-dark);
      box-shadow:
        0 12px 28px rgba(0,0,0,0.2),
        inset 0 5px 11px rgba(255, 255, 255, 0.25);
    }

    .info-content {
      flex-grow: 1;
    }

    .info-content h2 {
      font-size: 2.5rem;
      margin-bottom: 14px;
      font-weight: 700;
      color: var(--green);
    }

    .info-content p, .info-content ul {
      font-size: 1.15rem;
      color: var(--green-dark);
    }

    .info-content ul {
      padding-left: 28px;
    }

    .info-content ul li {
      margin-bottom: 10px;
    }

    /* Responsive */
    @media (max-width: 720px) {
      .info-block {
        flex-direction: column;
        text-align: center;
      }

      .info-icon {
        margin-bottom: 18px;
        width: 80px;
        height: 80px;
        font-size: 38px;
      }
    }
  </style>

  <section class="banner" role="banner" aria-label="Company Banner">
    <h1><?= bp_get_current_group_name(); ?></h1>
    <?php /*<p>Driving Innovation, Empowering Businesses Worldwide</p> */?>
  </section>

  <main role="main" aria-label="Company Profile Sections">
    <section class="info-block" tabindex="0" aria-labelledby="about-title">
      <div class="info-icon" aria-hidden="true"><i class="bi bi-building"></i></div>
      <div class="info-content">
        <h2 id="about-title">About Us</h2>
        <p><?= $group->description ?></p>
      </div>
    </section>

    <section class="info-block" tabindex="0" aria-labelledby="services-title">
      <div class="info-icon" aria-hidden="true"><i class="bi bi-tools"></i></div>
      <div class="info-content">
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

    <section class="info-block" tabindex="0" aria-labelledby="clients-title">
      <div class="info-icon" aria-hidden="true"><i class="bi bi-people-fill"></i></div>
      <div class="info-content">
        <h2 id="clients-title">Our Clients</h2>
        <p>Coming Soon</p>
      </div>
    </section>

    <section class="info-block" tabindex="0" aria-labelledby="contact-title">
      <div class="info-icon" aria-hidden="true"><i class="bi bi-envelope-fill"></i></div>
      <div class="info-content">
        <h2 id="contact-title">Contact Us</h2>
        <p><i class="bi bi-telephone-fill"></i> Phone: <?= !empty($phone) ? $phone : '-' ?></p>
        <p>
          <i class="bi bi-envelope-fill"></i> Email:
          <?php if(!empty($email)){ ?>
            <a href="mailto:<?= $email ?>"><?= $email ?></a>
          <?php } else { echo "-"; } ?>
        </p>
        <p>
          <i class="bi bi-globe"></i> Website:
          <?php if(!empty($grpwebsite)){ ?>
            <a href="<?= $grpwebsite ?>" target="_blank"><?= $grpwebsite ?></a>
          <?php } else { echo "-"; } ?>
        </p>
        <p><i class="bi bi-geo-alt-fill"></i> Location: <?= !empty($group_address) ? implode(", ", $group_address) : '-' ?></p>
      </div>
    </section>
  </main>

  <script>
    // Animate info blocks on scroll - fade + translateY in
    document.addEventListener("DOMContentLoaded", () => {
      const blocks = document.querySelectorAll('.info-block');

      const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if(entry.isIntersecting){
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.25 });

      blocks.forEach(block => observer.observe(block));
    });
  </script>
<?php get_footer(); ?>