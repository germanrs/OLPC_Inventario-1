<?php

namespace RNR\Repository;

/**
 * @author Robin Staes <robin.staes@student.odisee.be>
 */
class PeopleRepository extends \Knp\Repository {

	/**
	 * [getTableName description]
	 * This function returns the table name
	 * @return [string] The name of the table
	 */
	public function getTableName() {
		return 'people';
	}

	public function fetchAll() {
		return $this->db->fetchAll(
				'SELECT people.id as id, CONCAT(people.name," ",people.lastname) as name FROM people');
	}

	/**
	 * get the requested person
	 * @param String $PersonName
	 * @return The requested person
	 */
	public function getPerson($PersonName) {
		$query = 'SELECT id FROM people WHERE CONCAT(people.name," ",people.lastname) = ' . $this->db->quote($PersonName, \PDO::PARAM_INT);
		return $this->db->fetchColumn($query);
	}

	public function fetchAllPeople($curPage, $numItemsPerPage) {
		return $this->db->fetchAll(
				'SELECT people.*, profiles.description as profdescription, places.name as namedescription from people 
				INNER JOIN performs on performs.person_id = people.id
				INNER JOIN profiles on performs.profile_id = profiles.id
				INNER JOIN places on performs.place_id = places.id
				ORDER BY people.id DESC
        		LIMIT ' . (int) (($curPage - 1) * $numItemsPerPage) . ',' . (int) ($numItemsPerPage));
	}

	public function fetchTotalpeople() {
		return $this->db->fetchColumn('SELECT COUNT(*) FROM people');
	}

	public function fetchTotalFilterpeople($filter) {
		$extraWhere = '';

	    // Title set via Filter
	    if ($filter['searchstring'] != '') {
	        $extraWhere .= ' AND '.$filter['genres'].' LIKE ' . $this->db->quote('%'.$filter['searchstring'].'%', \PDO::PARAM_STR);

	    }

	    return $this->db->fetchColumn(
				'SELECT Count(*) FROM people 
				INNER JOIN performs on performs.person_id = people.id
				INNER JOIN profiles on performs.profile_id = profiles.id
				INNER JOIN places on performs.place_id = places.id
				'. $extraWhere .'
				ORDER BY '.$filter['genres'].' DESC

        		');
	}



	public function findFiltered($filter, $curPage, $numItemsPerPage ) {

	    $extraWhere = '';

	    // Title set via Filter
	    if ($filter['searchstring'] != '') {
	        $extraWhere .= ' AND '.$filter['genres'].' LIKE ' . $this->db->quote('%'.$filter['searchstring'].'%', \PDO::PARAM_STR);

	    }

	    return $this->db->fetchAll(
				'SELECT people.*, profiles.description as profdescription, places.name as namedescription from people 
				INNER JOIN performs on performs.person_id = people.id
				INNER JOIN profiles on performs.profile_id = profiles.id
				INNER JOIN places on performs.place_id = places.id
				'. $extraWhere .'
				ORDER BY '.$filter['genres'].' DESC

        		LIMIT ' . (int) (($curPage - 1) * $numItemsPerPage) . ',' . (int) ($numItemsPerPage));
	}

	public function deletePeople($peopleID) {
		return $this->db->delete('people', array('id' => $peopleID));
	}

	public function updateLaptop($people) {
		
		$result = 'UPDATE people SET '.
		'serial_number = '. $this->db->quote($laptop['serial_number'], \PDO::PARAM_STR) . ',' .
		'model_id = '. $this->db->quote($laptop['model_id'], \PDO::PARAM_STR) . ',' .
		'owner_id = '. $this->db->quote($laptop['owner_id'], \PDO::PARAM_STR) . ',' .
		'status_id = '. $this->db->quote($laptop['status_id'], \PDO::PARAM_STR) . ',' .
		'uuid = '. $this->db->quote($laptop['uuid'], \PDO::PARAM_STR) .
		' WHERE id = '.$this->db->quote($laptop['id'], \PDO::PARAM_INT);
		
		return $this->db->executeUpdate($result);
	}

	public function FindPeopleId($people) {
		return $this->db->fetchColumn('SELECT id FROM people where name = '. $this->db->quote($people['name'], \PDO::PARAM_STR) .'AND lastname = '. $this->db->quote($people['lastname'], \PDO::PARAM_STR));
	}
}