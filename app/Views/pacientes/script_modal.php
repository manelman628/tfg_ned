<script type="text/javascript">

  let direccion = '';
  let direccion_alternativa = '';
  let cp = '';
  let cp_alternativo = '';
  let poblacion = '';
  let poblacion_alternativa= '';
  let telefono = '';
  let telefono_alternativo = '';

  function muestraPaciente(nhc, idTratamiento, callback){
  
    direccion = '';
    poblacion = '';
    cp = '';
    telefono = '';

    direccion_alternativa = '';
    poblacion_alternativa = '';
    cp_alternativo = '';
    telefono_alternativo = '';

    $('#dato-direccion').text('');
    $('#dato-cp').text('');
    $('#dato-poblacion').text('');
    $('#dato-telefono').text('');
    $('#dato-direccion-alternativa').val('');
    $('#dato-cp-alternativo').val('');
    $('#dato-poblacion-alternativa').val('');
    $('#dato-telefono-alternativo').val('');
   
    $('#dato-direccion').removeClass('text-warning');
    $('#dato-poblacion').removeClass('text-warning');
    $('#dato-cp').removeClass('text-warning');
    $('#dato-telefono'). removeClass('text-warning');

    $('#boton-guarda-direccion-alternativa').data('nhc',nhc);
    $('#boton-borra-direccion-alternativa').data('nhc',nhc);   
    $('#boton-guarda-telefono-alternativo').data('nhc',nhc);
    $('#boton-borra-telefono-alternativo').data('nhc',nhc);   

    $('#wrapper-direccion').show();
    $('#wrapper-direccion-alternativa').hide();
    $('#wrapper-telefono').show();
    $('#wrapper-telefono-alternativo').hide()

    if(idTratamiento > 0){
  
      callback = callback || function(){};
      $.post('<?php echo base_url().route_to('tratamiento')?>',
      {
          "tratamiento_id"          :    idTratamiento
      }
      )
      .done(function(tratamiento){

          // datos del paciente
          $('#modal-paciente-cip').val(tratamiento != null ? tratamiento.cip : '');
          $('#modal-paciente-nhc').val(tratamiento != null ? tratamiento.nhc : '');
          $('#dato-cip').text(tratamiento != null ? tratamiento.cip : 'Error al obtener CIP del paciente');
          $('#dato-nhc').text(tratamiento != null ? tratamiento.nhc : 'Error al obtener NHC');
          $('#dato-nom').text(tratamiento != null ? (tratamiento.apellidos_paciente + ', ' + tratamiento.nombre_paciente) : '');
         
          let años = '';
          let edad = tratamiento && tratamiento.fecha_nacimiento ? new Date().getFullYear() - new Date(tratamiento.fecha_nacimiento).getFullYear() : '-';
          if(tratamiento && tratamiento.fecha_exitus != null){
            años = '<span title="Exitus" class="badge bg-danger ms-3">'+ fechaPretty(tratamiento.fecha_nacimiento) + ' - ' + fechaPretty(tratamiento.fecha_exitus) + '</span>';
            edad = new Date(tratamiento.fecha_exitus).getFullYear() - new Date(tratamiento.fecha_nacimiento).getFullYear();
          }else{
            años = '<span class="badge bg-info ms-3">' + fechaPretty(tratamiento.fecha_nacimiento) + '</span>';
          }
          $('#dato-fecha_nacimiento').html(edad + años);
          $('#dato-sexo').text(tratamiento != null ? tratamiento.sexo : '');
          
          direccion = (tratamiento != null ? tratamiento.direccion : '');
          poblacion = (tratamiento != null ? tratamiento.poblacion : '');
          cp = (tratamiento != null ? tratamiento.codigo_postal : '');
          telefono = (tratamiento != null ? tratamiento.telefono : '');
          $('#dato-direccion').text(direccion);
          $('#dato-poblacion').text(poblacion);
          $('#dato-cp').text(cp);
          $('#dato-telefono').text(telefono);

          $('#dato-antecedentes').text(tratamiento != null ? tratamiento.antecedents : '');

          // Datos del tratamiento
          $('#dato-tratamiento_id').text(tratamiento ? 'Tratamiento NED ' + idTratamiento : '-')  
          $('#dato-prescriptor').text(tratamiento ? tratamiento.usuario : '');
          $('#dato-colegiado').text(tratamiento ? tratamiento.usuario_num_colegiado : '');
          $('#dato-grupo').text(tratamiento ? tratamiento.usuario_grupo : '');
          $('#dato-servicio').text(tratamiento ? tratamiento.usuario_servicio : '');

          $('#dato-ingreso').text(tratamiento && tratamiento.fecha_ingreso ? fechaPretty(tratamiento.fecha_ingreso) : '');
          $('#dato-alta').text(tratamiento && tratamiento.fecha_alta ? fechaPretty(tratamiento.fecha_alta) : '');
          $('#dato-cambio_confirmado').text(tratamiento && tratamiento.fecha_cambio_confirmado ? fechaPretty(tratamiento.fecha_cambio_confirmado) : '');
          $('#dato-confirmado').text(tratamiento && tratamiento.confirmado_hasta ? fechaPretty(tratamiento.confirmado_hasta) : '');
          $('#dato-estado').text(tratamiento && tratamiento.estat ? tratamiento.estat : 'Pendiente');

          // también obtenemos datos extra del paciente
          if(nhc != ''){
            callback = callback || function(){};
            $.post('<?php echo base_url().route_to('paciente')?>',
            {
                "nhc"          :    nhc
            }
            )
            .done(function(paciente){     
             
              if(paciente != null){
                if(paciente.calle != null && paciente.calle != '' ){
                  $('#dato-direccion').addClass('text-warning');
                  $('#dato-direccion').text(paciente.calle);
                  $('#dato-direccion-alternativa').val(paciente.calle);
                }
                if(paciente.poblacion != null && paciente.poblacion != '' ){
                  $('#dato-poblacion').addClass('text-warning');
                  $('#dato-poblacion').text(paciente.poblacion);
                  $('#dato-poblacion-alternativa').val(paciente.poblacion);
                }
                if(paciente.codigo_postal != null && paciente.codigo_postal != '' ){
                  $('#dato-cp').addClass('text-warning');
                  $('#dato-cp').text(paciente.codigo_postal);
                  $('#dato-cp-alternativo').val(paciente.codigo_postal);
                }
                if(paciente.telefono != null && paciente.telefono != '' ){
                  $('#dato-telefono').addClass('text-warning');
                  $('#dato-telefono').text(paciente.telefono);
                  $('#dato-telefono-alternativo').val(paciente.telefono);
                }
              }
            })
            .fail(function(error){
                console.error(error);
                $('#boton-edita-direccion-alternativa').hide();
                $('#boton-edita-telefono-alternativa').hide();
                callback();
            });
          }
      })
      .fail(function(error){
          console.error(error);
          $('#dato-cip').text('Error al recuperar paciente');
          $('#dato-tratamiento').text('Error al recuperar tratamiento');
          callback();
      });
    }
    
    $('#modal-paciente').modal('show');
  }


  function modificaDireccion(callback){
    callback = callback || function(){};
   
    let cip = $('#modal-paciente-cip').val();
    let nhc = $('#modal-paciente-nhc').val();
    let modif_direccion = $('#dato-direccion-alternativa').val();
    let modif_poblacion = $('#dato-poblacion-alternativa').val();
    let modif_cp = $('#dato-cp-alternativo').val();

    $.post('<?php echo base_url().route_to('save_direccion_paciente')?>',
      {
        "cip"                 :     cip,
        "nhc"                 :     nhc,
        "calle"               :     modif_direccion,
        "poblacion"           :     modif_poblacion,
        "codigo_postal"       :     modif_cp
      }
    )
    .done(function(resultado){
      
      if(resultado.error == 1){
        $('#modal-error-paciente-body').text(resultado.txterror);
        $('#modal-error-paciente').modal('show');

      }else{
        $('#dato-direccion').text(modif_direccion);
        $('#dato-poblacion').text(modif_poblacion);  
        $('#dato-cp').text(modif_cp);  
        direccion_alternativa = modif_direccion;
        cp_alternativo = modif_cp;
        if(direccion_alternativa != direccion){
          $('#dato-direccion').addClass('text-warning');
        }else{
          $('#dato-direccion').removeClass('text-warning');
        }
        if(poblacion_alternativa != poblacion){
          $('#dato-poblacion').addClass('text-warning');
        }else{
          $('#dato-poblacion').removeClass('text-warning');
        }
        if(cp_alternativo != cp){
          $('#dato-cp').addClass('text-warning');
        }else{
          $('#dato-cp').removeClass('text-warning');
        }
      }
      callback();
    })
    .fail(function(error){
      console.log(error);
      $('#modal-error-paciente-body').text(error.text);
      $('#modal-error-paciente').modal('show');
    });
  }

  function modificaTelefono(callback){
    callback = callback || function(){};
   
    let cip = $('#modal-paciente-cip').val();
    let nhc = $('#modal-paciente-nhc').val();
    let modif_telefon = $('#dato-telefono-alternativo').val();

    $.post('<?php echo base_url().route_to('save_telefono_paciente')?>',
      {
        "cip"                 :     cip,
        "nhc"                 :     nhc,
        "telefono"             :     modif_telefon
      }
    )
    .done(function(resultado){
      
      if(resultado.error == 1){
        $('#modal-error-paciente-body').text(resultado.txterror);
        $('#modal-error-paciente').modal('show');

      }else{
        $('#dato-telefono').text(modif_telefon);
        telefono_alternativo = modif_telefon;
        if(telefono_alternativo != telefono){
          $('#dato-telefono').addClass('text-warning');
        }else{
          $('#dato-telefono').removeClass('text-warning');
        }
      }
      callback();
    })
    .fail(function(error){
      console.log(error);
      $('#modal-error-paciente-body').text(error.text);
      $('#modal-error-paciente').modal('show');
    });
  }
  
  function borraDireccion(callback){
    callback = callback || function(){};
   
    let cip = $('#modal-paciente-cip').val();

    $.post('<?php echo base_url().route_to('delete_direccion_paciente')?>',
      {
        "cip"                 :     cip
      }
    )
    .done(function(resultado){
      
      if(resultado.error == 1){
        $('#modal-error-paciente-body').text(resultado.txterror);
        $('#modal-error-paciente').modal('show');
      }else{
        $('#dato-direccion').text(direccion);
        $('#dato-poblacion').text(poblacion); 
        $('#dato-cp').text(cp); 
        $('#dato-direccion-alternativa').val('');  
        $('#dato-poblacion-alternativa').val('');   
        $('#dato-cp-alternativo').val('');   
        direccion_alternativa = '';
        poblacion_alternativa = '';
        cp_alternativo = '';
        $('#dato-direccion').removeClass('text-warning');
        $('#dato-poblacion').removeClass('text-warning');
        $('#dato-cp').removeClass('text-warning');
      }
      callback();
    })
    .fail(function(error){
      console.log(error);
      $('#modal-error-paciente-body').text(error.text);
      $('#modal-error-paciente').modal('show');
    });

  }

  function borraTelefono(callback){
    callback = callback || function(){};
   
    let cip = $('#modal-paciente-cip').val();

    $.post('<?php echo base_url().route_to('delete_telefono_paciente')?>',
      {
        "cip"                 :     cip
      }
    )
    .done(function(resultado){
      
      if(resultado.error == 1){
        $('#modal-error-paciente-body').text(resultado.txterror);
        $('#modal-error-paciente').modal('show');

      }else{
        $('#dato-telefono').text(telefono);
        $('#dato-telefono-alternativo').val('');  
        telefono_alternativo = '';
        $('#dato-telefono'). removeClass('text-warning');
      }
      callback();
    })
    .fail(function(error){
      console.log(error);
      $('#modal-error-paciente-body').text(error.text);
      $('#modal-error-paciente').modal('show');
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

  function permutaVisibilidadDireccion(){
    if ($('#wrapper-direccion').is(':visible')) {
      $('#wrapper-direccion').hide();
      $('#wrapper-direccion-alternativa').show();
    } else {
      $('#wrapper-direccion').show();
      $('#wrapper-direccion-alternativa').hide();
    }
 
  }

  function permutaVisibilidadTelefono(){
    if ($('#wrapper-telefono').is(':visible')) {
      $('#wrapper-telefono').hide();
      $('#wrapper-telefono-alternativo').show();
    } else {
      $('#wrapper-telefono').show();
      $('#wrapper-telefono-alternativo').hide();
    }
  }

  $('#boton-edita-direccion-alternativa').click(function(){
    permutaVisibilidadDireccion();
  });
  
  $('#boton-edita-telefono-alternativo').click(function(){
    permutaVisibilidadTelefono();
  });
  
  $('#boton-borra-direccion-alternativa').click(function(){
    borraDireccion();
    permutaVisibilidadDireccion(); 
  }); 
  
  $('#boton-borra-telefono-alternativo').click(function(){
    borraTelefono();
    permutaVisibilidadTelefono(); 
  }); 

  $('#boton-guarda-direccion-alternativa').click(function(){
    modificaDireccion();
    permutaVisibilidadDireccion(); 
  });  

  $('#boton-guarda-telefono-alternativo').click(function(){
    modificaTelefono();
    permutaVisibilidadTelefono(); 
  });  
  
</script>
