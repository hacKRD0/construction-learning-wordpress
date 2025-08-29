<?php
/*Template Name: Download CV 2 */
//wp_head();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include(get_stylesheet_directory() .'/dompdf/autoload.inc.php');
$user_id = $_GET['id'];
if($user_id!=""){
  $fname = 
  $linkedin = bp_get_profile_field_data('field=Linkedin Profile&user_id='. $user_id);
  $phone = bp_get_profile_field_data('field=Phone&user_id='. $user_id);
  $mobile = bp_get_profile_field_data('field=Mobile&user_id='. $user_id);
  $address = bp_get_profile_field_data('field=Address&user_id='. $user_id);
  $totalExpereince = bp_get_profile_field_data('field=Total Expereince&user_id='. $user_id);
  $skill = bp_get_profile_field_data('field=Skills&user_id='. $user_id);
  $user_meta = get_userdata($user_id);
  $fname = $user_meta->user_firstname;
  $lname = $user_meta->user_lastname;
  $email = $user_meta->user_email;
  $user_roles = $user_meta->roles;
  $user = new BP_Core_User( $user_id );
  $user_avatar = $user->avatar;
  
  global $wpdb,$bp;
  
  $members_experience = get_user_meta( $user_id,'members_experience_',true);
  $exp= get_user_meta( $user_id,'Total Expereince',true);
  $hedu= get_user_meta( $user_id,'Highest Education',true);
  $skill= get_user_meta( $user_id,'Skills',true);
  $crstd= get_user_meta( $user_id,'Current Year Of Study',true);
  $yearpass= get_user_meta( $user_id,'Year Of Passout',true);
  $toatlStudy= get_user_meta( $user_id,'Total year of study',true);
  $usersMetaArr = array($exp,$hedu,$skill,$crstd,$yearpass,$toatlStudy);
  $member_bio= get_user_meta( $user_id,'member_bio',true);
  $members_education = get_user_meta( $user_id,'members_education_',true);
  
  $memberDob = get_user_meta( $user_id, 'memberDob', true );
   $designation_current = get_user_meta( $user_id, 'designation_current', true );
   $gender = get_user_meta( $user_id, 'gender', true );
    $member_type = get_user_meta( $user_id, 'member_type', true );
     $bp_member_type = get_user_meta( $user_id, 'bp_member_type', true ); 
     //$members_experience = $wpdb->get_results("SELECT * from wpcw_user_expriances where user_id='$user_id' ORDER By id DESC");
}
ob_start();
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$options = $dompdf->getOptions(); 
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf->setOptions($options);
$html ='<!DOCTYPE html>
<html>
<head>
<link type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
<style> 
@media dompdf {
  * { line-height: 1.2; }
}
@page { margin:0px; }
    .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
     
    }
    .col-sm-12 {
      width: 100%;
    }
    .col-sm-11 {
      width: 91.66666667%;
    }
    .col-sm-10 {
      width: 83.33333333%;
    }
    .col-sm-9 {
      width: 75%;
    }
    .col-sm-8 {
      width: 66.66666667%;
    }
    .col-sm-7 {
      width: 58.33333333%;
    }
    .col-sm-6 {
      width: 50%;
    }
    .col-sm-5 {
      width: 41.66666667%;
    }
    .col-sm-4 {
      width: 33.33333333%;
    }
    .col-sm-3 {
      width: 25%;
    }
    .col-sm-2 {
      width: 16.66666667%;
    }
    .col-sm-1 {
      width: 8.33333333%;
    }
    .col-sm-pull-12 {
      right: 100%;
    }
    .col-sm-pull-11 {
      right: 91.66666667%;
    }
    .col-sm-pull-10 {
      right: 83.33333333%;
    }
    .col-sm-pull-9 {
      right: 75%;
    }
    .col-sm-pull-8 {
      right: 66.66666667%;
    }
    .col-sm-pull-7 {
      right: 58.33333333%;
    }
    .col-sm-pull-6 {
      right: 50%;
    }
    .col-sm-pull-5 {
      right: 41.66666667%;
    }
    .col-sm-pull-4 {
      right: 33.33333333%;
    }
    .col-sm-pull-3 {
      right: 25%;
    }
    .col-sm-pull-2 {
      right: 16.66666667%;
    }
    .col-sm-pull-1 {
      right: 8.33333333%;
    }
    .col-sm-pull-0 {
      right: auto;
    }
    .col-sm-push-12 {
      left: 100%;
    }
    .col-sm-push-11 {
      left: 91.66666667%;
    }
    .col-sm-push-10 {
      left: 83.33333333%;
    }
    .col-sm-push-9 {
      left: 75%;
    }
    .col-sm-push-8 {
      left: 66.66666667%;
    }
    .col-sm-push-7 {
      left: 58.33333333%;
    }
    .col-sm-push-6 {
      left: 50%;
    }
    .col-sm-push-5 {
      left: 41.66666667%;
    }
    .col-sm-push-4 {
      left: 33.33333333%;
    }
    .col-sm-push-3 {
      left: 25%;
    }
    .col-sm-push-2 {
      left: 16.66666667%;
    }
    .col-sm-push-1 {
      left: 8.33333333%;
    }
    .col-sm-push-0 {
      left: auto;
    }
    .col-sm-offset-12 {
      margin-left: 100%;
    }
    .col-sm-offset-11 {
      margin-left: 91.66666667%;
    }
    .col-sm-offset-10 {
      margin-left: 83.33333333%;
    }
    .col-sm-offset-9 {
      margin-left: 75%;
    }
    .col-sm-offset-8 {
      margin-left: 66.66666667%;
    }
    .col-sm-offset-7 {
      margin-left: 58.33333333%;
    }
    .col-sm-offset-6 {
      margin-left: 50%;
    }
    .col-sm-offset-5 {
      margin-left: 41.66666667%;
    }
    .col-sm-offset-4 {
      margin-left: 33.33333333%;
    }
    .col-sm-offset-3 {
      margin-left: 25%;
    }
    .col-sm-offset-2 {
      margin-left: 16.66666667%;
    }
    .col-sm-offset-1 {
      margin-left: 8.33333333%;
    }
    .col-sm-offset-0 {
      margin-left: 0%;
    }
    .visible-xs {
      display: none !important;
    }
    .hidden-xs {
      display: block !important;
    }
    table.hidden-xs {
      display: table;
    }
    tr.hidden-xs {
      display: table-row !important;
    }
    th.hidden-xs,
    td.hidden-xs {
      display: table-cell !important;
    }
    .hidden-xs.hidden-print {
      display: none !important;
    }
    .hidden-sm {
      display: none !important;
    }
    .visible-sm {
      display: block !important;
    }
    table.visible-sm {
      display: table;
    }
    tr.visible-sm {
      display: table-row !important;
    }
    th.visible-sm,
    td.visible-sm {
      display: table-cell !important;
    }
    .cs-layout{
      padding: 0px;
    }
    
    .fa {
      display: inline;
      font-style: normal;
      font-variant: normal;
      font-weight: normal;
      font-size: 14px;
      line-height: 1;
      font-family: FontAwesome;
      font-size: inherit;
      text-rendering: auto;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    .mr-left-10{
      margin-left:10px;
    }
    .mr-left-20{
      margin-left:10px;
    }
    .fa-star{
      color:#002a4d;
      padding-right:5px;
    }
    .img-box img{
        width: 120px;
        height:120px;
        border-radius: 50%;
    }
   .pgbrek{
    page-break-inside: avoid;
   }
   .container {
    padding-left: 16px;
    padding-right: 16px;
  }
  .col-6 {
    width: 50%;
    display: block;
    float: left;
    height:auto;
  }
</style>
<title>Professional profile</title>
</head>
<body>

<section class="top-sec" style="background-color: #002e63; padding:20px; color:#fff;">
<div class="container">
    <div class="row top-row" style="align:center">
      <div class="col-sm-8">
          <h2>'.$fname.' '. $lname.'</h2>
          <h5 style="color:#ffc40c">'.$designation_current.'</h5>
          <p>'.$member_bio.'</p>  
      </div>
      <div class="col-sm-4">
          <div class="img-box">
          '.$user_avatar.'
          </div>
      </div>
      
    </div>
</div>
</section>
<section class="top-second-sec" style="background-color: #1d2951; color:#fff; padding: 5px 10px;">
<div class="container">
    <div class="row" style="width: 90%; text-align:center;">
      <div class="col-sm-3">
      <div class="icon-box"><span class="fa fa-map-marker"> </span><span style="margin-left:10px;">'. $address .'</span></div> 
      </div>
      <div class="col-sm-3">
      <div class="icon-box"> <span class="fa fa-phone"> </span><span style="margin-left:10px;">'. $phone .'</span></div>  
      </div>
      <div class="col-sm-3">
      <div class="icon-box"><span class="fa fa-calendar"> </span><span style="margin-left:10px;">'. $memberDob.'</span></div> 
      </div>
      <div class="col-sm-3">
      <div class="icon-box"><span class="fa fa-envelope"> </span><span style="margin-left:10px;">'. $email .'</span></div>
      </div>
    </div>
</div>
</section>
<section class="con-sec" style="padding:20px;">
<div class="container">
  <div class="row">
    <div class="col-sm-12">
    <h2 style="color:#002a4d">Work & Experience</h2>                   
    '; 
  
    global $wpdb;
    $members_experience = $wpdb->get_results("SELECT * from wpcw_user_expriances where user_id='$user_id' ORDER By id DESC");
    $count =1;
foreach ($members_experience as $key => $experiences) { 
$html .='<div>';
 $html .=     '<h3>'.$experiences->organization.'</h3>';
 $html .=     '<p><span>Designation : </span>'. $experiences->position.'</p>';
$html .=      '<p><span>Work Experience : </span>'. $experiences->experience.'</p>';
$html .=      '<p><span>Description : </span>'. $experiences->responsibility.'</p>';    
$html .=     '<p><span>Time Period : </span>'. $experiences->fromdate.' -  '.$experiences->todate.'</p>';
$html .=    '</div> <div style="border-bottom: 2px solid #e1e1e1; padding:5px;"></div>';
   
      } 
      $html .=  '<h2 style="color:#002a4d">Education</h2>
        '; 
   
    global $wpdb;
    $members_experience = $wpdb->get_results("SELECT * from wpcw_user_educations where user_id='$user_id' ORDER By id DESC");
    $count =1;
foreach ($members_experience as $key => $experiences) { 
$html .='<div>';
 $html .=     '<h3>'.$experiences->institute_name.'</h3>';
 $html .=     '<p><span>Duration of Study : </span>'. $experiences->duration.'</p>';
$html .=      '<p><span>Course of Study  : </span>'. $experiences->courseofStudy.'</p>';
$html .=      '<p><span>Year of Passout : </span>'. $experiences->year_passout.'</p>';    
$html .=     '<p><span>Qualification : </span>'. $experiences->qualification.' </p>';
 $html .=     '<p><span>Description : </span>'. $experiences->description.' </p>';
  $html .=     '<p><span>Time Period : </span>'. $experiences->startDate.' </p>';
$html .=    '</div>';
   
      } 
       if(!empty($skill['skill']) && $skill['skill']!=""){
      $html .=  ' <div style="border-bottom: 2px solid #e1e1e1; padding:5px;"></div><h2 style="color:#002a4d">My skills</h2>                  
      <table class="table" style="width:100%;">
                  <tbody>
                      
      '; 
     
foreach ($skill['skill'] as $key => $value)
{
$scores =$skill['scores'][ $key];
if($scores == 1){ $star = '<i class="fa fa-star"></i>'; }
if($scores == 2){ $star = '<i class="fa fa-star"></i><i class="fa fa-star"></i>'; }
if($scores == 3){ $star = '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>'; }
if($scores == 4){ $star = '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>'; }
if($scores == 5){ $star = '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>'; }
  
 $html .=     '<tr><td style="font-size:16px;">'.$value.'</td><td style="font-size:16px;">'.$star.'</td></tr>';

      }
  }

     $html .=  ' </tbody></table></div></div></div></section></body></html>';
    $html = preg_replace('/>\s+</', "><", $html);
$dompdf->loadHtml($html);
// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');
// Render the HTML as PDF
$dompdf->render();
// Output the generated PDF to Browser
 $user_info = get_userdata($_GET['id']);
$dompdf->stream($user_info->user_login.'.pdf', array("Attachment" => false));
//$dompdf->clear();
?>