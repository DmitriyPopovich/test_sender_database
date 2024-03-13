<!DOCTYPE html>
<html>
<?=$header?>
<body>
<div id="container">
<header  id="header_section">
    <?php if(isset($top)) { echo $top; }?>
</header>
<section id="body_section">
	<div class="container">
        <?=$alertmessage?>
        <?php if(isset($auth)) echo $auth; ?>
        <?=$center?>
	</div>
</section>
<section id="footer_section">
    <div class="container">
    </div>
</section>
</div>
</body>
</html>