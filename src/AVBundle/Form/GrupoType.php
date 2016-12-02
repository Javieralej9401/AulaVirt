<?php

namespace AVBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AVBundle\Entity\GrupoCrearEditarEvento;

class GrupoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('recursos', null, array("label"=>false, "attr"=>array("class"=>"hidden")  ))
            ->add("EstudiantesField", "text", array(
                    "label"=>false,
                    "attr"=>array("class"=>"hidden"),
                    "mapped"=>false,
                )
            )
            ->add("Estudiantes", "collection", array(
                    "label"=>false,
                    "type"=> new GrupoestudianteType(),
                    "allow_add" =>true,
                    "allow_delete" =>true,

                                )
                )
            ->addEventSubscriber(new GrupoCrearEditarEvento())
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AVBundle\Entity\Grupo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'avbundle_grupo';
    }
}
