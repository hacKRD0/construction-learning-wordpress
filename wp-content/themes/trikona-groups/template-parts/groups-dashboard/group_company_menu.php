 <?php 
    // For demo purpose corporate-basic corporate-elite corporate-prime
    $current_user = wp_get_current_user();

    if($_GET['detsild']=="branch"){
        $activeMenu1 = "btn-primary";
    }else{
    	$activeMenu1 = "btn-default";
    }

    if($_GET['detsild']=="projects"){
        $activeMenu2 = "btn-primary";	
    }else{
    	$activeMenu2 = "btn-default";
    }

    if($_GET['detsild']=="banner"){
        $activeMenu3 = "btn-primary";	
    }else{
    	$activeMenu3= "btn-default";
    }

    if($_GET['detsild']=="document"){
        $activeMenu4 = "btn-primary";	
    }else{
    	$activeMenu4 = "btn-default";
    }

    if($_GET['detsild']==""){
        $activeMenu = "btn-primary";	
    }else{ 
        $activeMenu = "btn-default";
    }

    if($_GET['detsild']=="services"){
        $activeMenu5 = "btn-primary";	
    }else{
    	$activeMenu5 = "btn-default";
    }
    if($_GET['detsild']=="logo"){
        $activeMenu6 = "btn-primary";	
    }else{
    	$activeMenu6 = "btn-default";
    }
 ?>
 <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
    <div class="btn-group" role="group">
        <a type="button" id="favorites" class="btn <?= $activeMenu1 ?>" href="<?= home_url() ?>/groups-dashboard//?managesGroups=true&detsild=branch&group_id=<?= $_GET['group_id'] ?>" ><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
            <div class="hidden-xs">Branches</div>
        </a>
    </div>
    <div class="btn-group" role="group">
        <a type="button" id="following" class="btn <?= $activeMenu2 ?>" href="<?= home_url() ?>/groups-dashboard/?managesGroups=true&detsild=projects&group_id=<?= $_GET['group_id'] ?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span>
            <div class="hidden-xs">Projects</div>
        </a>
    </div>
    <div class="btn-group" role="group">
        <a class="btn <?= $activeMenu3 ?>" href="<?= home_url() ?>/groups-dashboard/?managesGroups=true&detsild=banner&group_id=<?= $_GET['group_id'] ?>" ><span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
            <div class="hidden-xs">Company Banner</div>

        </a>
    </div>
    <div class="btn-group" role="group">
        <a class="btn <?= $activeMenu6 ?>" href="<?= home_url() ?>/groups-dashboard/?managesGroups=true&detsild=logo&group_id=<?= $_GET['group_id'] ?>" ><span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
            <div class="hidden-xs">Logo</div>
            
        </a>
    </div>
      <div class="btn-group" role="group">
        <a class="btn <?= $activeMenu4 ?>" href="<?= home_url() ?>/groups-dashboard/?managesGroups=true&detsild=document&group_id=<?= $_GET['group_id'] ?>" ><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
            <div class="hidden-xs">Upload profile </div>
            
        </a>
    </div>
    <div class="btn-group" role="group">
        <a class="btn <?= $activeMenu5 ?>" href="<?= home_url() ?>/groups-dashboard/?managesGroups=true&detsild=services&group_id=<?= $_GET['group_id'] ?>" ><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>
            <div class="hidden-xs">Services</div>
            
        </a>
    </div>
</div>