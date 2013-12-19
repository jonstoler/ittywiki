<?php
	$title = g::get('wiki.title') . ' - ' . ucwords($page->name);
?>

<?php include_once('snippets' . DS . 'header.php') ?>
<?php include_once('snippets' . DS . 'breadcrumbs.php') ?>

<div class="content">
	<header>
		<h1 class="title"><?php echo $page->title() ?></h1>
	</header>

	<?php echo $page->body() ?>
</div>

<?php include_once('snippets' . DS . 'footer.php') ?>