<?= $this->extend('layout') ?>

<?= $this->section('extrahead') ?>
<style>
  @media print {
    header * {
      display: none;
    
    }
    footer * {
      display: none;
    }

    nav * {
      display: none;
    }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
  <?php if(isset($tratamiento)): ?>



      <fieldset>
      <?php echo form_open(route_to('print_solicitud'), ['id' => 'form-impressio', 'novalidate'=>'novalidate', 'target'=>'_blank']); ?>

      <?php if (isset($tratamiento->finalizacion)) : ?>
        <div class="text-warning p-3" style="border: 5px solid; border-radius: 15px; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(350deg); opacity: 0.5; font-size: 6rem; pointer-events: none; z-index: 1000;">
          Finalizada
          <div style="font-size: 2rem; text-align: center;">
            <?= $tratamiento->finalizacion->data ?? '-' ?>
          </div>
        </div>
      <?php endif; ?>

      <legend>
        Informe de solicitud y seguimiento del tratamiento con Nutrición Enteral Domiciliaria
        <div class="float-end" style="visibility: hidden;">
          <button type="button" class="btn btn-primary" title="Imprimir"><i class="fas fa-print"></i> Imprimir</button>
          <input type="hidden" id="id" name="id" value="<?= $tratamiento->id ?>">
        </div>
      </legend>
      <!-- datos del prescriptor -->
      <table class="table table-striped">
        <thead class="thead bg-primary text-light">
          <th scope="col" colspan="10">Datos del/de la médico/a prescriptor/a o responsable del seguimiento</th>         
        </thead>
        <tbody>
          <tr>
            <td class="w-25">Apellidos y nombre</td>
            <td><?= $tratamiento->usuario; ?></td>
          </tr>
          <tr>
            <td class="w-25">Servicio</td>
            <td><?= $tratamiento->usuario_servicio; ?></td>
          </tr>
          <tr>
            <td class="w-25">Centro</td>
            <td>HTVC</td>
          </tr>
        </tbody>
      </table>

      <!-- datos del paciente -->
      <table class="table table-striped">
        <thead class="thead bg-primary text-light">
          <th scope="col" colspan="10">Datos del/de la paciente</th>         
        </thead>
        <tbody>
          <tr>
            <td class="w-25">Apellidos y nombre</td>
            <td><?= $tratamiento->apellidos_paciente . ', ' . $tratamiento->nombre_paciente; ?></td>
          </tr>
          <tr>
            <td class="w-25">CIP</td>
            <td><?= $tratamiento->cip; ?></td>
          </tr>
          <tr>
            <td class="w-25">Dirección</td>
            <td>
              <?php if(isset($tratamiento->paciente) && !empty($tratamiento->paciente->calle)) : ?>
                <?= '<b>'.$tratamiento->paciente->calle.'</b>' ?>
              <?php else : ?>
                <?= $tratamiento->direccion; ?>
              <?php endif ?>  
            </td>
          </tr>
          <tr>
            <td class="w-25">CP</td>
            <td>
              <?php if(isset($tratamiento->paciente) && !empty($tratamiento->paciente->codigo_postal) ) : ?>
                <?= '<b>'.$tratamiento->paciente->codigo_postal.'</b>' ?>
              <?php else : ?>
                <?= $tratamiento->codigo_postal; ?>
              <?php endif ?>  
            </td>
          </tr>
          <tr>
            <td class="w-25">Municipio</td>
            <td>
              <?php if(isset($tratamiento->paciente) && !empty($tratamiento->paciente->poblacion) ) : ?>
                <?= '<b>'.$tratamiento->paciente->poblacion.'</b>' ?>
              <?php else : ?>
                <?= $tratamiento->poblacion; ?>
              <?php endif ?>  
            </td>
          </tr>
          <tr>
            <td class="w-25">Teléfono</td>
            <td><?= $tratamiento->telefono; ?></td>
          </tr>
        </tbody>
      </table>

      <!-- datos indicación tratamiento -->
      <table class="table table-striped">
        <thead class="thead bg-primary text-light">
          <th scope="col" colspan="10">Indicaciones de la NED</th>         
        </thead>
        <?php if(isset($tratamiento->indicaciones)) : ?>
        <thead class="thead bg-primary text-light">
          <th scope="col">Código</th>         
          <th scope="col">Indicaciones</th>         
          <th scope="col">Grupo</th>      
          <th scope="col">Vía</th>         
        </thead>
        <?php endif; ?>

        <tbody>
          <?php if(isset($tratamiento->indicaciones)) : ?>
            <?php foreach($tratamiento->indicaciones as $indicacion) : ?>
              <tr>

                <td class="w-25"><?= $indicacion->codigo; ?></td>
                <td><?= $indicacion->descripcion; ?></td>
                <td><?= $indicacion->grupo . ($indicacion->subgrupo != '' ? (' ('.$indicacion->subgrupo.')') : '') ; ?></td>
                <td><?= $indicacion->via; ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>  
            <tr>
              <td colspan=10>No se han encontrado datos de indicación</td>
            </tr>
          <?php endif ?>
          
          <tr>
            <td class="w-25">Observaciones</td>
            <td colspan="10"><?= ($tratamiento->obs_tratamiento != '' ? $tratamiento->obs_tratamiento : '-'); ?></td>
          </tr>
        </tbody>

      </table>

       <!-- datos de prescripción -->
       <table class="table table-striped">
        <thead class="thead bg-primary text-light">
          <th scope="col" colspan="10">Prescripción</th>         
        </thead>
        <tbody>
          <tr>
            <td class="w-25">Duración</td>
            <td>
              <?php if($tratamiento->duracion) : ?>
                <?=$tratamiento->duracion ?> días
              <?php endif ?>
            </td>
          </tr>
          <tr>
            <td class="w-25">Dieta/Productos</td>
            <td>
              <?php if ($tratamiento->prescripciones) : ?>
                <?php 
                $medi_descs = array_unique(array_map(function($prescripcio) {
                  return $prescripcio->medi_desc ;
                }, $tratamiento->prescripciones));

                foreach ($medi_descs as $medi_desc) : ?>
                  <?= $medi_desc; ?><br>
                <?php endforeach; ?>
              <?php endif ?>
            </td>
          </tr>

          <tr>
            <td class="w-25">Via de administración</td>
            <td>
              <?php if ($tratamiento->prescripciones) : ?>
                <?php 
                $vies = array_unique(array_map(function($prescripcio) {
                  return $prescripcio->via_descripcion;
                }, $tratamiento->prescripciones));

                foreach ($vies as $via) : ?>
                  <?= $via; ?><br>
                <?php endforeach; ?>
              <?php endif ?>
            </td>
          </tr>
          <tr>
            <td class="w-25">Equipo de administración</td>
            <td>
              <?php if (isset($tratamiento->equipo) ) : ?>
                <?= $tratamiento->equipo->bomba; ?>, model <?= $tratamiento->equipo->lab; ?> (<?= $tratamiento->equipo->equipo; ?>)
                <?= $tratamiento->equipo->observaciones != '' ? '<br>'.$tratamiento->equipo->observaciones : '' ?>
              <?php else : ?>
                  -
              <?php endif ?>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- datos estado de la solicitud -->
      <!-- datos del prescriptor -->
      <table class="table table-striped">
        <thead class="thead bg-primary text-light">
          <th scope="col" colspan="10">Estado de la solicitud</th>         
        </thead>
        <tbody>
          <tr>
            <td class="w-25">Prescripción</td>
            <td><?= $tratamiento->usuario; ?></td>
            <td><?= ($tratamiento->fecha_cambio_confirmado ? $tratamiento->getCanvioConfirmadoPretty() : $tratamiento->getFechaIngresoPretty()); ?></td>
          </tr>
          <tr>
            <td class="w-25">Validación</td>
            <td><?= $tratamiento->validacion->validador ?? '-' ?></td>
            <td><?= $tratamiento->validacion->fecha ?? '-' ?></td>
          </tr>
          <?php if (isset($tratamiento->aprobacion)) : ?>
            <tr>
              <td class="w-25 text-success">Aprobación</td>
              <td><?= $tratamiento->aprobacion->aprobador ?? '-' ?></td>
              <td><?= $tratamiento->aprobacion->fecha ?? '-' ?></td>
            </tr>
          <?php elseif (isset($tratamiento->denegacion)) : ?>
            <tr>
              <td class="w-25 text-danger">Denegación</td>
              <td><?= $tratamiento->denegacion->denegador ?? '-' ?></td> 
              <td><?= $tratamiento->denegacion->fecha ?? '-' ?></td>
            </tr>
          <?php else : ?>
            <tr>
              <td class="w-25">Aprobación</td>
              <td><?= '-' ?></td> 
              <td><?= '-' ?></td> 
            </tr>
          <?php endif; ?>
          <?php if (isset($tratamiento->finalizacion)) : ?>
            <td class="w-25 text-warning">Finalización</td>
            <td><?= $tratamiento->finalizacion->finalizador ?? '-' ?></td> 
            <td><?= $tratamiento->finalizacion->fecha ?? '-' ?></td>
          <?php endif; ?> 
                   
        </tbody>
      </table>
    <?php echo form_close(); ?>
    </fieldset>

  <?php else : ?>
    <p>No s'ha trobat la solicitud.</p>
  <?php endif ?>
<?= $this->endSection() ?>

<?= $this->section('extrafooter') ?>

<?= $this->endSection() ?>
