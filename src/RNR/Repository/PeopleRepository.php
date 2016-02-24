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
}