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
				'SELECT people.*, profiles.description as profdescription, places.name as namedescription, place_types.name as typedescription  from people 
				INNER JOIN performs on performs.person_id = people.id
				INNER JOIN profiles on performs.profile_id = profiles.id
				INNER JOIN places on performs.place_id = places.id
				INNER JOIN place_types on place_types.id = places.place_type_id
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
				INNER JOIN place_types on place_types.id = places.place_type_id
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
				'SELECT people.*, profiles.description as profdescription, places.name as namedescription, place_types.name as typedescription from people 
				INNER JOIN performs on performs.person_id = people.id
				INNER JOIN profiles on performs.profile_id = profiles.id
				INNER JOIN places on performs.place_id = places.id
				INNER JOIN place_types on place_types.id = places.place_type_id
				'. $extraWhere .'
				ORDER BY '.$filter['genres'].' DESC

        		LIMIT ' . (int) (($curPage - 1) * $numItemsPerPage) . ',' . (int) ($numItemsPerPage));
	}

	public function deleteperson($peopleID) {
		return $this->db->delete('people', array('id' => $peopleID));
	}

	public function updatePerson($people) {
		
		$result = 'UPDATE people SET '.
		'name = '. $this->db->quote($people['name'], \PDO::PARAM_STR) . ',' .
		'lastname = '. $this->db->quote($people['lastname'], \PDO::PARAM_STR) . ',' .
		'id_document = '. $this->db->quote($people['id_document'], \PDO::PARAM_STR) . ',' .
		'birth_date = '. $this->db->quote($people['birth_date'], \PDO::PARAM_STR) . ',' .
		'phone = '. $this->db->quote($people['phone'], \PDO::PARAM_STR) .',' .
		'email = '. $this->db->quote($people['email'], \PDO::PARAM_STR) .',' .
		'school_name = '. $this->db->quote($people['school_name'], \PDO::PARAM_STR) .',' .
		'barcode = '. $this->db->quote($people['barcode'], \PDO::PARAM_STR) .',' .
		'id_document_created_at = '. $this->db->quote($people['id_document_created_at'], \PDO::PARAM_STR) .',' .
		'notes = '. $this->db->quote($people['notes'], \PDO::PARAM_STR) .
		' WHERE id = '.$this->db->quote($people['id'], \PDO::PARAM_INT);
		
		return $this->db->executeUpdate($result);
	}

	public function updatesmallPerson($people) {
		
		$result = 'UPDATE people SET '.
		'school_name = '. $this->db->quote($people['places'], \PDO::PARAM_STR) .
		' WHERE id = '.$this->db->quote($people['id'], \PDO::PARAM_INT);
		return $this->db->executeUpdate($result);
	}

	public function FindPeopleId($people) {
		return $this->db->fetchColumn('SELECT id FROM people where name = '. $this->db->quote($people['name'], \PDO::PARAM_STR) .'AND lastname = '. $this->db->quote($people['lastname'], \PDO::PARAM_STR) .'AND barcode = '. $this->db->quote($people['barcode'], \PDO::PARAM_STR));
	}
}