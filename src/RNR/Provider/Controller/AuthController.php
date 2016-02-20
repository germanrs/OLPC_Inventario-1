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
		//Check if the user is logged in
		$userLogin = false;
		// Already logged in
		if ($app['session']->get('user') && ($app['db.users']->find($app['session']->get('user')['Email']))) {
			return $app->redirect($app['url_generator']->generate('Inventory'));
		}

		// Create Form
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

			$data = $loginform->getData();
			$user = $app['db.users']->findUserByName($data['username']);

			// Password checks out
			if (password_verify($data['password'], $user['Password'])) {

				// Only store needed data in session: here we only keep the id and firstname.
				$app['session']->set('user', array_intersect_key($user, array('ID' => '', 'Email' => '')));

				//var_dump($app['session']);
                // Redirect to admin index
				return $app->redirect($app['url_generator']->generate('home'));
			}

            // Password does not check out: add an error to the form
            else {
				$loginform->get('password')->addError(new \Symfony\Component\Form\FormError('Invalid credentials'));
			}
		}

		// return to login page if login has failed
		return $app['twig']->render('auth/login.twig', array(
			'user' => null, 'loginform' => $loginform->createView(),
			'userLogin' => $userLogin
		));
	}

	/**
	 * [Logout]
	 * This is the Logout method. 
	 * @param  Application $app
	 * @return [string] the generated url
	 */
	public function logout(Application $app) {
		$app['session']->remove('user');
		return $app->redirect($app['url_generator']->generate('auth.login'));
	}
}
// EOF