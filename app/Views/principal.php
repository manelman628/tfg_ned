<?= $this->extend('layout') ?>

<?= $this->section('extrahead') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
	<div class="text-center">
		<h1 class="text-primary m-3"><?php echo env('app.titleName');?></h1>
		
		<?php if(!isset($usuario)) : ?>
		  <p><?= lang('Messages.login_link'); ?></p>
		  <a href="<?php echo base_url('login'); ?>" role="button" class="btn btn-info"><?= lang('Messages.login_me'); ?></a>
	    <?php else: ?>
			<a href="<?php echo url_to('list_tratamientos'); ?>" role="button" class="btn btn-info m-2"><i class="fa-solid fa-list mx-2"></i><?= lang('Messages.view_request'); ?></a>
			<?php if(isset($avisos)) : ?>
				<a href="<?php echo url_to('list_avisos'); ?>" role="button" class="btn btn-info m-2"><i class="fa-regular fa-bell mx-2"></i><?= lang('Messages.pending_notices'); ?><span class="badge bg-danger mx-1"><?= $avisos ?></span></a>
			<?php endif ?>
			<!-- cuadro resumen con solicitudes pendientes, solicitudes aprobadas y solicitudes totales en la pestaña principal -->
			<div>
				<h4 class="mt-5"><?= lang('Messages.main_total_requests'); ?></h4>
				<div class="d-flex justify-content-center">
					<div class="card m-2" style="width: 18rem;">
						<div class="card-body">
							<h5 class="card-title"><?= lang('Messages.main_pending'); ?></h5>
							<p class="card-text display-4 text-secondary"><?= $pendientes ?></p>
						</div>
					</div>
					<div class="card m-2" style="width: 18rem;">
						<div class="card-body">
							<h5 class="card-title"><?= lang('Messages.main_aproved'); ?></h5>
							<p class="card-text display-4 text-success"><?= $aprovadas ?></p>
						</div>
					</div>
					<div class="card m-2" style="width: 18rem;">
						<div class="card-body">
							<h5 class="card-title"><?= lang('Messages.main_managed'); ?></h5>
							<p class="card-text display-4 text-primary"><?= $totales ?></p>
						</div>
					</div>
				</div>
			</div> 
		<?php endif ?>
	</div>
	
<?= $this->endSection() ?>

<?= $this->section('extrafooter') ?>
<?= $this->endSection() ?>
