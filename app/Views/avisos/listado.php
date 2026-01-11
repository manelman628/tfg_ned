<?= $this->extend('layout') ?>

<?= $this->section('extrahead') ?>
  <link href="<?php echo base_url('css/bootstrap-select.min.css');?>" rel="stylesheet" type="text/css" >
<?= $this->endSection() ?>

<?= $this->section('content') ?>



 <?php if(isset($avisos)) : ?>

  <div class=" card card-body my-3 container container-75" id="avisos">
  
  <?php if(!empty($avisos)) : ?>
  
    <div class="clearfix">
      <p class="h4">Los siguientes avisos estan pendientes de revisar:</p>
    </div>
     
    <div class="overflow-scroll">
      <table class="table table-sm table-hover table-responsive mt-3">
      <thead class="thead bg-primary text-light">
        <th scope="col">Fecha</th>
        <th scope="col">ID Tratamiento(Solicitud)</th>
        <th scope="col">Paciente</th>
        <th scope="col">Prescriptor/a</th>
        <th scope="col">Información a revisar/cambios</th>
        <th scope="col">Aviso dirigido a</th>
        <th scope="col">Acciones</th>
      </thead>
      <tbody>
        <?php foreach($avisos as $aviso) : ?>
          <?php if ($aviso->roles_proceso == null || strpos($aviso->roles_proceso, $rols[0]) !== false || $rols[0] == 'admin') : ?>
          <tr id="avis_<?= $aviso->id ?>" data-bs-toggle="collapse" data-bs-target="#mensajes_<?= $aviso->id ?>">
          
            <td class="w-auto"><?= $aviso->getFechaAvisoPretty() ?></td>
            <td class="w-auto"><?= $aviso->tratamiento_id ?></td>
            <td class="w-auto"><span class="fw-light" style="font-size: .87rem;"><?= $aviso->paciente ?></span></td>
            <td class="w-auto"><span class="fw-light" style="font-size: .87rem;"><?= $aviso->usuario ?></span></td>
            <td class="w-auto">
                    <?php 
                        $mensajes = explode('###', $aviso->group_mensaje);
                        foreach ($mensajes as $miss){
                            echo '<div><span class="fw-light" style="font-size: .87rem;">'.$miss.'</span></div>';
                        } 
                    ?>
            </td>
            <td class="w-auto"><span class="fw-light" style="font-size: .87rem;"><?= $aviso->roles_proceso ?></span></td>
            <td class="w-auto">
              <a class="btn btn-info mt-1" role="button" title="ver la solicitud" data-bs-toggle="tooltip" data-placement="top" target="_blank" href="<?= base_url().route_to('list_tratamientos_filtrado', $aviso->tratamiento_id)?>">
                <i class="fas fa-magnifying-glass"></i>
              </a>
              <?php if((isset($permissions['modificar aviso']) && $permissions['modificar aviso']) )  : ?>               
                <button class="btn btn-success mt-1 procesa-aviso" 
                    data-aviso_id="<?= $aviso->id ?>" 
                    title="Marcar como procesado" data-bs-toggle="tooltip" data-placement="top">
                  <i class="fas fa-check"></i>
                </button>
              <?php endif ?>
            </td>
          </tr>
        <?php endif ?>
        <?php endforeach ?>
      </tbody>
      </table>
    </div>
  <?php else :?>
    <p class="h5">No hay avisos pendientes</p>
  <?php endif ?>

  </div>
    
  <?php endif ?>

  <?= $this->endSection() ?>

<?= $this->section('extrafooter') ?>
  <script src="<?php echo base_url('js/bootstrap-select.min.js');?>"></script>
  <script src="<?php echo base_url('js/close-alerts.js');?>"></script>
  <?= $this->include('avisos/script_listado') ?>

<?= $this->endSection() ?>