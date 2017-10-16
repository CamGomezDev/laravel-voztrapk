var svg;
var posSvg;
var svgDoc;
var svgMunicipios;
var munColor;
var aElementComproId;



function InicMapaAntioquia () {
	var ids = new Array();
  
	for (var i = 0; i < svgMunicipios.length; i++) {
    if (munSelec == (svgMunicipios[i]).id.split("-")[0]) {
      $(svgMunicipios[i]).attr("style", "fill: #2665e2!important");
      $(svgMunicipios[i]).attr("stroke", "black");      
      $(svgMunicipios[i]).attr("stroke-width", "1.2");
    }
		$(svgMunicipios[i]).hover(function(){
			for (var a = 0; a < municipiosDatos.length; a++) {
        if (municipiosDatos[a].id_municipio == this.id.split("-")[0]) {
					var id = a;
          $('body').append("<div id='hoveringTooltip' class='panel panel-info' style='position:absolute;'><div id='municipio' class='panel-heading' style='padding:5px;color:black!important;background:#FFB300!important'></div><div id='datos' class='panel-body' style='padding:5px;background:#f4f4f4'></div></div>");
          $('#municipio').html(municipiosDatos[a].municipio);
          $('#datos').html("Votos Partido: " + municipiosDatos[a].votospartido + "<br> Votos Candidato " + municipiosDatos[a].votoscandidato);
				}
			}
		}, function(){
		  $('#hoveringTooltip').remove();
		}).mousemove(function(e) {
      var posX = e.pageX + posSvg.left - 100;
			var posY = e.pageY + posSvg.top - 98;
			$('#hoveringTooltip').css({ top: posY, left: posX});
		});

		$(svgMunicipios[i]).click(function() {
			window.location.href = "../Mapa?m=" + this.id.split("-")[0];
		})
	}
}

function resaltarMun (idmun) {
  for (var i = 0; i < svgMunicipios.length; i++) {
    if (idmun == svgMunicipios[i].id.split("-")[0]) {
      munColor = $(svgMunicipios[i]).css("fill");
      $(svgMunicipios[i]).attr("style", "fill:#2665e2!important");
      $(svgMunicipios[i]).attr("stroke", "black");      
      $(svgMunicipios[i]).attr("stroke-width", "1.2");
    }
  }
}

function atenuarMun (idmun) {
  for (var i = 0; i < svgMunicipios.length; i++) {
    if (idmun == svgMunicipios[i].id.split("-")[0]) {
      $(svgMunicipios[i]).attr("style", "fill:"+munColor);
      $(svgMunicipios[i]).attr("stroke", "black");
      $(svgMunicipios[i]).attr("stroke-width", "0.5102362");
    }
  }
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
    url: '../Mapa/conseguir',
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


$(window).on("load", function () {
  svg    = document.getElementById("ant_svg");
  posSvg = $(svg).position();
  svgDoc = svg.contentDocument;
  svgMunicipios = svgDoc.rootElement.getElementsByTagNameNS("http://www.w3.org/2000/svg", "path");
  if (munSelec) {
    conseguirFilasElectorales(1, 5);
    conseguirLideres(1, 5)
  }

  // $('#irabajo').click(function () {
  //   $('html,body').animate({
  //     scrollTop: $("#irarriba").offset().top},
  //   'slow');
  // });

  // $('#irarriba').click(function () {
  //   $('html,body').animate({
  //     scrollTop: $("body").offset().top},
  //   'slow');
  // });

  // if (munSelec) {
  //   $('html,body').animate({
  //     scrollTop: $("#inicioTablas").offset().top},
  //   'slow');
  // }

  if (typeof document.getElementById("ant_svg") !== 'undefined') {
    InicMapaAntioquia();
  }
  
  // $(document).click( function(event){
  //     var $trigger = $("#divBusqRes");
  //     if ($trigger !== event.target && !$trigger.has(event.target).length) {
  //       $("#cuadroResul").slideUp("fast");
  //     }
  // });
    
  // $('#myCarousel').bind('slide.bs.carousel', function (e) {
  // 	if (typeof document.getElementById("ant_svg") !== 'undefined') {
  // 		InicMapaAntioquia();
  // 	}
  // })
});

function conseguirFilasElectorales (page, rows) {
  $.ajax({
    type: "GET",
    url: "../Mapa/conseguir",
    data: {
      idwhatevs: munSelec,
      ejecutar: 'filasElectorales',
      rows: rows,
      page: page
    },
    cache: false,
    success: function (data) {
      $("#tablaVFilasElectorales").html($(data).filter("#tablaSFilasElectorales").html());
      $("#pagVFilasElectorales").html($(data).filter("#pagSFilasElectorales").html());
    }
  });
}

function conseguirLideres (page, rows) {
  $.ajax({
    type: "GET",
    url: "../Mapa/conseguir",
    data: {
      idwhatevs: munSelec,
      ejecutar: 'lideres',
      rows: rows,
      page: page
    },
    cache: false,
    success: function (data) {
      $("#tablaVLideres").html($(data).filter("#tablaSLideres").html());
      $("#pagVLideres").html($(data).filter("#pagSLideres").html());
    }
  });
}