<?php

namespace AVBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
class RetoPreguntaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pregunta')
            ->add('tipo','choice', array(
                'choices'   => array('vof' => 'Verdadero o Falso', 'sm' => 'Selección Simple'),
                'required'  => false,
                    ) 
            )
            ->add('idReto', null , array("label"=>false,"attr"=>array("class"=>"hidden") ) )
            ->add("respuestaReal")

        ;
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AVBundle\Entity\RetoPregunta'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'avbundle_retopregunta';
    }
}
