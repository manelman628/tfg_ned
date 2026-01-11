<!DOCTYPE html>
<html lang="es">
<head>
  <?= $this->include('partials/head') ?>
  <?= $this->renderSection('extrahead') ?>
</head>
<body>
  <header>
    <?= $this->include('partials/menu') ?>
  </header>
  <section class="my-section border container">
    <?= $this->include('partials/breadcrumb') ?>
		<?= $this->renderSection('content') ?>
  </section>
  <?= $this->include('partials/footer') ?>
  <?= $this->renderSection('extrafooter') ?>
</body>
</html>
