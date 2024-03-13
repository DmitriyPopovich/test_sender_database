<?php if($message) { ?>
<div id="<?=$messageFlag?>_alert" class="alert alert-<?=$messageFlag?> alert-dismissible" role="alert">
    <span class="glyphicon glyph_alt <?php if($messageFlag == 'danger') { ?>glyphicon-warning-sign<?php } ?>"></span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <i class="glyphicon glyphicon-remove"></i>
    </button>
    <?php if($messageActive) { ?><a href="<?=$link?>" class="alert-link"><?=$message?></a><?php }?>
    <?php if(!$messageActive) { ?><a href="<?php if(isset($_SERVER['REDIRECT_URL'])) { ?><?=$_SERVER['REDIRECT_URL']?><?php } ?><?php if(!isset($_SERVER['REDIRECT_URL'])) { ?><?=$_SERVER['HTTP_REFERER']?><?php } ?>" class="alert-link"><?=$message?></a><?php }?>
</div>
<?php } ?>