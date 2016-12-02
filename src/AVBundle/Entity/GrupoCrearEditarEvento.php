<?php 


namespace AVBundle\Entity;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GrupoCrearEditarEvento implements EventSubscriberInterface {

	 public static function getSubscribedEvents(){

        return array(FormEvents::PRE_SET_DATA => 'preSetData');
     }
     public function preSetData(FormEvent $event){

        $data = $event->getData();
        $form = $event->getForm();
        

        if ($data){
            if(!$data->getId()){
                $form->add('idprofesor');
                $form->add('materia');  

            }
            
        }

    }


}