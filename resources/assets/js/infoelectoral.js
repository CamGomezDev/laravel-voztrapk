$(window).on('load', function() {
	$("#ModalActualizar").on('show.bs.modal', function (event) {
	  var button             = $(event.relatedTarget); // Button that triggered the modal
	  var id      	    	 	 = button.data('id');
	  var id_municipio 	 	   = button.data('id_municipio');
	  var id_corporacion		 = button.data('id_corporacion');
	  var votostotales 	  	 = button.data('votostotales');
	  var votoscandidato	   = button.data('votoscandidato');
	  var votospartido		   = button.data('votospartido');
	  var potencialelectoral = button.data('potencialelectoral');
    var anio  	        	 = button.data('anio');
    
	  var modal = $(this);
	  modal.find('.modal-body #idInput').val(id);
	  modal.find('.modal-body #id_municipioInput').val(id_municipio);
	  modal.find('.modal-body #id_corporacionInput').val(id_corporacion);
	  modal.find('.modal-body #votostotalesInput').val(votostotales);
	  modal.find('.modal-body #votoscandidatoInput').val(votoscandidato);
	  modal.find('.modal-body #votospartidoInput').val(votospartido);
	  modal.find('.modal-body #potencialelectoralInput').val(potencialelectoral);
	  modal.find('.modal-body #anioInput').val(anio);
	});

	$("#ModalEliminar").on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget); // Button that triggered the modal
	  var id = button.data('id');

	  var modal = $(this);
	  modal.find('.modal-body #idInput').val(id);
	});
});