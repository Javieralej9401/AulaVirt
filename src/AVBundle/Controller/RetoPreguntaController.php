<?php

namespace AVBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AVBundle\Entity\RetoPregunta;
use AVBundle\Form\RetoPreguntaType;

/**
 * RetoPregunta controller.
 *
 */
class RetoPreguntaController extends Controller
{

    /**
     * Lists all RetoPregunta entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AVBundle:RetoPregunta')->findAll();

        return $this->render('AVBundle:RetoPregunta:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new RetoPregunta entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new RetoPregunta();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('retopregunta_show', array('id' => $entity->getId())));
        }

        return $this->render('AVBundle:RetoPregunta:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a RetoPregunta entity.
     *
     * @param RetoPregunta $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(RetoPregunta $entity)
    {
        $form = $this->createForm(new RetoPreguntaType(), $entity, array(
            'action' => $this->generateUrl('retopregunta_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new RetoPregunta entity.
     *
     */
    public function newAction()
    {
        $entity = new RetoPregunta();
        $form   = $this->createCreateForm($entity);

        return $this->render('AVBundle:RetoPregunta:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a RetoPregunta entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:RetoPregunta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RetoPregunta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AVBundle:RetoPregunta:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing RetoPregunta entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:RetoPregunta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RetoPregunta entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AVBundle:RetoPregunta:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a RetoPregunta entity.
    *
    * @param RetoPregunta $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(RetoPregunta $entity)
    {
        $form = $this->createForm(new RetoPreguntaType(), $entity, array(
            'action' => $this->generateUrl('retopregunta_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing RetoPregunta entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVBundle:RetoPregunta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RetoPregunta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('retopregunta_edit', array('id' => $id)));
        }

        return $this->render('AVBundle:RetoPregunta:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a RetoPregunta entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AVBundle:RetoPregunta')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find RetoPregunta entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('retopregunta'));
    }

    /**
     * Creates a form to delete a RetoPregunta entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('retopregunta_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
