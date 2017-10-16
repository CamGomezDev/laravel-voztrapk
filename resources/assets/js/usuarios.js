$(window).on('load', function() {
  $("#ModalActualizar").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var id     = button.data('id');
    var name   = button.data('name');
    var email  = button.data('email');
    var id_rol = button.data('id_rol');

    var modal = $(this);
    modal.find('.modal-body #idInput').val(id);
    modal.find('.modal-body #nameInput').val(name);
    modal.find('.modal-body #emailInput').val(email);
    modal.find('.modal-body #id_rolInput').val(id_rol);
  });

  $("#ModalEliminar").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var id     = button.data('id');

    var modal = $(this);
    modal.find('.modal-body #idInput').val(id);
  })
})