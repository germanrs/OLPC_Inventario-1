<?php

namespace RNR\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\Validator\Constraints as Assert;

require_once dirname(__FILE__).'/../../Classes/PHPExcel.php';
require_once dirname(__FILE__).'/../../Classes/PHPExcel/IOFactory.php';

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
	 * import page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function import(Application $app) {
		
		//the name of the place
		$placename='';

		//show is the design of the table that will be choosenn from in the template
		$show='';

		//the data for the table in the template
		$data=array();

		//returns a big red error
		$error="";

		//a general field for some data
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
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
		
		//city id
		$CiudadID ='';
		
		//department id
		$DepartamentoID='';

		//country id
		$PaisID='';
		
		//the uploadform for the students with all his fields
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

		//the uploadform for the teachers with all his fields
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

		//the uploadform for the schools with all his fields
		$uploadformescuelas = $app['form.factory']
		->createNamed('uploadformescuelas', 'form')
				->add('file', 'file', array(
						'required' => true,
						'constraints' => array(new Assert\NotBlank()),
						'label' => 'File:'
					));

		//the uploadform for the laptops with all his fields
		$uploadformlaptops = $app['form.factory']
		->createNamed('uploadformlaptops', 'form')
				->add('file', 'file', array(
						'required' => true,
						'constraints' => array(new Assert\NotBlank()),
						'label' => 'File:'
					));
		
		//process the data sended to the server
		$uploadformstudents->handleRequest($app['request']);
		$uploadformteachers->handleRequest($app['request']);
		$uploadformlaptops->handleRequest($app['request']);
		$uploadformescuelas->handleRequest($app['request']);

		//get the file that was uploaded
		$file = $app['request']->files->get($uploadformstudents->getName());
		$file = $app['request']->files->get($uploadformteachers->getName());
		$file = $app['request']->files->get($uploadformlaptops->getName());
		$file = $app['request']->files->get($uploadformescuelas->getName());

		//if the form students is valid, upload all the students from the form to the database
		if ($uploadformstudents->isValid()) {

			//get the data from the uploadform
			$data= $uploadformstudents->getData();

			//get the city id
			$CiudadID =$data['CiudadID'];

			//get the department id
			$DepartamentoID=$data['DepartamentoID'];

			//get the country id
			$PaisID=$data['PaisID'];

			//set the placename
			$placename = $data['PaisID'] . ' > '. $data['DepartamentoID'] .  ' > '. $data['CiudadID'];

			//get the id of the school
			$Ciudad = $app['db.places']->getPlace($CiudadID, 3);

			//get the id of the department
			$Departamento = $app['db.places']->getPlace($DepartamentoID, 2);

			//get the id of the country
			$Pais = $app['db.places']->getPlace($PaisID, 1);

			//set the laptop id to default
			$laptopid ='';

			//get the details from the uploaded file
			$filename=$_FILES["uploadformstudents"]["tmp_name"]["file"];

			//get the extention of the file
			$extension=$_FILES["uploadformstudents"]["name"]["file"];

			//if the extention is ok go on.
			if(strpos(substr($extension,-4),'lsx') || strpos(substr($extension,-4),'xlsx'))
		    {
		    	//if the file is set, go on
		    	if ( $_FILES["uploadformstudents"]["tmp_name"]['file'] )
				{
					//load the file into an PHP object
					$objPHPExcel = \PHPExcel_IOFactory::load($filename);

					//get the data from the active sheet
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

					//set the data for the table in the template
					$data = $sheetData;

					//loop over all the data in the form
					foreach ($sheetData as $value) {
						
						//set place to default
						$place="";

						//set profile to default
						$profile="";

						//set grade to default
						$grade="";

						
						try {

							//if the place not exists, create it and add also the dependency's
							$place= $app['db.places']->getSchool($value['D'],$Ciudad);
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

							//if the place not exists, create it and add also the dependency's
							$schoolid = $place;
							if(!empty($value['F'])){
								$name = ($value['F'] == 'm')? 'Turno Mañana': (($value['F'] == 't')? 'Turno Tarde': 'Turno Completo');
								$place= $app['db.places']->getTimeOfPlace($schoolid, $name);
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
									$place= $app['db.places']->getTimeOfPlace($schoolid, $name);
								}
								$turnoid = $place;
							}
							
							//Get the laptop id
							if(!empty($value['H'])){
								$laptopid = $app['db.laptops']->GetLaptopId($value['H']);
							}

							//get the place type id
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

								//if the place not exists, create it and add also the dependency's
								$place= $app['db.places']->getgradeOfPlace($turnoid, $name);
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
									$place= $app['db.places']->getgradeOfPlace($turnoid, $name);
								}
								$gradoid = $place;
							}

							//get the seccion of the place
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

								//if the place not exists, create it and add also the dependency's
								$place= $app['db.places']->getSeccionOfPlace($gradoid, $name);
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
									$place= $app['db.places']->getSeccionOfPlace($gradoid, $name);
								}
							}
						} catch (Exception $e) {
							var_dump($e);
						}

						//if place exists
						if(ctype_digit($place)){

							//generate a barcode for the user
							$barcode = 0;
							do {
							    $barcode = rand(1000000000, 9999999999);
							    $controlbarcode = $app['db.people']->findbarcode($barcode);
							} while (!empty($controlbarcode));
							
							//add the user to the database
							$object = array('created_at' => date("Y/m/d"), 'name' => $value['A'],'lastname' => $value['B'], 'school_name'=> $value['D'], 'barcode'=>$barcode);
							try {

								//add the person to the db
								$app['db.people']->insert($object);

								//get the last added person
								$person_id = $app['db.people']->Lastadded();

								//if laptop id is not nukk
								if(!empty($laptopid)){

									//if laptopid is a number
									if(ctype_digit($laptopid)){

										//change the latop owner id
										$app['db.laptops']->updatelaptopbyID($laptopid, $person_id);
									}
								}

								//create a new perform object
								$perform = array('person_id' => $person_id, 'place_id' => $place, 'profile_id' => 7);

								//add the perform to the database
								$app['db.performs']->insert($perform);

								//set the text, studentes added
								$error = "estudiantes agregan";
							} catch (Exception $e) {

								//set the text, server down...
								$error =  "Servidor colapsado, intente más tarde.";
							}	
						}
						else{
							var_dump($place);
							var_dump($value);

							//big error, there is an error in the code, fix this if this happens!
							$error ='The grade doesnt fit the school.';
						}	
					}

					//set the value to 1, this means wich table the twig will use
					$value = 1;

					
				}
		    }
		    else{

		    	//the ulploaded file was not xlsx
		        $error = 'Archivo no válido, ingrese un archivo XLSX';
		    }
		}

		//if the uploadform is the teacher form
		if ($uploadformteachers->isValid()) {

			//get the data
			$data= $uploadformteachers->getData();

			//get the location details
			$CiudadID =$data['CiudadID'];
			$DepartamentoID=$data['DepartamentoID'];
			$PaisID=$data['PaisID'];
			$placename = $data['PaisID'] . ' > '. $data['DepartamentoID'] .  ' > '. $data['CiudadID'];
			$Ciudad = $app['db.places']->getPlace($CiudadID, 3);
			$Departamento = $app['db.places']->getPlace($DepartamentoID, 2);
			$Pais = $app['db.places']->getPlace($PaisID, 1);
			$laptopid ='';

			//get the details of the file
			$filename=$_FILES["uploadformteachers"]["tmp_name"]["file"];
			$extension=$_FILES["uploadformteachers"]["name"]["file"];

			//if the file is xlsx, go wild
			if(strpos(substr($extension,-4),'lsx') || strpos(substr($extension,-4),'xlsx'))
		    {
		    	//if the file exists
		    	if ( $_FILES["uploadformteachers"]["tmp_name"]['file'] )
				{
					//load the file
					$objPHPExcel = \PHPExcel_IOFactory::load($filename);

					//get the active sheet
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

					//set the sheetdata into data
					$data = $sheetData;

					//loop over the data
					foreach ($sheetData as $value) {
						$place="";
						$profile="";
						$grade="";

						//get the school, if its not exists, create it and also create the necessary dependency's
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

						//if the place exists and its an int go and create all the teachers
						if(ctype_digit(intval($place))){

							//generate a barcode for the user
							$barcode = 0;
							do {
							    $barcode = rand(1000000000, 9999999999);
							    $barcodecontroll = $app['db.people']->findbarcode($barcode);
							} while (!empty($barcodecontroll));
							
							//create the person object
							$object = array('created_at' => date("Y/m/d"), 'name' => $value['A'],'lastname' => $value['B'], 'school_name'=> $value['D'], 'barcode' => $barcode);
							try {

								//insert the person into the databse
								$app['db.people']->insert($object);

								//get the last added person
								$person_id = $app['db.people']->Lastadded();

								//if the laptopid is not null, assign the laptop to the user
								if(!empty($laptopid)){
									if(ctype_digit($laptopid)){

										//assign the laptop to the correct user
										$app['db.laptops']->updatelaptopbyID($laptopid, $person_id);
									}
								}

								//create the correct perform
								$perform = array('person_id' => $person_id, 'place_id' => $place, 'profile_id' => 5);

								//add the perform to the database
								$app['db.performs']->insert($perform);

								//set the text, teachers added
								$error = "Profesor agregan";
							} catch (Exception $e) {

								//server down
								$error =  "Servidor colapsado, intente más tarde.";
							}	
						}
						else{

							//if this error show, there is an error in the code, fix this bug.
							$error ='The grade doesnt fit the school.';
						}	
					}
				}

				//set the value to 2, this means twig nows that he has to use the teacher form.
				$value = 2;
		    }
		    else{

		    	//the used file is not an xlsx file plz change
		        $error = 'Archivo no válido, ingrese un archivo XLSX';
		    }
		}

		//if the used form is the laptopform, go wild on the next code
		if ($uploadformlaptops->isValid()) {

			//get the correct data
			$data= $uploadformlaptops->getData();

			//get the details from the uploaded file
			$filename=$_FILES["uploadformlaptops"]["tmp_name"]["file"];
			$extension=$_FILES["uploadformlaptops"]["name"]["file"];

			//if the extensions are correct go on	
			if(strpos(substr($extension,-4),'lsx') || strpos(substr($extension,-4),'xlsx'))
		    {
		    	//if the file exists, go further
		    	if ( $_FILES["uploadformlaptops"]["tmp_name"]['file'] )
				{

					//load the excel file into an object
					$objPHPExcel = \PHPExcel_IOFactory::load($filename);

					//get the data from the active sheet
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$data = array();

					//loop over the data
					foreach ($sheetData as $value) {
						$place="";
						$profile="";
						$grade="";
						try {

							//check if the laptop already exists
							$controle = $app['db.laptops']->checkIfLaptopAlreadyExists($value['A'], $value['B']);

							//get the model
							$model = $app['db.models']->getModel($value['C']);

							//check if the data is valid, if so add the laptop to the database
							if($controle == 0 && !empty($model) && strlen($value['A']) == 11 && strlen($value['B']) == 36){

								//create the laptop object
								$laptop = array('serial_number' => $value['A'], 'uuid' => $value['B'], 'model_id' => $model, 'owner_id' => 5);

								//insert the laptop into the datbase
								$app['db.laptops']->insert($laptop);

								//get the id of the laptop
								$laptopID = $app['db.laptops']->FindnewestId();

								//create the movement
								$movement = array('created_at' => date("Y-m-d"),'source_person_id' => 5, 'destination_person_id' => 5,'comment' => 'created by uploading excel file', 'movement_type_id'=> 11 ,'laptop_id'=>$laptopID);
								
								//insert the movement into the datbase
								$app['db.movements']->insert($movement);

								//set the error to laptop added
								$error ="Laptop agregan";
							}

							//errors if something is wrong
							else if($controle != 0) {
								$error ="Laptop ya existe";
							}
							else if(empty($model)) {
								$error ="Versión no existe";
							}
							else if(strlen($value['A']) != 11) {
								$error ="Longitud de serie es incorrecto";
							}
							else if(strlen($value['B']) != 36) {
								$error ="Longitud de UUID es incorrecta";
							}
							else{
								$error ="Error del Servidor";
							}
							$value['D'] = $error;
							$error ='';

							//push the data to the array $data
							array_push($data, $value);
						}
						catch (Exception $e) {
								$error =  "Servidor colapsado, intente más tarde.";
						}	
					}	
				}
				$value = 3;
	    	}
		    else{

		    	//the file is not an excel file
		        $error = 'Archivo no válido, ingrese un archivo XLSX';
		    }
		}

		//if the form is a form for schools 
		if ($uploadformescuelas->isValid()) {

			//get the data from the form
			$data= $uploadformescuelas->getData();

			//get the data from the uploaded file
			$filename=$_FILES["uploadformescuelas"]["tmp_name"]["file"];
			$extension=$_FILES["uploadformescuelas"]["name"]["file"];	

			//if the file is an excel file go further
			if(strpos(substr($extension,-4),'lsx') || strpos(substr($extension,-4),'xlsx'))
		    {
		    	//if the file exists, go on
		    	if ( $_FILES["uploadformescuelas"]["tmp_name"]['file'] )
				{

					//load the excel file
					$objPHPExcel = \PHPExcel_IOFactory::load($filename);
					$placeID=0;
					$schoolID='';	
					$data = array();

					//get the number of sheets
					for($i=0;$i<($objPHPExcel->getSheetCount());$i++){

						//set the active sheet
						$objPHPExcel->setActiveSheetIndex($i);

						//get the data from the sheet
						$sheetData = $objPHPExcel->getActiveSheet()->rangeToArray('A1:R100');

						//get the title of the sheet
						$sheetName = $objPHPExcel->getActiveSheet()->getTitle();
						$schoolname='';

						//get the global data
						if($sheetName == 'Cierre Global'){
							$places = explode(" : ", $sheetData[1][1]);
							$departmentid = $app['db.places']->getDepartmentByName($places[0]);
							$cityID = $app['db.places']->getitemByNameandAncestorID($places[1], $departmentid);
							$schoolID = $app['db.places']->getitemByNameandAncestorID($places[2], $cityID);
							$schoolname = $places[2];
							$placeID=$schoolID;

							//if the school not exists, create it and add it to the database + dependencies
							if(empty($schoolID)){
								$school = array('created_at' => date("Y-m-d"),'name' => $places[2],'place_id' => $cityID,'place_type_id' => 4);
								$app['db.places']->insert($school);
								$schoolid = $app['db.places']->Lastadded();
								$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($cityID);
								foreach ($Ancestors as $waarde) {
								 	$dependency = array('descendant_id' => $schoolid, 'ancestor_id' => $waarde['ancestor_id']);
									$app['db.places_dependencies']->insert($dependency);
								}
								$dependency = array('descendant_id' => $schoolid, 'ancestor_id' => $schoolid);
								$app['db.places_dependencies']->insert($dependency);
								$placeID= $app['db.places']->getSchool($places[2],$cityID);
							}
						}

						//if the sheet is the teachers page, add the teachers to the database 
						else if($sheetName == 'Docente'){

							//loop over the sheetdata
							foreach ($sheetData as $value) {
								$remark='';

								//if the values are not empty go on and check the teacher if there are changes
								if(is_numeric($value[0]) && !empty($value[6])){
									if(empty($value[3])){

										//get the person from the databse
										$person = $app['db.people']->getPerson($value[6]);
										if(empty($person)){

											//generate a barcode for the user
											$barcode = 0;
											do {
											    $barcode = rand(1000000000, 9999999999);
											    $testcontrole =$app['db.people']->findbarcode($barcode); 
											} while (!empty($testcontrole));
											
											//get the name of the teacher
											$names = explode(" ", $value[6]);
											$firstname = '';
											$lastname ='';
											switch(count($names)){
												case 2:
											        $firstname = $names[0];
											        $lastname = $names[1];
											        break;
											    case 3:
											        $firstname = $names[0];
											        $lastname = $names[1] . ' ' . $names[2];
											        break;
											    case 4:
											        $firstname = $names[0] . ' ' . $names[1];
											        $lastname = $names[2] . ' ' . $names[3];
											        break;
											    default:
											        $firstname = $names[0] . ' ' . $names[1];
											        $lastname = $names[2] . ' ' . $names[3] . ' ' . $names[4];
											        break;
											}
											
											//create the teacher object
											$object = array('created_at' => date("Y/m/d"), 'name' => $firstname,'lastname' => $lastname, 'school_name'=>$schoolname, 'barcode' => $barcode);
											
											//insert the teacher to the db
											$app['db.people']->insert($object);

											//get the id off the last add person
											$person = $app['db.people']->Lastadded();

											//create the perform
											$perform = array('person_id' => $person, 'place_id' => $placeID, 'profile_id' => 5);

											//insert the perform
											$app['db.performs']->insert($perform);

											//set the remark person added
											$remark =";persona agregada";
										}

										//has the teacher a laptop in the excel file
										if(!empty($value['7'])){

											//get the laptop id from the person if its already assigned
											$laptopid = $app['db.laptops']->FindLaptopbySerialandOwner($value['7'], $person);
											if(empty($laptopid)){

												//get the laptop id
												$laptopid = $app['db.laptops']->GetLaptopId($value['7']);
												if(!empty($laptopid)){
													if(ctype_digit($laptopid)){

														//get the old owner
														$owner = $app['db.laptops']->GetownerbyId($value['7']);

														//change the laptop owner
														$app['db.laptops']->updatelaptopbyID($laptopid, $person);

														//create the movement
														$movement = array('created_at' => date("Y-m-d"),'source_person_id' => $owner, 'destination_person_id' => $person,'comment' => 'excel_movement by:'.$username, 'movement_type_id'=> 11 ,'laptop_id'=>$laptopid);
														
														//insert the movement into the db
														$app['db.movements']->insert($movement);

														//add the remart
														$remark .=";Laptop seral no existe";
													}
												}
												else{
													//add the remark, the laptop does noet exists
													$remark .=";Laptop seral no existe";
												}
											}
										}
										//check if user has a laptop, if so: reasign it to the fzt
										else{

											//does the user ahs a laptop??
											$laptopid = $app['db.laptops']->FindLaptopbySerialandOwner($value['7'],$person);
											if(!empty($laptopid)){
												//user ahs a laptop, reasign it to the FZT office
												$app['db.laptops']->updatelaptopbySerial($value['7'], 5);

												//add movement
												$movement = array('created_at' => date("Y-m-d"),'source_person_id' => $person, 'destination_person_id' => 5,'comment' => 'excel_movement by:'.$username, 'movement_type_id'=> 11 ,'laptop_id'=>$laptopid);
												
												//insert the movement
												$app['db.movements']->insert($movement);

												//add remark
												$remark .= ';laptop eliminar' ;
											}
										}

										//if the remark is empty, set it to 'no changes'
										if($remark==''){
											$remark= 'sin cambios';
										}

										//create a field with all the data
										$value = array('A' => $value[6],'B' => $value['7'],'C' => 'Teacher','D' => '','E' => '','F' => '', 'G'=>$remark);

										//add the data to the array data
										array_push($data, $value);
									}

									//delete the teacher
									else{

										//get the person from the database
										$person = $app['db.people']->getPerson($value[6]);
										if(!empty($person)){

											//change the owner of the laptop to FZT
											$app['db.laptops']->changeOwnerToFZT($person);

											//delete all his movements
											$app['db.movements']->deleteperson($person);

											//delete the person from performs
											$app['db.performs']->deleteperson($person);

											//delete the person from the table people
											$app['db.people']->deleteperson($person);

											//set the remark, person deleted
											$remark= 'person eliminar';
										}
										else{

											//person alread deleted
											$remark= "person not found";
										}

										//add the data to the arrayy
										$value = array('A' => $value[6],'B' => $value['7'],'C' => 'Student','D' => $places[0],'E' => $places[1],'F' => $places[2], 'G'=>$remark);
										array_push($data, $value);
									}
								}
							}
						}

						//if the sheetname has a length of 5, this means the sheet is a class
						else if(strlen($sheetName)==5){
							$places = explode(" ", $sheetName);
							$name = ($places[0] == 'M')? 'Turno Mañana': (($places[0] == 'T')? 'Turno Tarde': 'Turno Completo');
							$place= $app['db.places']->getitemByNameandAncestorID($name, $placeID);
							$turnoid = $place;

							//if the place does not exists, add it to the db and also the dependencies
							if(empty($place)){
								$turno = array('created_at' => date("Y-m-d"),'name' => $name,'place_id' => $placeID,'place_type_id' => 12);
								$app['db.places']->insert($turno);
								$turnoid = $app['db.places']->Lastadded();
								$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($placeID);
								foreach ($Ancestors as $waarde) {
								 	$dependency = array('descendant_id' => $turnoid, 'ancestor_id' => $waarde['ancestor_id']);
									$app['db.places_dependencies']->insert($dependency);
								}
								$dependency =  array('descendant_id' => $turnoid, 'ancestor_id' => $turnoid);
								$app['db.places_dependencies']->insert($dependency);

								$place= $app['db.places']->getitemByNameandAncestorID($name, $placeID);
							}
							$place_type_id='';
							switch ($places[1]) {
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

							//if the place does not exists, add it to the db and also the dependencies
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
							switch ($places[2]) {
							    case 'A':	
							        $name = 'Seccion A';
							        break;
							    case 'B':
							        $name = 'Seccion B';
							        break;
							    case 'C':
							        $name = 'Seccion C';
							        break;
							    case 'D':
							        $name = 'Seccion D';
							        break;
							}
							$place= $app['db.places']->getSeccionOfPlace($place, $name);

							//if the place does not exists, add it to the db and also the dependencies
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

							//loop over the students
							foreach ($sheetData as $value) {
								$remark ="";
								if(is_numeric($value[0]) && !empty($value[6])){

									//if value 3 is empty it means he is not getting deleted
									if(empty($value[3])){

										//get the person
										$person = $app['db.people']->getPerson($value[6]);
										if(empty($person)){

											//generate a barcode for the user
											$barcode = 0;
											do {
											    $barcode = rand(1000000000, 9999999999);
											    $testbarcode = $app['db.people']->findbarcode($barcode);
											} while (!empty($testbarcode));
											
											$names = explode(" ", $value[6]);
											$firstname = '';
											$lastname ='';

											//set the name of the student
											switch(count($names)){
												case 2:
											        $firstname = $names[0];
											        $lastname = $names[1];
											        break;
											    case 3:
											        $firstname = $names[0];
											        $lastname = $names[1] . ' ' . $names[2];
											        break;
											    case 4:
											        $firstname = $names[0] . ' ' . $names[1];
											        $lastname = $names[2] . ' ' . $names[3];
											        break;
											    case 4:
											    	$firstname = $names[0] . ' ' . $names[1];
											        $lastname = $names[2] . ' ' . $names[3]. ' ' . $names[4];
											        break;
											    default:
											        $firstname = $names[0] . ' ' . $names[1];
											        $lastname = $names[2] . ' ' . $names[3] . ' ' . $names[4]. ' ' . $names[5];
											        break;
											}
											
											//add the user to the db
											$object = array('created_at' => date("Y/m/d"), 'name' => $firstname,'lastname' => $lastname, 'school_name'=>$schoolname, 'barcode' => $barcode);
											$app['db.people']->insert($object);
											$person = $app['db.people']->Lastadded();
											$perform = array('person_id' => $person, 'place_id' => $place, 'profile_id' => 7);
											$app['db.performs']->insert($perform);
											$remark .= "user added";
										}
										//check if user has a (new) laptop assigned
										if(!empty($value['7'])){

											//check if laptop is assigned to owner
											$laptopid = $app['db.laptops']->FindLaptopbySerialandOwner($value['7'], $person);
											if(empty($laptopid)){

												//check if laptop exists
												$laptopid = $app['db.laptops']->GetLaptopId($value['7']);
												if(!empty($laptopid)){
													if(ctype_digit($laptopid)){

														//get the old owner
														$owner = $app['db.laptops']->GetownerbyId($value['7']);

														//change the laptop owner to the new one
														$app['db.laptops']->updatelaptopbyID($laptopid, $person);

														//add the movemnt to the database
														$movement = array('created_at' => date("Y-m-d"),'source_person_id' => $owner, 'destination_person_id' => $person,'comment' => 'excel_movement by:'.$username, 'movement_type_id'=> 11 ,'laptop_id'=>$laptopid);
														$app['db.movements']->insert($movement);

														//add remark
														$remark .=";laptop asignado";
													}
												}
												else{
													$remark .=";laptop serial no existe";
												}
											}
										}

										//check if user has a laptop, if so: reasign it to the fzt
										else{

											//does the user ahs a laptop??
											$laptopid = $app['db.laptops']->FindLaptopbySerialandOwner($value['7'],$person);
											if(!empty($laptopid)){
												//user ahs a laptop, reasign it to the FZT office
												$app['db.laptops']->updatelaptopbySerial($value['7'], 5);

												//add movement
												$movement = array('created_at' => date("Y-m-d"),'source_person_id' => $person, 'destination_person_id' => 5,'comment' => 'excel_movement by:'.$username, 'movement_type_id'=> 11 ,'laptop_id'=>$laptopid);
												$app['db.movements']->insert($movement);
												$remark .= ';laptop eliminar' ;
											}
										}

										//change the class of the use
										if(!empty($value[4])){
											$places = explode(" ", $value[4]);
											$name = ($places[0] == 'M')? 'Turno Mañana': (($places[0] == 'T')? 'Turno Tarde': 'Turno Completo');
											$place= $app['db.places']->getitemByNameandAncestorID($name, $schoolID);
											$turnoid = $place;

											//if the place does not exists, add it to the db and also the dependencies
											if(empty($place)){
												$turno = array('created_at' => date("Y-m-d"),'name' => $name,'place_id' => $schoolID,'place_type_id' => 12);
												$app['db.places']->insert($turno);
												$turnoid = $app['db.places']->Lastadded();
												$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($schoolID);
												foreach ($Ancestors as $waarde) {
												 	$dependency = array('descendant_id' => $turnoid, 'ancestor_id' => $waarde['ancestor_id']);
													$app['db.places_dependencies']->insert($dependency);
												}
												$dependency =  array('descendant_id' => $turnoid, 'ancestor_id' => $turnoid);
												$app['db.places_dependencies']->insert($dependency);

												$place= $app['db.places']->getitemByNameandAncestorID($name, $schoolID);
											}
											$place_type_id='';
											switch ($places[1]) {
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

											//if the place does not exists, add it to the db and also the dependencies
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
											switch ($places[2]) {
											    case 'A':	
											        $name = 'Seccion A';
											        break;
											    case 'B':
											        $name = 'Seccion B';
											        break;
											    case 'C':
											        $name = 'Seccion C';
											        break;
											    case 'D':
											        $name = 'Seccion D';
											        break;
											}
											$place= $app['db.places']->getSeccionOfPlace($place, $name);

											//if the place does not exists, add it to the db and also the dependencies
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
											$person = $app['db.people']->getPerson($value[6]);
											$perform = array('person_id' => $person, 'place_id' => $place, 'profile_id' => 7);
											$app['db.performs']->updatePerform($perform);
											$remark.= '; persona cambiada de clase';
										}
										
										if($remark==''){
											$remark= 'no cambio';
										}

										//add the data to the database
										$value = array('A' => $value[6],'B' => $value['7'],'C' => 'Student','D' => $places[0],'E' => $places[1],'F' => $places[2], 'G'=>$remark);
										array_push($data, $value);
									}

									//delete the person
									else{
										$person = $app['db.people']->getPerson($value[6]);
										if(!empty($person)){
											$app['db.laptops']->changeOwnerToFZT($person);
											$app['db.movements']->deleteperson($person);
											$app['db.performs']->deleteperson($person);
											$app['db.people']->deleteperson($person);
											$remark= 'person eliminar';
										}
										else{
											$remark= "person extraviado";
										}
										$value = array('A' => $value[6],'B' => $value['7'],'C' => 'Student','D' => $places[0],'E' => $places[1],'F' => $places[2], 'G'=>$remark);
										array_push($data, $value);
									}
								}
							}
						}
					}
				}
				$value = 4;
	    	}
		    else{
		        $error = 'Archivo no válido, ingrese un archivo XLSX';
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
			'uploadformescuelas' => $uploadformescuelas->createView(),
			'placename' => $placename,
			'access_level' => $access_level,
			'username' => $username
			//'userLogin' => $userLogin,
		));	
	}
}







	
		
