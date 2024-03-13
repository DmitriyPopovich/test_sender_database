<?php function printSubItem($item, &$items, $childrens, $active, $uri, $submenu = false) { ?>
<?php if (count($items) == 0) return;
?>
<li <?php if(in_array($item->id, $active)){ ?> class="active" <?php } else if(($item->external)&&(is_null($item->parent_id))){ ?> class="dropdown"<?php }?>>
<a href="<?=$item->link?>" <?php if (($item->external)&&(is_null($item->parent_id))){ ?>class="dropdown-toggle" data-toggle="dropdown"<?php } else if (($item->external)&&(!is_null($item->parent_id))) { ?>class="navbar-link"<?php } ?>>
<?=$item->name?>
</a>
<?php if(!(($item->external)&&(is_null($item->parent_id)))){ ?>
</li><?php } ?>
<?php if(($item->external)&&(is_null($item->parent_id))){ ?>
<ul  class="dropdown-menu<?php if ($submenu){ ?> navbar-link<?php } ?>" role="menu"><?php }?>

    <?php
    while(true) {
    $key = array_search($item->id, $childrens);
    if (!$key){
    break;
    }
    ?>
    <?=printSubItem($items[$key], $items, $childrens, $active, $uri, true);
    unset($childrens[$key]);
?>
    <?php if((!array_search($item->id, $childrens))&&($item->external)){ ?>
</ul>
</li>
<?php } ?>
<?php } ?>
<?php } ?>
<nav class="navbar navbar-default navbar-fixed-top " role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" id="icon_navbar" href="#"><?=$menu_name?></a>
            <?php if($visible) { ?>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse-1">
                <span class="sr-only">Панель навигации</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?php } ?>
        </div>
        <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
            <ul id="topmenu" class="nav navbar-nav navbar-right">
                <?php foreach ($items as $item) {
    if(is_null($item->parent_id)) printSubItem($item, $items, $childrens, $active, $uri); ?>
                <?php } ?>
            </ul>

        </div>
    </div>
</nav>