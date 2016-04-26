<?php

namespace RNR\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\Validator\Constraints as Assert;

class AdminController implements ControllerProviderInterface{

	public function connect(Application $app){
		$controllers = $app['controllers_factory'];

		$controllers
			->get('/', array($this, 'admin'))
			->method('GET|POST')
			->bind('Admin');

		return $controllers;
	}

	/**
	 * [admin]
	 * This is the admin method. 
	 * @param  Application $app
	 * @return [blob] tha admin page
	 */
	public function admin(Application $app) {
				
		//check if the user is logged in
		//set acces level and username to default
		$access_level = 0;
		$username = '';
		$usuario = '';

		// check if user is already logged in
		if ($app['session']->get('user') && ($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//get the user from de database.
			$user = $app['db.people']->fetchAdminPerson($app['session']->get('user'));
		
			//set acces level and username
			$username = $user[0]['name'];
			$usuario = $user[0]['usuario'];
			$access_level = $user[0]['access_level'];
		}
		else{

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('auth.login')); 
		}
		

		//return the rendered twig with parameters
		return $app['twig']->render('Admin/index.twig', array(
			'access_level' => $access_level,
			'usuario' => $usuario,
			'username' => $username
		));	
	}
}