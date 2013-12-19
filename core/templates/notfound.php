<?php
	$title = g::get('wiki.title') . ' - ' . 'Page Not Found';
?>

<?php include_once('snippets' . DS . 'header.php') ?>

<ul class="breadcrumb">
	<li>
		<a href="<?php echo $page->link() ?>">ittywiki</a>
	</li>
	<span class="divider">&rsaquo;</span>
	<li class="active">page not found</li>
</ul>

<div class="content">
	<header>
		<h1 class="center">404</h1>
		<h3 class="center">the page you were looking for could not be found.</h3>
	</header>
</div>

<?php include_once('snippets' . DS . 'footer.php') ?>