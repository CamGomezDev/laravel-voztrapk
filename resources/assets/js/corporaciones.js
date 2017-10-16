$(window).on('load', function() {
  $("#ModalActualizar").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var id     = button.data('id');
    var nombre = button.data('nombre');

    var modal = $(this);
    modal.find('.modal-body #idInput').val(id);
    modal.find('.modal-body #nombreInput').val(nombre);
  });

  $("#ModalEliminar").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var id     = button.data('id');

    var modal = $(this);
    modal.find('.modal-body #idInput').val(id);
  })
})