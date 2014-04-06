<?php

namespace App\Bundle\BackOfficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ConfigType extends AbstractType
{
    private $dafalutData;

    public function __construct($defalutData){
        $this->defalutData = $defalutData;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('autoDemandeState','checkbox')
            ->add('autoDemandeAmount')
            ->add('infoDateFormat')
            ->add('infoDatetimeFormat')
            ->add('infoServiceState','checkbox')
            ->add('infoSemester')
            ->add('infoYear')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Bundle\BackOfficeBundle\Form\Data\ConfigData'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'config';
    }
}
