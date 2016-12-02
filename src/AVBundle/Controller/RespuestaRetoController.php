<?php

namespace AVBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AVBundle\Entity\RespuestaReto;
use AVBundle\Form\RespuestaRetoType;

/**
 * RespuestaReto controller.
 *
 */
class RespuestaRetoController extends Controller
{

    /**
     * Lists all RespuestaReto entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AVBundle:RespuestaReto')->findAll();

        return $this->render('AVBundle:RespuestaReto:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    private function obtenerRespuestasReto($respuestaReto){

    	$respuestaField= $respuestaReto->getRespuestaRetado();
    	//Se convierte a un array asociativo php
    	$respuestasRetador=  json_decode( $respuestaField, true );

    	
    	$retoObj= $respuestaReto->getIdReto();

    	$preguntasReto= $retoObj->getPreguntas();
    	$PregsopcionesCorrectas=array();
    	foreach ($preguntasReto as $pregunta) {

             $respPregunta=   json_decode( $pregunta->getRespuestaReal(),true );

             $PregsopcionesCorrectas[]= array(
             		"idpreg"=>$pregunta->getId(), 
             		"OpCorrecta"=>$respPregunta["opcionCorrecta"], 
             );

               
        }
        $total;
        $cantCorrectas=0;
        $cantIncorrectas=0;
        $RespuestasPregs=array();
        for ($i=0; $i < sizeof( $PregsopcionesCorrectas )  ; $i++) { 

        	foreach ($respuestasRetador as $idpreg => $resp) {
    			if($idpreg== $PregsopcionesCorrectas[$i]["idpreg"] ){
    					$respPregunta=null;
    					if($resp== $PregsopcionesCorrectas[$i]["OpCorrecta"]){
    							$cantCorrectas++;
    							$respPregunta= array(
		    						"idPreg"=> $idpreg,
		    						"respuesta"=> "Correcta",
		    					);
    					}else{
    							$cantIncorrectas++;
    							$respPregunta= array(
		    						"idPreg"=> $idpreg,
		    						"respuesta"=> "Incorrecta",
		    					);
    					}
    					$RespuestasPregs[]= $respPregunta;

    					

    			}	

    		}

        }
        $total=array(
    		"respuestasAlasPregs"=>$RespuestasPregs,
    		"cantCorrectas"=>$cantCorrectas,
    		"cantIncorrectas"=>$cantIncorrectas,
    	);

    	//echo json_encode($total);
    	return $total;
    }
    private function registrarPuntosReto($respuestas){
    	//echo json_encode($respuestas);

    	//cuantos puntos vale un reto?
    	$puntosPorReto= 5;

    	$cantCorrectas= $respuestas["cantCorrectas"];
    	$cantIncorrectas= $respuestas["cantIncorrectas"];
    	$cantPregs= $cantCorrectas+ $cantIncorrectas;

   		//falta ver criterio de puntos...... :S

    }
    /**
     * Creates a new RespuestaReto entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new RespuestaReto();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        //verificado respuesta del retador;

	    $respuestas= $this->obtenerRespuestasReto($entity);

	    $puntos= $this->registrarPuntosReto($respuestas);

	   
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

              return  $this->redirect($this->getRequest()->headers->get('referer'));
        }

          return  $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * Creates a form to create a RespuestaReto entity.
     *
     * @param RespuestaReto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(RespuestaReto $entity)
    {
        $form = $this->createForm(new RespuestaRetoType(), $entity, array(
            'action' => $this->generateUrl('respuestareto_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new RespuestaReto entity.
     *
     */
    public function newAction()
    {
        $entity = new RespuestaReto();
        $form   = $this->createCreateForm($entity);

        return $this->render('AVBundle:RespuestaReto:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a RespuestaReto entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:RespuestaReto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RespuestaReto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AVBundle:RespuestaReto:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing RespuestaReto entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:RespuestaReto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RespuestaReto entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AVBundle:RespuestaReto:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a RespuestaReto entity.
    *
    * @param RespuestaReto $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(RespuestaReto $entity)
    {
        $form = $this->createForm(new RespuestaRetoType(), $entity, array(
            'action' => $this->generateUrl('respuestareto_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing RespuestaReto entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:RespuestaReto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RespuestaReto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('respuestareto_edit', array('id' => $id)));
        }

        return $this->render('AVBundle:RespuestaReto:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a RespuestaReto entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AVBundle:RespuestaReto')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find RespuestaReto entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('respuestareto'));
    }

    /**
     * Creates a form to delete a RespuestaReto entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('respuestareto_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
