<div class="modal fade" id="modal-seguimiento" tabindex="-1" role="dialog" aria-labelledby="modal-seguimiento" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      
      <div class="modal-header bg-info text-black">
        <h5 class="modal-title" id="modal-modificar-title"><?= lang('Messages.trackings'); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
    
      <div class="modal-body">
        <div class="mb-2">
            <span class="fw-light" style="font-size: .90rem;" id ="modal-seguimiento-span-nombre"></span>
            -
            <span class="text-end fw-light" style="font-size: .90rem;" id ="modal-seguimiento-span-estado">Solicitud XXXX</span>
        </div>
        <!-- card crear seguimiento -->
        <div class="card" id="modal-card-añadir-seguimiento">
          <div class="card-header" id="modal-card-header">
          <?= lang('Messages.tracking_add'); ?>
          </div>
    
          <div class="card-body">

            <div class="form-group row col-12 mt-2">
              <label class="col-form-label col-sm-3" for="tipo_seguimiento_id"><?= lang('Messages.tracking_type'); ?></label>
              <div class="col-sm-9">
                <select class="form-select te-popover" name="modal-tipo_seguimiento_id" id="modal-tipo_seguimiento_id" data-size="5" 
                  data-bs-toggle="popover" data-bs-placement="top" readonly>
                  <option value=""></option>
                <?php if (isset($tipo_seguimientos)) :?>
                  <?php foreach ($tipo_seguimientos as $tipus) :?>
                    <option value="<?php echo $tipus->id?>"><?php echo $tipus->descripcion ?></option>
                  <?php endforeach ?>
                <?php endif ?>
                </select>
              </div>
            </div>
            
            <div class="form-group row col-12 mt-2">
              <label class="col-form-label col-sm-3" for="fecha_seguimiento"><?= lang('Messages.tracking_date'); ?></label>
              <div class="col-sm-9">
                <input type="date" class="form-control te-popover" name="modal-fecha_seguimiento" id="modal-fecha_seguimiento" data-bs-toggle="popover" data-bs-trigger="focus">
              </div>
            </div>    

            <div class="form-group row col-12 mt-2">
              <label class="col-form-label col-sm-3" for="observacions"><?= lang('Messages.tracking_observations'); ?></label>
              <div class="col-sm-9">
                <textarea class="form-control" id="modal-observaciones" name="modal-observaciones"></textarea>
              </div>
            </div>

            <div class="form-group row col-12 mt-2" id="select-equipo">
              <label class="col-form-label col-sm-3" for="equipo-administracion"><?= lang('Messages.tracking_administration'); ?></label>
              <div class="col-sm-9">
              <select class="form-select te-popover" name="modal-equipo_administracion_id" id="modal-equipo_administracion_id" data-size="5" 
                  data-bs-toggle="popover" data-bs-placement="top" readonly>
                  <option value=""></option>
                  <?php if (isset($equipos_administracion)) :?>
                  <?php foreach ($equipos_administracion as $equipo) :?>
                    <option value="<?php echo $equipo->id?>"><?php echo $equipo->bomba ?> - Ref. <?php echo $equipo->equipo ?> - <?php echo $equipo->lab ?></option>
                  <?php endforeach ?>
                <?php endif ?>
                </select>
              </div>
            </div>

            <div class="form-group mt-4 text-center">
              <a class="btn btn-secondary" id="modal-boton-cancelar-seguimiento" role="button" aria-expanded="false">
              <?= lang('Buttons.cancel'); ?>
              </a>
              <button type="button" class="btn btn-primary" id="modal-modifica-acepta"><?= lang('Buttons.save'); ?></button>
            </div>

          </div>
  
        </div>

         <!-- listado de seguimientos -->         
        <div class="mt-3">
          <ol class="list-group" id="modal-list-seguimientos">
            <!-- contenido generado dinamicamente desde JS --> 
          </ol>
        </div>
      
      </div>

      <div class="modal-footer">
        <input type="hidden" class="form-control" id="modal-tratamiento_id" name="modal-tratamiento_id"></input>
        <button type="button" class="btn btn-primary" id="modal-boton-añadir-seguimiento"><?= lang('Buttons.add'); ?></button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('Buttons.close'); ?></button>
      </div>
     
    </div>
  </div>
</div>


<div class="modal fade" id="modal-error-segumiento" tabindex="-1" role="dialog" aria-labelledby="modal-error-segumiento" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-black">
        <h5 class="modal-title" id="modal-error-segumiento-title">Error</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="modal-error-segumiento-body"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('Buttons.close'); ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-msg" tabindex="-1" role="dialog" aria-labelledby="modal-msg" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-black">
        <h5 class="modal-title" id="modal-msg-title"><?= lang('Messages.message'); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="modal-msg-body"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('Buttons.close'); ?></button>
      </div>
    </div>
  </div>
</div>

  

