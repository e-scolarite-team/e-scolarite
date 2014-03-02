<?php

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface as FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface as OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;

/**
* 
*/
class ImportFormType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, $options = array()){

		$builder->add("table","choice",array(
										'empty_value' => 'chosir un la table charger',
										'multiple' => false,
										'preferred_choices' => array('etudiant'),
										'choices' => array('etudiant' => 'Etudiant', 'filiere' => 'Filiere', 'module' => 'Module', 'note' => 'Note', 'element' => 'Element'),
										'constraints' => array(
											new NotBlank(
												array('message' => "errors.import.table")
												),
											new Choice(
													array(
														'choices' => array('etudiant','note','module','filiere'),
														'message' => 'errors.import.table',
														)
													),
										),
										));

		$builder->add('attachement','file',array(
											'constraints' => array(
												new File(
													array(
														'uploadErrorMesssage' => 'errors.import.attachment',
														'mimeTypesMesssage' => 'errors.import.attachment.mimeTypes',
														'mimeTypes' => array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'text/csv'),
													)
												)
											)
											));

	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
			'csrf_protection' => true,
			'csrf_field_name' => '_protection',
			'intention' => 'data_exchange',
			));
	}

	public function getName(){
		return 'data_exchange';
	}

	
}