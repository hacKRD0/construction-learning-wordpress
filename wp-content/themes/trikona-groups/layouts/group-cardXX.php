<style>
  .shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
  }
  .rounded {
    border-radius: .25rem!important;
  }
  .bg-white {
    background-color: #fff!important;
  }
  .py-20 {
    padding: 15px;
    text-align: center;
  }
  .rounded {
    border-radius: 20px !important;
    border: 1px solid #c5cdf5;
  }
  .mb-20{
    margin-bottom: 30px;
  }
  .mt-10{
    margin-top: 15px;
  }
  .cc-role {
    text-align: center;
    background-color: #3F51B5;
    color: #fff;
    padding: 4px;
    border-radius: 5px 10px 0px 0px;
  }
  .row.inner-college-data {
    border-top: 1px solid #dddddd;
    margin-top: 8px;
  }
  .inner-college-data span {
    font-size: 11px;
  }
  .inner-college-data p {
    font-size: 22px;
      font-weight: 600;
  }
  .inner-college-data .col-md-6+.col-md-6 {
    border-left: 1px solid #dddddd;
  }
  .inner-college-data .col-md-6 {
    padding-top: 12px;
  }
  .row.inner-college-data {
    border-top: 1px solid #dddddd;
    margin-top: 8px;
    background-color: #efefef;
    border-radius: 0px 0px 20px 20px;
  }
  .vibebp_groups_directory {
    margin-top: -20px !IMPORTANT;
  }
  .company-dir .cs-hg-30 {
    height: 50px;
  }
  .company-dir .img-thumbnail {
    width: 128px;
    height: 128px;
    object-fit: contain;
  }
  .verified .rounded {
    border-color: #FFC107;
  }
  .verStatus img {
    width: 80%;
  }
  .verStatus {
    position: absolute;
    right: 0px;
    top: 0;
  }
  .custom_filter {
    margin-bottom: 25px;
    padding: 0px 10px!important;
  }
  .custom_filter_values {
    margin-top: 9px;
  }
  .custom_filter span {
    font-weight: 700;
  }
  .custom_filter .cstm-btn {
    margin-bottom: 10px;
  }
  :root {
    --color-text: #FFF;
    --color-front: hsl(250deg, 100%, 40%);
    --color-back: hsl(252deg, 100%, 60%);
  }
  .tag {
    position: relative;
    display: inline-block;
    border-radius: 6px;
    clip-path: polygon(20px 0px, 100% 0px, 100% 100%, 0% 100%, 0% 20px);
    background: var(--global-color-yellow);
    padding: 10px 25px;
    margin: 0 8px;
    font-weight: 600;
    font-size: 18px;
    color: var(--color-text);
    transition: clip-path 500ms;
    top: -4em;
    left: -3.7em;
  }
  .tag:after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 20px;
    height: 20px;
    background: var(--color-back);
    box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.1); 
    border-radius: 0 0 6px 0;
    transition: transform 500ms;
  }
  .tag:hover {
    clip-path: polygon(0px 0px, 100% 0px, 100% 100%, 0% 100%, 0% 0px);
  }
  .tag:hover:after {
    transform: translate(-100%, -100%);
  }
</style>
<div class="col-xl-4 col-sm-6 mb-20 <?php if(!empty($grpCons)){echo 'verified';}?>">
  <a href="javascript:void(0);" data-group-id="<?php echo base64_encode($group->id); ?>" class="group-info-card">
    <?php if(!empty($grpCons)){ ?>
      <div class="verStatus">
        <img src="<?php echo home_url()?>/wp-content/uploads/2023/08/quality.png" title="Verified">
      </div>
    <?php } ?>				
    <div class="bg-white rounded shadow-sm py-20" style="padding-bottom:0px;">
      <?php if(!empty($group_type)){ ?>
        <span class="group-type tag"><?php echo ucfirst($group_type); ?></span>
      <?php } ?>
      <img src="<?php echo  $img ?>" alt="" width="150" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
      <h5 class="cs-hg-30" style="margin-top:20px;">
        <?php echo $group->name; ?>
      </h5>
      <div class="row inner-college-data">            
        <div class="col-md-12" style="padding-top:5px">                   
          <p class="jobs-card"> <span>Members - </span> <?php echo bp_get_group_member_count($group->id); ?></p> 
        </div>
      </div>
    </div>
  </a>
</div>