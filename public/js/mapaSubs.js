var svg;
var posSvg;
var svgDoc;
var svgSubregiones;
var munColor;
var aElementComproId;

$(window).on("load", function () {
  svg    = document.getElementById("ant_svg");
  posSvg = $(svg).position();
  svgDoc = svg.contentDocument;
  svgSubregiones = svgDoc.rootElement.getElementsByTagNameNS("http://www.w3.org/2000/svg", "g");

  // conseguirResumen();
  // conseguirFilasElectorales(1, 5);
  conseguirLideres(1, 5);

  $('#irabajo').click(function () {
    $('html,body').animate({
      scrollTop: $("#irarriba").offset().top},
    'slow');
  });

  $('#irarriba').click(function () {
    $('html,body').animate({
      scrollTop: $("body").offset().top},
    'slow');
  });

  if (typeof document.getElementById("ant_svg") !== 'undefined') {
    InicMapaAntioquia();
  }
  
  // $(document).click( function(event){
  //     var $trigger = $("#divBusqRes");
  //     if ($trigger !== event.target && !$trigger.has(event.target).length) {
  //       $("#cuadroResul").slideUp("fast");
  //     }
  // });

  // $("#busquedaMapa").keyup(function () {
  //   var texto = $(this).val();
  //   $("#cuadroResul").toggle();

  //   if (texto == '') {} 
  //   else {
  //     $.ajax({
  //       type: "GET",
  //       url: "../Mapa/Subregion/busquedaSVG",
  //       data: {
  //         palabra: texto
  //       },
  //       cache: false,
  //       success: function (html) {
  //         $("#cuadroResul").html(html).show();
  //       }
  //     });
  //   }
  //   return false;
  // });
});

function InicMapaAntioquia () {
	var ids = new Array();
  
	for (var i = 0; i < svgSubregiones.length; i++) {
    if ((svgSubregiones[i]).id < 11 && (svgSubregiones[i]).id > 1) {
      var color;
      var pathTah;
      if (subSelec == (svgSubregiones[i]).id.split("-")[0]) {
        var pathTag = $(svgSubregiones[i]).find("path");
        $(pathTag).attr("style", "fill: #2665e2!important");
        $(pathTag).attr("stroke", "black");      
        $(pathTag).attr("stroke-width", "1.2");
      }
      $(svgSubregiones[i]).hover(function(){
        pathTag2 = $(this).find("path");
        color    = $(pathTag2).css("fill");
        $(pathTag2).attr("style", "fill: #2665e2!important;transition: fill 0.2s");
        $(pathTag2).attr("stroke", "black");      
        $(pathTag2).attr("stroke-width", "1.2");
        for (var a = 0; a < subregionesDatos.length; a++) {
          if (subregionesDatos[a].id == this.id.split("-")[0]) {
            var id = a;
            if (false) {
              $('body').append("<div id='hoveringTooltip' class='panel panel-info' style='position:absolute;'><div id='municipio' class='panel-heading' style='padding:5px;color:black!important;background:#FFB300!important'></div><div id='datos' class='panel-body' style='padding:5px;background:#f4f4f4'></div></div>");
              $('#municipio').html(subregionesDatos[a].nombre);
              $('#datos').html("Población: " + subregionesDatos[a].poblacion + "<br> Votos Partido: " + subregionesDatos[a].votospartido + "<br> Votos Candidato " + subregionesDatos[a].votoscandidato);
            }
            else {
              $('body').append("<div id='hoveringTooltip' class='panel panel-info' style='position:absolute;background:transparent;border:transparent'><div id='municipio' class='panel-heading' style='padding:5px;color:black!important;background:#FFB300!important;margin-top:75px;margin-left:40px;border:black'></div></div>");
              $('#municipio').html(subregionesDatos[a].nombre);
            }
          }
        }
      }, function(){
        $('#hoveringTooltip').remove();
        $(pathTag2).attr("style", "fill: "+color);
        $(pathTag2).attr("stroke-width", "0.5102362");
      }).mousemove(function(e) {
        var posX = e.pageX + posSvg.left - 30;
        var posY = e.pageY + posSvg.top + 15;
        $('#hoveringTooltip').css({ top: posY, left: posX});
      });
  
      $(svgSubregiones[i]).click(function() {
        window.location.href = "../Mapa/Subregion/" + this.id;
      })
    }
	}
}

// function conseguirFilasElectorales (page, rows) {
//   $.ajax({
//     type: "GET",
//     url: "../Mapa/Ant/conseguir",
//     data: {
//       idwhatevs: munSelec,
//       ejecutar: 'filasElectorales',
//       rows: rows,
//       page: page
//     },
//     cache: false,
//     success: function (data) {
//       $("#tablaVFilasElectorales").html($(data).filter("#tablaSFilasElectorales").html());
//       $("#pagVFilasElectorales").html($(data).filter("#pagSFilasElectorales").html());
//       $("#ModalPoblacionWrapV").html($(data).filter("#ModalPoblacionWrapS").html());

//       $("#ModalPoblacion").on("show.bs.modal", function (event) {
//         var button    = $(event.relatedTarget);
//         var id        = button.data('id');
//         var poblacion = button.data('poblacion');
    
//         var modal = $(this);
//         modal.find('.modal-body #idInput').val(id);
//         modal.find('.modal-body #poblacionInput').val(poblacion);
//       });
//     }
//   });
// }

function conseguirLideres (page, rows, busqueda = 0) {
  $.ajax({
    type: "GET",
    url: "../Ant/conseguir",
    data: {
      idwhatevs: subSelec,
      ejecutar: 'lideresSubs',
      rows: rows,
      page: page,
      busq: busqueda
    },
    cache: false,
    success: function (data) {
      if(!busqueda) {
        $("#tablaVLideres").html($(data).filter("#tablaSLideres").html());
        $("#tablaSLideresTabla").attr("id", "tablaVLideresTabla");
        $("#pagVLideres").html($(data).filter("#pagSLideres").html());
        busquedaLideres();
      } else {
        $("#tablaVLideresTabla").html($($(data).filter("#tablaSLideres").html()).filter("#tablaSLideresTabla").html());
        $("#pagVLideres").html($(data).filter("#pagSLideres").html());
      }
    }
  });
}

function busquedaLideres () {
  $("#busquedaLideres").keyup(function () {
    var busqueda = $(this).val();
    if (busqueda) {
      conseguirLideres(1, 5, busqueda)
    } else {
      conseguirLideres(1, 5)
    }
  });
}

// function conseguirResumen (idcorp, anio) {
//   $.ajax({
//     type: "GET",
//     url: "../Mapa/Ant/resumen",
//     data: {
//       idcorp: idcorp,
//       anio: anio
//     },
//     cache: false,
//     success: function (data) {
//       $("#tablaVResumen").html($(data).filter("#tablaSResumen").html());
//     }
//   });
// }

// function obtCompromisos (aElement) {
//   console.log(aElement);
//   if ($("#cellCompro").length) {
//     elimCompromisos(document.getElementsByClassName("liderComActivo")[0]);
//   }
  
//   var rowIndex = aElement.parentNode.parentNode.parentNode.rowIndex;
//   var table    = aElement.parentNode.parentNode.parentNode.parentNode.parentNode;
//   var row      = table.insertRow(rowIndex + 1);
//   var cell     = row.insertCell(0);
  
//   aElementComproId = aElement.id;
  
//   conseguirCompromisos(1, 5);
  
//   $(aElement).addClass("liderComActivo");
  
//   $(cell).attr("colspan", 9);
//   $(cell).attr("style", "text-align: center");
//   $(cell).attr("id", "cellCompro");
//   $(cell).html("<div id='tablaVCompromisos' class='row' style='margin-left: 10px; margin-right: 10px; margin-top: 10px'></div><div id='pagVCompromisos' class='fixed-table-pagination row' style='margin-right: 10px; margin-left: 10px; margin-bottom: 10px'></div>");
//   $(cell).hide().show(200);
  
//   var icon = aElement.getElementsByTagNameNS("http://www.w3.org/1999/xhtml","i");
//   $(icon).attr("class", "fa fa-minus-circle");
//   $(aElement).attr("onclick", "elimCompromisos(this)")
  
// }

// function conseguirCompromisos (page, rows) {
//   $.ajax({
//     type: 'GET',
//     url: '../Mapa/Ant/conseguir',
//     data: {
//       idwhatevs: aElementComproId,
//       ejecutar: 'compromisos',
//       page: page,
//       rows: rows
//     },
//     dataType: 'html',
//     async: true,
//     cache: false,
//     success: function(data) {
//       $("#tablaVCompromisos").html($(data).filter("#tablaSCompromisos").html());
//       $("#pagVCompromisos").html($(data).filter("#pagSCompromisos").html());
//     }, 
//     error: function(xhr) {
//       alert("Error cargando compromisos");
//     }
//   })
// }

// function elimCompromisos (aElement) {
//   $(aElement).removeClass("liderComActivo");

//   var rowIndex = aElement.parentNode.parentNode.parentNode.rowIndex;
//   var table    = aElement.parentNode.parentNode.parentNode.parentNode.parentNode;
//   var row      = table.deleteRow(rowIndex + 1);

//   var icon = aElement.getElementsByTagNameNS("http://www.w3.org/1999/xhtml","i");
//   $(icon).attr("class", "fa fa-plus-circle");
//   $(aElement).attr("onclick", "obtCompromisos(this)");
// }