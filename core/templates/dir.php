<?php
	$title = g::get('wiki.title') . ' - ' . ucwords($page->name);
?>

<?php include_once('snippets' . DS . 'header.php') ?>
<?php include_once('snippets' . DS . 'breadcrumbs.php') ?>

<div class="content">
		<?php
			function process($dir){
				foreach($dir->files as $nested){
					if(is_a($nested, 'dir')){
						echo '<li class="directory"><a href="' . $nested->link() . '">' . $nested->name . '/</a></li>';
						echo '<ul>';
						process($nested);
					} else {
						if($nested->name !== 'index'){
							echo '<li><a href="' . $nested->link() . '">' . $nested->name . '</a></li>';
						}
					}
				}

				echo '</ul>';
			}
		?>

		<?php if(count($page->files) === 0): ?>
			<header>
				<h1 class="center">this folder is empty</h1>
			</header>
		<?php else: ?>
			<header>
				<h1 class="title">Pages</h1>
			</header>
			<ul class="filesystem">
				<?php process($page) ?>
			</ul>
		<?php endif ?>
</div>

<?php include_once('snippets' . DS . 'footer.php') ?>