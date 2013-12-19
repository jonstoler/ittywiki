<?php $bc = $page->breadcrumbs(); ?>

<ul class="breadcrumb">
	<?php for($i = 0; $i < count($bc) - 1; $i++): ?>
		<li>
			<a href="<?php echo $bc[$i]['url'] ?>"><?php echo $bc[$i]['name'] ?></a>
			<span class="divider">&rsaquo;</span>
		</li>
	<?php endfor ?>

	<li class="active"><?php echo $bc[$i]['name'] ?></li>
</ul>