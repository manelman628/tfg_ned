<script type="text/javascript">

let cargandoPrescripciones = false;

  // Muestra filtros aplicados en la parte superior del listado
  function mostrarFiltrosAplicados(){
    var boto_filtre = false;
    $('#form-filtros').find('input[type="text"], input[type="number"], input[type="date"]').each(function(){  
      if(($(this).val()).length > 0){
        $('#botones-filtros').append('<span class="btn btn-outline-secondary boton-filtro" title="' + $(this).data('descrip-filtro') + '">' + $(this).val() + '</span>');       
        boto_filtre = true;
      }
    });
    $('#form-filtros').find('.selectpicker').each(function(){  
        if($(this).find(':selected').val()){
          if ($(this).attr('name') === 'estado_id[]' || $(this).attr('name') === 'servicio_codigo[]') {
            var seleccionats = '';
            $(this).find(':selected').each(function(index, value) {
              if (index > 0) {
                seleccionats += ', ';
              }
              seleccionats += $(this).text();
            });
            $('#botones-filtros').append('<span class="btn btn-outline-secondary boton-filtro" title="' + $(this).data('descrip-filtro') + '">' + seleccionats + '</span>');    
          }else{
            $('#botones-filtros').append('<span class="btn btn-outline-secondary boton-filtro" title="' + $(this).data('descrip-filtro') + '">' + $(this).find(':selected').text() + '</span>');    
          }
          boto_filtre = true;
        }   
      
    })

    $('#form-filtros').find('input[type=checkbox]').each(function(){  
      if($(this).prop('checked') == true){
        $('#botones-filtros').append('<span class="btn btn-outline-secondary boton-filtro" title="' + $(this).data('descrip-filtro') + '">' + $(this).data('descrip-filtro') + '</span>');    
        boto_filtre = true;
      }      
    });

    if(boto_filtre){
      $('#botones-filtros').append('<button class="btn btn-secondary boton-filtro" id="quitar-filtros" title="Limpiar Filtros"><i class="fas fa-filter-circle-xmark"></i></button>');    
    }
      
  }

  // Elimina un tratamiento
  function eliminaTratamiento(idTratamiento, idTipoSeguimiento, callback){
    callback = callback || function(){};
   
   $.post('<?php echo base_url().route_to('save_seguiment')?>',
     {
       "tratamiento_id"           :    idTratamiento,
       "tipo_seguimiento_id"      :    idTipoSeguimiento
     }
   )
   .done(function(result){
     
     if(result.error == 1){
       alert("Error al borrar tratamiento");
     }else{
      location.reload();
     }
     callback();
   })
   .fail(function(error){
      alert("Error al borrar tratamiento");
   });
  }

  // Carga las prescripciones de un tratamiento en el DOM del div correspondiente al tratamiento
  function cargaPrescripciones(botonTratamientos, muestraCaducadas, callback){
    callback = callback || function(){};
   
    let idTratamiento = botonTratamientos.data('tratamiento-id');
    $('#prescripciones_' + idTratamiento).empty();

    $.post('<?php echo base_url().route_to('prescripciones_tratamiento')?>',
      {
        "tratamiento_id"           :    idTratamiento,
        "muestra_caducadas"        :    muestraCaducadas
      }
    )
    .done(function(result){
      let caducades = 0;
      if(result.error == 1){
        alert("Error al obtener prescripciones");
      }else{
        if(result.prescripciones.length == 0){       
          if (muestraCaducadas){
            $('#prescripciones_' + idTratamiento).html('<div class="alert alert-warning" role="alert">No hay prescripciones para este tratamiento.</div>');
          }else{
            $('#prescripciones_' + idTratamiento).html('<div class="alert alert-danger" role="alert">No hay prescripciones activas para este tratamiento. Cambia las opciones de visualización para ver las prescripciones antiguas</div>');
          }
          

        }else{
          let prescripciones = result.prescripciones;
          
          html='';
          for (var i = 0; i < prescripciones.length; i++) {
            
            let prescripcion = prescripciones[i];
            if (prescripcion.caducada == 1) {
              caducades++;
            }
            html += `<li id="fprescripcion_' + $prescripcion.id +'" style="font-size: .90rem;" class="fw-light list-group-item list-group-item-` + (prescripcion.caducada == 1 ? 'danger' : 'info') + `">`;
            html += `<div class="row">
                      <div class="col-8">
                        <i class="fa-solid fa-bullseye text-secondary mx-1" title="Indicación"></i>
                         &nbsp;` + (prescripcion.codigo_indicacion == null ? `Sin indicación definida` : prescripcion.codigo_indicacion + `: `) + (prescripcion.indicacion ?? ``) + `
                      </div>
                      <div class="col-2">
                        <i class="fa-solid fa-syringe text-secondary mx-1" title="Vía"></i>
                        &nbsp;` + (prescripcion.via_descripcion ?? ``) + `
                      </div>
                      <div class="col-sm-2">` + (prescripcion.usuario_prescripcion ?? ``) + `</div>
                    </div>`;

            html += `<div class="row">
                        <div class="col-8">
                          <i class="fa-solid fa-pills text-secondary mx-1" title="Medicamento"></i>
                          &nbsp;` + (prescripcion.medi_desc ?? ``) + `
                        </div>
                        <div class="col-2">
                          <i class="fa-solid fa-calendar text-secondary mx-1" title="Frecuencia"></i>
                          &nbsp;` + (prescripcion.frec_descripcion ?? ``) + `
                        </div>
                        <div class="col-sm-2">
                          Inicio:&nbsp;` + (prescripcion.fecha_inicio ?? ``) + `
                        </div>
                      </div>`;

            html += `<div class="row">
                        <div class="col-8">
                          <i class="fa-solid fa-comment-medical text-secondary mx-1" title="Consejos administración"></i>
                          &nbsp;` + (prescripcion.consejos_admin ?? ``) + `
                        </div>
                        <div class="col-2">
                          <i class="fa-solid fa-scale-balanced text-secondary mx-1" title="Dosis"></i>
                          &nbsp;` + (prescripcion.dosis ?? ``) + `
                        </div>
                        <div class="col-sm-2">
                          Fin:&nbsp;`+ (prescripcion.fecha_fin ?? ``) + `
                            <div class="float-sm-end">
                            <span class="badge bg-secondary" title="ID prescripción">#` + prescripcion.id_prescripcion + `</span>
                            </div>
                        </div>
                      </div>
                    </li>`;                          
          }  
          $('#prescripciones_' + idTratamiento).append('<ul class="list-group">'+html+'</ul>').hide().fadeIn(500);    
        }
        callback();
      }
      cargandoPrescripciones = false;
      botonTratamientos.prop("disabled", false);
      botonTratamientos.find("svg").removeClass("fa-spin");
     
    })
   .fail(function(error){
      cargandoPrescripciones = false;
      botonTratamientos.prop("disabled", false);
      botonTratamientos.find("svg").removeClass("fa-spin");
      alert("Error al obtener prescripciones");
   });
  }

  $(document).ready(function(){

    $('[data-bs-toggle="popover"]').popover();

    mostrarFiltrosAplicados();
    
    $(".boton-ver-prescripciones").click(function() {
      
      if (cargandoPrescripciones){
        return;
      } 

      cargandoPrescripciones = true;
      this.disabled = true;

      let icon = $(this).find("svg");
      let llamadaPrescrip = icon.hasClass("fa-file-medical");
      icon.toggleClass("fa-file-medical fa-caret-up");

      // solo llamamos a cargaPrescripciones cuando el div se debe mostrar
      if (llamadaPrescrip) {
        icon.addClass("fa-spin");
        cargaPrescripciones($(this),$("#chk_muestra_prescripciones").prop("checked"));
        
      }else{
        cargandoPrescripciones = false;
        this.disabled = false;
        icon.removeClass("fa-spin");
      }
    
    });


    $('#quitar-filtros').click(function(e){
      e.preventDefault();
      // dejar los valores del formulario en blanco
      $('#form-filtros').find('.selectpicker').each(function(){  
          $(this).val('');
          $(this).selectpicker('refresh');     
      });
      $('#form-filtros').find('.input-filter').each(function(){  
        $(this).val('');
      });
      $('#form-filtros').find('.form-check-input').each(function(){  
          $(this).attr('checked',false);
      });

      // volvemos a enviar el formulario
      $('#form-filtros').submit();

    });

    $('.boton-elimina-tratamiento').click(function(){
      $('#modal-elimina-acepta').data('tratamiento_id', $(this).data('tratamiento_id'));
      $('#modal-elimina-acepta').data('tipo_seguimiento_id', $(this).data('tipo_seguimiento_id'));
      $('#modal-confirma-delete').modal('show');
    });

    $('#modal-elimina-acepta').click(function(){
      eliminaTratamiento($(this).data('tratamiento_id'),$(this).data('tipo_seguimiento_id'));
    });

    $('.boton-popup-seguimientos').click(function(){
      $('#modal-seguimiento-span-estado').text('Solicitud ' + $(this).data('estat'));
      muestraSeguimientos($(this).data('tratamiento_id'),);
    });

    $('.boton-popup-paciente').click(function(){
      muestraPaciente($(this).data('nhc'), $(this).data('tratamiento_id') );
    });
    
    $('#exporta').click(function(e){
      e.preventDefault();
    
      var filters = $('#form-filtros').serialize();
      window.open("<?= base_url().route_to('export_tratamientos') ?>?"+ filters);
    })

    setTimeout(function() {
      $(".alert-success").fadeTo(1000, 0).slideUp(1000, function(){
        $(this).remove(); 
      });
    }, 3000);

    $('#form-filtros').submit(function() {
      var submitButton = $(this).find('button[type="submit"]');
      var spinner = $('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
      submitButton.prop('disabled', true);
      submitButton.prepend(spinner);
      return true;
    });

    $('#btn-otros-filtros').click(function() {
      var checkboxes = $('#div-otros-filtros').find('input[type=checkbox]');
      var allChecked = checkboxes.length === checkboxes.filter(':checked').length;
      checkboxes.prop('checked', !allChecked);
    });

    $('#btn-visualizacion').click(function() {
      var checkboxes = $('#div-visualitzacio').find('input[type=checkbox]');
      var allChecked = checkboxes.length === checkboxes.filter(':checked').length;
      checkboxes.prop('checked', !allChecked);
    });

  });


</script>
