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
class ExportController implements ControllerProviderInterface {

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
			->get('/', array($this, 'export'))
			->method('GET|POST')
			->bind('Export');
		// Return ControllerCollection
		return $controllers;
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function export(Application $app) {
		$show='';
		//check if the user is logged in
		/*$userLogin = false;
		if ($app['session']->get('user') && ($app['db.users']->findUserByEmail($app['session']->get('user')['Email']))) {
			$userLogin = true;
			$userCred = $app['session']->get('user');
		*/
		

		//return the rendered twig with parameters
		return $app['twig']->render('Export/index.twig', array(
			'show' => $show,
			//'userLogin' => $userLogin,
		));	
	}
}
// EOF