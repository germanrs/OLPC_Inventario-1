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

		// Delete a blogpost
		$controllers
			->post('/Laptops/{LaptopId}/delete/', array($this, 'laptopsdelete'))
			->method('GET|POST')
			->assert('LaptopId', '\d+')
			->bind('inventory.Laptops.delete');

		// Delete a blogpost
		$controllers
			->post('/Laptops/{LaptopId}/edit/', array($this, 'laptopedit'))
			->assert('LaptopId', '\d+')
			->method('GET|POST')
			->bind('inventory.Laptops.edit');


		// Return ControllerCollection
		return $controllers;
	}

	/**
	 * home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function laptops(Application $app) {
		$show='';
		//check if the user is logged in
		/*$userLogin = false;
		if ($app['session']->get('user') && ($app['db.users']->findUserByEmail($app['session']->get('user')['Email']))) {
			$userLogin = true;
			$userCred = $app['session']->get('user');
		*/
	
		// Get parameters
		$params = $app['request']->query->all();
		
		//Get the number of items
		$numItems = $app['db.laptops']->fetchTotalLaptops();

		//set the number of items per pages to 9
		$numItemsPerPage = 20;

		//get the current page number, if page is not set use 1
		$curPage = max(1, (int) $app['request']->get('p'));

		//Calculate the number of pages by dividing the items count by the number of item per page 
		$numPages = ceil($numItems / $numItemsPerPage);

		//get the parameters
    	$params = $app['request']->query->all();
		$genre = (isset($params['filterform']['genres']))?$params['filterform']['genres']:'';
		$searchstring = isset($params['filterform']['searchstring'])?$params['filterform']['searchstring']:'';

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
    					'laptops.serial_number' => 'serial nbr',
    					'people.lastname' => 'owner',
    					'places.name' => 'school',
    					'models.name' => 'model',
    					'statuses.description' => 'status',
    					'laptops.uuid' => 'uuid'),
    				'placeholder' => 'Choose wisely!',
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
		
		//fetch all laptops
		$laptops = (isset($params['filterform']['genres']))?$app['db.laptops']->findFiltered($params['filterform'],$curPage,$numItemsPerPage):$app['db.laptops']->fetchAllLaptops($curPage,$numItemsPerPage);
		//return the rendered twig with parameters
		return $app['twig']->render('inventory/Laptops.twig', array(
			'show' => $show,
			'filterform' => $filterform->createView(),
			'laptops' => $laptops,
			'curPage'=>$curPage,
			'numPages'=>$numPages,
			'numItems'=>$numItems, 
			'baseUrl'=> $app['Inventory.base_url'],
			'pagination'=>generatePaginationSequence($curPage, $numPages),
			'requestParams' => $newparams
			//'userLogin' => $userLogin,
		));
		
	}

	public function laptopsdelete(Application $app) {
		$show='';
		//check if the user is logged in
		/*$userLogin = false;
		if ($app['session']->get('user') && ($app['db.users']->findUserByEmail($app['session']->get('user')['Email']))) {
			$userLogin = true;
			$userCred = $app['session']->get('user');
		*/

		//return the rendered twig with parameters
		return $app['twig']->render('inventory/Laptops.twig', array(
			'show' => $show
			//'userLogin' => $userLogin,
		));
		
	}

	public function laptopedit(Application $app) {
		$show='';
		//check if the user is logged in
		/*$userLogin = false;
		if ($app['session']->get('user') && ($app['db.users']->findUserByEmail($app['session']->get('user')['Email']))) {
			$userLogin = true;
			$userCred = $app['session']->get('user');
		*/

		//return the rendered twig with parameters
		return $app['twig']->render('inventory/Laptops.twig', array(
			'show' => $show
			//'userLogin' => $userLogin,
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