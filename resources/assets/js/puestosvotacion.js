$(window).on('load', function() {
  $("#ModalActualizar").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var id     = button.data('id');
    var comuna = button.data('comuna');
    var nombre = button.data('nombre');
    var barrio = button.data('barrio');
    var mesas  = button.data('mesas');

    var modal = $(this);
    modal.find('.modal-body #idInput').val(id);
    modal.find('.modal-body #comunaInput').val(comuna);
    modal.find('.modal-body #nombreInput').val(nombre)
    modal.find('.modal-body #barrioInput').val(barrio);
    modal.find('.modal-body #mesasInput').val(mesas);
  });

  $("#ModalEliminar").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var id     = button.data('id');

    var modal = $(this);
    modal.find('.modal-body #idInput').val(id);
  })
})