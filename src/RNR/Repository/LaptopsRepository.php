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

	public function fetchTotalFilterLaptops($filter) {
		$extraWhere = '';

	    // Title set via Filter
	    if ($filter['searchstring'] != '' && $filter['genres']!='people.lastname') {
	        $extraWhere .= ' WHERE '.$filter['genres'].' LIKE ' . $this->db->quote('%'.$filter['searchstring'].'%', \PDO::PARAM_STR);

	    }
	    else if ($filter['searchstring'] != '' && $filter['genres']=='people.lastname'){
	    	$extraWhere .= ' WHERE CONCAT(people.name," ",people.lastname) LIKE ' . $this->db->quote('%'.$filter['searchstring'].'%', \PDO::PARAM_STR);
	    }

	    return $this->db->fetchColumn(
				'SELECT Count(*) FROM laptops 
				INNER JOIN statuses ON statuses.id = laptops.status_Id 
				INNER JOIN models on models.id = laptops.model_id 
				INNER JOIN people on people.id = laptops.owner_id 
				INNER JOIN performs on performs.person_id = laptops.owner_id 
				INNER JOIN places on performs.place_id = places.id
				'. $extraWhere .'
				ORDER BY '.$filter['genres'].' DESC

        		');
	}



	public function findFiltered($filter, $curPage, $numItemsPerPage ) {

	    $extraWhere = '';

	    // Title set via Filter
	    if ($filter['searchstring'] != '' && $filter['genres']!='people.lastname') {
	        $extraWhere .= ' WHERE '.$filter['genres'].' LIKE ' . $this->db->quote('%'.$filter['searchstring'].'%', \PDO::PARAM_STR);

	    }
	    else if ($filter['searchstring'] != '' && $filter['genres']=='people.lastname'){
	    	$extraWhere .= ' WHERE CONCAT(people.name," ",people.lastname) LIKE ' . $this->db->quote('%'.$filter['searchstring'].'%', \PDO::PARAM_STR);
	    }

	    return $this->db->fetchAll(
				'SELECT laptops.id as laptopID, laptops.serial_number, laptops.uuid, people.name as firstname, people.lastname as lastname, CONCAT(people.name," ",people.lastname) AS FullName, people.id as peopleID, places.name as placename, places.id as placeID, models.name as modelName, statuses.description from laptops 
				INNER JOIN statuses ON statuses.id = laptops.status_Id 
				INNER JOIN models on models.id = laptops.model_id 
				INNER JOIN people on people.id = laptops.owner_id 
				INNER JOIN performs on performs.person_id = laptops.owner_id 
				INNER JOIN places on performs.place_id = places.id
				'. $extraWhere .'
				ORDER BY '.$filter['genres'].' DESC

        		LIMIT ' . (int) (($curPage - 1) * $numItemsPerPage) . ',' . (int) ($numItemsPerPage));
	}


	public function checkIfLaptopAlreadyExists($serial, $uuid) {
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

	public function deletelaptop($laptopID) {
		return $this->db->delete('laptops', array('id' => $laptopID));
	}

	public function updateLaptop($laptop) {
		if($laptop['serial_number'] == 'disabled' AND  $laptop['uuid'] == 'disabled'){
			$result = 'UPDATE laptops SET '.
			'model_id = '. $this->db->quote($laptop['model_id'], \PDO::PARAM_STR) . ',' .
			'owner_id = '. $this->db->quote($laptop['owner_id'], \PDO::PARAM_STR) . ',' .
			'status_id = '. $this->db->quote($laptop['status_id'], \PDO::PARAM_STR) .
			' WHERE id = '.$this->db->quote($laptop['id'], \PDO::PARAM_INT);
		}
		else{
			$result = 'UPDATE laptops SET '.
			'serial_number = '. $this->db->quote($laptop['serial_number'], \PDO::PARAM_STR) . ',' .
			'model_id = '. $this->db->quote($laptop['model_id'], \PDO::PARAM_STR) . ',' .
			'owner_id = '. $this->db->quote($laptop['owner_id'], \PDO::PARAM_STR) . ',' .
			'status_id = '. $this->db->quote($laptop['status_id'], \PDO::PARAM_STR) . ',' .
			'uuid = '. $this->db->quote($laptop['uuid'], \PDO::PARAM_STR) .
			' WHERE id = '.$this->db->quote($laptop['id'], \PDO::PARAM_INT);
		}
		return $this->db->executeUpdate($result);
	}

	public function FindLaptopId($laptop) {
		return $this->db->fetchColumn('SELECT id FROM laptops where serial_number = '. $this->db->quote($laptop['serial_number'], \PDO::PARAM_STR) .'AND uuid = '. $this->db->quote($laptop['uuid'], \PDO::PARAM_STR));
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
	    //return 'SELECT '.$obj['coloms'].' FROM laptops INNER JOIN statuses ON statuses.id = laptops.status_Id INNER JOIN models on models.id = laptops.model_id INNER JOIN people on people.id = laptops.owner_id INNER JOIN performs on performs.person_id = laptops.owner_id INNER JOIN places on performs.place_id = places.id' . $extraWhere .' ' . $orderby;
	
	    return $this->db->fetchAll(
				'SELECT '.$obj['coloms'].' FROM laptops INNER JOIN statuses ON statuses.id = laptops.status_Id INNER JOIN models on models.id = laptops.model_id INNER JOIN people on people.id = laptops.owner_id INNER JOIN performs on performs.person_id = laptops.owner_id INNER JOIN places on performs.place_id = places.id' . $extraWhere . ' ' . $orderby);
	
	}


	
}

