var svg;
var posSvg;
var svgDoc;
var svgComunas;
var comColor;
var aElementComproId;
var cuadder = 0;
var cuadsur = 0;

$(window).on("load", function () {
  svg    = document.getElementById("med_svg");
  posSvg = $(svg).position();
  svgDoc = svg.contentDocument;
  svgComunas = svgDoc.rootElement.getElementsByTagNameNS("http://www.w3.org/2000/svg", "path");
  conseguirResumen();
  if (comSelec) {
    conseguirFilasElectorales(1, 5);
    conseguirLideres(1, 5)
    cuadder = 70;
    cuadsur = 130;
  }

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

  if (comSelec) {
    $('html,body').animate({
      scrollTop: $("#inicioTablas").offset().top},
    'slow');
  }

  if (typeof document.getElementById("med_svg") !== 'undefined') {
    InicMapaMedellin();
  }
  
  $(document).click( function(event){
      var $trigger = $("#divBusqRes");
      if ($trigger !== event.target && !$trigger.has(event.target).length) {
        $("#cuadroResul").slideUp("fast");
      }
  });
    
  // $('#myCarousel').bind('slide.bs.carousel', function (e) {
  // 	if (typeof document.getElementById("ant_svg") !== 'undefined') {
  // 		InicMapaAntioquia();
  // 	}
  // })

  $("#busquedaMapa").keyup(function () {
    var texto = $(this).val();
    $("#cuadroResul").toggle();

    if (texto == '') {} 
    else {
      $.ajax({
        type: "GET",
        url: "../Mapa/Med/busquedaSVG",
        data: {
          palabra: texto
        },
        cache: false,
        success: function (html) {
          $("#cuadroResul").html(html).show();
        }
      });
    }
    return false;
  });
});

function InicMapaMedellin () {
	var ids = new Array();
  
	for (var i = 0; i < svgComunas.length; i++) {
    if (comSelec == (svgComunas[i]).id) {
      $(svgComunas[i]).attr("style", "fill: #2665e2!important");
      $(svgComunas[i]).attr("stroke", "black");      
      $(svgComunas[i]).attr("stroke-width", "1.2");
    }
		$(svgComunas[i]).hover(function(){
			for (var a = 0; a < comunasDatos.length; a++) {
        if (comunasDatos[a].id == this.id.split("-")[0]) {
          var id = a;
          if (comunasDatos[a].panel) {
            $('body').append("<div id='hoveringTooltip' class='panel panel-info' style='position:absolute;'><div id='municipio' class='panel-heading' style='padding:5px;color:black!important;background:#FFB300!important'></div><div id='datos' class='panel-body' style='padding:5px;background:#f4f4f4'></div></div>");
            $('#municipio').html(comunasDatos[a].nombre);
            $('#datos').html("Barrios: " + comunasDatos[a].barrios + "<br> Votos Partido: " + comunasDatos[a].votospartido + "<br> Votos Candidato " + comunasDatos[a].votoscandidato);
          }
          else {
            $('body').append("<div id='hoveringTooltip' class='panel panel-info' style='position:absolute;background:transparent;border:transparent'><div id='municipio' class='panel-heading' style='padding:5px;color:black!important;background:#FFB300!important;margin-top:75px;margin-left:40px;border:black'></div></div>");
            $('#municipio').html(comunasDatos[a].nombre);
          }
				}
			}
		}, function(){
		  $('#hoveringTooltip').remove();
		}).mousemove(function(e) {
      var posX = e.pageX + posSvg.left - 150 + cuadder;
			var posY = e.pageY + posSvg.top - 115 + cuadsur;
			$('#hoveringTooltip').css({ top: posY, left: posX});
		});

		$(svgComunas[i]).click(function() {
			window.location.href = "../Mapa/Med?c=" + this.id;
		})
	}
}

function resaltarMun (idcom) {
  for (var i = 0; i < svgComunas.length; i++) {
    if (idcom == svgComunas[i].id.split("-")[0]) {
      munColor = $(svgComunas[i]).css("fill");
      $(svgComunas[i]).attr("style", "fill:#2665e2!important");
      $(svgComunas[i]).attr("stroke", "black");      
      $(svgComunas[i]).attr("stroke-width", "1.2");
    }
  }
}

function atenuarMun (idcom) {
  for (var i = 0; i < svgComunas.length; i++) {
    if (idcom == svgComunas[i].id.split("-")[0]) {
      $(svgComunas[i]).attr("style", "fill:"+munColor);
      $(svgComunas[i]).attr("stroke", "black");
      $(svgComunas[i]).attr("stroke-width", "0.5102362");
    }
  }
}

function conseguirFilasElectorales (page, rows) {
  $.ajax({
    type: "GET",
    url: "../Mapa/Med/conseguir",
    data: {
      idwhatevs: comSelec,
      ejecutar: 'filasElectorales',
      rows: rows,
      page: page
    },
    cache: false,
    success: function (data) {
      $("#tablaVFilasElectorales").html($(data).filter("#tablaSFilasElectorales").html());
      $("#pagVFilasElectorales").html($(data).filter("#pagSFilasElectorales").html());
      $("#ModalPoblacionWrapV").html($(data).filter("#ModalPoblacionWrapS").html());

      $("#ModalPoblacion").on("show.bs.modal", function (event) {
        var button    = $(event.relatedTarget);
        var id        = button.data('id');
        var poblacion = button.data('poblacion');
    
        var modal = $(this);
        modal.find('.modal-body #idInput').val(id);
        modal.find('.modal-body #poblacionInput').val(poblacion);
      });
    }
  });
}

function conseguirLideres (page, rows, busqueda = 0) {
  $.ajax({
    type: "GET",
    url: "../Mapa/Med/conseguir",
    data: {
      idwhatevs: comSelec,
      ejecutar: 'lideres',
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

function conseguirResumen (idcorp, anio) {
  $.ajax({
    type: "GET",
    url: "../Mapa/Med/resumen",
    data: {
      idcorp: idcorp,
      anio: anio
    },
    cache: false,
    success: function (data) {
      $("#tablaVResumen").html($(data).filter("#tablaSResumen").html());
    }
  });
}

function obtCompromisos (aElement) {
  console.log(aElement);
  if ($("#cellCompro").length) {
    elimCompromisos(document.getElementsByClassName("liderComActivo")[0]);
  }
  
  var rowIndex = aElement.parentNode.parentNode.parentNode.rowIndex;
  var table    = aElement.parentNode.parentNode.parentNode.parentNode.parentNode;
  var row      = table.insertRow(rowIndex + 1);
  var cell     = row.insertCell(0);
  
  aElementComproId = aElement.id;
  
  conseguirCompromisos(1, 5);
  
  $(aElement).addClass("liderComActivo");
  
  $(cell).attr("colspan", 9);
  $(cell).attr("style", "text-align: center");
  $(cell).attr("id", "cellCompro");
  $(cell).html("<div id='tablaVCompromisos' class='row' style='margin-left: 10px; margin-right: 10px; margin-top: 10px'></div><div id='pagVCompromisos' class='fixed-table-pagination row' style='margin-right: 10px; margin-left: 10px; margin-bottom: 10px'></div>");
  $(cell).hide().show(200);
  
  var icon = aElement.getElementsByTagNameNS("http://www.w3.org/1999/xhtml","i");
  $(icon).attr("class", "fa fa-minus-circle");
  $(aElement).attr("onclick", "elimCompromisos(this)")
  
}

function conseguirCompromisos (page, rows) {
  $.ajax({
    type: 'GET',
    url: '../Mapa/Med/conseguir',
    data: {
      idwhatevs: aElementComproId,
      ejecutar: 'compromisos',
      page: page,
      rows: rows
    },
    dataType: 'html',
    async: true,
    cache: false,
    success: function(data) {
      $("#tablaVCompromisos").html($(data).filter("#tablaSCompromisos").html());
      $("#pagVCompromisos").html($(data).filter("#pagSCompromisos").html());
    }, 
    error: function(xhr) {
      alert("Error cargando compromisos");
    }
  })
}

function elimCompromisos (aElement) {
  $(aElement).removeClass("liderComActivo");

  var rowIndex = aElement.parentNode.parentNode.parentNode.rowIndex;
  var table    = aElement.parentNode.parentNode.parentNode.parentNode.parentNode;
  var row      = table.deleteRow(rowIndex + 1);

  var icon = aElement.getElementsByTagNameNS("http://www.w3.org/1999/xhtml","i");
  $(icon).attr("class", "fa fa-plus-circle");
  $(aElement).attr("onclick", "obtCompromisos(this)");
}