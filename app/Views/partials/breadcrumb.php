<nav aria-label="breadcrumb">
  <ol class="breadcrumb" style="background-color: #e3f2fd;">
    <!-- @var $data['breadcrumb'] $breadcrumb -->
    <?php foreach ($breadcrumb as $item):?>
    <!-- <li class="breadcrumb-item "><a href="<?php echo base_url(); ?>">Principal</a></li> -->
    <!-- <li class="breadcrumb-item active" aria-current="page">Principal</li> -->
    <li class="breadcrumb-item <?php if(!$item[1]) echo "active"?>" <?php if(!$item[1]) echo 'aria-current="page"'?> >
      <?php if($item[1]): ?>
        <a href="<?= $item[1];?>"><?= $item[0];?></a>
      <?php else : ?>
        <?= $item[0];?>
      <?php endif ?>
    </li>
    <?php endforeach;?>
  </ol>
</nav>
