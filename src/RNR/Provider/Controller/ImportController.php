<?php

namespace RNR\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\Validator\Constraints as Assert;

require_once '/../../Classes/PHPExcel.php';
require_once '/../../Classes/PHPExcel/IOFactory.php';

/**
 * Controller for the authors
 * @author Rein Bauwens	<rein.bauwens@student.odisee.be>
 */
class ImportController implements ControllerProviderInterface {

	/**
	 * Returns routes to connect to the given application.
	 * @param Application $app An Application instance
	 * @return ControllerCollection A ControllerCollection instance
	 */
	public function connect(Application $app) {

		//@note $app['controllers_factory'] is a factory that returns a new instance of ControllerCollection when used.
		//@see http://silex.sensiolabs.org/doc/organizing_controllers.html
		$controllers = $app['controllers_factory'];

		// Bind sub-routes
		$controllers
			->get('/', array($this, 'import'))
			->method('GET|POST')
			->bind('Import');
		// Return ControllerCollection
		return $controllers;
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function import(Application $app) {
		$placename='';
		$show='';
		$data=array();
		$error="";
		$value = 0;

		//check if the user is logged in
		//set acces level and username to default
		$access_level = 0;
		$username = '';

		// check if user is already logged in
		if ($app['session']->get('user') && ($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//get the user from de database.
			$user = $app['db.people']->fetchAdminPerson($app['session']->get('user'));

			//set acces level and username
			$username = $user[0]['name'];
			$access_level = $user[0]['access_level'];
		}
		else{

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('auth.login')); 
		}
		
		$CiudadID ='';
		$DepartamentoID='';
		$PaisID='';
		
		$uploadformstudents = $app['form.factory']
		->createNamed('uploadformstudents', 'form')
				->add('file', 'file', array(
						'required' => true,
						'constraints' => array(new Assert\NotBlank()),
						'label' => 'File:'
					))
				->add('PaisID', 'text', array(
						'required' => false,
						'constraints' => array(new Assert\NotBlank()),
						'data' => '',
					))
				->add('DepartamentoID', 'text', array(
						'required' => false,
						'constraints' => array(new Assert\NotBlank()),
						'data' => '',
					))
				->add('CiudadID', 'text', array(
						'required' => false,
						'constraints' => array(new Assert\NotBlank()),
						'data' => '',
					));

		$uploadformteachers = $app['form.factory']
		->createNamed('uploadformteachers', 'form')
				->add('file', 'file', array(
						'required' => true,
						'constraints' => array(new Assert\NotBlank()),
						'label' => 'File:'
					))
				->add('PaisID', 'text', array(
						'required' => false,
						'constraints' => array(new Assert\NotBlank()),
						'data' => '',
					))
				->add('DepartamentoID', 'text', array(
						'required' => false,
						'constraints' => array(new Assert\NotBlank()),
						'data' => '',
					))
				->add('CiudadID', 'text', array(
						'required' => false,
						'constraints' => array(new Assert\NotBlank()),
						'data' => '',
					));

		$uploadformlaptops = $app['form.factory']
		->createNamed('uploadformlaptops', 'form')
				->add('file', 'file', array(
						'required' => true,
						'constraints' => array(new Assert\NotBlank()),
						'label' => 'File:'
					));
		
		$uploadformstudents->handleRequest($app['request']);
		$uploadformteachers->handleRequest($app['request']);
		$uploadformlaptops->handleRequest($app['request']);

		$file = $app['request']->files->get($uploadformstudents->getName());
		$file = $app['request']->files->get($uploadformteachers->getName());
		$file = $app['request']->files->get($uploadformlaptops->getName());

		if ($uploadformstudents->isValid()) {

			$data= $uploadformstudents->getData();
			$CiudadID =$data['CiudadID'];
			$DepartamentoID=$data['DepartamentoID'];
			$PaisID=$data['PaisID'];
			$placename = $data['PaisID'] . ' > '. $data['DepartamentoID'] .  ' > '. $data['CiudadID'];
			$Ciudad = $app['db.places']->getPlace($CiudadID, 3);
			$Departamento = $app['db.places']->getPlace($DepartamentoID, 2);
			$Pais = $app['db.places']->getPlace($PaisID, 1);
			$laptopid ='';
			$data= $uploadformstudents->getData();
			$filename=$_FILES["uploadformstudents"]["tmp_name"]["file"];
			$extension=$_FILES["uploadformstudents"]["name"]["file"];
			if(strpos(substr($extension,-4),'lsx') || strpos(substr($extension,-4),'xlsx'))
		    {
		    	if ( $_FILES["uploadformstudents"]["tmp_name"]['file'] )
				{
					$objPHPExcel = \PHPExcel_IOFactory::load($filename);
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$data = $sheetData;
					foreach ($sheetData as $value) {
						$place="";
						$profile="";
						$grade="";
						try {
							$place= $app['db.places']->getSchool($value['D'],$Ciudad);
							$schoolid = $place;
							if(empty($place)){
								$school = array('created_at' => date("Y-m-d"),'name' => $value['D'],'place_id' => $Ciudad,'place_type_id' => 4);
								$app['db.places']->insert($school);
								$schoolid = $app['db.places']->Lastadded();
								$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($Ciudad);
								foreach ($Ancestors as $waarde) {
								 	$dependency = array('descendant_id' => $schoolid, 'ancestor_id' => $waarde['ancestor_id']);
									$app['db.places_dependencies']->insert($dependency);
								}
								$dependency = array('descendant_id' => $schoolid, 'ancestor_id' => $schoolid);
								$app['db.places_dependencies']->insert($dependency);
								$place= $app['db.places']->getSchool($value['D'],$Ciudad);
							}
							if(!empty($value['F'])){
								$name = ($value['F'] == 'm')? 'Turno Mañana': (($value['F'] == 't')? 'Turno Tarde': 'Turno Completo');
								$place= $app['db.places']->getTimeOfPlace($place, $name);
								$turnoid = $place;
								if(empty($place)){
									$turno = array('created_at' => date("Y-m-d"),'name' => $name,'place_id' => $schoolid,'place_type_id' => 12);
									$app['db.places']->insert($turno);
									$turnoid = $app['db.places']->Lastadded();
									$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($schoolid);
									foreach ($Ancestors as $waarde) {
									 	$dependency = array('descendant_id' => $turnoid, 'ancestor_id' => $waarde['ancestor_id']);
										$app['db.places_dependencies']->insert($dependency);
									}
									$dependency =  array('descendant_id' => $turnoid, 'ancestor_id' => $turnoid);
									$app['db.places_dependencies']->insert($dependency);
									$place= $app['db.places']->getTimeOfPlace($place, $name);
								}
							}
							if(!empty($value['H'])){
								$laptopid = $app['db.laptops']->GetLaptopId($value['H']);
							}
							if(!empty($value['E'])){
								$place_type_id='';
								switch ($value['E']) {
								    case 1:
								        $name = 'Primer Grado';
								        $place_type_id=5;
								        break;
								    case 2:
								        $name = 'Segundo Grado';
								        $place_type_id=6;
								        break;
								    case 3:
								        $name = 'Tercer Grado';
								        $place_type_id=7;
								        break;
								    case 4:
								        $name = 'Cuarto Grado';
								        $place_type_id=8;
								        break;
								    case 5:
								        $name = 'Quinto Grado';
								        $place_type_id=9;
								        break;
								    case 6:
								        $name = 'Sexto Grado';
								        $place_type_id=10;
								        break;
								    case 7:
								        $name = 'Septimo Grado';
								        $place_type_id=16;
								        break;
								    case 8:
								        $name = 'Octavo Grado';
								        $place_type_id=17;
								        break;
								    case 9:
								        $name = 'Noveno Grado';
								        $place_type_id=18;
								        break;
								    case 'k': 
								        $name = 'Preescolar';
								        $place_type_id=14;
								        break;
								    case 'special':
								        $name = 'Educacion Especial';
								        $place_type_id=13;
								        break;
								}
								$place= $app['db.places']->getgradeOfPlace($place, $name);
								$gradoid = $place;
								if(empty($place)){
									$grado = array('created_at' => date("Y-m-d"),'name' => $name,'place_id' => $turnoid,'place_type_id' => $place_type_id);
									$app['db.places']->insert($grado);
									$gradoid = $app['db.places']->Lastadded();
									$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($turnoid);
									foreach ($Ancestors as $waarde) {
									 	$dependency = array('descendant_id' => $gradoid, 'ancestor_id' => $waarde['ancestor_id']);
										$app['db.places_dependencies']->insert($dependency);
									}
									$dependency =  array('descendant_id' => $gradoid, 'ancestor_id' => $gradoid);
									$app['db.places_dependencies']->insert($dependency);
									$place= $app['db.places']->getgradeOfPlace($place, $name);
								}
							}
							if(!empty($value['G'])){
								switch ($value['G']) {
								    case 'a':	
								        $name = 'Seccion A';
								        break;
								    case 'b':
								        $name = 'Seccion B';
								        break;
								    case 'c':
								        $name = 'Seccion C';
								        break;
								    case 'd':
								        $name = 'Seccion D';
								        break;
								}
								$place= $app['db.places']->getSeccionOfPlace($place, $name);

								if(empty($place)){
									$seccion = array('created_at' => date("Y-m-d"),'name' => $name,'place_id' => $gradoid,'place_type_id' => 11);
									$app['db.places']->insert($seccion);
									$seccionid = $app['db.places']->Lastadded();
									$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($gradoid);
									foreach ($Ancestors as $waarde) {
									 	$dependency = array('descendant_id' => $seccionid, 'ancestor_id' => $waarde['ancestor_id']);
										$app['db.places_dependencies']->insert($dependency);
									}
									$dependency =  array('descendant_id' => $seccionid, 'ancestor_id' => $seccionid);
									$app['db.places_dependencies']->insert($dependency);
									$place= $app['db.places']->getSeccionOfPlace($place, $name);
								}
							}
						} catch (Exception $e) {
							var_dump($e);
						}
						
						if(ctype_digit($place)){
							$object = array('created_at' => date("Y/m/d"), 'name' => $value['A'],'lastname' => $value['B'], 'school_name'=> $value['D']);
							try {
								$app['db.people']->insert($object);
								$person_id = $app['db.people']->Lastadded();
								if(!empty($laptopid)){
									if(ctype_digit($laptopid)){
										$app['db.laptops']->updatelaptopbyID($laptopid, $person_id);
									}
								}
								$perform = array('person_id' => $person_id, 'place_id' => $place, 'profile_id' => 7);
								$app['db.performs']->insert($perform);
								$error = "Students added";
							} catch (Exception $e) {
								$error =  "server down, try again later";
							}	
						}
						else{
							$error ='The grade doesnt fit the school.';
						}	
					}
					$value = 1;

					
				}
		    }
		    else{
		        $error = 'Invalid File:Please Upload XLSX File';
		    }
		}

		if ($uploadformteachers->isValid()) {
			$data= $uploadformteachers->getData();
			$CiudadID =$data['CiudadID'];
			$DepartamentoID=$data['DepartamentoID'];
			$PaisID=$data['PaisID'];
			$placename = $data['PaisID'] . ' > '. $data['DepartamentoID'] .  ' > '. $data['CiudadID'];
			$Ciudad = $app['db.places']->getPlace($CiudadID, 3);
			$Departamento = $app['db.places']->getPlace($DepartamentoID, 2);
			$Pais = $app['db.places']->getPlace($PaisID, 1);
			$laptopid ='';
			$filename=$_FILES["uploadformteachers"]["tmp_name"]["file"];
			$extension=$_FILES["uploadformteachers"]["name"]["file"];
			if(strpos(substr($extension,-4),'lsx') || strpos(substr($extension,-4),'xlsx'))
		    {
		    	if ( $_FILES["uploadformteachers"]["tmp_name"]['file'] )
				{
					$objPHPExcel = \PHPExcel_IOFactory::load($filename);
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$data = $sheetData;
					foreach ($sheetData as $value) {
						$place="";
						$profile="";
						$grade="";
						try {
							$place= $app['db.places']->getSchool($value['D'],$Ciudad);
							$schoolid = $place;
							if(empty($place)){
								$school = array('created_at' => date("Y-m-d"),'name' => $value['D'],'place_id' => $Ciudad,'place_type_id' => 4);
								$app['db.places']->insert($school);
								$schoolid = $app['db.places']->Lastadded();
								$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($Ciudad);
								foreach ($Ancestors as $waarde) {
								 	$dependency = array('descendant_id' => $schoolid, 'ancestor_id' => $waarde['ancestor_id']);
									$app['db.places_dependencies']->insert($dependency);
								}
								$dependency = array('descendant_id' => $schoolid, 'ancestor_id' => $schoolid);
								$app['db.places_dependencies']->insert($dependency);
								$place= $app['db.places']->getSchool($value['D'],$Ciudad);
							}
							if(!empty($value['E'])){
								$laptopid = $app['db.laptops']->GetLaptopId($value['E']);
							}
						} catch (Exception $e) {
							var_dump($e);
						}
						if(ctype_digit($place)){
							$object = array('created_at' => date("Y/m/d"), 'name' => $value['A'],'lastname' => $value['B'], 'school_name'=> $value['D']);
							try {
								$app['db.people']->insert($object);
								$person_id = $app['db.people']->Lastadded();
								if(!empty($laptopid)){
									if(ctype_digit($laptopid)){
										$app['db.laptops']->updatelaptopbyID($laptopid, $person_id);
									}
								}
								$perform = array('person_id' => $person_id, 'place_id' => $place, 'profile_id' => 5);
								$app['db.performs']->insert($perform);
								$error = "Teachers added";
							} catch (Exception $e) {
								$error =  "server down, try again later";
							}	
						}
						else{
							$error ='The grade doesnt fit the school.';
						}	
					}
				}
				$value = 2;
		    }
		    else{
		        $error = 'Invalid File:Please Upload XLSX File';
		    }
		}

		if ($uploadformlaptops->isValid()) {
			$data= $uploadformlaptops->getData();
			$filename=$_FILES["uploadformlaptops"]["tmp_name"]["file"];
			$extension=$_FILES["uploadformlaptops"]["name"]["file"];	
			if(strpos(substr($extension,-4),'lsx') || strpos(substr($extension,-4),'xlsx'))
		    {
		    	if ( $_FILES["uploadformlaptops"]["tmp_name"]['file'] )
				{
					$objPHPExcel = \PHPExcel_IOFactory::load($filename);
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$data = array();
					foreach ($sheetData as $value) {
						$place="";
						$profile="";
						$grade="";
						try {
							$controle = $app['db.laptops']->checkIfLaptopAlreadyExists($value['A'], $value['B']);
							$model = $app['db.models']->getModel($value['C']);
							if($controle == 0 && !empty($model) && strlen($value['A']) == 11 && strlen($value['B']) == 36){
								$laptop = array('serial_number' => $value['A'], 'uuid' => $value['B'], 'model_id' => $model, 'owner_id' => 5);
								$app['db.laptops']->insert($laptop);
								$error ="laptop added";
							}
							else if($controle != 0) {
								$error ="Laptop already exists";
							}
							else if(empty($model)) {
								$error ="model does not exists";
							}
							else if(strlen($value['A']) != 11) {
								$error ="length of serial is incorrect";
							}
							else if(strlen($value['B']) != 36) {
								$error ="length of uuid is incorrect";
							}
							else{
								$error ="server error";
							}
							$value['D'] = $error;
							$error ='';
							array_push($data, $value);
						}
						catch (Exception $e) {
								$error =  "server down, try again later";
						}	
					}	
				}
				$value = 3;
	    	}
		    else{
		        $error = 'Invalid File:Please Upload XLSX File';
		    }
		}
		//return the rendered twig with parameters
		return $app['twig']->render('Import/index.twig', array(
			'show' => $show,
			'error' => $error,
			'data' => $data,
			'value' => $value,
			'uploadformstudents' => $uploadformstudents->createView(),
			'uploadformteachers' => $uploadformteachers->createView(),
			'uploadformlaptops' => $uploadformlaptops->createView(),
			'placename' => $placename,
			'access_level' => $access_level,
			'username' => $username
			//'userLogin' => $userLogin,
		));	
	}
}







	
		
