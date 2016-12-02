function mostrarRsUsuarios(rs,ev){
	
	var wr=ev.parents(".panel-body").find(".rsContent");
	wr.empty();
	wr.append('<tr><th style="width: 10px">Cédula</th><th>Nombre</th><th>Apellido</th></tr>');
	for (var i = 0; i < rs.length; i++) {
		
		wr.append(
			"<tr><td>"+ rs[i].Cedula +"</td><td>"+ rs[i].Nombre +"</td> <td>"+ rs[i].Apellido +"</td></tr>");
	};
	var ss=ev.parents(".panel-body").find(".rsBuscarEstudiante");
	if(rs.length==0){

		ss.html("<h3> No se encontró ningún resultado </h3>");
	}
	ss.show();
}
$(".buscarUsuarioBtn").livequery(function(){

	$(this).on('click', function (e) {
		
		e.preventDefault();
		var form= $(this).parents(".formUsuarioBusq");
		
		var busqueda={
			url: $(form).attr("action"),
			datos: $(form).serialize(), 
		}
		
		var rs= RecibirDatosAjax(busqueda); 

		mostrarRsUsuarios(rs,$(this));
	});

	
})
$(".FieldNombreEstBuscar").livequery(function(){
		// $(this).on('change', function () {
			

		// 		var form= $(this).parents(".formUsuarioBusq");
				
		// 		var busqueda={
		// 			url: $(form).attr("action"),
		// 			datos: $(form).serialize(), 
		// 		}
				
		// 		console.log( RecibirDatosAjax(busqueda) );
			
		// });
})
//variable global que almacena  los grupos del modelo actuales del prof.
var grupos= {};
//Selector Jquery del form nuevo grupo
var FormNuevoGrupoSelector;
//variable que se usa para inicializar los Input tags de agregar estudiantes de un grupo (nuevo grupo y los grupos del prof)
var EventosInputTagsGrupos={
	beforeItemAdd: function(event,grupo){

		var cedula=event.item;
		var resultado= buscarEstudianteEnGrupos(cedula); 
	    	if(resultado.respuesta>0){
	    		
	    		var IdEstudiante= resultado.respuesta;
	    		grupo.nuevosEstudiantes[cedula]= IdEstudiante;	
	    	}else{

	    		if(resultado.respuesta<0){
	    			//Es un profesor
	    			if(resultado.respuesta==-1){
	    				alert("No puede agregar un profesor al grupo");
	    			}else{
	    				alert("No es necesario que te agregues al grupo. El sistema te agregará una vez que crees el grupo.");
	    			}
	    		}else{
	    			 if(resultado.respuesta==0){
	    			 	alert("El usuario no existe")
	    			 }else{
	    				alert("Este estudiante ya pertenece a un grupo con la misma materia");	    			
	    			 }
	    		}
	    		event.cancel=true;
	    	}
	   	
	},
	beforeItemRemove: function(event, grupo){
		delete grupo.nuevosEstudiantes[event.item];
	}

}

//Busca un estudiante en el modelo y retorna el id (BD)
function buscarEstudianteEnGrupos(cedula){

	var url= GlobalBuscarGrupoEstudiantePath;
	url= url.replace("cedulainput", cedula);

	var datos={
		"url" : url,
		"datos": cedula
	}

   return RecibirDatosAjax(datos);
}
//Busca un estudiante dado un atributo
function buscarEstudiante(dato){

	return RecibirDatosAjax(dato);
}
//Busca un grupo en el modelo y retorna si esta disponible para uso (BD)
function buscarGrupo(nombre){

	var url= GlobalBuscarGrupoPath;
	url= url.replace("nombreGrupo", nombre );

	var datos={
		url: url,
		datos: null ,
	}

	return  RecibirDatosAjax(datos);
}
function RecibirDatosAjax(datos){
	var resultado="";
 	$.ajax({
		url: datos.url,
		type: 'get',
		async:false,
		data: datos.datos,
		success: function(data){
			resultado=data;
			
		},
		error: function(error){
			console.log(error)
		}

 	});

 	return resultado;	
}
var limit=false;
// .nombreGrupo es el campo nombre del grupo del formulario de crear grupo
$("body").on("change", ".nombreNgrupo", function(){

	var respuesta= buscarGrupo( $(this).val() )
	if(respuesta.length>0 && respuesta.length <=15){

		$("#materiaStatus").html("<i title='Este nombre esta disponible' class='fa fa-check'> </i>");
	}else{

		if(respuesta.disponible){
			limit=false;
			$("#materiaStatus").html("<i title='Este nombre esta disponible' class='fa fa-check'> </i>");
			
		}else{
			limit=true;
			$("#materiaStatus").html("<i title='Ya existe otro grupo con este nombre' class='glyphicon glyphicon-remove'> </i>");
				
		}
	}

})

$('.tagsinput').livequery( function() {

	$(this).tagsinput();
	//Se obtiene para que se usa ese tagsinput.
	var contexto= $(this).attr("data-contexto");

	// Se asignan los eventos add, remove depende del contexto..
	if(contexto=="grupo"){

		var idGrupo= $(this).parents(".infoEditarGrupo")
							.attr("id")
							.replace("w_","");
		
		$(this).on('beforeItemAdd', 
			function(event){ 
				EventosInputTagsGrupos
				.beforeItemAdd(event, grupos[idGrupo] ) 
			});

		$(this).on('beforeItemRemove', 
			function(event){ 
				EventosInputTagsGrupos
				.beforeItemRemove(event,  grupos[idGrupo] ) 
			});
	}


});

$(".datatableGrupo").livequery(function(){
		$(this).DataTable();
})
$("body").on('click', ".ElimEstBtn", function (e) {
	e.preventDefault();

	var idGE=$(this).parents("tr").attr("id");
	var formElim= "<div id='formE'>"+GlobalElimGrupoEstudianteForm+"</div>";
	formElim= formElim.replace("texto", idGE);

	$(this).parent().append(formElim);

	$("#formE").find("form").submit();


});
//grupoContainer es el contenedor donde estan las tablas de los grupos
$(".grupoContainer").livequery( function() {

	var idGrupo= $(this).attr("data-id-grupo");
	var nombreGrupo= $(this).attr("data-nombre-grupo");

	//Se asgina un hash en la variable global Grupos
	grupos[idGrupo]= { 
		"nuevosEstudiantes": {} 
	};
	
	//Se le coloca dinamicamente su formulario de editar
	var EditarWrapper= $(this)
					   .find(".EditarGrupoContainer .formularioEditarWrapper");
	
	var FormularioEditarHtml= GlobalEditarGrupoForm;
	FormularioEditarHtml=FormularioEditarHtml
						.replace("texto", idGrupo);
	
	EditarWrapper.html(FormularioEditarHtml);

	/*Se coloca el nombre del grupo en el campo del formulario
	 (ya que el prototipo no tiene el nombre) */
	$(this).find(".nombreGrupoField").val(nombreGrupo);
	

});
// .AgregarEstBtn es el boton para añadir mas estudiantes a un grupo existente
$("body").on('click', ".AgregarEstBtn", function () {
	
	var wrapper= $(this).attr("data-editgrupo-wrapper")
	$(wrapper).fadeToggle();
	
});

//Evento envio de formulario de editar de un grupo;
$("body").on('click', ".EditGrupoSubmit", function (e) {

	e.preventDefault();

	var formulario= $(this).parents("form");
	var wrapper= $(this).parents(".infoEditarGrupo");

	/*se inserta en el campo oculto del formulario de editar, los datos de los nuevos
	estudiantes almacenados en la variable global 'grupos'; */
	InsertarJsonNuevosEstudiantes(wrapper);


	//se envia el formulario
	$(formulario).submit();

});

//Inserta en el campo oculto del formulario (estField) el string de los datos 
//wrapper es el div que contiene el id del grupo y el campo oculto 
function InsertarJsonNuevosEstudiantes(wrapper){

	var idGrupo= wrapper.attr("id").replace("w_","");
	// busca el campo oculto real del form
	var campoTextoEsts= wrapper.find(".estField");

	// nuevos estudiantes que se agregaron del inputags
	var EstudiantesGrupo= grupos[idGrupo].nuevosEstudiantes;
	
	var StringJsonDatos= JSON.stringify(EstudiantesGrupo);

	campoTextoEsts.val(StringJsonDatos);
	
}
function EnviarFormAjax(form, acciones){	
	$.ajax({
		url: $(form).attr("action"),
		type: 'post',
		data:  $(form).serialize() ,
		success: function (data) {
			acciones.success();
		},
		error: function(error){
			console.log(error);
		}
	});
}


$(".crearGrupoBtn").on("click", function(e){
	e.preventDefault();
	var idForm="idg";
	var formularioNuevoGrupoHTML= GlobalNuevoGrupoForm;

	formularioNuevoGrupoHTML=formularioNuevoGrupoHTML
							 .replace('idg',idForm);

	grupos[idForm]= { "nuevosEstudiantes": {} };


	var datosModal={
          "IdModal": "modal_"+"j",
          "ModalHeader": "<h5><i class='fa fa-group' style='font-size:15px'> </i> <span>Nuevo grupo de estudiantes</span></h5>",
          "ModalBody": formularioNuevoGrupoHTML,
          "ModalFooter": " ",
          AccionModal: function(){
            
          }
	 }
	 CrearModal(datosModal);
	 FormNuevoGrupoSelector=$("#"+idForm);
		
});
$('body').on('click', ".next", function () {
	var current = $(this).data('currentBlock'),
	  next = $(this).data('nextBlock');
	  
	if (next > current)
	  if (false === FormNuevoGrupoSelector
	  				.parsley()
	  				.validate('block' + current))
	    return;
	if(!limit) {  
	 // data-submit indica si el boton es para envio del formulario
	  var ev= $(this).attr("data-submit");
	  if(ev){
	  	enviarFormularioNuevoGrupo(ev);
	  }

	    $('.block' + current)
	      .removeClass('show')
	      .addClass('hidden');

	    $('.block' + next)
	      .removeClass('hidden')
	      .addClass('show');
	
	}
});
$('body').on('click', ".prev", function () {
	var current = $(this).data('currentBlock'),
	  prev = parseInt(current)-1;
	  
	if (prev > 0){

	    $('.block' + current)
	      .removeClass('show')
	      .addClass('hidden');

	    $('.block' + prev)
	      .removeClass('hidden')
	      .addClass('show');
		
	}



});
function enviarFormularioNuevoGrupo(){

	var formulario= FormNuevoGrupoSelector;
	var wrapper= formulario.find(".infoEditarGrupo");

	/*se inserta en el campo oculto del formulario de nuevo grupo, los datos de los 
	estudiantes almacenados en la variable global 'grupos'; */
	InsertarJsonNuevosEstudiantes(wrapper);

	//Se envia el formulario
	var accionesAjax={
		success: function(){
			$(".third").html('<div class="callout callout-success">'+
								'<h4>¡El grupo se creó exitosamente!</h4>'+
								'<p>Cierra esta ventana y  recarga la página.</p>'+
							 +'</div>');
		},
		error: function(){
			$(".third").html('<div class="callout callout-danger">'+
								'<h4>¡El grupo no se pudo crear!</h4>'+
								'<p>Por favor ntentelo nuevamente.</p>'+
							  '</div>');
		}
	}
	EnviarFormAjax(formulario,accionesAjax);
}




