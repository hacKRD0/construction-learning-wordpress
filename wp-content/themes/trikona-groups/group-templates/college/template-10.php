<?php
  get_header();
  require_once('college_data.php');
?>
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

<style>
  :root {
    --green: #1B3B4C;
    --yellow: #FFC000;
    --green-dark: #14303b;
    --green-light: #2f5e74;
    --yellow-light: #ffd24d;
    --yellow-dark: #cc9a00;
    --bg-white: #fff;
    --bg-light: #f7fafc;
    --shadow: rgba(27, 59, 76, 0.2);
  }

  * {
    box-sizing: border-box;
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
    margin: 0 0 12px 0;
    letter-spacing: 3px;
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

  ul li::marker {
    color: var(--yellow);
    font-weight: 700;
  }

  a {
    color: var(--green-dark);
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

  /* Banner with gradient overlay */
  .banner {
    position: relative;
    background: url('<?= !empty($bp_group_banner) ? $bp_group_banner[0] : "https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1470&q=80";?>') no-repeat center center/cover;
    color: var(--yellow);
    text-align: center;
    padding: 140px 20px 100px;
    overflow: hidden;
  }

  .banner::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(27,59,76,0.8), rgba(255,192,0,0.8));
    z-index: 0;
  }

  .banner h1 {
    font-size: 4.5rem;
    position: relative;
    z-index: 1;
    text-transform: uppercase;
    user-select: none;
    text-shadow: 0 2px 6px rgba(0,0,0,0.3);
  }

  .banner p {
    max-width: 750px;
    margin: 12px auto 0 auto;
    font-weight: 600;
    font-size: 1.5rem;
    position: relative;
    z-index: 1;
    text-shadow: 0 1px 4px rgba(0,0,0,0.3);
  }

  .naac-badge {
    position: absolute;
    top: 30px;
    right: 30px;
    background: linear-gradient(45deg, var(--yellow), var(--yellow-light));
    color: var(--green-dark);
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    padding: 14px 40px;
    border-radius: 50px;
    font-size: 1.2rem;
    letter-spacing: 2px;
    box-shadow: 0 6px 18px rgba(255, 192, 0, 0.7);
    user-select: none;
    z-index: 2;
  }

  main {
    max-width: 960px;
    margin: 70px auto 100px;
    padding: 0 20px;
  }

  .info-block {
    background: linear-gradient(135deg, var(--bg-white), #fcfcfc);
    border-radius: 16px;
    padding: 28px 28px 28px 48px;
    margin-bottom: 55px;
    display: flex;
    align-items: center;
    gap: 28px;
    box-shadow: 0 14px 25px rgba(27, 59, 76, 0.1);
    opacity: 0;
    transform: translateY(25px);
    transition: all 0.65s cubic-bezier(0.35, 0, 0.25, 1);
    position: relative;
  }

  .info-block.visible {
    opacity: 1;
    transform: translateY(0);
  }

  .info-block:hover {
    box-shadow: 0 20px 40px rgba(27, 59, 76, 0.2);
    transform: translateY(-5px) scale(1.02);
    transition: all 0.3s ease;
  }

  .info-icon {
    flex-shrink: 0;
    width: 80px;
    height: 80px;
    font-size: 40px;
    line-height: 1;
    color: var(--yellow);
    background: linear-gradient(145deg, var(--green), var(--green-light));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow:
      0 6px 12px rgba(0,0,0,0.1),
      inset 0 3px 6px rgba(255, 255, 255, 0.25);
    transition: background 0.3s ease, color 0.3s ease;
    user-select: none;
  }

  .info-block:hover .info-icon {
    background: linear-gradient(145deg, var(--green-light), var(--green-dark));
    color: var(--yellow-light);
    box-shadow:
      0 10px 22px rgba(0,0,0,0.15),
      inset 0 5px 8px rgba(255, 255, 255, 0.3);
  }

  .info-block:nth-child(even) {
    background: linear-gradient(135deg, var(--yellow-light), #fff8cc);
  }

  .info-block:nth-child(even) .info-icon {
    background: linear-gradient(145deg, var(--yellow), var(--yellow-light));
    color: var(--green);
  }

  .info-block:nth-child(even):hover .info-icon {
    background: linear-gradient(145deg, var(--yellow-light), var(--yellow-dark));
    color: var(--green-dark);
    box-shadow:
      0 10px 22px rgba(0,0,0,0.12),
      inset 0 5px 8px rgba(255, 255, 255, 0.25);
  }

  .info-content h2 {
    font-size: 2.4rem;
    margin-bottom: 10px;
    font-weight: 700;
  }

  .info-content p, .info-content ul {
    font-size: 1.15rem;
    line-height: 1.7;
    color: var(--green-dark);
  }

  .info-block:nth-child(even) .info-content p,
  .info-block:nth-child(even) .info-content ul {
    color: #4b4b00;
  }

  .info-content ul {
    padding-left: 28px;
  }

  /* Responsive */
  @media (max-width: 750px) {
    .info-block {
      flex-direction: column;
      padding-left: 28px;
      text-align: center;
      gap: 20px;
    }
    .info-content h2 {
      font-size: 2rem;
    }
    .info-icon {
      width: 72px;
      height: 72px;
      font-size: 36px;
    }
  }
</style>

<section class="banner" role="banner" aria-label="College Banner">
  <div class="naac-badge" aria-label="<?= !empty($group->status) ? ucfirst($group->status) : '-' ?>"><?= !empty($group->status) ? ucfirst($group->status) : '-' ?></div>
  <h1><?= bp_get_current_group_name(); ?></h1>
  <?php /*<p>Empowering Minds Since 1995 — Affiliated to Delhi University</p>*/ ?>
</section>

<main role="main" aria-label="College Profile Sections">
  <section class="info-block" tabindex="0" aria-labelledby="about-title">
    <div class="info-icon" aria-hidden="true"><i class="bi bi-building"></i></div>
    <div class="info-content">
      <h2 id="about-title">About Us</h2>
      <p><?= $group->description ?></p>
    </div>
  </section>

  <section class="info-block" tabindex="0" aria-labelledby="programs-title">
    <div class="info-icon" aria-hidden="true"><i class="bi bi-mortarboard-fill"></i></div>
    <div class="info-content">
      <h2 id="programs-title">Academic Programs</h2>
      <ul>
        <li>Coming soon: Coming soon</li>
        <?php /*<li>B.A / B.Com: English, History, Psychology, Economics</li>
        <li>M.Sc: Data Science, Environmental Studies</li>
        <li>Ph.D: Life Sciences and Interdisciplinary Research</li>*/ ?>
      </ul>
    </div>
  </section>

  <section class="info-block" tabindex="0" aria-labelledby="facilities-title">
    <div class="info-icon" aria-hidden="true"><i class="bi bi-gear-fill"></i></div>
    <div class="info-content">
      <h2 id="facilities-title">Facilities & Quick Facts</h2>
      <ul>
        <li><i class="bi bi-geo-alt-fill"></i> Location: <?= !empty($group_address) ? implode(", ", $group_address) : '-' ?></li>
        <li><i class="bi bi-people-fill"></i> Students Enrolled: Coming soon</li>
        <li><i class="bi bi-tree-fill"></i> Campus Area: Coming soon</li>
      </ul>
    </div>
  </section>

  <section class="info-block" tabindex="0" aria-labelledby="contact-title">
    <div class="info-icon" aria-hidden="true"><i class="bi bi-envelope-fill"></i></div>
    <div class="info-content">
      <h2 id="contact-title">Contact Information</h2>
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