<?php

namespace AVBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AVBundle\Entity\Usuario;
use AVBundle\Entity\Grupo;
use AVBundle\Entity\Reto;
use AVBundle\Entity\FotoPerfil;
use AVBundle\Entity\RetoPregunta;
use AVBundle\Form\RetoType;
use AVBundle\Form\GrupoType;
use AVBundle\Form\UsuarioType;
use AVBundle\Form\GrupoestudianteType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

/**
 * Usuario controller.
 *
 */
class UsuarioController extends Controller
{
   
    public function BuscarUsuarioAjaxAction(Request $request){

        $form= $this->crearFormBusqueda();
        $form->handleRequest($request);
        $input=  $form->get("campoBuscarUsuario")->getData();

        // $em = $this->getDoctrine()->getManager();
        // $ent = $em->getRepository('AVBundle:Usuario')->findOneBy(array("nombre"=>$input));



       $em = $this->getDoctrine()->getManager();

        // $qb = $em->createQueryBuilder('u');
        // $qb->where(
        //          $qb->expr()->like('u.nombre', ':user')
        //      )
        //      ->setParameter('user','%'.$input.'%')
        //      ->getQuery()
        //      ->getResult();
        $query = $em->createQuery(
            'SELECT u
               FROM AVBundle:Usuario u
              WHERE u.nombre like :input OR u.nombre like :input' 
        )->setParameter('input', "%".$input."%")
        ->setMaxResults(5);
     
        $usuarios  = $query->getResult();


        $arrayUsuario=array();
        if($usuarios){
                foreach ($usuarios as $key => $value) {
                   $arrayUsuario[]= array(
                          "Nombre"=>  $value->getNombre(),
                          "Apellido" => $value->getApellido(),
                          "Tlf"=>  $value->getTlf(),
                          "Email"=>  $value->getEmail(),
                          "TipoUsuario"=> $value->getIdTipoUsuario()->getNombre(),
                          "Cedula"=> $value->getCedula(),

                    );
                }
        
            

        }
        return  new JsonResponse($arrayUsuario);
    }
    private function crearFormBusqueda(){
        return $this->createFormBuilder()
                    ->setAction($this->generateUrl('usuario_buscar'))
                    ->setMethod("GET")
                    ->add("campoBuscarUsuario")
                    ->getForm();
                    
    }

    public function mostrarDashboardAction(){
        
        $rol= $this->get("security.context");
        $arrayRecursos= array();

        if( $rol->isGranted("ROLE_Profesor") ){
             $arrayRecursos= $this->GetRecursosProfesor();
        }else{
             if ( $rol->isGranted("ROLE_Estudiante")) {
                $arrayRecursos= $this->GetRecursosEstudiante();
             }else{
                $arrayRecursos= $this->GetRecursosAdministrador();
             }
        }
        $arrayRecursos["FormBuscarUsuario"]=  $this->crearFormBusqueda()
                                                     ->createView();
       

        return $this->render('AVBundle:Usuario:dashboard.html.twig', 
            $arrayRecursos
        );
    }
    private function GetRecursosProfesor(){

        $profesorLogeado= $this->getUser();
        $grupo= new Grupo();
        $grupoType= new GrupoType();
        $nuevoGrupoForm= $this->generarFormGenericNuevo($grupo,$grupoType, "grupo_new");

        
        $nuevoGrupoForm->get("idprofesor")->setData($profesorLogeado);


        $formEditarGrupo= $this->generarFormGenericEditar($grupo,$grupoType, "grupo_update");
        $formEditarGrupo->get("idprofesor")->setData($profesorLogeado);

        $formEliminarGrEstudiantes= $this->generarFormGenericEliminar("grupoestudiante_delete");

        return array(
           "nuevoGrupoForm" => $nuevoGrupoForm->createView(),
           "formEditarGrupo"=> $formEditarGrupo->createView(),
           "formEliminarGrEstudiantes"=> $formEliminarGrEstudiantes->createView(),
        );
    }
    private function GetRecursosEstudiante(){
        
    }
    private function GetRecursosAdministrador(){

        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository('AVBundle:Usuario')->findAll();

        $usuario= new Usuario();
        $usuarioType= new UsuarioType();

        $formCrearUsuario= $this->
                generarFormGenericNuevo($usuario,$usuarioType,"usuario_create");
        $formEditarUsuarioProto= $this->
                generarFormGenericEditar($usuario,$usuarioType,"usuario_update");
        $formEliminarUsuarioProto= $this->
                generarFormGenericEliminar("usuario_delete");

        $gestionarUsuarios= array(
            "FormCrear"=> $formCrearUsuario->createView(),
            "FormEditar"=> $formEditarUsuarioProto->createView(),
            "FormEliminar"=>$formEliminarUsuarioProto->createView(),
        );

        return  array(
            'usuarios' => $usuarios,
            "gestionarUsuarios"=>$gestionarUsuarios,

        );
    }
    private function generarFormGenericNuevo($ent,$type,$path){
        $form = $this->createForm($type, $ent, array(
            'action' => $this->generateUrl($path),
            'method' => 'POST',
        ));
        return $form;
    }
    private function generarFormGenericEditar($ent,$type,$path){
        $form = $this->createForm($type, $ent, array(
            'action' => $this->generateUrl($path, array('id' => "texto")),
            'method' => 'PUT',
        ));
         return $form;
    }
    private function generarFormGenericEliminar($path){
        return $this->createFormBuilder()
            ->setAction($this->generateUrl($path, array('id' => "texto")))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    /**
     * Lists all Usuario entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AVBundle:Usuario')->findAll();

        return $this->render('AVBundle:Usuario:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Usuario entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Usuario();
        

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $entity->getIdFotoPerfil()->setIdUsuario($entity);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('usuario_show', array('id' => $entity->getId())));
        }

        return $this->render('AVBundle:Usuario:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Usuario entity.
     *
     * @param Usuario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Usuario $entity)
    {
        $form = $this->createForm(new UsuarioType(), $entity, array(
            'action' => $this->generateUrl('usuario_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Usuario entity.
     *
     */
    public function newAction()
    {
        $entity = new Usuario();
        $form   = $this->createCreateForm($entity);

        return $this->render('AVBundle:Usuario:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }
    public function perfilAction($id){

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

       
         $editForm = $this->createEditForm($entity);

        return $this->render('AVBundle:Usuario:perfil.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Finds and displays a Usuario entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AVBundle:Usuario:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Usuario entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }
        $editForm = $this->createEditForm($entity);

         
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AVBundle:Usuario:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Usuario entity.
    *
    * @param Usuario $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Usuario $entity)
    {
        $form = $this->createForm(new UsuarioType(), $entity, array(
            'action' => $this->generateUrl('usuario_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Usuario entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }


        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

          $entity->getIdFotoPerfil()->setIdUsuario($entity);


        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('usuario_edit', array('id' => $id)));
        }

        return $this->render('AVBundle:Usuario:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Usuario entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AVBundle:Usuario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Usuario entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('usuario'));
    }

    /**
     * Creates a form to delete a Usuario entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('usuario_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
