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
}