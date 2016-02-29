<?php

namespace RNR\Repository;

/**
 * @author Robin Staes <robin.staes@student.odisee.be>
 */
class PlacesRepository extends \Knp\Repository {

	/**
	 * [getTableName description]
	 * This function returns the table name
	 * @return [string] The name of the table
	 */
	public function getTableName() {
		return 'places';
	}

	public function fetchAll() {
		return $this->db->fetchAll(
				'SELECT places.name as name, places.id as id  FROM places group by places.name');
	}

	/**
	 * get the requested person
	 * @param String $PersonName
	 * @return The requested person
	 */
	public function getPlace($placename, $grade) {
		$query = 'SELECT id FROM places WHERE name = ' . $this->db->quote($placename, \PDO::PARAM_INT).'AND place_type_id = '. $this->db->quote($grade, \PDO::PARAM_INT);
		return $this->db->fetchColumn($query);
	}

	public function getPlaceById($id) {
		$query = 'SELECT places.* FROM places WHERE id = ' . $this->db->quote($id, \PDO::PARAM_INT);
		return $this->db->fetchAll($query);
	}

	public function getPlaceByName($placename) {
		$query = 'SELECT id FROM places WHERE name = ' . $this->db->quote($placename, \PDO::PARAM_INT)	;
		return $this->db->fetchColumn($query);
	}

	public function FindnewestId() {
		$query = 'SELECT id FROM places ORDER BY id DESC';
		return $this->db->fetchColumn($query);
	}

	public function fetchAllPlaces($curPage, $numItemsPerPage) {
		return $this->db->fetchAll(
				'SELECT places.*, places.id as ider, place_dependencies.*, place_types.name as placename, school_infos.id as schoolid, school_infos.* from places
				LEFT JOIN place_dependencies ON place_dependencies.descendant_id = places.id 
				LEFT JOIN place_types on place_types.id = places.place_type_id
				LEFT JOIN school_infos on school_infos.place_id = places.id
				group by places.name
				ORDER BY places.id DESC
        		LIMIT ' . (int) (($curPage - 1) * $numItemsPerPage) . ',' . (int) ($numItemsPerPage));
	}

	public function fetchTotalPlaces() {
		return $this->db->fetchColumn(
				'SELECT COUNT(*) FROM (SELECT places.name as name, places.id as id  FROM places group by places.name) as egg');
	}

	public function fetchTotalFilterPlaces($filter) {
		$extraWhere = '';

	    // Title set via Filter
	    if ($filter['searchstring'] != '') {
	        $extraWhere .= ' AND '.$filter['genres'].' LIKE ' . $this->db->quote('%'.$filter['searchstring'].'%', \PDO::PARAM_STR);
	    }
	    if($filter['genres']=='school_infos.id'){
	    	return $this->db->fetchColumn(
					'SELECT COUNT(*) FROM places
					LEFT JOIN place_types on place_types.id = places.place_type_id
					Inner JOIN school_infos on school_infos.place_id = places.id
					'. $extraWhere .'
					ORDER BY '.$filter['genres'].' DESC

	        		');
	    }
	    else{
		    return $this->db->fetchColumn(
					'SELECT COUNT(*) FROM places
					LEFT JOIN place_types on place_types.id = places.place_type_id
					LEFT JOIN school_infos on school_infos.place_id = places.id
					'. $extraWhere .'
					ORDER BY '.$filter['genres'].' DESC

	        		');
	    }
	}



	public function findFiltered($filter, $curPage, $numItemsPerPage ) {

	    $extraWhere = '';

	    // Title set via Filter
	    if ($filter['searchstring'] != '') {
	        $extraWhere .= ' AND '.$filter['genres'].' LIKE ' . $this->db->quote('%'.$filter['searchstring'].'%', \PDO::PARAM_STR);
	    }

	    return $this->db->fetchAll(
				'SELECT places.*, places.id as ider, place_dependencies.*, place_types.name as placename, school_infos.id as schoolid, school_infos.* from places
				LEFT JOIN place_dependencies ON place_dependencies.descendant_id = places.id 
				LEFT JOIN place_types on place_types.id = places.place_type_id
				LEFT JOIN school_infos on school_infos.place_id = places.id
				'. $extraWhere .'
				group by places.name
				ORDER BY '.$filter['genres'].' DESC

        		LIMIT ' . (int) (($curPage - 1) * $numItemsPerPage) . ',' . (int) ($numItemsPerPage));
	}


	public function checkIfPlaceAlreadyExists($serial, $uuid) {
		$data1 = $this->db->fetchAll(
				'SELECT * from laptops where uuid LIKE '.$this->db->quote('%'.$uuid.'%', \PDO::PARAM_STR).' AND serial_number LIKE'.$this->db->quote('%'.$serial.'%', \PDO::PARAM_STR));
		$data2 = $this->db->fetchAll(
				'SELECT * from laptops where uuid LIKE '.$this->db->quote('%'.$uuid.'%', \PDO::PARAM_STR));
		$data3 = $this->db->fetchAll(
				'SELECT * from laptops where serial_number LIKE'.$this->db->quote('%'.$serial.'%', \PDO::PARAM_STR));
		if(empty($data1) && empty($data2) && empty($data3)){
			return 0;
		}
		else if(!empty($data1)){
			return 1;
		}
		else if(!empty($data2)){
			return 2;
		}
		else{
			return 3;
		}
	}

	public function deletePlace($placeId) {
		return $this->db->delete('places', array('id' => $placeId));
	}

	public function updatePlace($place) {
		
		$result = 'UPDATE places SET '.
		'name = '. $this->db->quote($place['name'], \PDO::PARAM_STR) . ',' .
		'place_id = '. $this->db->quote($place['place_id'], \PDO::PARAM_STR) . ',' .
		'place_type_id = '. $this->db->quote($place['place_type_id'], \PDO::PARAM_STR) .
		' WHERE id = '.$this->db->quote($place['id'], \PDO::PARAM_INT);
		return $this->db->executeUpdate($result);
	}

	public function FindPlaceId($place) {
		return $this->db->fetchColumn('SELECT id FROM places where serial_number = '. $this->db->quote($place['serial_number'], \PDO::PARAM_STR) .'AND uuid = '. $this->db->quote($laptop['uuid'], \PDO::PARAM_STR));
	}
}