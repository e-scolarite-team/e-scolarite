<?php

namespace App\Bundle\BackOfficeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TypeReclamationType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', 'text')
            ->add('libelle', 'text')
            ->add('maxAutorise', 'text')
            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Bundle\BackOfficeBundle\Entity\TypeReclamation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_bundle_backofficebundle_typereclamation';
    }
}
