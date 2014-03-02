<?php
namespace App\Bundle\BackOfficeBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface as FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface as OptionsResolverInterface;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Choice;

/**
* 
*/
class ImportFormType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options){

		$builder->add("table","choice",array(
										'empty_value' => 'chosir un la table charger',
										'multiple' => false,
										'choices' => array('etudiant' => 'Etudiant', 'filiere' => 'Filiere', 'module' => 'Module', 'note' => 'Note', 'element' => 'Element'),
										));

		$builder->add('attachement','file');

	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		
		$resolver->setDefaults(array(
			'data_class' => 'App\Bundle\BackOfficeBundle\Form\Data\ImportData',
			'csrf_protection' => true,
			'csrf_field_name' => '_protection',
			'intention' => 'data_exchange',
			));
	}

	public function getName(){
		return 'data_exchange';
	}

	
}