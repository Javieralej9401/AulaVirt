<?php

namespace AVBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AVBundle\Entity\Grupo;
use AVBundle\Entity\Usuario;
use AVBundle\Entity\Reto;
use AVBundle\Entity\RetoPregunta;
use AVBundle\Form\RetoType;
use AVBundle\Entity\Grupoestudiante;
use AVBundle\Form\GrupoType;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Grupo controller.
 *
 */
class GrupoController extends Controller
{   

    //ir a la sala del grupo
    public function mostrarSalaAction($nombreGrupo, Request $request){


        $em= $this->getDoctrine()->getManager();

        $grupoRs= $em->getRepository("AVBundle:Grupo")
                     ->findOneByNombre($nombreGrupo);

        if($grupoRs){

            $usuarioLogeado= $this->getUser();
            $usuarioRs= $em->getRepository("AVBundle:Usuario")
                           ->findOneById($usuarioLogeado);

            if($usuarioRs){

                $estaEnelGrupo = $usuarioRs->verificarGrupoInscrito($grupoRs);
                if($estaEnelGrupo>0){

                         // $session = $request->getSession();
                         // $session->set('grupo', $grupoRs );
                         // $session->set('id_usuario', $usuarioRs->getId() );
                    
                   
                    return $this->render('AVBundle:Grupo:sala.html.twig', array(
                        'grupo' => $grupoRs,
                    )); 

                }
            }
        } 
    }
    
    public function BuscarAction($nombre){
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AVBundle:Grupo')->findOneBy(array("nombre"=>$nombre));
        $respuesta= array("disponible"=>false, "msj"=> null);
        if(!$entity){
            $respuesta["disponible"]=true;
        }
        return new JsonResponse($respuesta);
    }   
    /**
     * Lists all Grupo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AVBundle:Grupo')->findAll();
       

        return $this->render('AVBundle:Grupo:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    private function  ObtenerEstudiantesFromJsonToArray($JsonEstudiantes){
          //Se convierte el json  a un arreglo php
        $IdEstudiantes= json_decode($JsonEstudiantes);

        $em = $this->getDoctrine()->getManager();

        
        $ListaGrupoEstudiantes=array();

        foreach ($IdEstudiantes as $idEst) {

            $Grupoestudiante= new Grupoestudiante();
            $usuario = $em->getRepository('AVBundle:Usuario')->find($idEst);
            $Grupoestudiante->setIdEstudiante($usuario);
            //Se agrega a la lista de grupoestudiantes
            $ListaGrupoEstudiantes[] = $Grupoestudiante;

        }

        return $ListaGrupoEstudiantes;

    }
    /**
     * Creates a new Grupo entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Grupo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        //Se obtiene el input de los estudiantes (json)
        $estudiantesF= $form->get("EstudiantesField")->getData();
        
        $em = $this->getDoctrine()->getManager();

        $Estudiantes= $this->ObtenerEstudiantesFromJsonToArray($estudiantesF);

        $ProfesorEst= new Grupoestudiante();
        $ProfesorEst->setIdEstudiante($this->getUser());
        
        $Estudiantes[]=$ProfesorEst;

        if ($form->isValid()) {

            $entity->setEstudiantes($Estudiantes);
            $entity->setIdprofesor( $this->getUser() );
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('grupo_show', array('id' => $entity->getId())));
        }
         return new JsonResponse(array("token"=>"false"));
        // return $this->render('AVBundle:Grupo:new.html.twig', array(
        //     'entity' => $entity,
        //     'form'   => $form->createView(),
        // ));
    }

    /**
     * Creates a form to create a Grupo entity.
     *
     * @param Grupo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Grupo $entity)
    {
        $entity->addEstudiante( new Grupoestudiante() );

        $form = $this->createForm(new GrupoType(), $entity, array(
            'action' => $this->generateUrl('grupo_create'),
            'method' => 'POST',
        ));



        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Grupo entity.
     *
     */
    public function newAction()
    {
        $entity = new Grupo();
        $form   = $this->createCreateForm($entity);

        return $this->render('AVBundle:Grupo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Grupo entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:Grupo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grupo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AVBundle:Grupo:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Grupo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:Grupo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grupo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AVBundle:Grupo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Grupo entity.
    *
    * @param Grupo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Grupo $entity)
    {
        
        $form = $this->createForm(new GrupoType(), $entity, array(
            'action' => $this->generateUrl('grupo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Grupo entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:Grupo')->find($id);

        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grupo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        $estudiantesF= $editForm->get("EstudiantesField")->getData();
        $Estudiantes= $this->ObtenerEstudiantesFromJsonToArray($estudiantesF);
        $entity->setEstudiantes($Estudiantes);

        
        if ($editForm->isValid()) {
            $em->flush();


            return $this->redirect($this->getRequest()->headers->get('referer'));
        }

        return $this->render('AVBundle:Grupo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Grupo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AVBundle:Grupo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Grupo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * Creates a form to delete a Grupo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('grupo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
