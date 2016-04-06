<?php

namespace RNR\Repository;

/**
 * @author Rein Bauwens <rein.bauwens@student.odisee.be>
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

	public function fetchAllschools() {
		return $this->db->fetchAll(
				'SELECT places.name as name, places.id as id  FROM places where (places.place_type_id=4 OR places.place_type_id=3 OR places.place_type_id=2 OR places.place_type_id=1) group by places.name ');
	}

	/**
	 * get the requested person
	 * @param String $PersonName
	 * @return The requested person
	 */
	public function getPlace($placename, $place_type_id) {
		$query = 'SELECT id FROM places WHERE name = ' . $this->db->quote($placename, \PDO::PARAM_INT).'AND place_type_id = '. $this->db->quote($place_type_id, \PDO::PARAM_INT);
		return $this->db->fetchColumn($query);
	}

	public function getPlaceonlyoneName($placename) {
		$query = 'SELECT id, place_type_id FROM places WHERE name = ' . $this->db->quote($placename, \PDO::PARAM_INT).' AND (place_type_id = 1 OR place_type_id = 2 OR place_type_id = 3 OR place_type_id = 4)';
		return $this->db->fetchAll($query);
	}

	public function getSchool($placename, $id) {
		$query = 'SELECT places.id FROM places 
		Inner join place_dependencies on places.id = descendant_id
		WHERE `ancestor_id`= '. $this->db->quote($id, \PDO::PARAM_STR).'
		and place_type_id = 4 
		and name = '.$this->db->quote($placename, \PDO::PARAM_STR).' limit 1';
		return $this->db->fetchColumn($query);
	}

	public function getPlaceById($id) {
		$query = 'SELECT places.* FROM places WHERE id = ' . $this->db->quote($id, \PDO::PARAM_INT);
		return $this->db->fetchAll($query);
	}

	public function getPlaceByName($placename) {
		$query = 'SELECT id FROM places where name = ' . $this->db->quote($placename, \PDO::PARAM_INT)	;
		return $this->db->fetchColumn($query);
	}

	public function getCityByName($placename) {
		$query = 'SELECT id FROM places WHERE place_type_id = 3 AND name = ' . $this->db->quote($placename, \PDO::PARAM_INT)	;
		return $this->db->fetchColumn($query);
	}

	

	public function getitemByNameandAncestorID($placename, $cityid) {
		$query = 'SELECT id FROM places WHERE name = ' . $this->db->quote($placename, \PDO::PARAM_INT).' and place_id = '. $this->db->quote($cityid, \PDO::PARAM_INT);
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
	        $extraWhere .= ' WHERE '.$filter['genres'].' LIKE ' . $this->db->quote('%'.$filter['searchstring'].'%', \PDO::PARAM_STR);
	    }
	    if($filter['genres']=='school_infos.server_hostname'){
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
					'SELECT COUNT(*) FROM 
						(SELECT places.id as ider, place_dependencies.*, place_types.name as placename from places
						LEFT JOIN place_dependencies ON place_dependencies.descendant_id = places.id 
						LEFT JOIN place_types on place_types.id = places.place_type_id
						'. $extraWhere .'
						group by places.name
						ORDER BY '.$filter['genres'].' DESC) as test
	        		');
	    }
	}



	public function findFiltered($filter, $curPage, $numItemsPerPage ) {

	    $extraWhere = '';

	    // Title set via Filter
	    if ($filter['searchstring'] != '') {
	        $extraWhere .= ' WHERE '.$filter['genres'].' LIKE ' . $this->db->quote('%'.$filter['searchstring'].'%', \PDO::PARAM_STR);
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
		'name = '. $this->db->quote($place['name'], \PDO::PARAM_STR) .
		' WHERE id = '.$this->db->quote($place['id'], \PDO::PARAM_INT);
		return $this->db->executeUpdate($result);
	}

	public function updateSmallPlace($place) {
		
		$result = 'UPDATE places SET '.
		'place_type_id = '. $this->db->quote($place['place_type_id'], \PDO::PARAM_STR) .
		' WHERE id = '.$this->db->quote($place['id'], \PDO::PARAM_INT);
		return $this->db->executeUpdate($result);
	}

	public function FindPlaceId($place) {
		return $this->db->fetchColumn('SELECT id FROM places where serial_number = '. $this->db->quote($place['serial_number'], \PDO::PARAM_STR) .'AND uuid = '. $this->db->quote($laptop['uuid'], \PDO::PARAM_STR));
	}

	public function getTimeOfPlace($id, $name) {
		return $this->db->fetchColumn('SELECT places.id FROM `place_dependencies`
		Inner join places on places.id = descendant_id
		WHERE `ancestor_id`= '. $this->db->quote($id, \PDO::PARAM_STR).' 
		and name = '.$this->db->quote($name, \PDO::PARAM_STR).' limit 1');
	}

	public function getgradeOfPlace($id, $name) {
		return $this->db->fetchColumn('SELECT places.id FROM `place_dependencies`
		Inner join places on places.id = descendant_id
		WHERE `ancestor_id`= '. $this->db->quote($id, \PDO::PARAM_STR).' 
		and name = '.$this->db->quote($name, \PDO::PARAM_STR));
	}

	public function getSeccionOfPlace($id, $name) {
		return $this->db->fetchColumn('SELECT places.id FROM `place_dependencies`
		Inner join places on places.id = descendant_id
		WHERE `ancestor_id`= '. $this->db->quote($id, \PDO::PARAM_STR).' 
		and name = '.$this->db->quote($name, \PDO::PARAM_STR));
	}



	public function getalldescendants($id) {
		return $this->db->fetchAll('SELECT place_dependencies.*, places.* FROM `place_dependencies`
		Inner join places on places.id = descendant_id
		WHERE `ancestor_id`= '. $this->db->quote($id, \PDO::PARAM_STR));
	}

	public function fetchList($obj) {
		$extraWhere = '';
	    $orderby ='';

	    // Title set via Filter
	    if ($obj['OrderByTerm'] != 'null') {
	        $orderby .= ' ORDER BY '.$obj['OrderByTerm'].' '.$obj['orderList'];
	    }
	    if ($obj['GroupByTerm'] != 'null' && $obj['inputfield']!='search field...'){
	    	$extraWhere .= ' WHERE '.$obj['GroupByTerm'].' LIKE ' . $this->db->quote('%'.$obj['inputfield'].'%', \PDO::PARAM_STR);
	    }

		return $this->db->fetchAll(
				'SELECT '.$obj['coloms'].' from places
				LEFT JOIN place_dependencies ON place_dependencies.descendant_id = places.id 
				LEFT JOIN place_types on place_types.id = places.place_type_id
				LEFT JOIN school_infos on school_infos.place_id = places.id
				'. $extraWhere . ' ' . $orderby);
	}

	public function fetchcountry() {
		return $this->db->fetchAll(
				'SELECT places.id, places.name from places
				where places.place_type_id = 1');
	}

	public function Lastadded() {
		return $this->db->fetchColumn('SELECT id FROM places order BY ID DESC');
	}

	public function fetchstate($idcountry) {
		return $this->db->fetchAll(
				'SELECT places.id, places.name from places
				inner join place_dependencies on descendant_id = places.id
				where places.place_type_id = 2
				and ancestor_id ='.$idcountry.' order by name');
	}

	public function fetchCity($idstate) {
		return $this->db->fetchAll(
				'SELECT places.id, places.name from places
				inner join place_dependencies on descendant_id = places.id
				where places.place_type_id = 3
				and ancestor_id ='.$idstate.' order by name');
	}

	public function fetchSchool($idcity) {
		return $this->db->fetchAll(
				'SELECT places.id, places.name from places
				inner join place_dependencies on descendant_id = places.id
				where places.place_type_id = 4
				and ancestor_id ='.$idcity.' order by name');
	}

	public function fetchSchoolwithservername($idcity) {
		return $this->db->fetchAll(
				'SELECT places.id, places.name, school_infos.server_hostname from places
				inner join place_dependencies on descendant_id = places.id
				LEFT join school_infos on school_infos.place_id = places.id
				where places.place_type_id = 4
				and ancestor_id ='.$idcity.' order by name');
	}
	

	public function fetchTurno($idschool) {
		return $this->db->fetchAll(
				'SELECT places.id, places.name from places
				inner join place_dependencies on descendant_id = places.id
				where places.place_type_id = 12
				and ancestor_id ='.$idschool.' order by name');
	}

	public function fetchGrade($idTurno) {
		return $this->db->fetchAll(
				'SELECT places.id, places.name from places
				where place_id ='.$idTurno.' order by place_type_id');
	}

	public function fetchSeccion($idSeccion) {
		return $this->db->fetchAll(
				'SELECT places.id, places.name from places
				where place_id ='.$idSeccion.' order by name');
	}
}
