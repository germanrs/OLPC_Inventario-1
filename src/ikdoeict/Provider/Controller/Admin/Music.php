<?php

namespace Ikdoeict\Provider\Controller\Admin;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints as Assert;

class Music implements ControllerProviderInterface {

	public function connect(Application $app) {

		// Create new ControllerCollection
		$controllers = $app['controllers_factory'];

		// Overview of blogposts
		$controllers
			->get('/', array($this, 'music'))
			->method('GET|POST')
			->bind('admin.music');

		return $controllers;

	}

	public function music(Application $app) {
		// Get parameters
		$params = $app['request']->query->all();
		
		//Get the number of items
		$numItems = $app['db.music']->fetchAantalAlbums();

		//set the number of items per pages to 9
		$numItemsPerPage = 9;

		//get the current page number, if page is not set use 1
		$curPage = max(1, (int) $app['request']->get('p'));

		//Calculate the number of pages by dividing the items count by the number of item per page 
		$numPages = ceil($numItems / $numItemsPerPage);

		//get the albums of 1 page
		$albums = $app['db.music']->fetchAllAlbums($curPage, $numItemsPerPage);
		
		//get the parameters
    	$params = $app['request']->query->all();
    	
		// when the form is applyed the first the following code is used
		if ($params!=null && isset($params['filterform'])) {

			//get all the data from the params['filterform'] and put it in data
			$data = $params['filterform'];

			//get the number of items
			$numItems = $app['db.music']->fetchAantalFilterAlbums($data);

			//set the number of items per pages to 9
			$numItemsPerPage = 9;

			//get the current page number, if page is not set use 1
			$curPage = max(1, (int) $app['request']->get('p'));

			//Calculate the number of pages by dividing the items count by the number of item per page 
			$numPages = ceil($numItems / $numItemsPerPage);
			
			//get the albums of page 1
			$albums = $app['db.music']->findFiltered($data, $curPage, $numItemsPerPage);

			// get all the items from params['filterform'] and assign the to te corresponding item
			$title = $params['filterform']['title'];
			if((int)$params['filterform']['genres'] == null){
				$genre = null;
			}
			else{
				$genre = (int)$params['filterform']['genres'];
			}
			
			$year = $params['filterform']['year'];
		}

		// when the form is applayed and the page is not 1 use the following code
		else if($params!=null && isset($params['year'])){

			//get all the data from the params and put it in data
			$data = $params;

			//get the number of items
			$numItems = $app['db.music']->fetchAantalFilterAlbums($data);

			//set the number of items per pages to 9
			$numItemsPerPage = 9;

			//get the current page number, if page is not set use 1
			$curPage = max(1, (int) $app['request']->get('p'));

			//Calculate the number of pages by dividing the items count by the number of item per page
			$numPages = ceil($numItems / $numItemsPerPage);
			
			//get the albums of page 1
			$albums = $app['db.music']->findFiltered($data, $curPage, $numItemsPerPage);

			// get all the items from params and assign the to te corresponding item
			$title = $params['title'];
			if((int)$params['genres'] == null){
				$genre = null;
			}
			else{
				$genre = (int)$params['genres'];
			}
			
			$year = $params['year'];
		}
		else{
			$title = null;
			$genre = null;
			$year = null;
		}

		//get all the genres and put them in $genres.	
		$genres = $app['db.genres']->FindAll();
		
		// Create Form
		$filterform = $app['form.factory']
				->createNamed('filterform', 'form')
				->add('title', 'text', array(
					'attr' => array('class' => 'required'),
					'required' => false,
					'data' => $title
				))
				->add('genres', 'choice', array(
    				'choices'  => array_column($genres, 'title'),
    				'placeholder' => 'Choose wisely!',
    				'required' => false,
					'attr' => array('class' => 'required'),
					'data' => $genre,
				))
				->add('year', 'text', array(
					'attr' => array('class' => 'required'),
					'required' => false,
					'data' => $year
				));

		//if params['filterform'] isset, use the data from it. if its not set, the data is availeble in params.		
		if(!isset($params['filterform'])){
			$newparams = $params;
		}
		else{
			$newparams = $params['filterform'];
		}

		// Render template
		return $app['twig']->render('admin/music/overview.twig', array(
			'albums' => $albums,
			'curPage'=>$curPage,
			'numPages'=>$numPages,
			'numItems'=>$numItems, 
			'baseUrl'=> $app['admin.base_url'],
			'pagination'=>generatePaginationSequence($curPage, $numPages),
			'filterform' => $filterform->createView(),
			'requestParams' => $newparams,
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