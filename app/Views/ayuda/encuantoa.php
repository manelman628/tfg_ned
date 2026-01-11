<?= $this->extend('layout') ?>

<?= $this->section('extrahead') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
	<h3 class="text-secondary"><?php echo env('app.titleName');?></h3>
	<p><?php echo env('app.description');?></p>
	<h3 class="text-secondary">Autoria</h3>
	<p>Direcció de Sistemes d'Informació i Comunicació - Gerència Territorial de les Terres de l'Ebre - Institut Català de la Salut</p>
	<h3 class="text-secondary">Equipo del proyecto</h3>
	<p>Jordi Baucells, Adrià Suazo, Manel Segura</p>
	<h3 class="text-secondary">Programario utilitzado</h3>
	<div class="row">
		<div class="col-sm-12 col-md-6">
			<ul class="list-group mb-3">
				<a href="https://github.com/briannesbitt/Carbon" rel="noopener noreferrer" target="_blank" class="list-group-item list-group-item-action">Carbon</a>
				<a href="https://github.com/codeigniter4/CodeIgniter4" rel="noopener noreferrer" target="_blank" class="list-group-item list-group-item-action">Codeigniter</a>
				<a href="https://github.com/php" rel="noopener noreferrer" target="_blank" class="list-group-item list-group-item-action">PHP</a>
				<a href="https://github.com/apereo/phpCAS" rel="noopener noreferrer" target="_blank" class="list-group-item list-group-item-action">PHPCas</a>
			</ul>
		</div>
		<div class="col-sm-12 col-md-6">
			<ul class="list-group mb-3">
				<a href="https://github.com/twbs/bootstrap" rel="noopener noreferrer" target="_blank" class="list-group-item list-group-item-action">Bootstrap</a>
				<a href="https://github.com/snapappointments/bootstrap-select" rel="noopener noreferrer" target="_blank" class="list-group-item list-group-item-action">Bootstrap-select</a>
				<a href="https://github.com/FortAwesome/Font-Awesome" rel="noopener noreferrer" target="_blank" class="list-group-item list-group-item-action">Font Awesome</a>
				<a href="https://github.com/jquery/jquery" rel="noopener noreferrer" target="_blank" class="list-group-item list-group-item-action">JQuery</a>
			</ul>
		</div>
	</div>

<?= $this->endSection() ?>

<?= $this->section('extrafooter') ?>
<?= $this->endSection() ?>
