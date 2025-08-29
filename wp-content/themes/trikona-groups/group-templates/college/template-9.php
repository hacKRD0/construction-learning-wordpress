<?php
  get_header();
  require_once('college_data.php');
?>
<style>
  :root {
    --green: #1B3B4C;
    --yellow: #FFC000;
    --text-light: #f5f5f5;
    --bg-light: #fafafa;
  }
  body {
    margin: 0;
    background-color: var(--bg-light);
    font-family: 'Roboto', sans-serif;
    color: var(--green);
  }

  /* Hero Banner */
  .hero {
    position: relative;
    height: 360px;
    background: url('<?= !empty($bp_group_banner) ? $bp_group_banner[0] : "https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1470&q=80";?>') center/cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: var(--text-light);
    overflow: hidden;
  }

  .hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(27, 59, 76, 0.85), rgba(27, 59, 76, 0.6));
    z-index: 1;
  }

  .hero-content {
    position: relative;
    z-index: 2;
    max-width: 900px;
    animation: fadeSlideDown 1s ease forwards;
    padding: 0 15px;
  }

  .hero-content h1 {
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    font-size: 3.6rem;
    letter-spacing: 3px;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    background: linear-gradient(90deg, var(--yellow), #fff 70%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .hero-content p {
    font-weight: 500;
    font-size: 1.4rem;
    letter-spacing: 1.1px;
    color: var(--yellow);
    text-shadow: 0 1px 8px rgba(0,0,0,0.3);
  }

  /* NAAC Badge */
  .naac-badge {
    position: absolute;
    top: 24px;
    right: 24px;
    background-color: var(--yellow);
    color: var(--green);
    font-weight: 700;
    padding: 12px 30px;
    border-radius: 50px;
    font-size: 1.1rem;
    letter-spacing: 1.3px;
    box-shadow: 0 8px 25px rgba(255, 192, 0, 0.6);
    font-family: 'Poppins', sans-serif;
    user-select: none;
    z-index: 3;
    text-transform: uppercase;
    opacity: 0;
    animation: fadeIn 1.5s ease forwards 0.5s;
  }

  /* Main container */
  main.container {
    max-width: 960px;
    margin: 50px auto 80px;
    padding: 0 15px;
  }

  /* Card grid: Full width cards stacked vertically */
  .card-grid {
    display: flex;
    flex-direction: column;
    gap: 2.25rem;
  }

  /* Cards */
  .profile-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 12px 30px rgba(27, 59, 76, 0.15);
    padding: 32px 28px;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
    color: var(--green);
    cursor: default;
    position: relative;
    overflow: hidden;
    width: 100%;
    opacity: 0;
    animation: fadeSlideUp 0.7s ease forwards;
    animation-delay: var(--delay, 0s);
  }

  .profile-card::before {
    content: "";
    position: absolute;
    height: 5px;
    width: 60px;
    background: var(--yellow);
    top: 0;
    left: 0;
    border-radius: 0 0 8px 0;
  }

  .profile-card:hover {
    box-shadow: 0 25px 50px rgba(27, 59, 76, 0.35);
    transform: scale(1.04);
  }

  .profile-card h2 {
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    font-size: 1.8rem;
    margin-bottom: 1rem;
    letter-spacing: 1.2px;
    text-transform: uppercase;
  }

  .profile-card p,
  .profile-card ul {
    font-size: 1.05rem;
    line-height: 1.65;
  }

  .profile-card ul {
    padding-left: 20px;
    margin-top: 10px;
  }

  .profile-card ul li {
    margin-bottom: 10px;
    position: relative;
    padding-left: 22px;
    color: var(--green);
  }

  /* Links */
  a {
    position: relative;
    color: var(--yellow);
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
  }
  a::after {
    content: "";
    position: absolute;
    width: 0%;
    height: 2px;
    left: 0;
    bottom: -3px;
    background-color: var(--yellow);
    transition: width 0.3s ease;
  }
  a:hover {
    color: var(--green);
  }
  a:hover::after {
    width: 100%;
  }

  /* Animations */
  @keyframes fadeSlideDown {
    0% {
      opacity: 0;
      transform: translateY(-30px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }
  @keyframes fadeSlideUp {
    0% {
      opacity: 0;
      transform: translateY(40px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }
  @keyframes fadeIn {
    to {
      opacity: 1;
    }
  }

  /* Responsive */
  @media (max-width: 600px) {
    .hero-content h1 {
      font-size: 2.5rem;
    }
    .hero-content p {
      font-size: 1.1rem;
    }
  }
</style>

<section class="hero" role="banner" aria-label="College Banner with image and name">
  <div class="naac-badge" aria-label="<?= !empty($group->status) ? ucfirst($group->status) : '-' ?>"><?= !empty($group->status) ? ucfirst($group->status) : '-' ?></div>
  <div class="hero-content">
    <h1><?= bp_get_current_group_name(); ?></h1>
    <?php /*<p>Empowering Minds Since 1995 &mdash; Affiliated to Delhi University</p>*/ ?>
  </div>
</section>

<main class="container" role="main" aria-label="College Profile Details">
  <div class="card-grid">

    <article class="profile-card" tabindex="0" aria-labelledby="about-title">
      <h2 id="about-title">About Us</h2>
      <p><?= $group->description ?></p>
    </article>

    <article class="profile-card" tabindex="0" aria-labelledby="programs-title">
      <h2 id="programs-title">Academic Programs</h2>
      <ul>
        <li>Coming Soon: Coming Soon</li>
        <?php /*<li>B.A / B.Com: English, History, Psychology, Economics</li>
        <li>M.Sc: Data Science, Environmental Studies</li>
        <li>Ph.D: Life Sciences and Interdisciplinary Research</li>*/ ?>
      </ul>
    </article>

    <article class="profile-card" tabindex="0" aria-labelledby="facilities-title">
      <h2 id="facilities-title">Facilities & Quick Facts</h2>
      <ul>
        <li><i class="bi bi-geo-alt-fill"></i> Location: <?= !empty($group_address) ? implode(", ", $group_address) : '-' ?></li>
        <li><i class="bi bi-people-fill"></i> Students Enrolled: Coming Soon</li>
        <li><i class="bi bi-building"></i> Campus Area: Coming Soon</li>
      </ul>
    </article>

    <article class="profile-card" tabindex="0" aria-labelledby="contact-title">
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
    </article>

  </div>
</main>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll('.profile-card');
    cards.forEach((card, index) => {
      card.style.setProperty('--delay', `${index * 0.3}s`);
    });
  });
</script>
<?php get_footer(); ?>