<?php 
/**
 * Contient l'entète du site
 * 
 * @author Nicolas BORDES <nicolasbordes@me.com>
 * 
 * @version 1.0
 */
?>
<div class="container">
	<div class="row">
		<div class="col s4">
			<a href="<?= BASE_URL ?>"><img src="<?= BASE_URL ?>_image/logo.jpg" id="logo" title="logo" /></a>
		</div>
		<div class="col s8">
			<h1 id="titre"><?= strtoupper(TITRE_SITE) ?></h1>
		</div>
	</div>
</div>
