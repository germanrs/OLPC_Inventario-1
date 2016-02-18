<?php

namespace Ikdoeict\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class Admin implements ControllerProviderInterface {

	public function connect(Application $app) {

		// Create new ControllerCollection
		$controllers = $app['controllers_factory'];

		// Mount Admin “Subcontrollers”
		$app->mount('/admin/music/', new Admin\Music());

		// Redirect to blog overview if we hit /admin/
		$controllers->get('/', function(Application $app) {
			return $app->redirect($app['url_generator']->generate('admin.music'));
		})->bind('admin.index');

		return $controllers;

	}

}