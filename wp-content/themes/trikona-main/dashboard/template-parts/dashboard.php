  <?php if($_GET['job-applications']=='' && $_GET['upload-resume']=='' && $_GET['resume']=='' && $_GET['mypoints']=='' && $_GET['myaccout']=='' && $_GET['memberships']=='' && $_GET['updateEducations']=='' && $_GET['updateExpereince']=='' && $_GET['updateprofile']=="" && $_GET['updateavatar']=="" && $_GET['career']==''){?>
        <div class="card user-card-full">
            <div class="row m-l-0 m-r-0">
               
                <div class="col-sm-12">
                    <div class="card-block">                       
                    <!-- First Row -->
                        <div class="row profile-readfull">
                        <!-- First col -->
                        <div class="col-md-6">
                                <h6 class="cs-label">About Me</h6>
                                <p class="cs-data"><?php echo $member_bio;?></p>
                            </div> 
                        <!--Second col  -->
                        <div class="col-md-6">
                                <!-- Email -->
                                <h6 class="cs-label">Email : 
                                <span class="cs-data"><?php echo  $current_user->user_email ; ?></span></h6>
                                <!-- Phone -->
                                <h6 class="cs-label">Phone : 
                                <span class="cs-data"><?php echo $phone;?></span></h6>
                                <!-- DOB -->
                                <h6 class="cs-label">DOB : 
                                <span class="cs-data"><?php echo $memberDob;?></span></h6>
                                <!-- Gender -->
                                <h6 class="cs-label">Gender : 
                                <span class="cs-data"><?php  echo $gender;?></span></h6>
                                <!-- Linkedin -->
                                <h6 class="cs-label">Linkedin Profile : 
                                <span class="cs-data"><?php echo $linkedinProfile;?></span></h6>
                                <!-- Address -->
                                <h6 class="cs-label">Address : 
                                <span class="cs-data"><?php echo $address;?></span></h6>
                            </div>
                        </div>                 
                        
                        <div class="row profile-readfull">
                            <div class="col-md-6">
                                 <!-- Designation -->
                                 <h6 class="cs-label">Position : 
                                <span class="cs-data"><?php echo $designation_current;?></span></h6>
                                <!-- Organization -->
                                <h6 class="cs-label">Organization : 
                                <span class="cs-data"><?php echo $company_current;?></span></h6>
                                <!-- Total Exp. -->
                                <h6 class="cs-label"> Total Experience : 
                                <span class="cs-data"><?php echo $exp;?></span></h6>
                            </div>
                            <div class="col-md-6">                               
                                <!-- Highest Edu -->
                                <h6 class="cs-label"> Highest Education : 
                                <span class="cs-data"><?php echo $hedu;?></span></h6>
                                 <!-- Year passout -->
                                 <h6 class="cs-label">Year Of Passout : 
                                <span class="cs-data"><?php echo $yearpass;?></span></h6>
                                 <!-- Total Study -->
                           <h6 class="cs-label">Total year of study : 
                                <span class="cs-data"><?php echo $toatlStudy;?></span></h6>
                                <!-- Current yr of study -->
                                <h6 class="cs-label">Current Year Of Study : 
                                <span class="cs-data"><?php echo $crstd;?></span></h6>
                            </div>

                        </div>                    
                       
                        <div class="row profile-readfull">
                            <div class="col-md-6">
                                <!-- Skills -->
                            <h6 class="cs-label"> Skills : 
                            <span class="cs-data">
                                  <ul>
                                	<?php
                                	if(!empty($skill['skill'])){
                                  if($skill['skill']!=""){
                                 foreach ($skill['skill'] as $key => $value) { ?>

                                 	<li><?php echo $value;?></li>
                                	
                               <?php  } }  }?> 
                             </ul>
                                 </span>
                           </h6>
                          
                            </div>
                            
                        </div>

                         
                         <!-- <h6 class="m-b-20 m-t-40 p-b-5 b-b-default f-w-600"></h6>
                        <div class="col-sm-6">
                                <p class="m-b-10 f-w-600">  Skill Set</p>
                                <h6 class="text-muted f-w-400"><?php //echo $skill_set;?></h6>
                            </div> -->
                        </div>
                      </div>
                        
                    </div>
                </div>
            </div>
        </div>
       <?php } ?>