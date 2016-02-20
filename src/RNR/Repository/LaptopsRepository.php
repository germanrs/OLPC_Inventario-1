<?php

namespace RNR\Repository;

/**
 * @author Robin Staes <robin.staes@student.odisee.be>
 */
class LaptopsRepository extends \Knp\Repository {

	public function getTableName() {
		return 'laptops';
	}

	public function fetchAllLaptops($curPage, $numItemsPerPage) {
		return $this->db->fetchAll(
				'SELECT laptops.id as laptopID, laptops.serial_number, laptops.uuid, people.name as firstname, people.lastname as lastname, people.id as peopleID, places.name as placename, places.id as placeID, models.name as modelName, statuses.description from laptops 
				INNER JOIN statuses ON statuses.id = laptops.status_Id 
				INNER JOIN models on models.id = laptops.model_id 
				INNER JOIN people on people.id = laptops.owner_id 
				INNER JOIN performs on performs.person_id = laptops.owner_id 
				INNER JOIN places on performs.place_id = places.id
				ORDER BY laptops.id DESC
        		LIMIT ' . (int) (($curPage - 1) * $numItemsPerPage) . ',' . (int) ($numItemsPerPage));
	}

	public function fetchTotalLaptops() {
		return $this->db->fetchColumn('SELECT COUNT(*) FROM laptops');
	}

	public function fetchAantalFilterAlbums($filter) {
		$extraJoins = '';
	    $extraWhere = '';

	    // Title set via Filter
	    if ($filter['title'] != '') {
	        $extraWhere .= ' AND albums.title LIKE ' . $this->db->quote('%'.$filter['title'].'%', \PDO::PARAM_STR);
	        //$extraWhere .= ' OR artists.title LIKE ' . $this->db->quote('%'.$filter['title'].'%', \PDO::PARAM_STR);
	    }

	    // Type set via Filter
	    if ($filter['genres'] != '') {
	        $extraJoins .= ' INNER JOIN genres ON albums.genre_id = genres.id';
	        $extraWhere .= ' AND genres.id = ' . $this->db->quote($filter['genres']+1, \PDO::PARAM_INT);
	    }

	    return $this->db->fetchColumn(
	    	'SELECT Count(*) from albums INNER JOIN artists ON albums.artist_id = artists.id' . $extraJoins .$extraWhere);
	}



	public function findFiltered($filter, $curPage, $numItemsPerPage ) {

	    $extraWhere = '';

	    // Title set via Filter
	    if ($filter['searchstring'] != '') {
	        $extraWhere .= ' AND '.$filter['genres'].' LIKE ' . $this->db->quote('%'.$filter['searchstring'].'%', \PDO::PARAM_STR);
	        //$extraWhere .= ' OR artists.title LIKE ' . $this->db->quote('%'.$filter['title'].'%', \PDO::PARAM_STR);

	    }

	    return $this->db->fetchAll(
				'SELECT laptops.id as laptopID, laptops.serial_number, laptops.uuid, people.name as firstname, people.lastname as lastname, people.id as peopleID, places.name as placename, places.id as placeID, models.name as modelName, statuses.description from laptops 
				INNER JOIN statuses ON statuses.id = laptops.status_Id 
				INNER JOIN models on models.id = laptops.model_id 
				INNER JOIN people on people.id = laptops.owner_id 
				INNER JOIN performs on performs.person_id = laptops.owner_id 
				INNER JOIN places on performs.place_id = places.id
				'. $extraWhere .'
				ORDER BY '.$filter['genres'].' DESC

        		LIMIT ' . (int) (($curPage - 1) * $numItemsPerPage) . ',' . (int) ($numItemsPerPage));
	}
}