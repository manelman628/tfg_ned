<!-- Modal información paciente -->
<div class="modal fade" id="modal-paciente" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modal-paciente-label" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h1 class="modal-title fs-5" id="modal-paciente-title">
                <?= lang('Messages.pat_info'); ?>
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    
                    <!-- DATOS paciente
                    ----------------------------------------------------------------------------------------------->
                    <div class="col-md-6">
                        <p class="h6 mb-3 border-bottom border-secondary-subtle">
                            <span id="dato-nom">name</span>
                        </p>

                        <div class="row">
                            <div class="col-md-12 fw-light">
                                <dl class="row">
                                    <dt class="col-sm-4 mb-2">CIP:</dt>
                                    <dd id="dato-cip" class="col-sm-8"></dd>

                                    <dt class="col-sm-4 mb-2">NHC:</dt>
                                    <dd id="dato-nhc" class="col-sm-8"></dd>

                                    <dt class="col-sm-4 mb-2"><?= lang('Messages.pat_age'); ?>:</dt>
                                    <dd id="dato-fecha_nacimiento" class="col-sm-8"></dd>

                                    <dt class="col-sm-4 mb-2"><?= lang('Messages.pat_sex'); ?>:</dt>
                                    <dd id="dato-sexo" class="col-sm-8"></dd>
                                    
                                    <dt class="col-sm-4 mb-2"><?= lang('Messages.pat_address'); ?>: 
                                        <span id="boton-edita-direccion-alternativa" role="button" class="badge text-bg-warning help" data-bs-togglet="tooltip" data-bs-placement="left" title="<?= lang('Messages.pat_info'); ?>">
                                            <i class="fa-solid fa-edit"></i> 
                                        </span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <div id="wrapper-direccion">
                                            <span id="dato-direccion"></span><br>
                                            <span id="dato-cp" style="font-size: .90rem;"></span>
                                            <span id="dato-poblacion" style="font-size: .90rem;"></span>
                                        </div>
                                        <div id="wrapper-direccion-alternativa" class="card p-2">
                                            <div>
                                                <input placeholder="<?= lang('Messages.pat_alt_address'); ?>" type="text" class="form-control mb-2" id="dato-direccion-alternativa" name="dato-direccion-alternativa" value="" />
                                            </div>
                                            <div>
                                                <input placeholder="<?= lang('Messages.pat_alt_cp'); ?>" type="text" class="form-control mb-2" id="dato-cp-alternativo" name="dato-cp-alternativo" value="" />
                                            </div>  
                                            <div>
                                                <input placeholder="<?= lang('Messages.pat_alt_city'); ?>" type="text" class="form-control mb-2" id="dato-poblacion-alternativa" name="dato-poblacion-alternativa" value="" />
                                            </div>                                   
                                            <div class="text-end">
                                                <span id="boton-guarda-direccion-alternativa" role="button" class="badge text-bg-info help mb-2" data-bs-togglet="tooltip" data-bs-placement="left" title="<?= lang('Messages.save_changes'); ?>">
                                                    <i class="fa-solid fa-save"></i> 
                                                </span>
                                                <span id="boton-borra-direccion-alternativa" role="button" class="badge text-bg-danger help" data-bs-togglet="tooltip" data-bs-placement="left" title="<?= lang('Messages.pat_del_address'); ?>">
                                                    <i class="fa-solid fa-trash"></i> 
                                                </span>
                                            </div>
                                        </div>
                                    </dd>
                                   

                                    <dt class="col-sm-4 mb-2"><?= lang('Messages.pat_phone'); ?>:
                                        <span id="boton-edita-telefono-alternativo" role="button" class="badge text-bg-warning help" data-bs-togglet="tooltip" data-bs-placement="left" title="<?= lang('Messages.pat_phone'); ?>"">
                                            <i class="fa-solid fa-edit"></i> 
                                        </span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <div id="wrapper-telefono">
                                            <span id="dato-telefono"></span>
                                        </div>
                                        <div id="wrapper-telefono-alternativo" class="card p-2">
                                            <div>
                                                <input placeholder="<?= lang('Messages.pat_alt_phone'); ?>"" type="text" class="form-control mb-2" id="dato-telefono-alternativo" name="dato-telefono-alternativo" value="" />
                                            </div>
                                            <div class="text-end">
                                                <span id="boton-guarda-telefono-alternativo" role="button" class="badge text-bg-info help mb-2" data-bs-togglet="tooltip" data-bs-placement="left" title="<?= lang('Messages.save_changes'); ?>">
                                                    <i class="fa-solid fa-save"></i> 
                                                </span>
                                                <span id="boton-borra-telefono-alternativo" role="button" class="badge text-bg-danger help" data-bs-togglet="tooltip" data-bs-placement="left" title="<?= lang('Messages.pat_del_phone'); ?>">
                                                    <i class="fa-solid fa-trash"></i> 
                                                </span>
                                            </div> 
                                        </div>
                                    </dd>
                                    
                                    <dt class="col-sm-4 mb-2"><?= lang('Messages.pat_history'); ?>:</dt>
                                    <dd id="dato-antecedentes" class="col-sm-8"></dd>

                                </dl>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.col-md -->

                    <!-- DATOS tratamiento
                    ----------------------------------------------------------------------------------------------->
                    <div class="col-md-6">
                        <p class="h6 mb-3 border-bottom border-secondary-subtle">
                            <span id="dato-tratamiento_id"><?= lang('Messages.pat_treatment'); ?></span>
                        </p>

                        <div class="row">
                            <div class="col-md-12 fw-light">
                                <dl class="row">
                                    <dt class="col-sm-4 mb-2"><?= lang('Messages.pat_prescriptor'); ?>:</dt>
                                    <dd id="dato-prescriptor" class="col-sm-8"></dd>

                                    <dt class="col-sm-4 mb-2"><?= lang('Messages.pat_college'); ?>:</dt>
                                    <dd id="dato-colegiado" class="col-sm-8"></dd>

                                    <dt class="col-sm-4 mb-2"><?= lang('Messages.pat_user_group'); ?>:</dt>
                                    <dd id="dato-grupo" class="col-sm-8"></dd>

                                    <dt class="col-sm-4 mb-2"><?= lang('Messages.pat_service'); ?>:</dt>
                                    <dd id="dato-servicio" class="col-sm-8"></dd>

                                    <dt class="col-sm-4 mb-2"><?= lang('Messages.pat_admission_date'); ?>:</dt>
                                    <dd id="dato-ingreso" class="col-sm-8"></dd>

                                    <dt class="col-sm-4 mb-2"><?= lang('Messages.pat_last_confirmed'); ?>:</dt>
                                    <dd id="dato-cambio_confirmado" class="col-sm-8"></dd>

                                    <dt class="col-sm-4 mb-2"><?= lang('Messages.pat_confirmed_to'); ?>:</dt>
                                    <dd id="dato-confirmado" class="col-sm-8"></dd>
                                    
                                    <dt class="col-sm-4 mb-2"><?= lang('Messages.pat_discharged_date'); ?>:</dt>
                                    <dd id="dato-alta" class="col-sm-8"></dd>

                                    <dt class="col-sm-4 mb-2"><?= lang('Messages.pat_request_status'); ?>:</dt>
                                    <dd id="dato-estado" class="col-sm-8"></dd>
                                </dl>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.col-md -->
                </div>
            </div>
            
            <div class="modal-footer">
                <input type="hidden" class="form-control" id="modal-paciente-cip" name="modal-paciente-cip"></input>
                <input type="hidden" class="form-control" id="modal-paciente-nhc" name="modal-paciente-nhc"></input>
                <button type="button" class="btn btn-secondary pulse-secondary-hover" data-bs-dismiss="modal"><?= lang('Messages.close'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-error-pacient" tabindex="-1" role="dialog" aria-labelledby="modal-error-pacient" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-black">
        <h5 class="modal-title" id="modal-error-pacient-title">Error</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="modal-error-pacient-body"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang('Messages.close'); ?></button>
      </div>
    </div>
  </div>
</div>