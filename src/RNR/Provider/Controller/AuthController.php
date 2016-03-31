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
class AuthController implements ControllerProviderInterface {

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
			->get('/login/', array($this, 'login'))
			->method('GET|POST')
			->bind('auth.login');
		$controllers
			->get('/logout/', array($this, 'logout'))
			->bind('auth.logout');

		// Redirect to login by default
		$controllers->get('/', function(Application $app) {
			return $app->redirect($app['url_generator']->generate('login'));
		});	

		// Return ControllerCollection
		return $controllers;
	}

	/**
	 * login page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function login(Application $app) {
		
		//check if the user is logged in
		//set acces level and username to default
		$access_level = 0;
		$username = '';

		// check if user is already logged in
		if ($app['session']->get('user') && ($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {
			return $app->redirect($app['url_generator']->generate('Inventory.laptops'));
		}

		// Create Login Form
		$loginform = $app['form.factory']
		->createNamed('loginform', 'form')
			->add('username', 'text', array(
				'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 1)))
			))
			->add('password', 'password', array(
				'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
			));

		// Form was submitted: process it
		$loginform->handleRequest($app['request']);

		// Form is valid
		if ($loginform->isValid()) {

			//get data from the form
			$data = $loginform->getData();

			//get the user that fits the username
			$user = $app['db.users']->findUserByName($data['username']);

			// Password checks out
			if (sha1($data['password']) == $user['clave']) {

				// Only store needed data in session: here we only keep the id
				$app['session']->set('user', array('ID' => $user['person_id']));

                // Redirect to Inventory index
				return $app->redirect($app['url_generator']->generate('Inventory.laptops'));
			}

            // Password does not check out: add an error to the form
            else {
				$loginform->get('password')->addError(new \Symfony\Component\Form\FormError('Invalid credentials'));
			}
		}

		// return to login page if login has failed
		return $app['twig']->render('auth/login.twig', array(
			'user' => null, 'loginform' => $loginform->createView(),
			'access_level' => $access_level,
			'username' => $username
		));
	}

	/**
	 * [Logout]
	 * This is the Logout method. 
	 * @param  Application $app
	 * @return [string] the generated url to the login page
	 */
	public function logout(Application $app) {

		//remove the session
		$app['session']->remove('user');

		//return to the login page
		return $app->redirect($app['url_generator']->generate('auth.login'));
	}
}


// EOF