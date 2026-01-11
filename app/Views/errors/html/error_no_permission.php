<?= $this->extend('layout') ?>

<?= $this->section('extrahead') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="text-center">
	<h1 class="text-primary bg-gradient-light"><?php echo env('app.titleName');?></h1>
	<p class="h5 text-danger"><?= lang('Messages.error_page'); ?></p>
	<i class="fas fa-ban" style="color:red; width:120px;height:auto;"></i>
</div>
<?= $this->endSection() ?>

<?= $this->section('extrafooter') ?>
<?= $this->endSection() ?>
