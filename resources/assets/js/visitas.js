$(window).on("load", function () {
  $("#ModalActualizar").on("show.bs.modal", function (event) {
    var button       = $(event.relatedTarget);
    var id           = button.data('id');
    var notas        = button.data('notas');
    var llegada      = button.data('llegada');
    var salida       = button.data('salida');
    var id_municipio = button.data('id_municipio');

    var modal = $(this);
    modal.find('.modal-body #idInput').val(id);
    modal.find('.modal-body #notasInput').val(notas);
    modal.find('.modal-body #llegadaInput').val(llegada);
    modal.find('.modal-body #salidaInput').val(salida);
    modal.find('.modal-body #id_municipioInput').val(id_municipio);
  });

  $("#ModalEliminar").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var id     = button.data('id');

    var modal = $(this);
    modal.find('.modal-body #idInput').val(id);
  })
})