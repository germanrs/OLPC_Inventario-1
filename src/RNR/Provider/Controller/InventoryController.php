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
class InventoryController implements ControllerProviderInterface {

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
			->get('/', array($this, 'laptops'))
			->method('GET|POST')
			->bind('Inventory.laptops');

		$controllers
			->get('/laptops/', array($this, 'laptops'))
			->method('GET|POST')
			->bind('Inventory.Laptops');

		$controllers
			->get('/people/', array($this, 'people'))
			->method('GET|POST')
			->bind('Inventory.people');

		$controllers
			->get('/places/', array($this, 'places'))
			->method('GET|POST')
			->bind('Inventory.places');

		$controllers
			->get('/massassignment/', array($this, 'massassignment'))
			->method('GET|POST')
			->bind('Inventory.MassAssignment');
			

		// Return ControllerCollection
		return $controllers;
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function laptops(Application $app) {

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

		//set the number of items per pages to 20
		$numItemsPerPage = 20;

		//get the current page number, if page is not set use 1
		$curPage = max(1, (int) $app['request']->get('p'));
	
		// Get parameters
		$params = $app['request']->query->all();

    	// when the form is applayed and the page has multiple filters
    	if ($params!=null && isset($params['filterform']['genres']) && ''!=($params['filterform']['genres'])) {
    		
    		//get the genre (sort filter)
    		$genre = $params['filterform']['genres'];

    		//get the search value
			$searchstring = $params['filterform']['searchstring'];
			
			//Get the number of items
			$numItems = $app['db.laptops']->fetchTotalFilterLaptops($params['filterform']);	

			//get a list of laptops
			$laptops = $app['db.laptops']->findFiltered($params['filterform'],$curPage,$numItemsPerPage);
    	}

    	// when the form is applayed and the page has only an order by filter
    	else if ($params!=null && isset($params['genres'])) {

    		//get the genre (sort filter)
    		$genre = $params['genres'];
			
			//get the search value
			$searchstring = $params['searchstring'];
			
			//Get the number of items
			$numItems = $app['db.laptops']->fetchTotalFilterLaptops($params);

			//get a list of laptops
			$laptops = $app['db.laptops']->findFiltered($params,$curPage,$numItemsPerPage);
    	}

    	//get the laptops from a page without filters
    	else{

    		//set the genre to null
    		$genre = '';

    		//set the searchstring to null
			$searchstring = '';
			

			//Get the number of items
			$numItems = $app['db.laptops']->fetchTotalLaptops();

			//get a list of laptops
			$laptops = $app['db.laptops']->fetchAllLaptops($curPage,$numItemsPerPage);            
    	}

    	//Calculate the number of pages by dividing the items count by the number of item per page 
		$numPages = ceil($numItems / $numItemsPerPage);

		$laptoparray = array();
		foreach ($laptops as $laptop) {
			$datadump= $app['db.performs']->fetchAllByPersonId($laptop['peopleID']);	
			$datadump = $app['db.places_dependencies']->fetchAllAncestorsFromSchool($datadump[0]['place_id']);
			if(!empty($datadump)){
				foreach ($datadump as $data) {
					$place_type_id=0;
					if(!empty($data)){
						$laptop['region'] ='';
						if(2==$data['place_type_id']){
							$laptop['region'] = $data['name'];
						}
						else if(4==$data['place_type_id']){
							$laptop['placename'] = $data['name'];
						}
					}
				}
			}
			else{
				$laptop['region'] =$laptop['placename'];
				$laptop['placename'] ='';
			}
			array_push($laptoparray,$laptop);
		}


		// Create Form
		$filterform = $app['form.factory']
				->createNamed('filterform', 'form')
				->add('searchstring', 'text', array(
					'attr' => array('class' => 'required'),
					'required' => false,
					'data' => $searchstring
				))
				->add('genres', 'choice', array(
    				'choices'  => array(
    					'laptops.serial_number' => 'Número serial',
    					'people.lastname' => 'Propietario',
    					'places.name' => 'Escuela',
    					'models.name' => 'Versión',
    					'statuses.description' => 'Estado',
    					'laptops.uuid' => 'uuid'),
    				'placeholder' => 'Elige sabiamente!',
    				'required' => false,
					'attr' => array('class' => 'required'),
					'data' => $genre,
				));

		//if params['filterform'] isset, use the data from it. if its not set, the data is availeble in params.		
		if(!isset($params['filterform'])){
			$newparams = $params;
		}
		else{
			$newparams = $params['filterform'];
		}
		
		if (isset($params['filterform']['genres']) && ''==($params['filterform']['genres'])){
				$filterform->get('genres')->addError(new \Symfony\Component\Form\FormError('Select a type'));
		}

		//return the rendered twig with parameters
		return $app['twig']->render('Inventory/Laptops.twig', array(
			'filterform' => $filterform->createView(),
			'laptops' => $laptoparray,
			'curPage'=>$curPage,
			'numPages'=>$numPages,
			'numItems'=>$numItems, 
			'baseUrl'=> $app['Inventory.base_url'],
			'pagination'=>generatePaginationSequence($curPage, $numPages),
			'requestParams' => $newparams,
			'access_level' => $access_level,
			'username' => $username
		));
		
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function people(Application $app) {
		
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

		//set the number of items per pages to 20
		$numItemsPerPage = 20;

		//get the current page number, if page is not set use 1
		$curPage = max(1, (int) $app['request']->get('p'));
	
		// Get parameters
		$params = $app['request']->query->all();
    	// when the form is applayed and the page is not 1 use the following code
    	if ($params!=null && isset($params['filterform']['genres']) && ''!=($params['filterform']['genres'])) {
    		$genre = $params['filterform']['genres'];
			$searchstring = $params['filterform']['searchstring'];
			
			//Get the number of items
			$numItems = $app['db.people']->fetchTotalFilterpeople($params['filterform']);	

			$people = $app['db.people']->findFiltered($params['filterform'],$curPage,$numItemsPerPage);
    	}

    	else if ($params!=null && isset($params['genres'])) {
    		$genre = $params['genres'];
			$searchstring = $params['searchstring'];
			
			//Get the number of items
			$numItems = $app['db.people']->fetchTotalFilterpeople($params);

			$people = $app['db.people']->findFiltered($params,$curPage,$numItemsPerPage);
    	}
    	else{
    		$genre = '';
			$searchstring = '';
			

			//Get the number of items
			$numItems = $app['db.people']->fetchTotalpeople();

			$people = $app['db.people']->fetchAllPeople($curPage,$numItemsPerPage);

			// Password does not check out: add an error to the form
            
    	}

    	$peoplearray = array();
		foreach ($people as $person) {
			$datadump= $app['db.performs']->fetchAllByPersonId($person['id']);
			$datadump = $app['db.places_dependencies']->fetchAllAncestorsFromSchool($datadump[0]['place_id']);
			$person['Seccion'] = '';
			$person['Turno'] = '';
			$person['grade'] = '';
			$person['region'] ='';
			$person['city'] ='';
			$person['Schoolname'] ='';

			if(!empty($datadump)){
				foreach ($datadump as $data) {
					$place_type_id=0;
					if(!empty($data)){
						if(2==$data['place_type_id']){
							$person['region'] = $data['name'];
						}
						else if(3==$data['place_type_id']){
							$person['city'] = $data['name'];
						}
						else if(4==$data['place_type_id']){
							$person['Schoolname'] = $data['name'];
						}
						else if(12==$data['place_type_id']){
							$person['Turno'] = $data['name'];
						}
						else if(11==$data['place_type_id']){
							$person['Seccion'] = $data['name'];
						}
						else if(5==$data['place_type_id'] ||
							6==$data['place_type_id'] ||
							7==$data['place_type_id'] ||
							8==$data['place_type_id'] ||
							9==$data['place_type_id'] ||
							10==$data['place_type_id'] ||
							13==$data['place_type_id'] ||
							14==$data['place_type_id'] ||
							16==$data['place_type_id'] ||
							17==$data['place_type_id'] ||
							18==$data['place_type_id']){
							$person['grade'] = $data['name'];
						}
					}
				}
			}
			else{
				$person['region'] =$person['Schoolname'];
				$person['Schoolname'] ='';
			}
			array_push($peoplearray,$person);
		}

    	//Calculate the number of pages by dividing the items count by the number of item per page 
		$numPages = ceil($numItems / $numItemsPerPage);


		// Create Form
		$filterform = $app['form.factory']
				->createNamed('filterform', 'form')
				->add('searchstring', 'text', array(
					'attr' => array('class' => 'required'),
					'required' => false,
					'data' => $searchstring
				))
				->add('genres', 'choice', array(
    				'choices'  => array(
    					'CONCAT(people.name," ",people.lastname)' => 'Nombre',
    					'places.name' => 'Escuela',
    					'profiles.description' => 'perfiles'),
    				'placeholder' => 'Elige sabiamente!',
    				'required' => false,
					'attr' => array('class' => 'required'),
					'data' => $genre
				));

		//if params['filterform'] isset, use the data from it. if its not set, the data is availeble in params.		
		if(!isset($params['filterform'])){
			$newparams = $params;
		}
		else{
			$newparams = $params['filterform'];
		}
		
		if (isset($params['filterform']['genres']) && ''==($params['filterform']['genres'])){
				$filterform->get('genres')->addError(new \Symfony\Component\Form\FormError('Select a type'));
		}

		//return the rendered twig with parameters
		return $app['twig']->render('Inventory/people.twig', array(
			'access_level' => $access_level,
			'username' => $username,
			'filterform' => $filterform->createView(),
			'people' => $peoplearray,
			'curPage'=>$curPage,
			'numPages'=>$numPages,
			'numItems'=>$numItems, 
			'baseUrl'=> $app['Inventory.base_url'],
			'pagination'=>generatePaginationSequence($curPage, $numPages),
			'requestParams' => $newparams
			
		));
		
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function places(Application $app) {
		
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
		
		//return the rendered twig with parameters
		return $app['twig']->render('Inventory/places.twig', array(
			'access_level' => $access_level,
			'username' => $username
		));
		
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function massassignment(Application $app) {
		
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
		
		//return the rendered twig with parameters
		return $app['twig']->render('Inventory/massassignment.twig', array(
			'access_level' => $access_level,
			'username' => $username
		));
		
	}
}

function generatePaginationSequence($curPage, $numPages, $numberOfPagesAtEdges = 2, $numberOfPagesAroundCurrent = 2, $glue = '..', $indicateActive = false) {
	
		// Define the number of items we would generate in a normal scenario
		// (viz. lots of pages, current page in the middle):
		//
		// numItemsInSequence = the current page + the number of items surrounding
		// the current page (left and right) + the number of items at the edges
		// of the generated sequence (left and right) + the glue in between the
		// different parts generated
		//
		// The goal is to enforce all sequences generated to have this amount
		// of items. By default this magic number would be 11, as seen/counted
		// in this sequence: 1-02-..-11-12-[13]-14-15-..-88-74
		$numItemsInSequence = 1 + ($numberOfPagesAroundCurrent * 2) + ($numberOfPagesAtEdges * 2) + 2;
		
		// Fix: curPage cannot be greater than numPages.
		$curPage = min($curPage, $numPages);
		
		// If we have less than $numItemsInSequence pages in total, there is no need to
		// start calculating but just return the full sequence, starting at 1
		if ($numPages <= $numItemsInSequence) {
			$finalSequence = range(1, $numPages);
		}
		
		// We have more pages than $numItemsInSequence, start calculating
		else {
		
			// If we have no forced amount of items on the edges, then the 
			// sequence must start from the current page number instead of 1
			$start = ($numberOfPagesAtEdges > 0) ? 1 : $curPage;
			
			// Parts of the sequence we'll be generating
			$sequence = array(
				'leftEdge' => null,
				'glueLeftCenter' => null,
				'centerPiece' => null,
				'glueCenterRight' => null,
				'rightEdge' => null
			);
			
			// If the current page is nearby the left edge (viz. curPage is
			// less than half of $numItemsInSequence away from left edge):
			// Don't generate a Center Piece, but extend the left part as
			// the left part would otherwise overlap the center piece.
			if ($curPage < ($numItemsInSequence/2)) {
				$sequence['leftEdge'] = range(1, ceil($numItemsInSequence/2) + $numberOfPagesAroundCurrent);
				$sequence['centerPiece'] = array($glue);
				if ($numberOfPagesAtEdges > 0) $sequence['rightEdge'] = range($numPages-($numberOfPagesAtEdges-1), $numPages);
			}

			// If the current page is nearby the right edge (viz. curPage is
			// less than half of $numItemsInSequence away from right edge):
			// Don't generate a center piece but extend the right part as
			// the right part would otherwise overlap the center piece.
			else if ($curPage > $numPages - ($numItemsInSequence/2)) {
				if ($numberOfPagesAtEdges > 0) $sequence['leftEdge'] = range($start, $numberOfPagesAtEdges);
				$sequence['centerPiece'] = array($glue);
				$sequence['rightEdge'] = range(min($numPages - floor($numItemsInSequence/2) - $numberOfPagesAroundCurrent, $curPage - $numberOfPagesAroundCurrent), $numPages);
			} 
			
			// The current page falls somewhere in the middle:
			// Generate ranges normally
			else {
				
				// Center Piece
				$sequence['centerPiece'] = range($curPage - $numberOfPagesAroundCurrent, $curPage + $numberOfPagesAroundCurrent);
				
				// Left/Right Edges (only if we requested)
				if ($numberOfPagesAtEdges > 0) $sequence['leftEdge'] = range($start,$numberOfPagesAtEdges);
				if ($numberOfPagesAtEdges > 0) $sequence['rightEdge'] = range($numPages-($numberOfPagesAtEdges-1), $numPages);
				
				// The glue we'll use to stick left, center, and right together
				// Special case: If the gap between left and center is only one
				// unit, don't add '...' but add that number instead
				$sequence['glueLeftCenter'] = ($sequence['centerPiece'][0] == ($numberOfPagesAtEdges+2)) ? array($numberOfPagesAtEdges+1) : array($glue);
				$sequence['glueCenterRight'] = array($glue);
				
			}
			
			// Join all (non-empty) parts of sequence into the final sequence
			$finalSequence = array();
			foreach($sequence as $k => $v) {
				if ($v !== null) {
					$finalSequence = array_merge($finalSequence, $v);
				}
			}

		}
		
		// Return the final sequence
		if ($indicateActive) {
			return array_replace($finalSequence, array(array_search($curPage, $finalSequence) => '[' . $curPage. ']'));
		} else {
			return $finalSequence;
		}

	}
// EOF