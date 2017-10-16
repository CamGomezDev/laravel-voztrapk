$(window).on('load', function() {
	$("#ModalActualizar").on('show.bs.modal', function (event) {
	  var button       = $(event.relatedTarget); // Button that triggered the modal
	  var id      	   = button.data('id');
	  var id_lider 	 	 = button.data('id_lider');
	  var nombre 	  	 = button.data('nombre');
	  var descripcion	 = button.data('descripcion');
	  var cumplimiento = button.data('cumplimiento');
	  var costo        = button.data('costo');
    
	  var modal = $(this);
	  modal.find('.modal-body #idInput').val(id);
	  modal.find('.modal-body #id_liderInput').val(id_lider);
	  modal.find('.modal-body #nombreInput').val(nombre);
	  modal.find('.modal-body #descripcionInput').val(descripcion);
	  modal.find('.modal-body #cumplimientoInput').val(cumplimiento);
	  modal.find('.modal-body #costoInput').val(costo);
	});

	$("#ModalEliminar").on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget); // Button that triggered the modal
	  var id = button.data('id');

	  var modal = $(this);
	  modal.find('.modal-body #idInput').val(id);
	});
});