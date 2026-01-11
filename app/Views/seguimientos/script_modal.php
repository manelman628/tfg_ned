<script type="text/javascript">


  //Prepara el modal con la info del seguimiento
   function muestraSeguimientos(idTratamiento, idSeguimiento, callback){
    
    $(".te-popover").popover('dispose');

    $('#modal-card-header').text("Nuevo Seguimiento");
    $('#modal-tipo_seguimiento_id').val("").trigger('change');
    $('#modal-fecha_seguimiento').val("");
    $('#modal-observaciones').val("");
    $('#modal-equipo_administracion_id').val("").trigger('change');

    var date = new Date();
    var curDateStr = date.getFullYear().toString() + '-' + (date.getMonth() + 1).toString().padStart(2, 0) + '-' + date.getDate().toString().padStart(2, 0);

    if(idSeguimiento > 0){
      
      $('#modal-boton-añadir-seguimiento').hide();
      $('#modal-list-seguimientos').hide();
      $('#modal-card-añadir-seguimiento').show();


      callback = callback || function(){};
      $.post('<?php echo base_url().route_to('seguimiento')?>',
      {
          "id"          :    idSeguimiento
      }
      )
      .done(function(seguimiento){
                
        if(idTratamiento > 0){
        
        }else{
          //edita existent
          $('#modal-card-header').text("Edita Seguiment");
          $('#modal-tratamiento_id').val(seguimiento.tratamiento_id);
          $('#modal-seguimiento').data('id', idSeguimiento);
          $('#modal-tipo_seguimiento_id').val(seguimiento.tipo_seguimiento_id).trigger('change');
          $('#modal-tipo_seguimiento_id').prop('disabled', true);
          $('#modal-fecha_seguimiento').val(seguimiento.fecha_seguimiento);
          $('#modal-observaciones').val(seguimiento.observaciones);
          $('#modal-equipo_administracion_id').val(seguimiento.equipo_administracion_id).trigger('change');
        }
      })
      .fail(function(error){
          console.error(error);
          callback();
          $('#modal-error-segumiento-body').text(error);
          $('#modal-error-segumiento').modal('show');
      });
    }

    if(idTratamiento > 0){

      $('#modal-list-seguimientos').show();
      $('#modal-card-añadir-seguimiento').hide();
      $('#modal-boton-añadir-seguimiento').show();

      //nuevo seguimiento
      $('#modal-seguimiento').data('id', '');
      $('#modal-tratamiento_id').val(idTratamiento); 
      $('#modal-fecha_seguimiento').val(curDateStr);
      $('#modal-tipo_seguimiento_id').prop('disabled', false);
 
      callback = callback || function(){};   
      $.post('<?php echo base_url().route_to('seguimientos_tratamiento')?>',
      {
          "tratamiento_id"          :    idTratamiento
      }
      )
      .done(function(datos){

        //pintamos historico seguimientos
        $('#modal-list-seguimientos').empty();

        if(datos.seguimientos.length == 0){
          $('#modal-list-seguimientos').append('No hay seguimientos registrados para esta solicitud.');
          $('#modal-card-añadir-seguimiento').show();
          $('#modal-boton-añadir-seguimiento').hide();
        }else{
          let seguimientos = datos.seguimientos;
          for (var i = 0; i < seguimientos.length; i++) {
            let editable = '';
            
            if(seguimientos[i].usuario_creacion == '<?php echo $usuario; ?>' || <?php echo (isset($is_admin) && $is_admin==true ? 'true' : 'false'); ?>){
                //si el usuario que ha creado el seguimiento es el mismo que el usuario logueado, o es un administrador, permite editar
              if (seguimientos[i].editable == 1){
                editable = '<span data-id_editar_seguimiento="'+seguimientos[i].id+'" role="button" class="badge bg-warning mx-2 modal-editar-seguimiento" title="Edita"><i class="fa-regular fa-pen-to-square"></i></span>';
              }else{
                //si no es editable, solo muestra icono a modo informativo
                editable = '<span class="badge bg-secondary mx-2" title="Este tipo de seguimiento no es editable"><i class="fa-regular fa-pen-to-square"></i></span>';
              }
            }

            let html = '<li class="list-group-item d-flex justify-content-between align-items-start">'+
                '<div class="w-100">'+
                  '<div class="fw-bold my-1">' + seguimientos[i].tipo_seguimiento +
                    '<div class="float-end">'+
                      '<span class="badge bg-info" title="Creado per '+ seguimientos[i].usuario_creacion +' el '+ fechaPretty(seguimientos[i].fecha_seguimiento) + '">' + seguimientos[i].usuario_creacion + ' - ' + fechaPretty(seguimientos[i].fecha_seguimiento) +'</span>'+
                      editable +
                    '</div>'+
                  '</div>'+
                  '<div>'+
                    (seguimientos[i].observaciones ? '<span class="fw-light" style="font-size: .90rem;">'+ seguimientos[i].observaciones + '</span>' : '') +
                    (seguimientos[i].observaciones && seguimientos[i].bomba ? '<br>' : '') +
                    (seguimientos[i].bomba ? '<span title="Equipo administración" class="badge bg-secondary">'+ seguimientos[i].bomba + ' - ' + seguimientos[i].lab + ' - ' + seguimientos[i].equipo + '</span>' : '') +
                  '</div>'+
                '</div>'+
              '</li>';
            $('#modal-list-seguimientos').append(html);
            if (editable != ''){
              $('.modal-editar-seguimiento').on('click', function() {
                muestraSeguimientos(null, $(this).data('id_editar_seguimiento'));
              });
            }
          }  
        }

        //limpiamos y añadimos al desplegable las opciones disponibles segun estado de solicitud y rol de usuario
        $('#modal-tipo_seguimiento_id').find('option').remove();
        if(datos.tipos.length > 0){
          let tipos = datos.tipos;
          for (var i = 0; i < tipos.length; i++) {
            $('#modal-tipo_seguimiento_id').append('<option value="'+tipos[i].id+'">'+tipos[i].descripcion + '</option>');
          }
        }

      })
      .fail(function(error){
          console.error(error);
          callback();
          $('#modal-error-segumiento-body').text(error);
          $('#modal-error-segumiento').modal('show');
      });


      $.post('<?php echo base_url().route_to('tratamiento')?>',
      {
          "tratamiento_id"          :    idTratamiento
      })
      .done(function(tratamiento){
        $('#modal-seguimiento-span-nombre').text(tratamiento != null ? (tratamiento.apellidos_paciente + ', ' + tratamiento.nombre_paciente) : '');
        $('#modal-seguimiento-span-estado').text(tratamiento != null ? ('Solicitud ' + tratamiento.estado) : 'Solicitud Pendiente');
        callback();

      })
      .fail(function(error){
          console.error(error);
          callback();
      });

    }
    $('#modal-seguimiento').modal('show');
  
  }


  //
  // Validación de campos del modal de seguimiento
  // 
  function validaSeguimiento(){

    var errors  = 0;
      
    if(!$('#modal-tipo_seguimiento_id').val()){  // si empty string, false, 0, null, undefined, ...
      $('#modal-tipo_seguimiento_id').attr('data-bs-content', "Seleccionar tipo de seguimiento");
      $('#modal-tipo_seguimiento_id').attr('data-bs-title',"Tipo de Seguimiento");
      $('#modal-tipo_seguimiento_id').attr('data-bs-trigger',"focus");
      new bootstrap.Popover($('#modal-tipo_seguimiento_id'), {
        trigger: 'focus'
      }).show(); 
      errors += 1;
    }

    if(!$('#modal-fecha_seguimiento').val() || $('#modal-fecha_seguimiento').val() == ''){
      $('#modal-fecha_seguimiento').attr('data-bs-content', "Seleccionar fecha de seguimiento");
      $('#modal-fecha_seguimiento').attr('data-bs-title',"Fecha de Seguimiento");
      $('#modal-fecha_seguimiento').attr('data-bs-trigger',"focus");
      new bootstrap.Popover($('#modal-fecha_seguimiento'), {
          trigger: 'focus'
        }).show();
      errors += 1;
    }else{

      let dateInput = $('#modal-fecha_seguimiento').val().replace("-","/");
      if(isNaN(new Date(dateInput))){
        new bootstrap.Popover($('#modal-fecha_seguimiento'), {
          trigger: 'focus'
        }).show();
        errors += 1;
      }
    }
    return (errors == 0);
  }


  //
  // Guarda modificaciones del seguimiento realizadas en el modal
  //
  function modificaSeguimiento(callback){
    callback = callback || function(){};
   
    let idt = $('#modal-tratamiento_id').val();
   
    $.post('<?php echo base_url().route_to('save_seguimiento')?>',
      {
        "id"                        :    $('#modal-seguimiento').data('id'),
        "tratamiento_id"            :    $('#modal-tratamiento_id').val(),
        "observaciones"             :    $('#modal-observaciones').val(),
        "fecha_seguimiento"         :    $('#modal-fecha_seguimiento').val(),
        "tipo_seguimiento_id"       :    $('#modal-tipo_seguimiento_id').val(),
        "equipo_administracion_id"  :    $('#modal-equipo_administracion_id').val()
      }
    )
    .done(function(resultado){
      
      if(resultado.error == 1){
        $('#modal-error-segumiento-body').text(resultado.txterror);
        $('#modal-error-segumiento').modal('show');

      }else{
        if(resultado.changed){
          //si ha cambiado el estado del tratamiento, actualitzamos el listado principal
          let spanEstat = $('#listado-tratamientos').find('span[data-estado-tratamiento-id="'+resultado.changed.tratamiento_id+'"]');
          if(spanEstat.length > 0){
              spanEstat.text(resultado.changed.estado!= null ? resultado.changed.estado : 'Pendent');
              spanEstat.removeClass();
              spanEstat.addClass('badge bg-'+(resultado.changed.clase_bootstrap!= null ? (resultado.changed.clase_bootstrap != '' ? resultado.changed.clase_bootstrap : 'secondary') : 'secondary'));
          }
        }
        muestraSeguimientos(idt);
      
      }
      callback();
    })
    .fail(function(error){
      $('#modal-error-segumiento-body').text(error);
      $('#modal-error-segumiento').modal('show');
    });

  }
  
  function fechaPretty(data){
    try{
      if(data == null || data == ''){
        return '';
      }
      let date = new Date(data);
      return date.getDate().toString().padStart(2, 0) + '/' + (date.getMonth() + 1).toString().padStart(2, 0) + '/' + date.getFullYear().toString();
    }catch(e){
      return '';
    }
  }

  $('#modal-modifica-acepta').click(function(){
      if(validaSeguimiento()){
        $(".te-popover").popover('dispose');
        modificaSeguimiento();
        $('#modal-card-añadir-seguimiento').hide();
        $('#modal-boton-añadir-seguimiento').show();
        $('#modal-list-seguimientos').show();
      }
  });  

  $('#modal-boton-añadir-seguimiento').click(function(){
   
    muestraSeguimientos($('#modal-tratamiento_id').val());
    $('#modal-list-seguimientos').hide();
    $('#modal-card-añadir-seguimiento').show();
    $(this).hide();
    
  });  
 
  $('#modal-boton-cancelar-seguimiento').click(function(){
    $('#modal-card-añadir-seguimiento').hide();
    $('#modal-boton-añadir-seguimiento').show();
    $('#modal-list-seguimientos').show();
  }); 

  //
  // Mostrar/ocultar select equipo administración según tipo seguimiento
  //
  $('#modal-tipo_seguimiento_id').change(function(){
    if($(this).val() == 12){
      $('#select-equipo').show();
    }else{
      $('#modal-equipo_administracion_id').val('').trigger('change');
      $('#select-equipo').hide();
    }
  }); 
 

</script>