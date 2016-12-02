<?php

namespace AVBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AVBundle\Entity\Grupoestudiante;
use AVBundle\Form\GrupoestudianteType;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Grupoestudiante controller.
 *
 */
class GrupoestudianteController extends Controller
{
    
    public function buscarAction($cedula){

        $em = $this->getDoctrine()->getManager();

        $usuario = $em->getRepository('AVBundle:Usuario')->findOneByCedula($cedula);

        $opciones= array(
            "DISPONIBLE"=>"Id",
            "NO EXISTE"=>0,
            "OTRO GRUPOMAT"=> array("grupo"=>"Id"),
            "ES OTRO PROFESOR"=>-1,
            "ES EL PROFESOR"=>-2 
        );
        if (!$usuario){
            
            $respuesta= $opciones["NO EXISTE"];
           
        }else{
            if($usuario->getIdtipousuario()=="Profesor"){

                //Se verifica si el profesor es el mismo...
                $idProfLog =  $this->getUser()->getId();

                if($usuario->getId()== $idProfLog){

                    $respuesta= $opciones["ES EL PROFESOR"]; 

                }else{
                    $respuesta= $opciones["ES OTRO PROFESOR"]; 

                }

            }else{

               $grupoestudiante= $em->getRepository('AVBundle:Grupoestudiante')->findOneByIdEstudiante($usuario);
               if(!$grupoestudiante){

                    $opciones["DISPONIBLE"]= $usuario->getId();
                    $respuesta= $opciones["DISPONIBLE"];
                    
               }else{
                    $respuesta= $opciones["OTRO GRUPOMAT"];
                    $respuesta["grupo"]= $grupoestudiante->getIdGrupo()->getId();
               } 

            }
        }

        return new JsonResponse(array("respuesta"=> $respuesta));

    }
    /**
     * Lists all Grupoestudiante entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AVBundle:Grupoestudiante')->findAll();

        return $this->render('AVBundle:Grupoestudiante:index.html.twig', array(
            'entities' => $entities,
        ));
    }

  
    /**
     * Creates a new Grupoestudiante entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Grupoestudiante();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);


        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('grupoestudiante_show', array('id' => $entity->getId())));
        }

        return $this->render('AVBundle:Grupoestudiante:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Grupoestudiante entity.
     *
     * @param Grupoestudiante $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Grupoestudiante $entity)
    {
        $form = $this->createForm(new GrupoestudianteType(), $entity, array(
            'action' => $this->generateUrl('grupoestudiante_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Grupoestudiante entity.
     *
     */
    public function newAction()
    {
        $entity = new Grupoestudiante();
        $form   = $this->createCreateForm($entity);

        return $this->render('AVBundle:Grupoestudiante:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Grupoestudiante entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:Grupoestudiante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grupoestudiante entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AVBundle:Grupoestudiante:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Grupoestudiante entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:Grupoestudiante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grupoestudiante entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AVBundle:Grupoestudiante:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Grupoestudiante entity.
    *
    * @param Grupoestudiante $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Grupoestudiante $entity)
    {
        $form = $this->createForm(new GrupoestudianteType(), $entity, array(
            'action' => $this->generateUrl('grupoestudiante_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Grupoestudiante entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:Grupoestudiante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Grupoestudiante entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('grupoestudiante_edit', array('id' => $id)));
        }

        return $this->render('AVBundle:Grupoestudiante:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Grupoestudiante entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AVBundle:Grupoestudiante')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Grupoestudiante entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

       return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * Creates a form to delete a Grupoestudiante entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('grupoestudiante_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
