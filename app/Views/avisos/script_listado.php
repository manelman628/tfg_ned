<script type="text/javascript">

function procesaAviso(idAvis, callback){
   callback = callback || function(){};
   
   $('#alerta').remove();

   $.post('<?php echo base_url().route_to('procesa_aviso')?>',
     {
       "id"       :    idAvis
     }
   )
   .done(function(resultat){
     if(resultat.error == 1){
        msg = '<h4 class="alert-heading">Error</h4><p>No se ha podido procesar el aviso.</p>';
        clase = 'alert-danger';
     }else{
        msg = '<h4 class="alert-heading">Éxito</h4><p>Se ha procesado el aviso correctamente.</p>';
        clase = 'alert-success';
        $('#avis_' + idAvis).fadeOut(300, function() {
          $(this).remove();
        });
     }

     $('#avisos').hide().prepend('<div id="alerta" class="alert ' + clase + ' alert-dismissible fade show" role="alert">' +
                        msg +
                      '<button type="button" class="btn-close btn-close-success" data-bs-dismiss="alert" aria-label="Close"></button></div>').fadeIn(500);
     callback();
   })
   .fail(function(error){
      alert("Error al procesar el aviso.");
   });
  }

  $(document).ready(function(){

    $('.procesa-aviso').click(function(e){
      procesaAviso($(this).data('aviso_id'));
    });

  });

</script>
