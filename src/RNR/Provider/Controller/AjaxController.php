<?php

namespace RNR\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\Validator\Constraints as Assert;
$typesort ='';
/**
 * Controller for the authors
 * @author Rein Bauwens	<rein.bauwens@student.odisee.be>
 */
class AjaxController implements ControllerProviderInterface {

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
			->get('/model/', array($this, 'model'))
			->method('GET|POST')
			->bind('Ajax.models');

		$controllers
			->get('/people/', array($this, 'people'))
			->method('GET|POST')
			->bind('Ajax.people');

		$controllers
			->get('/status/', array($this, 'status'))
			->method('GET|POST')
			->bind('Ajax.statuses');

		$controllers
			->get('/places/', array($this, 'places'))
			->method('GET|POST')
			->bind('Ajax.places');

		$controllers
			->get('/placescountries/', array($this, 'placescountries'))
			->method('GET|POST')
			->bind('Ajax.placescountries');

		$controllers
			->get('/placescitys/', array($this, 'placescitys'))
			->method('GET|POST')
			->bind('Ajax.placescitys');

		$controllers
			->get('/placesstates/', array($this, 'placesstates'))
			->method('GET|POST')
			->bind('Ajax.placesstates');

		$controllers
			->get('/placesschools/', array($this, 'placesschools'))
			->method('GET|POST')
			->bind('Ajax.placesschools');

		$controllers
			->get('/placesturnos/', array($this, 'placesturnos'))
			->method('GET|POST')
			->bind('Ajax.placesturnos');

		$controllers
			->get('/placesgrados/', array($this, 'placesgrados'))
			->method('GET|POST')
			->bind('Ajax.placesgrados');

		$controllers
			->get('/placesseccions/', array($this, 'placesseccions'))
			->method('GET|POST')
			->bind('Ajax.placesseccions');

		$controllers
			->get('/profiles/', array($this, 'profiles'))
			->method('GET|POST')
			->bind('Ajax.profiles');

		$controllers
			->get('/grade/', array($this, 'grade'))
			->method('GET|POST')
			->bind('Ajax.grade');

		$controllers
			->get('/place_type/', array($this, 'place_type'))
			->method('GET|POST')
			->bind('Ajax.place_type');

		$controllers
			->get('/ancestor/', array($this, 'ancestor'))
			->method('GET|POST')
			->bind('Ajax.ancestor');

		$controllers
			->get('/getList/', array($this, 'getList'))
			->method('GET|POST')
			->bind('Ajax.getList');

		$controllers
			->get('/addlaptop/', array($this, 'addlaptop'))
			->method('GET|POST')
			->bind('Ajax.addlaptop');

		$controllers
			->get('/editlaptop/', array($this, 'editlaptop'))
			->method('GET|POST')
			->bind('Ajax.editlaptop');

		$controllers
			->get('/deletelaptop/', array($this, 'deletelaptop'))
			->method('GET|POST')
			->bind('Ajax.deletelaptop');

		$controllers
			->get('/getidoflaptop/', array($this, 'getidoflaptop'))
			->method('GET|POST')
			->bind('Ajax.getidoflaptop');

		$controllers
			->get('/addperson/', array($this, 'addperson'))
			->method('GET|POST')
			->bind('Ajax.addperson');

		$controllers
			->get('/editperson/', array($this, 'editperson'))
			->method('GET|POST')
			->bind('Ajax.editperson');

		$controllers
			->get('/deleteperson/', array($this, 'deleteperson'))
			->method('GET|POST')
			->bind('Ajax.deleteperson');

		$controllers
			->get('/getidofperson/', array($this, 'getidofperson'))
			->method('GET|POST')
			->bind('Ajax.getidofperson');

		$controllers
			->get('/addplace/', array($this, 'addplace'))
			->method('GET|POST')
			->bind('Ajax.addplace');

		$controllers
			->get('/editplace/', array($this, 'editplace'))
			->method('GET|POST')
			->bind('Ajax.editplace');

		$controllers
			->get('/deleteplace/', array($this, 'deleteplace'))
			->method('GET|POST')
			->bind('Ajax.deleteplace');

		$controllers
			->get('/getidofplace/', array($this, 'getidofplace'))
			->method('GET|POST')
			->bind('Ajax.getidofplace');

		$controllers
			->get('/massassignment/', array($this, 'massassignment'))
			->method('GET|POST')
			->bind('Ajax.massassignment');

		// Return ControllerCollection
		return $controllers;
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function model(Application $app) {
		
		$data = $app['db.models']->fetchAll();
		echo json_encode($data);
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function people(Application $app) {
		
		$data = $app['db.people']->fetchAll();
		echo json_encode($data);
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function status(Application $app) {
		
		$data = $app['db.statuses']->fetchAll();
		echo json_encode($data);
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function places(Application $app) {
		
		$data = $app['db.places']->fetchAllschools();
		echo json_encode($data);
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function placescountries(Application $app) {
		$data = $app['db.places']->fetchcountry();
		echo json_encode($data);			
		return $app['twig']->render('Ajax/Dump.twig');	
	}


	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function placesstates(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			$id = $app['db.places']->getPlaceByName($obj['name']);
			$data = $app['db.places']->fetchstate($id);
			echo json_encode($data);	
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function placescitys(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			$id = $app['db.places']->getPlaceByName($obj['name']);
			$data = $app['db.places']->fetchCity($id);
			echo json_encode($data);			
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	public function placesschools(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			$cityid = $app['db.places']->getCityByName($obj['Ciudad']);
			$data = $app['db.places']->fetchSchool($cityid);
			echo json_encode($data);			
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	public function placesturnos(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			$cityid = $app['db.places']->getCityByName($obj['Ciudad']);
			$schoolid = $app['db.places']->getitemByNameandAncestorID($obj['name'], $cityid);
			$data = $app['db.places']->fetchTurno($schoolid);
			echo json_encode($data);			
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	public function placesgrados(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);

			$cityid = $app['db.places']->getCityByName($obj['Ciudad']);
			$schoolid = $app['db.places']->getitemByNameandAncestorID($obj['Escuela'], $cityid);
			$turnoId = $app['db.places']->getitemByNameandAncestorID($obj['name'], $schoolid);
			$data = $app['db.places']->fetchGrade($turnoId);
			echo json_encode($data);			
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	public function placesseccions(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			$cityid = $app['db.places']->getCityByName($obj['Ciudad']);
			$schoolid = $app['db.places']->getitemByNameandAncestorID($obj['Escuela'], $cityid);
			$turnoId = $app['db.places']->getitemByNameandAncestorID($obj['Turno'], $schoolid);
			$gradoid = $app['db.places']->getitemByNameandAncestorID($obj['name'], $turnoId);
			$data = $app['db.places']->fetchSeccion($gradoid);
			echo json_encode($data);			
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}


	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function fetchCity(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			$data = $app['db.places']->fetchAll($obj['id']);
			echo json_encode($data);			
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function profiles(Application $app) {
		
		$data = $app['db.profiles']->fetchAll();
		echo json_encode($data);
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function grade(Application $app) {
		$data = $app['db.place_types']->fetchAllgrades();
		echo json_encode($data);
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function place_type(Application $app) {
		$data = $app['db.place_types']->fetchAll();
		echo json_encode($data);
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function ancestor(Application $app) {
		$data = $app['db.places']->fetchAll();
		echo json_encode($data);
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function getList(Application $app) {
		$peoplearray = array();
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			if($obj['formname']=='laptopsForm'){
				$Departamentoid = $app['db.places']->getPlace($obj['Departamento'], 2);
				$cityid = $app['db.places']->getCityByName($obj['Ciudad']);
				$placeid =1;
				$schoolid = $app['db.places']->getitemByNameandAncestorID($obj['Escuela'], $cityid);
				$turnoId = $app['db.places']->getitemByNameandAncestorID($obj['Turno'], $schoolid);
				$gradoid = $app['db.places']->getitemByNameandAncestorID($obj['grado'], $turnoId);
				$Seccionid = $app['db.places']->getitemByNameandAncestorID($obj['Seccion'], $gradoid);	
				$data = array();
				if(!empty($Seccionid)){
					$placeid = $Seccionid;
					array_push($data, array('name' => $obj['Departamento'].' : '. $obj['Ciudad'].' : '. $obj['Escuela'].' : '. $obj['Turno'].' : '. $obj['grado'].' : '. $obj['Seccion'], 'data' => $app['db.laptops']->fetchList($obj,$placeid)));
				}
				else if(!empty($gradoid)){
					$placeid = $gradoid;
					$seccions =  $app['db.places']->fetchSeccion($gradoid);
					foreach ($seccions as $seccion) {
						array_push($data, array('name' => $obj['Departamento'].' : '. $obj['Ciudad'].' : '. $obj['Escuela'].' : '. $obj['Turno'].' : '. $obj['grado'].' : '. $seccion['name'], 'data' => $app['db.laptops']->fetchList($obj,$seccion['id'])));
					}
				}
				else if(!empty($turnoId)){
					$placeid = $turnoId;
					$grades =  $app['db.places']->fetchGrade($turnoId);
					foreach ($grades as $grade) {
						$seccions =  $app['db.places']->fetchSeccion($grade['id']);
						foreach ($seccions as $seccion) {
							array_push($data, array('name' => $obj['Departamento'].' : '. $obj['Ciudad'].' : '. $obj['Escuela'].' : '. $obj['Turno'].' : '. $grade['name'].' : '. $seccion['name'], 'data' => $app['db.laptops']->fetchList($obj,$seccion['id'])));
						}
					}
				}
				else if(!empty($schoolid)){
					$placeid = $schoolid;
					$teachers = $app['db.laptops']->fetchList($obj,$schoolid);
					array_push($data, array('name' => $obj['Departamento'].' : '. $obj['Ciudad'].' : '. $obj['Escuela'], 'data' => $teachers));
					$turnos =  $app['db.places']->fetchTurno($schoolid);
					foreach ($turnos as $turno) {
						$grades =  $app['db.places']->fetchGrade($turno['id']);
						foreach ($grades as $grade) {
							$seccions =  $app['db.places']->fetchSeccion($grade['id']);
							foreach ($seccions as $seccion) {
								array_push($data, array('name' => $obj['Departamento'].' : '. $obj['Ciudad'].' : '. $obj['Escuela'].' : '. $turno['name'].' : '. $grade['name'].' : '. $seccion['name'], 'data' => $app['db.laptops']->fetchList($obj,$seccion['id'])));
							}
						}
					}
				}
				else if(!empty($cityid)){
					$placeid = $cityid;
					$schools =  $app['db.places']->fetchSchool($cityid);
					foreach ($schools as $school) {
						$teachers = $app['db.laptops']->fetchList($obj,$school['id']);
						array_push($data, array('name' =>  $obj['Departamento'].' : '. $obj['Ciudad'].' : '. $school['name'], 'data' => $teachers));
						$turnos =  $app['db.places']->fetchTurno($school['id']);
						foreach ($turnos as $turno) {
							$grades =  $app['db.places']->fetchGrade($turno['id']);
							foreach ($grades as $grade) {
								$seccions =  $app['db.places']->fetchSeccion($grade['id']);
								foreach ($seccions as $seccion) {
									array_push($data, array('name' => $obj['Departamento'].' : '. $obj['Ciudad'].' : '. $school['name'].' : '. $turno['name'].' : '. $grade['name'].' : '. $seccion['name'], 'data' => $app['db.laptops']->fetchList($obj,$seccion['id'])));
								}
							}
						}
					}
				}
				else if(!empty($Departamentoid)){
					$placeid = $Departamentoid;
					$citys =  $app['db.places']->fetchCity($Departamentoid);
					foreach ($citys as $city) {
						$schools =  $app['db.places']->fetchSchool($city['id']);
						foreach ($schools as $school) {
							$teachers = $app['db.laptops']->fetchList($obj,$school['id']);
							array_push($data, array('name' => $obj['Departamento'].' : '. $city['name'].' : '. $school['name'], 'data' => $teachers));
							$turnos =  $app['db.places']->fetchTurno($school['id']);
							foreach ($turnos as $turno) {
								$grades =  $app['db.places']->fetchGrade($turno['id']);
								foreach ($grades as $grade) {
									$seccions =  $app['db.places']->fetchSeccion($grade['id']);
									foreach ($seccions as $seccion) {
										array_push($data, array('name' => $obj['Departamento'].' : '. $city['name'].' : '. $school['name'].' : '. $turno['name'].' : '. $grade['name'].' : '. $seccion['name'], 'data' => $app['db.laptops']->fetchList($obj,$seccion['id'])));
									}
								}
							}
						}
					}
				}
				else{
					$placeid =1;
					$states =  $app['db.places']->fetchstate($placeid);
					foreach ($states as $state) {
						$citys =  $app['db.places']->fetchCity($state['id']);
						foreach ($citys as $city) {
							$schools =  $app['db.places']->fetchSchool($city['id']);
							foreach ($schools as $school) {
								$teachers = $app['db.laptops']->fetchList($obj,$school['id']);
								array_push($data, array('name' => $state['name'].' : '. $city['name'].' : '. $school['name'], 'data' => $teachers));
								$turnos =  $app['db.places']->fetchTurno($school['id']);
								foreach ($turnos as $turno) {
									$grades =  $app['db.places']->fetchGrade($turno['id']);
									foreach ($grades as $grade) {
										$seccions =  $app['db.places']->fetchSeccion($grade['id']);
										foreach ($seccions as $seccion) {
											array_push($data, array('name' => $state['name'].' : '. $city['name'].' : '. $school['name'].' : '. $turno['name'].' : '. $grade['name'].' : '. $seccion['name'], 'data' => $app['db.laptops']->fetchList($obj,$seccion['id'])));
										}
									}
								}
							}
						}
					}
				}									
				echo json_encode($data);
			}
			/*else if($obj['formname']=='peopleForm'){
				$data = $app['db.people']->fetchList2($obj);
				if(!empty($data[0]['placename'])){
					foreach ($data as $person) {
						$datadump= $app['db.performs']->fetchAllByPersonId($person['peopleid']);	
						$datadump = $app['db.places_dependencies']->fetchAllAncestorsFromSchool($datadump[0]['place_id']);
						if(!empty($datadump)){
							foreach ($datadump as $data) {
								$place_type_id=0;
								if(!empty($data)){
									$person['region'] ='';
									if(2==$data['place_type_id']){
										$person['region'] = $data['name'];
									}
									else if(4==$data['place_type_id']){
										$person['school'] = $data['name'];
									}
								}
							}
						}
						else{
							$person['region'] = $person['placename'];
							$person['school'] ='';
						}
						array_push($peoplearray,$person);
						
					}
					echo json_encode($peoplearray);
				}
				else{
				echo json_encode($data);
				}	
			}*/
			else if($obj['formname']=='placesForm'){
				$data = $app['db.places']->fetchList($obj);
				echo json_encode($data);
			}
			
		}
		
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function addlaptop(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				$obj['model_id'] = $app['db.models']->getModel($obj['model_id']);
				$obj['owner_id'] = $app['db.people']->getPerson($obj['owner_id']);
				$obj['status_id'] = $app['db.statuses']->getStatus($obj['status_id']);
			} catch (Exception $e) {
			}
			
			if(ctype_digit($obj['model_id']) && ctype_digit($obj['owner_id']) && ctype_digit($obj['status_id'])){
				$value = $app['db.laptops']->checkIfLaptopAlreadyExists($obj['serial_number'],$obj['uuid']);
				if($value==0){
					$obj['last_activation_date'] = null;
					try {
						$app['db.laptops']->insert($obj);
						echo "laptop added";
					} catch (Exception $e) {
						echo "server down, try again later";
					}
				}
				else if($value==1){
					echo "serial and uuid already in use";
				}
				else if($value==2){
					echo "uuid already in use";
				}
				else{
					echo "serial already in use";
				}		
				
			}
			else{
				echo 'Select a laptop/user/status from the list.';
			}
			
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function editlaptop(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				$obj['model_id'] = $app['db.models']->getModel($obj['model_id']);
				$obj['owner_id'] = $app['db.people']->getPerson($obj['owner_id']);
				$obj['status_id'] = $app['db.statuses']->getStatus($obj['status_id']);
			} catch (Exception $e) {
			}
			
			if(ctype_digit($obj['model_id']) && ctype_digit($obj['owner_id']) && ctype_digit($obj['status_id'])){
				try {
					$app['db.laptops']->updateLaptop($obj);
					echo "laptop edited";
				} catch (Exception $e) {
					echo "server down, try again later";
				}
			}
			else{
				echo 'Select a laptop/user/status from the list.';
			}
			
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function deletelaptop(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				echo $app['db.laptops']->deletelaptop($obj['id']);
				echo "laptop deleted";
			} catch (Exception $e) {
				echo "laptop already deleted";
			}
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function getidoflaptop(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				echo $app['db.laptops']->FindLaptopId($obj);
			} catch (Exception $e) {
				echo "Not found";
			}
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function addperson(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			$place="";
			$profile="";
			$gradoid=0;
			$turnoId=0;
			$Seccionid=0;
			
			$barcodeControl = $app['db.people']->FindPeopleByBarcodeId($obj['barcode']);
			if(!empty($barcodeControl)){
				echo "Barcode is not unique";
			}
			else{
				if($obj['profiles'] == 'Estudiante'){
					$place= $app['db.places']->getPlaceonlyoneName($obj['Departamento']);
					$place= $app['db.places']->getitemByNameandAncestorID($obj['Ciudad'], $place[0]['id']);
					$place= $app['db.places']->getitemByNameandAncestorID($obj['Escuela'], $place);
					$turnoId = $app['db.places']->getitemByNameandAncestorID($obj['Turno'], $place);
					
					if(empty($turnoId)){
						$turno = array('created_at' => date("Y-m-d"),'name' => $obj['Turno'], 'place_id' => $place,'place_type_id' => 12);
						$app['db.places']->insert($turno);
						$turnoid = $app['db.places']->Lastadded();
						$dependency = array('descendant_id' => $turnoid, 'ancestor_id' => $place);
						$app['db.places_dependencies']->insert($dependency);
						$turnoId = $app['db.places']->getitemByNameandAncestorID($obj['Turno'], $place);
					}
					
					
					$gradoid = $app['db.places']->getitemByNameandAncestorID($obj['grade'], $turnoId);
					if(empty($gradoid)){
						$place_type_id='';
						switch ($obj['grade']) {
						    case 'Primer Grado':
						        $place_type_id=5;
						        break;
						    case 'Segundo Grado':
						        $place_type_id=6;
						        break;
						    case 'Tercer Grado':
						        $place_type_id=7;
						        break;
						    case 'Cuarto Grado':
						        $place_type_id=8;
						        break;
						    case 'Quinto Grado':
						        $place_type_id=9;
						        break;
						    case 'Sexto Grado':
						        $place_type_id=10;
						        break;
						    case 'Septimo grado':
						        $place_type_id=16;
						        break;
						    case 'Octavo grado':
						        $place_type_id=17;
						        break;
						    case 'Noveno grado':
						        $place_type_id=18;
						        break;
						    case 'Preescolar': 
						        $place_type_id=14;
						        break;
						    case 'Educacion Especial':
						        $place_type_id=13;
						        break;
						    }
						$grado = array('created_at' => date("Y-m-d"),'name' => $obj['grade'],'place_id' => $turnoId,'place_type_id' => $place_type_id);
						$app['db.places']->insert($grado);
						$gradoid = $app['db.places']->Lastadded();

						$dependency = array('descendant_id' => $gradoid, 'ancestor_id' => $turnoId);
						$app['db.places_dependencies']->insert($dependency);
						$gradoid = $app['db.places']->getitemByNameandAncestorID($obj['grade'], $turnoId);
					}

					
					$Seccionid = $app['db.places']->getitemByNameandAncestorID($obj['Seccion'], $gradoid);
					if(empty($Seccionid)){
						$seccion = array('created_at' => date("Y-m-d"),'name' => $obj['Seccion'],'place_id' => $gradoid,'place_type_id' => 11);
						$app['db.places']->insert($seccion);
						$seccionid = $app['db.places']->Lastadded();

						$dependency = array('descendant_id' => $Seccionid, 'ancestor_id' => $gradoid);
						$app['db.places_dependencies']->insert($dependency);
						$Seccionid = $app['db.places']->getitemByNameandAncestorID($obj['Seccion'], $gradoid);
					}
					
					if(ctype_digit($Seccionid)){
						unset($obj['Departamento']);
						unset($obj['Ciudad']);
						$obj['school_name']= $obj['Escuela'];
						unset($obj['Escuela']);
						unset($obj['profiles']);
						unset($obj['grade']);
						unset($obj['Turno']);
						unset($obj['Seccion']);
						$app['db.people']->insert($obj);
						$person_id = $app['db.people']->FindPeopleId($obj);
						$perform = array('person_id' => $person_id, 'place_id' => $Seccionid, 'profile_id' => 7);
						$app['db.performs']->insert($perform);
						echo "Student added";
					}
					else{
						echo 'profile or place does not exist.';
					}

				}
				else{
					$place= $app['db.places']->getPlaceonlyoneName($obj['Departamento']);
					if($obj['Ciudad']!=''){
						$place= $app['db.places']->getitemByNameandAncestorID($obj['Ciudad'], $place[0]['id']);
						if($obj['Escuela']!=''){
							$place= $app['db.places']->getitemByNameandAncestorID($obj['Escuela'], $place);
						}
					}
					else{
						$place = $place[0]['id'];
					}
					$profile = $app['db.profiles']->getProfile($obj['profiles']);

					if(ctype_digit($place) && ctype_digit($profile)){
						unset($obj['Departamento']);
						unset($obj['Ciudad']);
						$obj['school_name']= $obj['Escuela'];
						unset($obj['Escuela']);
						unset($obj['profiles']);
						unset($obj['grade']);
						unset($obj['Turno']);
						unset($obj['Seccion']);
						$app['db.people']->insert($obj);
						$person_id = $app['db.people']->FindPeopleId($obj);
						$perform = array('person_id' => $person_id, 'place_id' => $place, 'profile_id' => $profile);
						$app['db.performs']->insert($perform);
						echo "person added";
					}
					else{
						echo 'profile or place does not exist.';
					}
				}
			}
			

			/*if($place[0]['place_type_id']==4){
				$turnoId = $app['db.places']->getitemByNameandAncestorID($obj['Turno'], $place[0]['id']);
				$gradoid = $app['db.places']->getitemByNameandAncestorID($obj['grade'], $turnoId);
				$Seccionid = $app['db.places']->getitemByNameandAncestorID($obj['Seccion'], $gradoid);
			}
			if($Seccionid !=0){
				$placeid = $Seccionid;
			}
			else if($gradoid !=0){
				$placeid = $gradoid;
			}
			else if($turnoId !=0){
				$placeid = $turnoId;
			}
			else{
				$placeid = $place[0]['id'];
			}
			
			
			if(ctype_digit($placeid) && ctype_digit($profile)){
				unset($obj['places']);
				unset($obj['profiles']);
				unset($obj['grade']);
				unset($obj['Turno']);
				unset($obj['Seccion']);
				try {
					$app['db.people']->insert($obj);
					$person_id = $app['db.people']->FindPeopleId($obj);
					$perform = array('person_id' => $person_id, 'place_id' => $placeid, 'profile_id' => $profile);
					$app['db.performs']->insert($perform);
					echo "person added";
				} catch (Exception $e) {
					echo "server down, try again later";
				}	
			}
			else{
				echo 'The grade doesnt fit the school.';
			}*/
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function editperson(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			$place="";
			$profile="";
			$gradoid=0;
			$turnoId=0;
			$Seccionid=0;
			$place= $app['db.places']->getPlaceonlyoneName($obj['places']);
			if($place[0]['place_type_id']==4){
				$turnoId = $app['db.places']->getitemByNameandAncestorID($obj['Turno'], $place[0]['id']);
				$gradoid = $app['db.places']->getitemByNameandAncestorID($obj['grade'], $turnoId);
				$Seccionid = $app['db.places']->getitemByNameandAncestorID($obj['Seccion'], $gradoid);

			}
			if($Seccionid !=0){
				$placeid = $Seccionid;
			}
			else if($gradoid !=0){
				$placeid = $gradoid;
			}
			else if($turnoId !=0){
				$placeid = $turnoId;
			}
			else{
				$placeid = $place[0]['id'];
			}
			$profile = $app['db.profiles']->getProfile($obj['profiles']);

			var_dump($turnoId);
			var_dump($gradoid);
			var_dump($Seccionid);

			if(ctype_digit($placeid) && ctype_digit($profile)){
				unset($obj['places']);
				unset($obj['profiles']);
				unset($obj['grade']);
				unset($obj['Turno']);
				unset($obj['Seccion']);
				try {
					$app['db.people']->updatePerson($obj);
					$perform = array('person_id' => $obj['id'], 'place_id' => $placeid, 'profile_id' => $profile);
					$app['db.performs']->updatePerform($perform);
					echo "laptop edited";
				} catch (Exception $e) {
					echo "server down, try again later";
				}
			}
			else if(empty($obj['name'])){
				try {
					$app['db.people']->updatesmallPerson($obj);
					$perform = array('person_id' => $obj['id'], 'place_id' => $placeid, 'profile_id' => $profile);
					$app['db.performs']->updatePerform($perform);
					echo "laptop edited";
				} catch (Exception $e) {
					echo "server down, try again later";
				}

			}
			else{
				echo 'The grade doesnt fit the school.';
			}
			
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function deleteperson(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				var_dump('ffff');
				$app['db.laptops']->changeOwnerToFZT($obj['id']);
				$app['db.movements']->deleteperson($obj['id']);
				$app['db.performs']->deleteperson($obj['id']);
				$app['db.people']->deleteperson($obj['id']);

				echo "person deleted";
			} catch (Exception $e) {
				echo "person already deleted";
			}
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function getidofperson(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				echo $app['db.people']->FindPeopleId($obj);
			} catch (Exception $e) {
				echo "Not found";
			}
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function addplace(Application $app) {

		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			$place_type="";
			$ancestor="";
			try {
				$grade = $app['db.place_types']->getGrade($obj['place_type']);
				$place= $app['db.places']->getPlaceByName($obj['ancestor']) ;

			} catch (Exception $e) {
			}
			if(ctype_digit($grade) && ctype_digit($place)){
				$insertplace = array('created_at' => $obj['created_at'], 'name' => $obj['name'], 'place_id' => $place,'place_type_id' => $grade);
				try {
					$app['db.places']->insert($insertplace);
					$place_id = $app['db.places']->FindnewestId();
					$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($place);
					foreach ($Ancestors as $waarde) {
					 	$dependency = array('descendant_id' => $place_id, 'ancestor_id' => $waarde['ancestor_id']);
						$app['db.places_dependencies']->insert($dependency);
					}
					$dependency = array('descendant_id' => $place_id, 'ancestor_id' => $place_id);
					$app['db.places_dependencies']->insert($dependency); 
					if(!empty($obj['server_hostname'])){
						$schoolinfo = array('lease_duration' => null, 'server_hostname' => $obj['server_hostname'], 'place_id' => $place_id);
						$app['db.school_infos']->insert($schoolinfo);

					}
					echo "place added";
				} catch (Exception $e) {
					echo "server down, try again later";
				}	
			}
			else{
				echo 'select a type and an ancestor.';
			}
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function editplace(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			$place_type="";
			$ancestor="";
			if(!empty($obj['ancestor']) && !empty($obj['id'])){
				try {
					$grade = $app['db.place_types']->getGrade($obj['place_type']);
					$place= $app['db.places']->getPlaceByName($obj['ancestor']) ;
				} catch (Exception $e) {
				}
				if(ctype_digit($grade) && ctype_digit($place) && !empty($obj['ancestor'])){
					$insertplace = array('id' => $obj['id'], 'name' => $obj['name'], 'place_id' => $place,'place_type_id' => $grade);
					try {
						$oldplace = $app['db.places']->getPlaceById($obj['id']);
						$app['db.places']->updatePlace($insertplace);
						if($place!=$oldplace[0]['place_id']){
							$app['db.places_dependencies']->DeleteALL($obj['id']);
							$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($place);
							foreach ($Ancestors as $waarde) {
							 	$dependency = array('descendant_id' => $obj['id'], 'ancestor_id' => $waarde['ancestor_id']);
								$app['db.places_dependencies']->insert($dependency);
							}
							$dependency = array('descendant_id' => $obj['id'], 'ancestor_id' => $obj['id']);
							$app['db.places_dependencies']->insert($dependency); 

						} 
						if(!empty($obj['server_hostname'])){
							$schoolinfo = array('lease_duration' => null, 'server_hostname' => $obj['server_hostname'], 'place_id' => $obj['id']);
							$app['db.school_infos']->updateSchool($schoolinfo);
						}
						else if(empty($obj['server_hostname'])){
							$app['db.school_infos']->deleteSchool($obj['id']);
						}
						echo "place updated";
					}catch (Exception $e) {
						echo "server down, try again later";
					}	
				}
			}
			else if(empty($obj['ancestor'])){
				try {
					$grade = $app['db.place_types']->getGrade($obj['place_type']);
					$insertplace = array('id' => $obj['id'],'place_type_id' => $grade);
					$app['db.places']->updateSmallPlace($insertplace);
					echo "place edited";
				} catch (Exception $e) {
					echo "Server down, try again later";
				}

			}
			else{
				echo 'Internal error, reload page';
			}
			
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function deleteplace(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				$app['db.places_dependencies']->DeleteALL($obj['id']);
				$app['db.school_infos']->deleteSchool($obj['id']);
				$app['db.places']->deletePlace($obj['id']);
				echo "place deleted";
			} catch (Exception $e) {
				echo "place was already deleted";
			}
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function getidofplace(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				echo $app['db.places']->FindnewestId();
			} catch (Exception $e) {
				echo "Not found";
			}
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function massassignment(Application $app) {
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				$barcodes = explode(", ", $obj['barcodes']);
				$serials = explode(", ", $obj['serials']);
				for($i =0; $i<count($barcodes);$i++){
					if(strlen($barcodes[$i]) ==10 && strlen($serials[$i]) == 11){
						echo $app['db.laptops']->massassignment($barcodes[$i],$serials[$i]);
					}
				}
			} catch (Exception $e) {
				echo "Not found";
			}
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}
}

/*	function sort_objects_by_school($a, $b) {
	if($a->school == $b->school){ return 0 ; }
	return ($a->school < $b->school) ? -1 : 1;
	}

	function sort_objects_by_region($a, $b) {
		if($a->region == $b->region){ return 0 ; }
		return ($a->region < $b->region) ? -1 : 1;
	}*/