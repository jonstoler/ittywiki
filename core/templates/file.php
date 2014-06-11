<?php
	$title = g::get('wiki.title') . ' - ' . ucwords($page->name);
?>

<?php if(g::get('toc') && count($page->headers()) > 1): ?>
	<div class="toc">
		<?php foreach($page->headers() as $h): ?>
			<a href="#<?php echo $h['hash'] ?>" class="<?php echo $h['level'] ?>"><?php echo $h['text'] ?></a>
		<?php endforeach ?>
	</div>
<?php endif ?>

<div class="container">
	<?php include_once('snippets' . DS . 'header.php') ?>
	<?php include_once('snippets' . DS . 'breadcrumbs.php') ?>
	<div class="content">
		<header>
			<h1 class="title"><?php echo $page->title() ?></h1>
		</header>

		<?php echo $page->body() ?>
	</div>
	<?php include_once('snippets' . DS . 'footer.php') ?>
</div>
