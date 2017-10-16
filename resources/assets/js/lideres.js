$(window).on('load', function() {
	
	$("#ModalActualizar").on('show.bs.modal', function (event) {
	  var button         = $(event.relatedTarget); // Button that triggered the modal
	  var id      	     = button.data('id');
	  var nombre 	 	     = button.data('nombre');
	  var cedula		     = button.data('cedula');
	  var correo 	  	   = button.data('correo');
	  var telefono	     = button.data('telefono');
	  var nivel		       = button.data('nivel');
	  var tipolider      = button.data('tipolider');
    var activo  	     = button.data('activo');
    var votosestimados = button.data('votosestimados');
    var id_municipio   = button.data('id_municipio');
    
	  var modal = $(this);
	  modal.find('.modal-body #idInput').val(id);
	  modal.find('.modal-body #nombreInput').val(nombre);
	  modal.find('.modal-body #cedulaInput').val(cedula);
	  modal.find('.modal-body #correoInput').val(correo);
	  modal.find('.modal-body #telefonoInput').val(telefono);
	  modal.find('.modal-body #nivelInput').val(nivel);
	  modal.find('.modal-body #tipoliderInput').val(tipolider);
    modal.find('.modal-body #activoInput').val(activo);
    modal.find('.modal-body #votosestimadosInput').val(votosestimados);
    modal.find('.modal-body #id_municipioInput').val(id_municipio);
	});

	$("#ModalEliminar").on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget); // Button that triggered the modal
	  var id = button.data('id');

	  var modal = $(this);
	  modal.find('.modal-body #idInput').val(id);
	});
});