<?= $this->extend('layout') ?>

<?= $this->section('extrahead') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="text-center">
	<h1 class="text-primary bg-gradient-light"><?php echo env('app.titleName');?></h1>
	<p class="h5 text-danger"><?= lang('Messages.no_login'); ?></p>
	<i class="fas fa-ban" style="color:red; width:120px;height:auto;"></i>
	<p><?= lang('Messages.login_link'); ?></p>
	<a href="<?php echo base_url('login'); ?>" role="button" class="btn btn-info"><?= lang('Messages.login_me'); ?></a>
</div>
<?= $this->endSection() ?>

<?= $this->section('extrafooter') ?>
<?= $this->endSection() ?>
