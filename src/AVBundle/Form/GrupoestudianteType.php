<?php

namespace AVBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GrupoestudianteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idEstudiante', null, array("label"=>null,"attr"=>array("class"=>"hidden")))
            ->add('idGrupo')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AVBundle\Entity\Grupoestudiante'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'avbundle_grupoestudiante';
    }
}
