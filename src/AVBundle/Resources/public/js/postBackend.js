

 $(function () {
    $("#PublicacionesContainer").animate("height","100%").fadeIn();
     $('[data-toggle="tooltip"]').tooltip();
});


 var ruta="{{ app.request.server.get('DOCUMENT_ROOT')  }}"+"{{ asset('bundles/av/upload.php') }}"+"/../../../../AulaVirt/web/bundles/av/upload.php";
 //alert(ruta);
   var socket = io.connect('http://localhost:3000');
          datosUsuario={"fotoPerfil": GlobalFotoPerfilU, 'id_usuario':GlobalIdUsuario, 'id_grupo':GlobalIdGrupo, "idTipousuario": GlobalTipoUsuario}

          socket.emit('conectar',datosUsuario);
          socket.on('conectado',function(datos){
            // console.log("Acabo de conectarme con los siguientes datos: ");
            // console.log(datos);

          });
          socket.on('resultadoAccion',function(datos){
            // console.log(datos.mensaje);
        });
  
        CantPostMostrados= 5;

        
        Handlebars.registerHelper('ifIgual',function(v1,v2,options){
            if(v1 == v2){
              return options.fn(this);
            }
            return options.inverse(this);

        });
           Handlebars.registerHelper('ifDif',function(v1,v2,options){
            if(v1 != v2){
              return options.fn(this);
            }
            return options.inverse(this);

        });


 var Usuario=Backbone.Model.extend({
            defaults:{
              "id":null,
              "login":"",
              "nombre":"",
              "apellido":"",
              "email":"",
              "tlf":"",
              "iddepartamento":"",
              "idTipousuario":"",
            },
            idAttribute:"id",
            urlRoot:"http://localhost:3000/usuarios",
            save: function (attributes, options) {
              options       = options || {};
              attributes    = attributes || {};
             
              Backbone.emulateJSON = true;
              this.set(attributes);
             
              options.data  = this.toJSON();
             
              return Backbone.Model.prototype.save.call(this, attributes, options);
            },
        });
        //Grupo de usuarios
         var GrupoUsuario= Backbone.Collection.extend({
             model:Usuario,
              url:"http://localhost:3000/GrupoUsuario",
          });
        //Modelo de la publicacion (Un Post)
          var Publicacion= Backbone.Model.extend({
            defaults:{
              "usuario":{"id_usuario":datosUsuario.id_usuario, "login":''},
              "id_grupo":datosUsuario.id_grupo,
              "titulo":'',
              "contenido":'',
              "likes":0,
              "likeUsuarioLogeado": false,
              "calidad":'Sin Calificar',
              "recursos":'',
              "fecha":'',
              "hora":"",
              // "usuarios_likes":"",
            },
            // initialize:function(){
            //   this.set("usuarios_likes",new GrupoUsuario);
            // },
            idAttribute: 'id',
            urlRoot:"http://localhost:3000/publicaciones",
            save: function (attributes, options) {
              options       = options || {};
              attributes    = attributes || {};
              
              Backbone.emulateJSON = true;
              this.set(attributes);
             
              options.data  = this.toJSON();
             
              return Backbone.Model.prototype.save.call(this, attributes, options);

            },
            destroy: function (attributes, options) {
              options       = options || {};
              attributes    = attributes || {};
             
              Backbone.emulateJSON = true;
              this.set(attributes);
             
              options.data  = this.toJSON();
             
              return Backbone.Model.prototype.destroy.call(this, attributes, options);
            },
            obtenerUsuariosLikes: function(){

                
            }


          });
        // Creamos una coleccion de publicaciones (de modelo Publicacion arriba definido):
        var Publicaciones= Backbone.Collection.extend({
             model:Publicacion,
               url:"http://localhost:3000/publicacionesGrupo",

          });

  /*===================================== Vistas de los modelos ===============================)*/
          //Creamos la vista del elemento (Un post)
        var PostVista = Backbone.View.extend({
              // el: '#target',
               tagName: 'li',
            // className:"publicacion",
            initialize:function(){
                  this.listenTo(this.model, "change",  this.render);

                  this.render();
                   var caracteresAMostrar = 300;
                   var elCONt=$(this.el).find(".contenido");
                  var contenido =  elCONt.html();
                   
                  if (contenido.length > caracteresAMostrar) {
                  
                    var todo = contenido;
                    var resumen = contenido.substr(0, caracteresAMostrar)+"....";
                   
                   
                      elCONt.html(resumen); 

                      var btleer=$(this.el).find(".leerTbtn");
                      btleer.fadeToggle();  

                       btleer.click(function() {
                        if(btleer.text()=="Seguir leyendo"){
                          btleer.text("Menos");
                            elCONt.html(todo); 
                        }else{
                          btleer.text("Seguir leyendo");
                        
                            elCONt.html(resumen);
                        }
                        });
                  }


            },
            events: {
                "click .like_btn ": "darLike",
                "click .eliminar_post": "eliminarPost",
                "click .editar_post":"editarPost",
                "click .guardarCambios": "guardarCambios",
                "click .cancelarCambios": "cancelarCambios",
                "click .cal_op": "cambiarCalidadPost",
                "click .leerTbtn": "mostrarTodoPost",
                // "click .verUsuariosLikes":"MostrarUsuariosLikes",
            },
            render: function () {

              var fuente=$('#plantillaPost').html();
              var template = Handlebars.compile(fuente);
              var rendered = template({infopost: this.model.toJSON(),datosUsuario: datosUsuario,foto: datosUsuario.fotoPerfil});
              

              
              $(this.el).fadeIn("slow");
              $(this.el).html(rendered);
                $(this.el).find(".contenido").html(this.model.get("contenido"));
                $(this.el).find(".titulo").html(this.model.get("titulo"));

                var botonlike=$(this.el).find(".like_btn");
          if(this.model.get("likeUsuarioLogeado")){

            botonlike.css("color","blue");
            botonlike.append("Te gusta");
            // console.log(this.model.get("usuario")+" le gusta "+this.model.get("id") )

          }else{
             botonlike.css("color","black");
            botonlike.append("Me gusta");

          }

                // $('[data-toggle="tooltip"]').tooltip();
                // return this;
            },
            darLike:function(){
               // alert("dio click en publicacion "+this.model.get("id"));
                 if(!this.model.get("likeUsuarioLogeado") ){
                      var likeAnt= parseInt(this.model.get("likes"));
                      var nuevoLike=likeAnt+1;
                      this.model.set("likeUsuarioLogeado",true);
                      this.model.set("likes",nuevoLike);

                        this.model.save(null,{ 

                          success:function(datos){

                            socket.emit("likePost", {id_usuario:datosUsuario.id_usuario, id:datos.id});
                          }

                        });
                 }      
             },
             eliminarPost: function(){
              var x=this.model;
                var datosModal={
                        "IdModal": "modal_"+"j",
                        "ModalHeader": "Eliminar Publicación",
                        "ModalBody": "Seguro que desea eliminar",
                        "ModalFooter": "b",
                        AccionModal: function(){
                          x.destroy({
                            success:function(datos){
                             var as='<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4>  <i class="icon fa fa-check"></i> Eliminado!</h4> Tu post fue eliminado exitosamente</div>';
                                $("#"+"modal_"+"j").find(".modal-body").html(as);
                                 $("#"+"modal_"+"j").find(".modal-footer").empty();
                              socket.emit("borrarPost",{id:datos.id})
                              // $('#modalj').modal('toggle');


                            }
                          });
                        }
                 }
                 CrearModal(datosModal);
                
             },
             editarPost: function(){
                $(this.el).find(".titulo").attr("contenteditable",true).css("border-color", "#01AFDE");
                $(this.el).find(".contenido").attr("contenteditable",true).css("border-color", "#01AFDE");

                if($('.guardarCambios').length ==0){
                  $(this.el).find(".pie").prepend(" <center><a class=' guardarCambios btn btn-primary '>Guardar</a><a class=' cancelarCambios btn btn-primary '>Cancelar</a></center>")
                }


             },
             cancelarCambios:function(){
               $(this.el).find(".titulo").attr("contenteditable",false);
                $(this.el).find(".contenido").attr("contenteditable",false);
                if($('.guardarCambios').length >0){
                  $('.cancelarCambios').remove();
                 $('.guardarCambios').remove();
                 this.render();
                }
             }
             ,
             guardarCambios: function(){
                 

                var nuevoTitulo= $(this.el).find(".titulo").html();
                var nuevoCont= $(this.el).find(".contenido").html();
                 this.model.set("titulo", nuevoTitulo);
                 this.model.set("contenido", nuevoCont);

                this.model.save(null,{ 

                  success:function(datos){

                    socket.emit("editarPost", datos);
                  }

                });
                

             },
             cambiarCalidadPost:function(evento){
              //mosca cambiar si se cambia el dropdwon menu de bootstrap
              // console.log(evento.target.id);
                var calidad= evento.target.id;
                console.log(calidad);
                this.model.set("calidad",calidad);
                this.model.save(null,{ 

                  success:function(datos){

                    //socket.emit("likePost", datos);
                  }

                });
                
             },
             mostrarTodoPost: function(){

                var a=  $(this.el).find(".contenidoWr");
                a.fadeOut("fast").css("height","100%").fadeIn("fast")
             }
             // MostrarUsuariosLikes: function(){
           
             //    this.model.get("usuarios_likes").fetch({
             //         url:this.model.get("usuarios_likes").url+"/Publicacion_likes/"+this.model.get("id"),
             //    });
             //             console.log("Luego de fecthera los usuarios likes");
             //             console.log(this.model);
             // }

        });
      //Creamos una vista para un nuevo post
       var FormularioNuevoPost = Backbone.View.extend({
              el: '#target',
              tagName: 'div',
            initialize:function(){
                  // this.listenTo(this.model, "change", this.render);
                  this.render();
            },
            events: {
               "click .enviarPost ": "enviarPost",
            },
            render: function () {
              var fuente=$('#plantillaNuevoPost').html();
              var template = Handlebars.compile(fuente);
              var rendered = template({rutaSubir: "/opt/lampp/htdocs/AulaVirt/web/bundles/av/upload.php" });
              $(this.el).html(rendered);
                // return this;
            },
    enviarPost:function(){
              if($("#tituloNuevoPost").val()!="" && $("#contenidoNuevoPost").html()!=""){
                
              var nuevop=new Publicacion();
              // alert("dio click en publicacion "+this.model.get("id"));
              
              nuevop.set("titulo",$("#tituloNuevoPost").val());
              nuevop.set("contenido",$("#contenidoNuevoPost").html());
              nuevop.save(null,{ 
                success:function(datos){
                  $("#tituloNuevoPost").val("");
                  $("#contenidoNuevoPost").html("");

                  socket.emit("nuevoPost", nuevop.toJSON());
                 
                }
              });
              }
             }
        });

        //Vista de el conjunto de publicaciones
       var publicacionesVista = Backbone.View.extend({
            el: '#target2 ',
            initialize:function(){
                this.render();
                 this.listenTo(this.model, "change", this.render);
                this.listenTo(this.model, "add", this.render);   
                this.listenTo(this.model, "destroy",  this.render);
                
            },
            events: { 
                "click #verMasPosts": "verMasPosts",
            },
            render: function () {
                var coleccionDePosts=this.model.models;
                var hoy=new Date('d/m/Y');
                //Limpiamos la vista
                this.limpiarVista();
                   var Reloj='</ul><li><i class="fa fa-clock-o bg-gray"></i></li>';
               this.model.each(function(o,indice) {
                if(this.model.indexOf(o)==0 ){

                  var EtiquetaFecha='<li class="time-label"> <span class="bg-aqua-active" > '+o.get("fecha") +'</span></li>';
                            $(this.el).find("#PublicacionesContainer").append(EtiquetaFecha);
                        
                }else{
                  if(o.get("fecha")< this.model.at(indice-1).get("fecha")){

                    var EtiquetaFecha='<li class="time-label"> <span class="bg-aqua-active" > '+o.get("fecha") +'</span></li>';
                       $(this.el).find("#PublicacionesContainer").append(EtiquetaFecha);
                   // console.log(this.model.at(indice-1).get("fecha"));
                    
                  }
                }
              //A cada modelo del la coleccion le asignamos una vista
                  var vistaPost=new PostVista ({model:o});
                  $(this.el).find("#PublicacionesContainer").append(vistaPost.el);
                }, this); 

               $(this.el).find("#PublicacionesContainer").append(Reloj);

             $(this.el).find("#PublicacionesContainer").append(Reloj);
                  if(coleccionDePosts.length==0){
                    var nohay='<div  style="margin-left:100px" class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4>  <i class="icon fa fa-warning"></i> No hay posts</h4> Sé el primero en publicar !</div>';
                    $(this.el).find("#PublicacionesContainer").html(nohay);
                  }
            
                return this;
            },
            limpiarVista: function(){
              $(this.el).find("#PublicacionesContainer").empty();
            },
            verMasPosts: function(){
                CantPostMostrados+=CantPostMostrados;
               this.model.fetch({
                url:this.model.url+"/"+datosUsuario.id_grupo+"/"+CantPostMostrados+"/"+datosUsuario.id_usuario,
               
               });
            }
        });
         //Creamos una instancia de Publicaciones (Conjunto de posts);
             var publicacionesDelGrupo=new Publicaciones();
             //Traemos del servidor, las publicaciones del grupo
             publicacionesDelGrupo.fetch({
              url:publicacionesDelGrupo.url+"/"+datosUsuario.id_grupo+"/"+CantPostMostrados+"/"+datosUsuario.id_usuario,

             });


             // console.log(publicacionesDelGrupo);
             // Creamos la vista de Publicaciones
            var pvista=new publicacionesVista({model: publicacionesDelGrupo});

            //Creamos la vista del formulario de nuevo post
             var FNP= new FormularioNuevoPost();   
                socket.on("cambioEnPost", function(datos){
                  
                     publicacionesDelGrupo.get(datos.id).set(datos);
                   // console.log(publicacionesDelGrupo.get(datos.id)) ;
  
                });
               socket.on("nuevoPostAjeno", function(datos){
                       publicacionesDelGrupo.unshift(datos);
                });
                socket.on("eliminarPost", function(datos){
                  // alert("algo se elimino");
                   publicacionesDelGrupo.get(datos.id).destroy();
                });

               // console.log(publicacionesDelGrupo)

        // $(document).ready(function() {
           
        // });
               