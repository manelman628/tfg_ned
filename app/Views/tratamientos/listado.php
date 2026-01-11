<?= $this->extend('layout') ?>

<?= $this->section('extrahead') ?>
  <link href="<?php echo base_url('css/bootstrap-select.min.css');?>" rel="stylesheet" type="text/css" >
 
  <?= $this->endSection() ?>

<?= $this->section('content') ?>

  <?php if(isset($alert) && $alert=='ok-edit') : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <h4 class="alert-heading">Èxito</h4>
      <p>Se ha modificado el tratamiento correctamente.</p>
      <button type="button" class="btn-close btn-close-success" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif ?>
  <?php if(isset($alert) && $alert=='error-edit') : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <h4 class="alert-heading">Error</h4>
      <p>No se a podido modificar el tratamiento correctamente.</p>
      <button type="button" class="btn-close btn-close-danger" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif ?>
 
  <div class="clearfix">

    <div class="btn-group float-sm-start m-1" id="botones-filtros">
      <button class="btn btn-primary float-left" type="button" data-bs-toggle="collapse" data-bs-target="#filtres" aria-expanded="false" aria-controls="filtres"><?= lang('Messages.filters'); ?></button>
    </div>

    <div class="btn-group float-sm-end m-1">
      <button id="exporta" type="button" class="btn btn-primary">
        <i class="fas fa-file-excel"></i> <span id="num_tratamientos"> <?= lang('Messages.export'); ?> (<?= isset($tratamientos) ? count($tratamientos) : 0 ?>)</span>
      </button>
    
    </div>

  </div>

  <?php echo form_open(route_to('list_tratamientos'), ['novalidate'=>'novalidate', 'method' => 'get', 'id' => 'form-filtros']); ?>
  
  <div class="collapse card card-body my-3 container container-100" id="filtres">
   
      <fieldset>
        
        <div class="row">
          <div class="col-sm-6 col-md-8 col-lg-9">
          
            <div class="row g-3 mb-2">
              
              <div class="col-sm-6 col-md-6 col-lg-4">
                <div class="row g-2">
                  <div class="col">
                    <label class="col-form-label col" for="cip" id="label_cip"><?= lang('Messages.patient_CIP'); ?></label>
                    <div class="col-md-10">
                      <input type="text" class="fw-bold form-control col-sm-6 col-md-10 input-filter" name="cip" id="cip" data-descrip-filtro="CIP" 
                      value="<?= isset($_GET['cip']) ? $_GET['cip'] : '' ?>"/>
                    </div>
                  </div>
                  <div class="col">
                    <label class="col-form-label col" for="nhc" id="label_nhc"><?= lang('Messages.patient_NHC'); ?></label>
                    <div class="col-md-10">
                      <input type="text" class="fw-bold form-control col-sm-6 col-md-10 input-filter" name="nhc" id="nhc" data-descrip-filtro="NHC" 
                      value="<?= isset($_GET['nhc']) ? $_GET['nhc'] : '' ?>"/>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-6 col-md-6 col-lg-4">
                <label class="col-form-label col" for="nom" id="label_nom"><?= lang('Messages.patient_name'); ?></label>
                <div class="col-md-10">
                  <input type="text" class="fw-bold form-control col-sm-6 col-md-10 input-filter" name="nombre_completo" id="nombre_completo" data-descrip-filtro="Nombre/Apellidos" 
                  value="<?= isset($_GET['nombre_completo']) ? $_GET['nombre_completo'] : ''  ?>"/>
                </div>
              </div>

             
              <div class="col-sm-6 col-md-6 col-lg-4">
                <div class="row g-2">
                  <div class="col">
                    <label class="col-form-label col" for="tratamiento_id" id="label_tratamiento_id"><?= lang('Messages.treatment_id'); ?></label>
                    <div class="col-md-10">
                      <input type="number" min="1" max="99999999" class="fw-bold form-control col-sm-6 col-md-10 input-filter" name="tratamiento_id" id="tratamiento_id" data-descrip-filtro="ID Tratamiento" 
                      value="<?= isset($_GET['tratamiento_id']) ? $_GET['tratamiento_id'] : '' ?>"/>
                    </div>
                  </div>
                  <div class="col">
                    <label class="col-form-label col" for="estado" id="label_estat"><?= lang('Messages.request_status'); ?></label>
                    <div class="col-md-10">
                      <select multiple class="form-control selectpicker" name="estado_id[]" id="estado_id[]" data-descrip-filtro="Estat"  data-size="5" data-none-selected-text="Sin seleccionar">
                      
                        <?php foreach ($estados as $estado) :?>
                          <? 
                            // Si se pasa un estado_id a la vista como filtro o por GET, se crea un array con los ids para comparar si el estado actual está seleccionado
                            $array_estats = [];
                            if (isset($_GET['estado_id'])) {
                              if(is_array($_GET['estado_id'])){
                                $array_estats = $_GET['estado_id'];
                              }else {
                                $array_estats = [$_GET['estado_id']];
                              }
                            }elseif(isset($filtres['estado_id'])){
                              if (is_array($filtres['estado_id'])) {
                                $array_estats = $filtres['estado_id'];
                              } else {
                                $array_estats = [$filtres['estado_id']];
                              }
                            }              
                          ?>
                          <option value="<?= $estado->id ?>" <?= isset($array_estats) && in_array($estado->id, $array_estats) ? 'selected' : '';?>><?= $estado->descripcion ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>  
                </div> 
              </div>
 
            </div>
        
            <div class="row g-3 mb-2">
              
              <div class="col">
                <label class="col-form-label col" for="servicio" id="label_serv"><?= lang('Messages.service'); ?></label>
                <div class="col-md-10">
                    <select multiple class="form-control selectpicker" name="servicio_codigo[]" id="servicio_codigo[]" data-descrip-filtro="Servei"  data-size="5" data-none-selected-text="Sin seleccionar">
                      <?php foreach ($servicios as $servicio) :?>
                        <? 
                          // Si se pasa un servicio a la vista como filtre o por GET, se crea un array con los ids para comparar si el servicio actual está seleccionado
                          $array_serveis = [];
                          if (isset($_GET['servicio_codigo'])) {
                            if(is_array($_GET['servicio_codigo'])){
                              $array_serveis = $_GET['servicio_codigo'];
                            }else {
                              $array_serveis = [$_GET['servicio_codigo']];
                            }
                          }elseif(isset($filtres['servicio_codigo'])){
                            if (is_array($filtres['servicio_codigo'])) {
                              $array_serveis = $filtres['servicio_codigo'];
                            } else {
                              $array_serveis = [$filtres['servicio_codigo']];
                            }
                          }              
                        ?>
                        <option value="<?= $servicio->servicio_codigo ?>" <?= isset($array_serveis) && in_array($servicio->servicio_codigo, $array_serveis) ? 'selected' : '';?>><?= $servicio->servicio ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
              </div>

              <div class="col">
                <label class="col-form-label col" for="usuario" id="label_pres"><?= lang('Messages.prescriber'); ?></label>
                <div class="col-md-10">
                  <select class="form-control selectpicker" name="usuario" id="usuario" data-descrip-filtro="Prescriptor/a" data-live-search="true" data-none-results-text="Cap resultat"  data-size="5" data-none-selected-text="Sin seleccionar">
                    <option value="">Sin seleccionar</option>
                    <?php foreach ($usuarios as $usuario) :?>
                      <option value="<?= $usuario->id ?>" <?= isset($_GET['usuario']) && $_GET['usuario'] == $usuario->id ? 'selected' :'';?>><?= $usuario->usuario ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
             
              <div class="col">
              <label class="col-form-label col" for="nom" id="label_confirmat"><?= lang('Messages.confirmed_between'); ?></label>
                <div class="col-md-10">
                  <div class="row">
                    <div class="col-6">
                      <input type="date" class="form-control col-sm-6 col-md-10 input-filter" name="confirmado_hasta_ini" id="confirmado_hasta_ini" data-descrip-filtro="<?= lang('Messages.confirmed_between_start'); ?>" 
                      value="<?= isset($_GET['confirmado_hasta_ini']) ? $_GET['confirmado_hasta_ini'] : ''  ?>"/>
                    </div>
                    <div class="col-6">
                      <input type="date" class="form-control col-sm-6 col-md-10 input-filter" name="confirmado_hasta_fi" id="confirmado_hasta_fi" data-descrip-filtro="<?= lang('Messages.confirmed_between_end'); ?>" 
                      value="<?= isset($_GET['confirmado_hasta_fi']) ? $_GET['confirmado_hasta_fi'] : ''  ?>"/>
                    </div>
                  </div>  
                </div>
              </div>
             
            </div>

            <div class="row g-3 mb-2">
             
              <div class="col">
                <label class="col-form-label col" for="nom" id="label_ingres"><?= lang('Messages.entered_between'); ?></label>
                <div class="col-md-10">
                  <div class="row">
                    <div class="col-6">
                      <input type="date" class="form-control col-sm-6 col-md-10 input-filter" name="fecha_ingreso_ini" id="fecha_ingreso_ini" data-descrip-filtro="<?= lang('Messages.entered_between_start'); ?>" 
                      value="<?= isset($_GET['fecha_ingreso_ini']) ? $_GET['fecha_ingreso_ini'] : ''  ?>"/>
                    </div>
                    <div class="col-6">
                      <input type="date" class="form-control col-sm-6 col-md-10 input-filter" name="fecha_ingreso_fi" id="fecha_ingreso_fi" data-descrip-filtro="<?= lang('Messages.entered_between_end'); ?>" 
                      value="<?= isset($_GET['fecha_ingreso_fi']) ? $_GET['fecha_ingreso_fi'] : ''  ?>"/>
                    </div>
                  </div>  
                </div>
              </div>

              <div class="col">
                <label class="col-form-label col" for="nom" id="label_alta"><?= lang('Messages.discharged_between'); ?></label>
                <div class="col-md-10">
                  <div class="row">
                    <div class="col-6">
                      <input type="date" class="form-control col-sm-6 col-md-10 input-filter" name="fecha_alta_ini" id="fecha_alta_ini" data-descrip-filtro="<?= lang('Messages.discharged_between_start'); ?>" 
                      value="<?= isset($_GET['fecha_alta_ini']) ? $_GET['fecha_alta_ini'] : ''  ?>"/>
                    </div>
                    <div class="col-6">
                      <input type="date" class="form-control col-sm-6 col-md-10 input-filter" name="fecha_alta_fi" id="fecha_alta_fi" data-descrip-filtro="<?= lang('Messages.discharged_between_end'); ?>" 
                      value="<?= isset($_GET['fecha_alta_fi']) ? $_GET['fecha_alta_fi'] : ''  ?>"/>
                    </div>
                  </div>  
                </div>
              </div>

              <div class="col">
                <label class="col-form-label col" for="nom" id="label_exitus"><?= lang('Messages.exitus_between'); ?></label>
                <div class="col-md-10">
                  <div class="row">
                    <div class="col-6">
                      <input type="date" class="form-control col-sm-6 col-md-10 input-filter" name="fecha_exitus_ini" id="fecha_exitus_ini" data-descrip-filtro="<?= lang('Messages.exitus_between_start'); ?>" 
                      value="<?= isset($_GET['fecha_exitus_ini']) ? $_GET['fecha_exitus_ini'] : ''  ?>"/>
                    </div>
                    <div class="col-6">
                      <input type="date" class="form-control col-sm-6 col-md-10 input-filter" name="fecha_exitus_fi" id="fecha_exitus_fi" data-descrip-filtro="<?= lang('Messages.exitus_between_end'); ?>" 
                      value="<?= isset($_GET['fecha_exitus_fi']) ? $_GET['fecha_exitus_fi'] : ''  ?>"/>
                    </div>
                  </div>  
                </div>
              </div>

            </div>           

          </div>
          

          <div class="col-sm-6 col-md-4 col-lg-3">
          
            <div class="row">
              <div class="col m-3" id="div-otros-filtros">
                <div class="row mb-2">
                  <button type="button" title="<?= lang('Buttons.select_all'); ?>" id="btn-otros-filtros" class="btn btn-outline-primary"><?= lang('Messages.another_filters'); ?></button>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="chk_mostra_tractaments" name="tratamientos" value="1" data-descrip-filtro="<?= lang('Messages.show_old_confirmed'); ?>" 
                  <?= isset($_GET['tratamientos']) && $_GET['tratamientos'] == 1 ? ' checked' : ''  ?> />
                  <label class="form-check-label" for="inlineCheckboxTr"
                  title="<?= lang('Messages.show_old_confirmed_title'); ?>"
                  ><?= lang('Messages.show_old_confirmed'); ?></label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="chk_mostra_altes" name="altas" value="1" data-descrip-filtro="<?= lang('Messages.show_discharged'); ?>" 
                  <?= isset($_GET['altas']) && $_GET['altas'] == 1 ? ' checked' : ''  ?> />
                  <label class="form-check-label" for="inlineCheckboxAl"
                  title="<?= lang('Messages.show_discharged_title'); ?>"
                  ><?= lang('Messages.show_discharged'); ?></label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="chk_mostra_exitus" name="exitus" value="1" data-descrip-filtro="<?= lang('Messages.show_exitus'); ?>" 
                  <?= isset($_GET['exitus']) && $_GET['exitus'] == 1 ? ' checked' : ''  ?> />
                  <label class="form-check-label" for="inlineCheckboxEx"
                  title="<?= lang('Messages.show_exitus_title'); ?>"
                  ><?= lang('Messages.show_exitus'); ?></label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="chk_mostra_eliminats" name="eliminados" value="1" data-descrip-filtro="<?= lang('Messages.show_deleted'); ?>"
                  <?= isset($_GET['eliminados']) && $_GET['eliminados'] == 1 ? ' checked' : ''  ?> />
                  <label class="form-check-label" for="inlineCheckboxEl"
                  title="<?= lang('Messages.show_deleted_title'); ?>"
                  ><?= lang('Messages.show_deleted'); ?></label>
                </div>
               
              </div>
            </div>

            <div class="row">
              <div class="col m-3" id="div-visualitzacio">
                <div class="row mb-2">
                  <button type="button" class="btn btn-outline-primary" title="<?= lang('Buttons.select_all'); ?>" id="btn-visualizacion"><?= lang('Buttons.view_options'); ?></button>
                </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="chk_muestra_prescripciones"  name="prescripciones" value="1" data-descrip-filtro="<?= lang('Messages.show_old_prescriptions'); ?>"
                <?= isset($_GET['prescripciones']) && $_GET['prescripciones'] == 1 ? ' checked' : ''  ?> />
                <label class="form-check-label" for="inlineCheckboxPr" 
                  title="<?= lang('Messages.show_old_prescriptions_title'); ?>"
                  ><?= lang('Messages.show_old_prescriptions'); ?></label>
                </label>
              </div>
            </div>

          </div>
      </div>

        <div class="form-row text-center m-3">
          <button type="submit" class="btn btn-primary"><i class="fas fa-magnifying-glass"></i><?= lang('Messages.search'); ?></button>
        </div>
    

      </fieldset>
    
  </div>

 
  <?php echo form_close(); ?>


  <?php if(isset($tratamientos) && !empty($tratamientos)) : ?>
    
      <table id="listado-tratamientos" class="table table-sm table-hover table-responsive mt-3">
      <thead class="thead bg-primary text-light">
        <th scope="col"><?= lang('Messages.patient'); ?></th>
        <th scope="col"><?= lang('Messages.treatment_service'); ?></th>
        <th scope="col"><?= lang('Messages.last_prescription'); ?></th>
        <th scope="col"><?= lang('Messages.confirmed_until'); ?></th>
        <th scope="col"><?= lang('Messages.expected_duration'); ?></th>
        <th scope="col" class="text-nowrap" title="Alta o exitus"><?= lang('Messages.discharged/exitus'); ?></th>
        <th scope="col" class="px-2"><?= lang('Messages.observations'); ?></th>
        <th scope="col"><?= lang('Messages.indications'); ?></th>
        <th scope="col"><?= lang('Messages.request_status'); ?></th>
        <th scope="col"><?= lang('Messages.actions'); ?></th>
      </thead>
      <tbody>
        <?php foreach($tratamientos as $tratamiento) : ?>

          <tr id="tractament_<?= $tratamiento->id ?>">

            <td class="w-auto">
              <div class="row">
                <div class="col">
                  <?= $tratamiento->apellidos_paciente . ', ' . $tratamiento->nombre_paciente ?>
                </div>
              </div>
              <div class="row">
                <div class="col fw-light" style="font-size: .90rem;">
                  <i class="fa-solid fa-id-card text-secondary mx-1"></i>   <?= $tratamiento->cip ?>
                </div>
              </div>
            </td>
            <!-- servicio y Prescriptor -->
            <td class="auto">
              <div class="row">
                <div class="col fw-light" style="font-size: .90rem;">
                  <?= $tratamiento->usuario_servicio ?>
                </div>
              </div>
              <div class="row">
                <div class="col fw-light" style="font-size: .90rem;">
                  <?= $tratamiento->usuario ?>
                </div>
              </div>
            </td>

            <!-- últimas prescripciones -->
            <td class="w-auto">
              <div class="row">

                <div class="col fw-light" style="font-size: .90rem;">

                  <?php if(is_array($tratamiento->prescriptors)) :  ?>
                    
                    <?php foreach ($tratamiento->prescriptors as $key => $value) : ?>
                      <div><?= $value ?></div>
                      
                    <?php endforeach ?> 
                    
                  <?php else : ?>
                    <?php if($tratamiento->prescripciones_totales == $tratamiento->prescripciones_caducadas) : ?>
                      <?= $tratamiento->prescripciones_prescriptores_totales ?>
                    <?php else : ?>
                      <?= $tratamiento->prescripciones_prescriptores ?>
                    <?php endif ?>
                  <?php endif ?>
                </div>
              </div>
            </td>

            <td class="w-auto">
              <div class="row"></div>
                <div class="col">
                  <?= $tratamiento->getConfirmadoHastaPretty() ?>
                </div>
              </div>              
            </td>

            <!-- duración del tracamiento en dias -->
            <td class="w-auto">
              <div class="row">
                <?php if($tratamiento->duracion) : ?>
                  <?=$tratamiento->duracion ?> <?= lang('Messages.days'); ?>
                <?php endif ?>
                </div>
            </td>

            <!-- Paciente de alta o Exitus -->
            <td class="w-auto">
              <div class="row">
                <?php if($tratamiento->fecha_exitus != null) : ?>
                  <span class="badge bg-danger" title="Exitus <?=$tratamiento->fecha_exitus ?> ">E</span>
                <?php elseif($tratamiento->fecha_alta != null) : ?>
                  <span class="badge bg-info" title="Alta <?=$tratamiento->fecha_alta ?> ">A</span>
                <?php endif ?>
              </div>
            </td>

            <!-- observaciones del tratamiento -->
            <td class="w-auto">
              <div class="row fw-light"  style="font-size: .90rem;">
                  <?=$tratamiento->obs_tratamiento ?>
              </div>
            </td>

            <td class="w-auto">
              <div> 
                <?php if($tratamiento->prescripciones_totales == $tratamiento->prescripciones_caducadas) : ?>
                  <div style="font-size: .90rem;" class="fw-light"><?= ($tratamiento->prescripciones_indicacion_totales != '' ? $tratamiento->prescripciones_indicacion_totales : 'Sin indicación definida') ?></div>
                <?php else : ?>
                  <div style="font-size: .90rem;" class="fw-light"><?= ($tratamiento->prescripciones_indicacion != '' ? $tratamiento->prescripciones_indicacion : 'Sin indicación definida') ?></div>
                <?php endif ?>    
              </div>
            </td>

            <!-- Estado de solicitud asociado al tratamiento -->
            <td class="w-auto">
              <a title="<?= lang('Messages.view_request_info'); ?>" data-toggle="tooltip" data-placement="top" target="_blank" href="<?=  base_url().route_to('view_solicitud', $tratamiento->id) ?>">
                <?php if($tratamiento->estado) : ?>
                  <span data-estado-tratamiento-id="<?=$tratamiento->id ?>" class="badge bg-<?=$tratamiento->bootstrap_class ?>"><?=$tratamiento->estado ?></span>
                <?php endif ?>
              </a>
            </td>
            
            <!-- botons acció-->
            <td class="text-nowrap" >
              <!-- Obre popup dades pacient -->
              <?php if(isset($permissions['modificar paciente'])) : ?>
                <button type="button" class="btn btn-info boton-popup-paciente mt-1" title="Datos Paciente" data-placement="top" data-cip="<?= $tratamiento->cip ?>"  data-nhc="<?= $tratamiento->nhc ?>" data-tratamiento_id="<?= $tratamiento->id ?>">
                  <i class="fa-solid fa-user"></i>
                </button>
              <?php endif ?>
                            
              <!-- Veure prescripciones inline -->
              <?php if($tratamiento->prescripciones_totales > 0) : ?>
                <button class="btn btn-warning boton-ver-prescripciones mt-1" type="button" title="Ver/Ocultar prescripciones" data-placement="top" data-bs-toggle="collapse" data-tratamiento-id="<?= $tratamiento->id ?>" data-bs-target="#prescripciones_<?= $tratamiento->id ?>" aria-expanded="false" aria-controls="tprescripciones_<?= $tratamiento->id ?>" data-placement="top">
                  <i class="fas fa-file-medical"></i>
                  <span class="fw-bold px-1"><?= $tratamiento->prescripciones_totales - $tratamiento->prescripciones_caducadas . '/' .$tratamiento->prescripciones_totales ?></span>
                 </button>
              <?php else : ?>
                <button class="btn btn-outline-warning mt-1" type="button" title="No hay productos prescritos" data-placement="top">
                  <i class="fas fa-file-medical"></i>
                  <span class="fw-bold px-1">0</span>
                </button>
              <?php endif ?>

              <!-- Veure popup seguiments -->
              <?php if($tratamiento->seguimientos_totales>0) : ?>
                <button type="button" class="btn btn-info boton-popup-seguimientos mt-1" title="Seguiments" data-placement="top" data-tratamiento_id="<?= $tratamiento->id ?>" data-estado="<?= $tratamiento->estado ?>">
                  <i class="fa-solid fa-clipboard-list"></i><span class="fw-bold px-1"><?= $tratamiento->seguimientos_totales ?></span>
                </button>
              <?php else : ?>
                <button type="button" class="btn btn-outline-info boton-popup-seguimientos mt-1" title="Sin seguimientos. Añadir" data-placement="top" data-tratamiento_id="<?= $tratamiento->id ?>" data-estado="<?= $tratamiento->estado ?>">
                <i class="fa-solid fa-clipboard-list"></i><span class="fw-bold px-1">+</span>
              </button>
              <?php endif ?>

              <!-- Obre popup per confirmar esborrat de tratamiento -->
              <?php if((isset($permissions['eliminar tratamiento']) && $permissions['eliminar tratamiento']) && ($tratamiento->estado_id < 3)  ) : ?>
                <button class="btn btn-danger boton-elimina-tratamiento mt-1" type="button" title="Eliminación del tratamiento y toda la información asociada" data-bs-toggle="tooltip" data-placement="top" 
                  data-tratamiento_id="<?= $tratamiento->id ?>" data-tipo_seguimiento_id="<?= $tipo_seguimiento_eliminacion ?>">
                  <i class="fas fa-trash"></i>
                </button>
              <?php endif ?>

            </td>
           
          </tr>

          <?php if(isset($tratamiento->prescripciones_totales) && $tratamiento->prescripciones_totales>0) : ?>
            
            <tr>
            <td colspan="10" class="hiddenRow">
              <div class="<?= (isset($currentTratamiento) && $currentTratamiento!=0 && $currentTratamiento == $tratamiento->id) ? '' : ' collapse ' ?>" id="prescripciones_<?=$tratamiento->id ?>"> 
                           
              </div>
            </td>
           
            </tr>
            
          <?php endif ?>

        <?php endforeach ?>
      </tbody>
      </table>
      
    </div>
  <?php else :?>
    <p><?= lang('Messages.filter_no_results'); ?></p>
  <?php endif ?>
  
  <!-- 
    Modals 
  -->
  
  <div class="modal fade" id="modal-confirma-delete" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modal-confirma-delete" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header bg-danger text-black">
	        <h5 class="modal-title" id="modal-error-title">Confirmar eliminación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>

	      <div class="modal-body">
            <p id="modal-msg-body">¿Seguro que deseas eliminar el tratamiento seleccionado? Se marcará como eliminado y por defecto no se visualizará desde el listado.</p>
	      </div>
	      <div class="modal-footer">
          <button type="button" class="btn btn-danger" id="modal-elimina-acepta">Elimina</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancela</button>
	      </div>
	    </div>
	  </div>
	</div>

  <?= $this->include('pacientes/modal') ?>
  <?= $this->include('seguimientos/modal') ?>


<?= $this->endSection() ?>

<?= $this->section('extrafooter') ?>
  <script src="<?php echo base_url('js/bootstrap-select.min.js');?>"></script>
  <script src="<?php echo base_url('js/close-alerts.js');?>"></script>
  <?= $this->include('tratamientos/script_listado') ?>
  <?= $this->include('seguimientos/script_modal') ?>
  <?= $this->include('pacientes/script_modal') ?>
<?= $this->endSection() ?>
