<?= $this->extend('layout') ?>

<?= $this->section('extrahead') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card card-body mb-3">
  <?= $this->include('ICSEbre\NotaLegal\Views\notalegal') ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('extrafooter') ?>
<?= $this->endSection() ?>
