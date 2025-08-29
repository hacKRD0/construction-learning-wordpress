
<?php  if($_GET['mypoints']=='true'){ ?>
    <div class="row">
        <div class="col-md-12">
          <h5 class="cs-label-1">My Points</h5>
          </div>
      </div>
      <?php 
      echo do_shortcode('[mycred_history user_id='.$current_user->ID.' type=trikona_credit]');
         
     ?>

<?php } ?>