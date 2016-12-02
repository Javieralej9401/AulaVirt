<?php

namespace AVBundle\Entity;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class CrearEditarUsuarioEvento implements EventSubscriberInterface{
     public static function getSubscribedEvents(){

        return array(FormEvents::PRE_SET_DATA => 'preSetData');
     }
     public function preSetData(FormEvent $event){

        $data = $event->getData();
        $form = $event->getForm();
        

        if ($data){
            if(!$data->getId()){

                    $form->add('nombre');
                    $form->add('apellido');
                    $form->add('cedula');
                    $form->add('login');
                    $form->add('clave');
                    $form->add('email');
                    $form->add('idtipousuario');
                    $form->add('iddepartamento');

            }
            
        }

    }
}
