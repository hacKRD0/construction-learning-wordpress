<?php
  get_header();
  require_once('college_data.php');
?>
<style>
  :root {
    --green: #1B3B4C;
    --yellow: #FFC000;
    --gray-light: #f8f9fa;
    --gray-dark: #6c757d;
  }

  body {
    font-family: 'Inter', sans-serif;
    background-color: var(--gray-light);
    margin: 0;
    color: var(--green);
  }

  .profile-container {
    max-width: 900px;
    margin: 0 auto;
    background-color: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 6px 20px rgb(27 59 76 / 0.1);
    padding: 2.5rem 3rem;
  }

  .profile-header {
    border-bottom: 3px solid var(--yellow);
    padding-bottom: 1rem;
    margin-bottom: 2rem;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
  }

  .profile-header h1 {
    font-weight: 700;
    font-size: 2.5rem;
    margin: 0;
    letter-spacing: 1.5px;
  }

  .naac-badge {
    background-color: var(--yellow);
    color: var(--green);
    font-weight: 700;
    padding: 0.45rem 1rem;
    border-radius: 1.5rem;
    font-size: 1rem;
    letter-spacing: 1px;
    white-space: nowrap;
    box-shadow: 0 3px 8px rgb(255 192 0 / 0.5);
    user-select: none;
  }

  .subtitle {
    color: var(--gray-dark);
    font-size: 1.1rem;
    margin-top: 0.25rem;
    font-weight: 500;
    letter-spacing: 0.6px;
  }

  section {
    margin-bottom: 2.5rem;
  }

  section h2 {
    font-weight: 600;
    font-size: 1.6rem;
    border-left: 5px solid var(--yellow);
    padding-left: 12px;
    margin-bottom: 1rem;
    color: var(--green);
  }

  section p, section ul {
    font-size: 1.05rem;
    line-height: 1.6;
    color: var(--green);
  }

  ul {
    padding-left: 20px;
  }

  ul li {
    margin-bottom: 0.6rem;
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

  @media (max-width: 576px) {
    .profile-header {
      flex-direction: column;
      align-items: flex-start;
    }
    .profile-header h1 {
      font-size: 2rem;
      margin-bottom: 0.5rem;
    }
    .naac-badge {
      font-size: 0.9rem;
      padding: 0.35rem 0.75rem;
    }
  }
</style>

<main class="profile-container mt-4 mb-4" role="main" aria-label="College Profile">
  <header class="profile-header">
    <div>
      <h1><?= bp_get_current_group_name(); ?></h1>
      <?php /*<div class="subtitle" aria-label="College affiliation and establishment">Established 1995 | Affiliated to Delhi University</div>*/ ?>
    </div>
    <div class="naac-badge" aria-label="<?= !empty($group->status) ? ucfirst($group->status) : '-' ?>"><?= !empty($group->status) ? ucfirst($group->status) : '-' ?></div>
  </header>

  <section aria-labelledby="about-title" tabindex="0">
    <h2 id="about-title">About Us</h2>
    <p><?= $group->description ?></p>
  </section>

  <section aria-labelledby="programs-title" tabindex="0">
    <h2 id="programs-title">Academic Programs</h2>
    <ul>
      <li><strong>Coming Soon:</strong> Coming Soon</li>
      <?php /*<li><strong>B.A / B.Com:</strong> English, History, Psychology, Economics, Commerce (General & Honours)</li>
      <li><strong>M.Sc Programs:</strong> Data Science, Environmental Studies</li>
      <li><strong>Ph.D Programs:</strong> Life Sciences, Interdisciplinary Research Areas</li>*/ ?>
    </ul>
  </section>

  <section aria-labelledby="facilities-title" tabindex="0">
    <h2 id="facilities-title">Facilities & Quick Facts</h2>
    <ul>
      <li><strong>Location:</strong> New Delhi, India</li>
      <li><strong>Students Enrolled:</strong> Coming Soon</li>
      <li><strong>Campus Area:</strong> Coming Soon</li>
    </ul>
  </section>

  <section aria-labelledby="contact-title" tabindex="0">
    <h2 id="contact-title">Contact Information</h2>
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