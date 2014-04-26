<?php
namespace App\Bundle\BackOfficeBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface as FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface as OptionsResolverInterface;


/**
* 
*/
class ReponseReclamationType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options){

		

		$builder->add('reponse','textarea');

	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){

		
		$resolver->setDefaults(array(
			'data_class' => 'App\Bundle\BackOfficeBundle\Entity\Reclamation',
			
			));
	}

	public function getName(){
		return 'data_exchange';
	}

	
}