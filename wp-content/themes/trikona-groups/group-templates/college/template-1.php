<?php
    get_header();
    require_once('college_data.php');
?>
<style>
  body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #e0eafc, #cfdef3);
    margin: 0;
    padding: 0;
    color: #333;
  }

  .college-details-container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 30px;
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  }

  .header {
    display: flex;
    align-items: center;
    gap: 20px;
    border-bottom: 2px solid #eee;
    padding-bottom: 20px;
  }

  .logo {
    width: 90px;
    height: 90px;
    object-fit: contain;
    border-radius: 12px;
  }

  .header-info h1 {
    margin: 0;
    font-size: 30px;
    font-weight: 600;
    color: #2c3e50;
  }

  .header-info p {
    font-size: 15px;
    color: #666;
  }

  .section {
    margin-top: 30px;
  }

  .section h2 {
    font-size: 22px;
    color: #2980b9;
    border-left: 5px solid #3498db;
    padding-left: 12px;
    margin-bottom: 15px;
  }

  .card {
    background-color: #f9f9f9;
    border-radius: 10px;
    padding: 15px 20px;
    margin-bottom: 15px;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.05);
  }

  .grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
  }

  .list li {
    margin: 8px 0;
  }

  .contact a {
    color: #3498db;
    text-decoration: none;
  }

  .footer {
    text-align: center;
    margin-top: 40px;
    font-size: 14px;
    color: #aaa;
  }

  @media (max-width: 600px) {
    .header {
      flex-direction: column;
      text-align: center;
    }

    .logo {
      width: 70px;
      height: 70px;
    }
  }
</style>
<div class="container college-details-container">
  <div class="header">
    <?php if(!empty($bp_group_banner)){ ?>
        <img src="<?= $bp_group_banner[0];?>" alt="College Logo" class="logo">
    <?php } ?>
    <div class="header-info">
      <h1><?= bp_get_current_group_name(); ?></h1>
    </div>
  </div>

  <div class="section">
    <h2>About the College</h2>
    <div class="card">
      <p><?= $group->description ?></p>
    </div>
  </div>

  <div class="section">
    <h2>Quick Facts</h2>
    <div class="grid">
      <div class="card"><strong>Location:</strong> <?= !empty($group_address) ? implode(", ", $group_address) : '-' ?></div>
      <div class="card"><strong>Type:</strong> <?= !empty($group->status) ? ucfirst($group->status) : '-' ?></div>
      <div class="card"><strong>Campus:</strong> comming soon</div>
      <div class="card"><strong>Student Strength:</strong> comming soon</div>
    </div>
  </div>

  <div class="section">
    <h2>Academic Programs</h2>
    <div class="card">
        comming soon
      <?php /*<ul class="list">
        <li>B.Sc. – Computer Science, Physics, Chemistry</li>
        <li>B.A. – English, History, Economics</li>
        <li>B.Com (Hons. & General)</li>
        <li>M.Sc. – Data Science, Applied Mathematics</li>
        <li>Ph.D. – Life Sciences, Environmental Studies</li>
      </ul>*/ ?>
    </div>
  </div>

  <div class="section">
    <h2>Contact Information</h2>
    <div class="card contact">
        <p><strong>Phone:</strong> <?= !empty($phone) ? $phone : '-' ?></p>
        <p>
            <strong>Email:</strong>
            <?php if(!empty($email)){ ?>
                <a href="mailto:<?= $email ?>"><?= $email ?></a>
            <?php } else { echo "-"; }?>
        </p>
        <p>
            <strong>Website:</strong>
            <?php if(!empty($grpwebsite)){ ?>
                <a href="<?= $grpwebsite ?>" target="_blank"><?= $grpwebsite ?></a>
            <?php } else { echo "-"; }?>
        </p>
    </div>
  </div>
</div>

<?php get_footer(); ?>