<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Prueba del servidor</title>
  <?php   $server='http://localhost:3000'; ?>
    <script src="<?php echo $server; ?>/socket.io/socket.io.js"> </script>
    <script src="js/jquery-1.11.1.min.js"> </script>
    <script src="js/underscore-min.js"> </script>
    <script src="js/backbone-min.js"> </script>
   <script src="js/handlebars-v3.0.3.js"> </script>

  <link rel="stylesheet" href="css/style.css">
    <script>

        var socket = io.connect('http://localhost:3000');
          datosUsuario={'id_usuario':4, 'id_grupo':10, "idTipousuario": 1}
          socket.emit('conectar',datosUsuario);
          socket.on('conectado',function(datos){
            // console.log("Acabo de conectarme con los siguientes datos: ");
            // console.log(datos);

          });
          socket.on('resultadoAccion',function(datos){
            // console.log(datos.mensaje);
        });
  
        CantPostMostrados= 5;

        
        Handlebars.registerHelper('igual',function(v1,v2,options){
            if(v1==v2){
              return options.fn(this);
            }
            return options.inverse(this);

        });

    </script>
</head>
<body style=" overflow: scroll;overflow-x: hidden; ">


 <script id="plantillaNuevoPost" type="text/x-handlebars-template">
<div id="nuevoPost" class="post-form" >
        <div class="wrapper-post-form">
          <div class="seccion-post">
            <div class="titulo">

              <input type="text" name="" id="tituloNuevoPost" class="form-control" placeholder="Titulo del post" value="" required="required" pattern="" title="">
            </div>

          </div>
          <div class="panel-body" style='text-align: left; padding-bottom: 0px; padding-top: 0px;'> 
            <button id="add_code" class="p_op btn btn-default btn-xs" style="background-color:#424439; color:#EFEFEF">codigo</button>
            <button id="add_sep"  class="p_op btn btn-default btn-xs" style="background-color:#424439; color:#EFEFEF">separador</button>
            <button id="add_link" class="p_op btn btn-default btn-xs" style="background-color:#424439; color:#EFEFEF">link</button>
            <button id="add_img" class=" p_op btn btn-default btn-xs" style="background-color:#424439; color:#EFEFEF">imagen</button>
          </div>
          <div class="seccion-post">

            <div contentEditable='true' id= "contenidoNuevoPost"  class="contenido_p"></div>             

          </div>
          <div class="seccion-post">

            <div class="recursos-post">

              <form id= "dragupload" action="upload.php" class="dropzone dz-clickable" onmouseover="$('.msj_drag').fadeOut();" style="min-height:124px"  method="post" enctype="multipart/form-data">

                <div class="msj_drag"> Haz click o arrastra los archivos </div>
              </form>


            </div>
          </div>
          <div class="footer-post-form">
            
            <button class=" enviarPost btn btn-success btn-lg"> Publicar </button>
            <!-- <button class="btn btn-primary btn-lg" onclick="preview_post()"> Vista previa </button> -->
          </div>


        </div>
      </div>
</script>

 <script id="plantillaPost" type="text/x-handlebars-template">
    <div  class="post" >
     <div class="row">
       <div class="post-heading">
        <div class="left-side-header-post">
         <div class="profile-photo" >
           <img src="foto.jpg" alt="">
         </div>
       </div>
       <div class="right-side-header-post">
         <ul class="post-info">
           <li>{{usuario.login}} ha publicado:</li>
           <li>Fecha: {{fecha}} </li>
           <li>Calidad de la publicación:
            <br>

            <div class="post-progress-bar" style="padding:0">
             <div class='progress-bar progress-bar-success' role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%">{{calidad}} 
              <span class="sr-only">60% Complete</span>
            </div>

          </div>
        </li>

      </ul>

      <div class="opcionesPost" style='position:absolute; top:2px; right:8px'> 

     
        <button class='editar_post btn btn-info btn-xs' ><span class='glyphicon glyphicon-pencil'> </span></button> 
     
      
        <button class='eliminar_post btn btn-danger btn-xs' ><span class='glyphicon glyphicon-minus'> </span></button> 
        </div>
     
    </div>

    
  </div>
  <div class="post-body">
   <div class="titulo"> {{titulo}} </div>
   <div class="contenido">{{contenido}}</div>
   <div class="recursos-post">
     <div  class="tags tags-post"></div>
   </div>
 </div>
 <div class="post-footer">
   <div class="col-md-4 col-xs-4">

    <div   class="btn-group dropup pull-left">

      <button   type="button" class=" cal_btn  btn btn-danger btn-sm  img-responsive dropdown-toggle" data-toggle="dropdown">
        Calificar <span class="caret"></span>
      </button>
      <ul  class=" dropdown-menu" role="menu">
        <li  ><a id= 'Mala' class='cal_op' >Mala</a></li>
        <li     ><a id= 'Regular'  class='cal_op' > Regular</a></li>
        <li   ><a id= 'Muy buena'  class='cal_op' >Muy buena</a></li>
        <li   ><a id= 'Excelente'  class='cal_op' >Excelente</a></li>
      </ul>

    </div>
  </div>
  <div class="col-md-4 col-xs-4">




 <button {{#if likeUsuarioLogeado }} disabled {{/if}}  class="like_btn btn btn-primary     btn-lg center-block img-responsive">Me gusta!</button>



 </div>
 <div class="col-md-4 col-xs-4"><a href="#" class=" verUsuariosLikes pull-right img-responsive"> Likes <span   class="likes_span badge" >{{likes}}</span> </a> </div>
</div>
</div>

</div>      
 </div>
</script>
<div id="target" style=" width: 50% " >Aqui se cargara la plantilla... wait for it</div>
<div id="target2" style=" width: 50% " > 

<div id="PublicacionesContainer">
  

</div>
<button id="verMasPosts" class="btn btn-sucess  btn-lg">ver mas posts</button>

</div>

<script type="text/javascript">
/*============================ Modelos ===============================)*/

        //Usuarios del grupo (Seccion)
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
              "likeUsuarioLogeado":false,
              "calidad":'Sin Calificar',
              "recursos":'',
              "fecha":'',
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
              // tagName: 'div',
            // className:"publicacion",
            initialize:function(){
                  this.listenTo(this.model, "change",  this.render);
                  this.render();

            },
            events: {
                "click .like_btn ": "darLike",
                "click .eliminar_post": "eliminarPost",
                "click .editar_post":"editarPost",
                "click .guardarCambios": "guardarCambios",
                // "click .verUsuariosLikes":"MostrarUsuariosLikes",
            },
            render: function () {

              var fuente=$('#plantillaPost').html();
              var template = Handlebars.compile(fuente);
              var rendered = template(this.model.toJSON());
              $(this.el).html(rendered);
                // return this;
            },
            darLike:function(){
              // alert("dio click en publicacion "+this.model.get("id"));
                if(! this.model.get("likeUsuarioLogeado") ){
                      var likeAnt= parseInt(this.model.get("likes"));
                      var nuevoLike=likeAnt+1;
                      this.model.set("likes",nuevoLike);

                        this.model.save(null,{ 

                          success:function(datos){

                            socket.emit("likePost", datos);
                          }

                        });
                }      
             },
             eliminarPost: function(){

                this.model.destroy();
             },
             editarPost: function(){
               $(this.el).find(".titulo").attr("contenteditable",true);
                $(this.el).find(".contenido").attr("contenteditable",true);
                $(this.el).find(".opcionesPost").prepend("<button class='btn btn-default guardarCambios'>Guardar Cambios </button>")
             },
             guardarCambios: function(){

                this.model.save(null,{ 

                    success:function(datos){
                      alert("adad");
                     // socket.emit("likePost", datos);
                    }

                });

             },
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
              var rendered = template();
              $(this.el).html(rendered);
                // return this;
            },
            enviarPost:function(){

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
        });

        //Vista de el conjunto de publicaciones
       var publicacionesVista = Backbone.View.extend({
            el: '#target2 ',
            initialize:function(){
                this.render();
                 this.listenTo(this.model, "change", this.render);
                this.listenTo(this.model, "add", this.render);   
  
            },
            events: { 
                "click #verMasPosts": "verMasPosts",
            },
            render: function () {
                var coleccionDePosts=this.model.models;
                //Limpiamos la vista
                this.limpiarVista();
              //A cada modelo del la coleccion le asignamos una vista
               this.model.each(function(o) {
                  var vistaPost=new PostVista ({model:o});
                  $(this.el).find("#PublicacionesContainer").append(vistaPost.el);
                }, this); 
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
              async:true,
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

               console.log(publicacionesDelGrupo)
</script>

</body>
</html>