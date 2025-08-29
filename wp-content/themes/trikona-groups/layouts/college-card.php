<!-- Team item-->
<div class="col-xl-4 col-sm-6 mb-20 <?php if(!empty($grpCons)){echo 'verified';}?>">
  <a class="card-link" href="<?= $href ?>">
    <div class="bg-white rounded shadow-sm py-20" style="padding-bottom:0px;"><img src="<?= $img ?>" alt="" width="150" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
      <?php if(!empty($grpCons)){ ?>
      <div class="verStatus">
        <img src="<?= home_url() ?>/wp-content/uploads/2023/08/quality.png" title="Verified">
      </div>
      <?php } ?>
      <h5 class="cs-hg-30" style="margin-top:20px;"><?= wp_trim_words( $mt->name, 4, '...' ) ?></h5>
      <span class="small text-uppercase text-muted" style="margin-top:-10px;"><?php if(!empty($grpcity)) { echo $grpcity; }else { echo '-'; } ?></span>
      <div class="row inner-college-data">
        <div class="col-md-6">
          <span>No. of Courses</span>         
          <p><?= $no_of_cources ?></p>     
        </div>
        <div class="col-md-6">
          <span>No. of Students</span>
          <p> <?= $no_of_students ?> </p> 
        </div>
      </div>
    </div>
  </a>
</div>
<!-- End-->