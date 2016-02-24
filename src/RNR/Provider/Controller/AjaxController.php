<?php

namespace RNR\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\Validator\Constraints as Assert;

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
			->get('/addlaptop/', array($this, 'addlaptop'))
			->method('GET|POST')
			->bind('Ajax.addlaptop');

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
}