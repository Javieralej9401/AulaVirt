<?php

namespace AVBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RetoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('status', null,array("label"=>false,"attr"=>array("class"=>"hidden")))
            ->add("idgrupo", null,array("label"=>false,"attr"=>array("class"=>"hidden")))
            ->add('fechaCreacion',null, array("label"=>false,"attr"=>array("class"=>"hidden")))
            ->add('fechaContestado',null, array("label"=>false,"attr"=>array("class"=>"hidden")))
            ->add('puntos',null, array("label"=>false,"attr"=>array("class"=>"hidden")))
            ->add('retador',null, array("label"=>false,"attr"=>array("class"=>"hidden")))
            ->add('retados')
            ->add("preguntas", "collection", 
                    array(
                        "label"=>false,
                        "type"=> new RetoPreguntaType(),
                        "allow_add"=>true,
                        "allow_delete"=> true,
                     )
            )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AVBundle\Entity\Reto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'avbundle_reto';
    }
}
