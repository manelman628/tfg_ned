<nav class="navbar navbar-expand-md navbar-light bg-light">
  <div class="container-fluid">
    <img class="navbar-brand" src="<?php echo base_url('Logo-ICS-GC-color.png');?>" alt="Gerencia TE" height="48px" width="301.58px">
    <a class="navbar-brand" href="<?php echo base_url(); ?>"><?php echo env('app.titleName');?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item ">
            <a class="nav-link <?php if($pagina == 'listar tratamientos') echo 'active'; ?>" href="<?php echo url_to('list_tratamientos')?>"><?= lang('Messages.request'); ?></a>
        </li>
        <li class="nav-item ">
            <a class="nav-link <?php if($pagina == 'listar avisos') echo 'active'; ?>" href="<?php echo url_to('list_avisos')?>"><?= lang('Messages.notice'); ?></a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?php if($pagina == 'encuantoa' || $pagina == 'notalegal') echo 'active'; ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <?= lang('Messages.help'); ?>
          </a>
          <div class="dropdown-menu">
            <a class="dropdown-item <?php if($pagina == 'encuantoa') echo 'active'; ?>" href="<?php echo base_url('ayuda/encuantoa')?>"><?=  lang('Messages.about'); ?></a>
            <a class="dropdown-item <?php if($pagina == 'notalegal') echo 'active'; ?>" href="<?php echo base_url('ayuda/nota')?>"><?= lang('Messages.legal'); ?></a>
          </div>
        </li>
      </ul>
      <span class="navbar-text">
        <?php if(isset($usuario)) : ?>
          <div class="dropdown">
            <a class="btn btn-light dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?= $usuario . (isset($rols) ? ' (' . implode($rols) . ')' : ''); ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuUserLink">
              <a class="dropdown-item" href="<?php echo base_url('logout'); ?>">Surt</a>
            </div>
          </div>
        <?php else : ?>
          <a href="<?php echo base_url('login'); ?>" role="button" class="btn btn-light"><?= lang('Messages.manage'); ?></a>
        <?php endif ?>
      </span>
    </div>
  </div>
</nav>
