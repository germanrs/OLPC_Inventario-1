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
			->get('/getdataforplacestable/', array($this, 'getdataforplacestable'))
			->method('GET|POST')
			->bind('Ajax.getdataforplacestable');

		$controllers
			->get('/getdataforuserstable/', array($this, 'getdataforuserstable'))
			->method('GET|POST')
			->bind('Ajax.getdataforuserstable');


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
			->get('/getuserbyid/', array($this, 'getuserbyid'))
			->method('GET|POST')
			->bind('Ajax.getuserbyid');

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

		$controllers
			->get('/getBarcodeList/', array($this, 'getBarcodeList'))
			->method('GET|POST')
			->bind('Ajax.getBarcodeList');

		$controllers
			->get('/getusersdata/', array($this, 'getusersdata'))
			->method('GET|POST')
			->bind('Ajax.getusersdata');

		$controllers
			->get('/getusersinfo/', array($this, 'getusersinfo'))
			->method('GET|POST')
			->bind('Ajax.getusersinfo');

		$controllers
			->get('/deleteUser/', array($this, 'deleteUser'))
			->method('GET|POST')
			->bind('Ajax.deleteUser');

		$controllers
			->get('/persons/', array($this, 'persons'))
			->method('GET|POST')
			->bind('Ajax.persons');

		$controllers
			->get('/addUser/', array($this, 'addUser'))
			->method('GET|POST')
			->bind('Ajax.addUser');

		$controllers
			->get('/editUser/', array($this, 'editUser'))
			->method('GET|POST')
			->bind('Ajax.editUser');

		$controllers
			->get('/validateUser/', array($this, 'validateUser'))
			->method('GET|POST')
			->bind('Ajax.validateUser');

		// Return ControllerCollection
		return $controllers;
	}

	/**
	 * model page
	 * @param Application $app An Application instance
	 * @return All posible laptop models
	 */
	public function model(Application $app) {

		// check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
		

		//fetch all models from the db
		$data = $app['db.models']->fetchAll();

		//dump the models in the twig file
		echo json_encode($data);

		//render the twig file
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function people(Application $app) {
		// check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
		
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
		
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
		
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
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
					array_push($data, array('name' => $obj['Departamento'].' : '. $obj['Ciudad'].' : '. $obj['Escuela'].' : teachers', 'data' => $teachers));
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
						array_push($data, array('name' =>  $obj['Departamento'].' : '. $obj['Ciudad'].' : '. $school['name'].' : teachers', 'data' => $teachers));
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
							array_push($data, array('name' => $obj['Departamento'].' : '. $city['name'].' : '. $school['name'].' : teachers', 'data' => $teachers));
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
			else if($obj['formname']=='placesForm'){
				$data = $app['db.places']->fetchList($obj);
				echo json_encode($data);
			}
			
		}
		
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * ajax php file to add a laptop
	 * @param all the params to create a laptop
	 * @return return a statement of the proces
	 */
	public function addlaptop(Application $app) {
        
		// check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}


		if(isset($_POST['action'])){

			//decode the json object
			$obj = json_decode($_POST['action'], true);
			try {

				//get the id of the model
				$obj['model_id'] = $app['db.models']->getModel($obj['model_id']);

				//get the id of the owner
				$obj['owner_id'] = $app['db.people']->getPerson($obj['owner_id']);

				//get the id of the status
				$obj['status_id'] = $app['db.statuses']->getStatus($obj['status_id']);

				//get the id of the assignd user
				$obj['assignee_id'] = $app['db.people']->getPerson($obj['assignee_id']);
			} catch (Exception $e) {
			}
			
			//if the id's does not exist, throw error
			if(ctype_digit($obj['model_id']) && ctype_digit($obj['owner_id']) && ctype_digit($obj['status_id']) && ctype_digit($obj['assignee_id'])){
				
				//check if the laptop already exist.
				$value = $app['db.laptops']->checkIfLaptopAlreadyExists($obj['serial_number'],$obj['uuid']);
				
				//if laptop not exist
				if($value==0){

					//set a parameter to null, it is not used in the db
					$obj['last_activation_date'] = null;
					try {

						//insert the laptop into the db
						$app['db.laptops']->insert($obj);

						//get the id of the newest added laptop
						$laptopID = $app['db.laptops']->FindnewestId();

						//create a movement
						$movement = array('created_at' => date("Y-m-d"),'source_person_id' => $obj['assignee_id'], 'destination_person_id' => $obj['assignee_id'],'comment' => 'Manual created', 'movement_type_id'=> 11 ,'laptop_id'=>$laptopID);
						
						//add the movement to the database
						$app['db.movements']->insert($movement);
						echo "laptop agregan";
					} catch (Exception $e) {
						echo "Servidor colapsado, intente más tarde.";
					}
				}

				//if laptop id and uuid already exists
				else if($value==1){
					echo "uuid de serie y ya está en uso";
				}

				//if uuid already exist
				else if($value==2){
					echo "uuid ya en uso";
				}

				//if laptop id already exist
				else{
					echo "serial ya en uso";
				}		
				
			}

			//the user did not select a laptop/user/state out of the list
			else{
				echo 'Seleccione un laptop/usuario/estado de la lista.';
			}
			
		}

		//render the twig file
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function editlaptop(Application $app) {
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				$obj['model_id'] = $app['db.models']->getModel($obj['model_id']);
				$obj['owner_id'] = $app['db.people']->getPerson($obj['owner_id']);
				$obj['status_id'] = $app['db.statuses']->getStatus($obj['status_id']);
				$obj['assignee_id'] = $app['db.people']->getPerson($obj['assignee_id']);
			} catch (Exception $e) {
			}
			
			if(ctype_digit($obj['model_id']) && ctype_digit($obj['owner_id']) && ctype_digit($obj['status_id']) && ctype_digit($obj['assignee_id'])){
				try {
					$app['db.laptops']->updateLaptop($obj);
					echo "laptop editado";
				} catch (Exception $e) {
					echo "Servidor colapsado, intente más tarde.";
				}
			}
			else{
				echo 'Seleccione un laptop/usuario/estado de la lista.';
			}
			
		}

		//return the rendered twig with the id of the laptop.
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function deletelaptop(Application $app) {
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				echo $app['db.laptops']->deletelaptop($obj['id']);
				echo "laptop eliminado";
			} catch (Exception $e) {
				echo "laptop ya eliminado";
			}
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return returns the id of a laptop
	 */
	public function getidoflaptop(Application $app) {
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		//if the value is set, return the id of the user.
		if(isset($_POST['action'])){

			//decode the json object
			$obj = json_decode($_POST['action'], true);
			try {

				//find the laptop id
				echo $app['db.laptops']->FindLaptopId($obj);

			} catch (Exception $e) {

				//error
				echo "Extraviado";
			}
		}

		//return t
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function addperson(Application $app) {
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			$place="";
			$profile="";
			$gradoid=0;
			$turnoid=0;
			$Seccionid=0;

			//generate a barcode for the user
			$upperbound = 0;
			do {
			    $upperbound = rand(1000000000, 9999999999);
			    $testbarcode =$app['db.people']->findbarcode($upperbound); 
			} while (!empty($testbarcode));
			$obj['barcode'] = $upperbound;
			
			if($obj['profiles'] == 'Estudiante'){
				$place= $app['db.places']->getPlaceonlyoneName($obj['Departamento']);
				$place= $app['db.places']->getitemByNameandAncestorID($obj['Ciudad'], $place[0]['id']);
				$place= $app['db.places']->getitemByNameandAncestorID($obj['Escuela'], $place);
				$turnoid = $app['db.places']->getitemByNameandAncestorID($obj['Turno'], $place);
				if(empty($turnoid)){
					$turno = array('created_at' => date("Y-m-d"),'name' => $obj['Turno'], 'place_id' => $place,'place_type_id' => 12);
					$app['db.places']->insert($turno);
					$turnoid = $app['db.places']->Lastadded();

					//fetch all the ancestors from the place
					$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($place);
					//loop over all the ancestors and add a new dependency for each ancestor to the db with the new descendent
					foreach ($Ancestors as $waarde) {
					 	$dependency = array('descendant_id' => $turnoid, 'ancestor_id' => $waarde['ancestor_id']);
						$app['db.places_dependencies']->insert($dependency);
					}

					//add the dependency from the new item that points to itself.
					$dependency =  array('descendant_id' => $turnoid, 'ancestor_id' => $turnoid);
					$app['db.places_dependencies']->insert($dependency);

					$turnoid = $app['db.places']->getitemByNameandAncestorID($obj['Turno'], $place);
				}
				
				$gradoid = $app['db.places']->getitemByNameandAncestorID($obj['grade'], $turnoid);

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
					$grado = array('created_at' => date("Y-m-d"),'name' => $obj['grade'],'place_id' => $turnoid,'place_type_id' => $place_type_id);
					$app['db.places']->insert($grado);
					$gradoid = $app['db.places']->Lastadded();
					//fetch all the ancestors from the place
					$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($turnoid);
					//loop over all the ancestors and add a new dependency for each ancestor to the db with the new descendent
					foreach ($Ancestors as $waarde) {
					 	$dependency = array('descendant_id' => $gradoid, 'ancestor_id' => $waarde['ancestor_id']);
						$app['db.places_dependencies']->insert($dependency);
					}

					//add the dependency from the new item that points to itself.
					$dependency =  array('descendant_id' => $gradoid, 'ancestor_id' => $gradoid);
					$app['db.places_dependencies']->insert($dependency);

					$gradoid = $app['db.places']->getitemByNameandAncestorID($obj['grade'], $turnoid);
				}

				$Seccionid = $app['db.places']->getitemByNameandAncestorID($obj['Seccion'], $gradoid);
				
				if(empty($Seccionid)){
					$seccion = array('created_at' => date("Y-m-d"),'name' => $obj['Seccion'],'place_id' => $gradoid,'place_type_id' => 11);
					$app['db.places']->insert($seccion);
					$Seccionid = $app['db.places']->Lastadded();

					//fetch all the ancestors from the place
					$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($gradoid);

					//loop over all the ancestors and add a new dependency for each ancestor to the db with the new descendent
					foreach ($Ancestors as $waarde) {
					 	$dependency = array('descendant_id' => $Seccionid, 'ancestor_id' => $waarde['ancestor_id']);
						$app['db.places_dependencies']->insert($dependency);
					}

					//add the dependency from the new item that points to itself.
					$dependency =  array('descendant_id' => $Seccionid, 'ancestor_id' => $Seccionid);
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
					echo "Estudiante agregan";
				}
				else{
					echo 'del perfil o lugar no existe.';
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
					echo "Persona agregada";
				}
				else{
					echo 'del perfil o lugar no existe.';
				}
				
			}
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function editperson(Application $app) {
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			$place="";
			$profile="";
			$gradoid=0;
			$turnoid=0;
			$Seccionid=0;
			
			
			if($obj['profiles'] == 'Estudiante'){
				$place= $app['db.places']->getPlaceonlyoneName($obj['Departamento']);
				$place= $app['db.places']->getitemByNameandAncestorID($obj['Ciudad'], $place[0]['id']);
				$place= $app['db.places']->getitemByNameandAncestorID($obj['Escuela'], $place);
				$turnoid = $app['db.places']->getitemByNameandAncestorID($obj['Turno'], $place);
				if(empty($turnoid)){
					$turno = array('created_at' => date("Y-m-d"),'name' => $obj['Turno'], 'place_id' => $place,'place_type_id' => 12);
					$app['db.places']->insert($turno);
					$turnoid = $app['db.places']->Lastadded();

					//fetch all the ancestors from the place
					$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($place);
					//loop over all the ancestors and add a new dependency for each ancestor to the db with the new descendent
					foreach ($Ancestors as $waarde) {
					 	$dependency = array('descendant_id' => $turnoid, 'ancestor_id' => $waarde['ancestor_id']);
						$app['db.places_dependencies']->insert($dependency);
					}

					//add the dependency from the new item that points to itself.
					$dependency =  array('descendant_id' => $turnoid, 'ancestor_id' => $turnoid);
					$app['db.places_dependencies']->insert($dependency);

					$turnoid = $app['db.places']->getitemByNameandAncestorID($obj['Turno'], $place);
				}
				
				$gradoid = $app['db.places']->getitemByNameandAncestorID($obj['grade'], $turnoid);

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
					$grado = array('created_at' => date("Y-m-d"),'name' => $obj['grade'],'place_id' => $turnoid,'place_type_id' => $place_type_id);
					$app['db.places']->insert($grado);
					$gradoid = $app['db.places']->Lastadded();
					//fetch all the ancestors from the place
					$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($turnoid);
					//loop over all the ancestors and add a new dependency for each ancestor to the db with the new descendent
					foreach ($Ancestors as $waarde) {
					 	$dependency = array('descendant_id' => $gradoid, 'ancestor_id' => $waarde['ancestor_id']);
						$app['db.places_dependencies']->insert($dependency);
					}

					//add the dependency from the new item that points to itself.
					$dependency =  array('descendant_id' => $gradoid, 'ancestor_id' => $gradoid);
					$app['db.places_dependencies']->insert($dependency);

					$gradoid = $app['db.places']->getitemByNameandAncestorID($obj['grade'], $turnoid);
				}

				$Seccionid = $app['db.places']->getitemByNameandAncestorID($obj['Seccion'], $gradoid);
				
				if(empty($Seccionid)){
					$seccion = array('created_at' => date("Y-m-d"),'name' => $obj['Seccion'],'place_id' => $gradoid,'place_type_id' => 11);
					$app['db.places']->insert($seccion);
					$Seccionid = $app['db.places']->Lastadded();

					//fetch all the ancestors from the place
					$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($gradoid);

					//loop over all the ancestors and add a new dependency for each ancestor to the db with the new descendent
					foreach ($Ancestors as $waarde) {
					 	$dependency = array('descendant_id' => $Seccionid, 'ancestor_id' => $waarde['ancestor_id']);
						$app['db.places_dependencies']->insert($dependency);
					}

					//add the dependency from the new item that points to itself.
					$dependency =  array('descendant_id' => $Seccionid, 'ancestor_id' => $Seccionid);
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
					if(empty($obj['name']) || $obj['name'] == ''){
						$app['db.people']->updatesmallPerson($obj);
					}else{
						$app['db.people']->updatePerson($obj);
					}
					$perform = array('person_id' => $obj['id'], 'place_id' => $Seccionid, 'profile_id' => 7);
					$app['db.performs']->updatePerform($perform);
					echo "Person editadar";
				}
				else{
					echo 'del perfil o lugar no existe.';
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
					if(empty($obj['name']) || $obj['name'] == ''){
						$app['db.people']->updatesmallPerson($obj);
					}else{
						$app['db.people']->updatePerson($obj);
					}
					$perform = array('person_id' => $obj['id'], 'place_id' => $place, 'profile_id' => $profile);
					$app['db.performs']->updatePerform($perform);
					echo "Person editadar";
				}
				else{
					echo 'del perfil o lugar no existe.';
				}
				
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				echo $app['db.people']->FindPeopleId($obj);
			} catch (Exception $e) {
				echo "Extraviado";
			}
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * addplace page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function addplace(Application $app) {
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			$place_type="";
			$ancestor="1";
			try {
				if($obj['Departamento']!=''){
					$ancestor= $app['db.places']->getPlaceonlyoneName($obj['Departamento']);
					$ancestor = $ancestor[0]['id'];
					if($obj['Ciudad']!=''){
						$ancestor= $app['db.places']->getitemByNameandAncestorID($obj['Ciudad'], $ancestor);
						if($obj['Escuela']!=''){
							$ancestor= $app['db.places']->getitemByNameandAncestorID($obj['Escuela'], $ancestor);
							if($obj['Turno']!=''){
								$ancestor= $app['db.places']->getitemByNameandAncestorID($obj['Turno'], $ancestor);
								if($obj['grade']!=''){
									$ancestor= $app['db.places']->getitemByNameandAncestorID($obj['grade'], $ancestor);
								}
							}
						}
					}
				}
				if($obj['place_type']=="Grado"){
					if($app['db.place_types']->getGrade($obj['name'])==''){
						echo "grado no existe, cambio de nombre para resolver el problema.";
					}
					else{
						$place_type = $app['db.place_types']->getGrade($obj['name']);
					}
				}
				else{
					$place_type = $app['db.place_types']->getGrade($obj['place_type']);
				}

			} catch (Exception $e) {
			}
			if(ctype_digit($place_type) && ctype_digit($ancestor)){
				$insertplace = array('created_at' => $obj['created_at'], 'name' => $obj['name'], 'place_id' => $ancestor,'place_type_id' => $place_type);
				try {
					$app['db.places']->insert($insertplace);
					$place_id = $app['db.places']->FindnewestId();
					$Ancestors=$app['db.places_dependencies']->fetchAllAncestors($ancestor);
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
					echo "Servidor colapsado, intente más tarde.";
				}	
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
		if(isset($_POST['action'])){
			try{
				$obj = json_decode($_POST['action'], true);
				
				$insertplace = array('id' => $obj['id'], 'name' => $obj['name']);
				$app['db.places']->updatePlace($insertplace);

				if(!empty($obj['server_hostname'])){
					$schoolinfo = array('lease_duration' => null, 'server_hostname' => $obj['server_hostname'], 'place_id' => $obj['id']);
					$app['db.school_infos']->updateSchool($schoolinfo);

				}
				else if(empty($obj['server_hostname'])){
					$app['db.school_infos']->deleteSchool($obj['id']);
				}
				echo "place updated";
			}catch (Exception $e) {
				echo "Servidor colapsado, intente más tarde.";
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if ($app['db.people']->fetchAdminPerson($app['session']->get('user'))[0]['access_level']<499){
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				$children = $app['db.places_dependencies']->fetchAllChildren($obj['id']);
				var_dump($children);
				foreach ($children as $child) {
					$performs = $app['db.performs']->fetchAllByPlaceid($child['descendant_id']);
					foreach ($performs as $perform) {
						$app['db.laptops']->changeOwnerToFZT($perform['person_id']);
						$app['db.movements']->deleteperson($perform['person_id']);
						$app['db.performs']->deleteperson($perform['person_id']);
						$app['db.people']->deleteperson($perform['person_id']);						
					}
					$app['db.places_dependencies']->DeleteALL($child['descendant_id']);
					$app['db.school_infos']->deleteSchool($child['descendant_id']);
					$app['db.places']->deletePlace($child['descendant_id']);
					
				}
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				echo $app['db.places']->FindnewestId();
			} catch (Exception $e) {
				echo "Extraviado";
			}
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	public function getuserbyid(Application $app) {
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				echo $app['db.people']->FindPeopleById($obj['assignee_id']);
			} catch (Exception $e) {
				echo "Extraviado";
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
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
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
				echo "Extraviado";
			}
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function getdataforplacestable(Application $app) {
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}
		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				$Departamento= $app['db.places']->getitemByNameandAncestorID($obj['Departamento'],1);
				$Ciudad= $app['db.places']->getitemByNameandAncestorID($obj['Ciudad'], $Departamento);
				$Escuela= $app['db.places']->getitemByNameandAncestorID($obj['Escuela'], $Ciudad);
				$Turno= $app['db.places']->getitemByNameandAncestorID($obj['Turno'], $Escuela);
				$grade= $app['db.places']->getitemByNameandAncestorID($obj['grade'], $Turno);
				$data ='';
				if(!empty($grade)){
					$data = $app['db.places']->fetchSeccion($grade);
				}
				else if(!empty($Turno)){
					$data = $app['db.places']->fetchGrade($Turno);
				}
				else if(!empty($Escuela)){
					$data = $app['db.places']->fetchTurno($Escuela);
				}
				else if(!empty($Ciudad)){
					$data = $app['db.places']->fetchSchoolwithservername($Ciudad);
				}
				else if(!empty($Departamento)){
					$data = $app['db.places']->fetchCity($Departamento);
				}
				else{
					$data = $app['db.places']->fetchstate(1);
				}
				echo json_encode($data);
			} catch (Exception $e) {
				echo "Extraviado";
			}
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	public function getdataforuserstable(Application $app){
		// check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if ($app['db.people']->fetchAdminPerson($app['session']->get('user'))[0]['access_level']<499){
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		$data ='';
		//var_dump($app['session']->get('user'));
		$data = $app['db.users']->getUsers($app['session']->get('user')['ID']);
		echo json_encode($data);
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function getusersdata(Application $app) {
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if ($app['db.people']->fetchAdminPerson($app['session']->get('user'))[0]['access_level']<499){
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			$data = $app['db.people']->fetchAllusersbyfirstletter($obj['name']);
			echo json_encode($data);
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}


	public function getusersinfo(Application $app) {
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if ($app['db.people']->fetchAdminPerson($app['session']->get('user'))[0]['access_level']<499){
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			$data = false;
			if ($obj['name'] != $obj['actualName']) {
				$data = $app['db.users']->getUsersByName($obj['name']);
			}
			if ($data != false) {
				$data = true;
			}
			$pass = false;
			$data2 = $app['db.users']->getUsersInfo($obj['actualName']);
			if ($data2['clave'] == sha1($obj['password']) ) {
				$pass = true;
			}
			//
			$upd = false;
			if (!$data && $pass) {
				$user = array('usuario' => $obj['name'], 
					'clave' => sha1($obj['newPassword']), 
					'id' => $data2['id']);
				$app['db.users']->updateUser($user);
				$upd = true;
			}
			echo json_encode(array('exist' => $data, 'pass' => $pass, 'upd' => $upd) );
		}
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	public function validateUser(Application $app){
		// check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if ($app['db.people']->fetchAdminPerson($app['session']->get('user'))[0]['access_level']<499){
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if(isset($_POST['action'])) {
			$obj = json_decode($_POST['action'], true);
			$data = false;
			if ($obj['name'] != $obj['actualName']) {
				$data = $app['db.users']->getUsersByName($obj['name']);
			}
			if ($data != false) {
				$data = true;
			}
			echo json_encode(array('resp' => $data) );
		}
		return $app['twig']->render('Ajax/Dump.twig');
	}

	public function deleteUser(Application $app) {
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if ($app['db.people']->fetchAdminPerson($app['session']->get('user'))[0]['access_level']<499){
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				$app['db.users']->deleteUser($obj['id']);
				echo "person deleted";
			} catch (Exception $e) {
				echo "person already deleted";
			}
		}
		return $app['twig']->render('Ajax/Dump.twig');
	}

	public function persons(Application $app) {
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if ($app['db.people']->fetchAdminPerson($app['session']->get('user'))[0]['access_level']<499){
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		$data = $app['db.people']->fetchAll();
		echo json_encode($data);
		return $app['twig']->render('Ajax/Dump.twig');	
	}

	public function addUser(Application $app) {
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if ($app['db.people']->fetchAdminPerson($app['session']->get('user'))[0]['access_level']<499){
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if (isset($_POST['action'])) {
			$obj = json_decode($_POST['action'], true);
			$userid = $app['db.people']->getPerson($obj['person']);	
			try {
				$user = array('usuario' => $obj['usuario'], 
					'clave' => sha1($obj['clave']), 
					'person_id' => $userid);
				$app['db.users']->insert($user);
				$id = $app['db.users']->Lastadded();
				$data = $app["db.users"]->getUser($id['id']);
				echo json_encode($data);
			} catch (Exception $e) {
				echo "person already added";
			}
		}
		return $app['twig']->render('Ajax/Dump.twig');
	}

	public function editUser(Application $app) {
        // check if user is already logged in
		if (!$app['session']->get('user') || !($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if ($app['db.people']->fetchAdminPerson($app['session']->get('user'))[0]['access_level']<499){
			return $app->redirect($app['url_generator']->generate('Auth.Login')); 
		}

		if(isset($_POST['action'])){
			$obj = json_decode($_POST['action'], true);
			try {
				if (strlen($obj['clave']) == 0) {
					$user = array('usuario' => $obj['usuario'],  
						'id' => $obj['id']);
					$app['db.users']->updateUserWithoutPass($user);
				}else{
					$user = array('usuario' => $obj['usuario'], 
						'clave' => sha1($obj['clave']), 
						'id' => $obj['id']);
					$app['db.users']->updateUser($user);
				}
				$data = $app['db.users']->getUserPerson($obj['id']);
				//
				$perform = array('profile_id' => $obj['profile_id'], 'person_id' => $data[0]['person_id']);
				$app['db.performs']->updatePerformProfile($perform);
				//
				echo "usuario editado";
			} catch (Exception $e) {
				echo "Servidor colapsado, intente más tarde.";
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