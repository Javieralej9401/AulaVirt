<?php

namespace AVBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AVBundle\Entity\Reto;
use AVBundle\Entity\UsuarioReto;
use AVBundle\Entity\RespuestaReto;
use AVBundle\Form\RetoType;
use AVBundle\Form\RespuestaRetoType;
/**
 * Reto controller.
 *
 */
class RetoController extends Controller
{

    private function UserGetRetosDePersonas(){
        $usuario= $this->getUser();

        $retosDePersonas= $usuario->getRetosDePersonas();


        $misRetosDePersonas= array();

        foreach ($retosDePersonas as $reto) {

              $misRetosDePersonas[ $reto->getId() ] = array(
                                                      "RetoObj"=> $reto,
                                                      "preguntas"=> array()
                                                       );
              $preguntas= $reto->getPreguntas();

              $preguntasArray= array();

              foreach ($preguntas as $pregunta) {

                 $resp=  $pregunta->getRespuestaReal();
                 $resparr=  json_decode( $resp, true );
                 $preguntasArray[] =  array(
                              "preguntaObj"=> $pregunta,
                              "respuesta"=> $resparr
                      );                        
              }
              $misRetosDePersonas[ $reto->getId() ]
                                    ["preguntas"]= $preguntasArray;
                
        }
         return $misRetosDePersonas;  


    }
    private function ProtoFormRepuestaReto($ent,$type,$path){

        $form = $this->createForm($type, $ent, array(
            'action' => $this->generateUrl($path),
            'method' => 'POST',
        ));
        return $form;
    }
    private function crearFormNuevoReto(Reto $entity){

        $form = $this->createForm(new RetoType(), $entity, array(
            'action' => $this->generateUrl('reto_create'),
            'method' => 'POST',
        ));


        return $form;
    }
    public function MisRetosAction($idg){

       $em= $this->getDoctrine()->getManager();

        $grupoRs= $em->getRepository("AVBundle:Grupo")
                     ->findOneById($idg);

       if($grupoRs){

        $misRetosDePersonas= $this->UserGetRetosDePersonas();
        $ProtoFormRespuestaReto= $this->ProtoFormRepuestaReto(new RespuestaReto(), new RespuestaRetoType(),"respuestareto_create" );
        $ProtoFormRespuestaReto->get("idUsuario")->setData($this->getUser());

        $reto= new Reto();
        $FormNuevoReto= $this->crearFormNuevoReto($reto)->createView();

        return $this->render('AVBundle:Reto:MisRetos.html.twig', array(
            "misRetosDePersonas"=> $misRetosDePersonas,
            "ProtoFormRespuestaReto"=> $ProtoFormRespuestaReto->createView(),
            "FormNuevoReto"=>$FormNuevoReto,
            "grupo"=>$grupoRs,
        ));
        
       }else{
           return  $this->redirect($this->getRequest()->headers->get('referer'));
           
       }

    }
    /**
     * Lists all Reto entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AVBundle:Reto')->findAll();

        return $this->render('AVBundle:Reto:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Reto entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Reto();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $preg =  $entity->getPreguntas();


        $entity->setPreguntas($preg);
        $entity->setRetador($this->getUser());



        

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            //se puede mejorar con cascade pero ya que...(Mente en el objetivo)
            $retados= $entity->getRetados();
            foreach ($retados as $retado) {
              
              $reto_usuario= new UsuarioReto();
              $reto_usuario->setUsuarioId($retado);
              $reto_usuario->setRetoId($entity);

              $em->persist($reto_usuario);
              $em->flush();
              
            }

             return  $this->redirect($this->getRequest()->headers->get('referer'));
        }

         return  $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * Creates a form to create a Reto entity.
     *
     * @param Reto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Reto $entity)
    {
        $form = $this->createForm(new RetoType(), $entity, array(
            'action' => $this->generateUrl('reto_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Reto entity.
     *
     */
    public function newAction()
    {
        $entity = new Reto();
        $form   = $this->createCreateForm($entity);

        return $this->render('AVBundle:Reto:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Reto entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:Reto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Reto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AVBundle:Reto:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Reto entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:Reto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Reto entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AVBundle:Reto:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Reto entity.
    *
    * @param Reto $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Reto $entity)
    {
        $form = $this->createForm(new RetoType(), $entity, array(
            'action' => $this->generateUrl('reto_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Reto entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:Reto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Reto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('reto_edit', array('id' => $id)));
        }

        return $this->render('AVBundle:Reto:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Reto entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AVBundle:Reto')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Reto entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('reto'));
    }

    /**
     * Creates a form to delete a Reto entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reto_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
