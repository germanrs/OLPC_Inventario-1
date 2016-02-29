<?php

namespace RNR\Repository;

/**
 * @author Robin Staes <robin.staes@student.odisee.be>
 */
class PlacestypesRepository extends \Knp\Repository {

	/**
	 * [getTableName description]
	 * This function returns the table name
	 * @return [string] The name of the table
	 */
	public function getTableName() {
		return 'place_types';
	}

	public function fetchAll() {
		return $this->db->fetchAll(
				'SELECT name as name, id as id  FROM place_types');
	}

	/**
	 * get the requested Status
	 * @param String $StatusDescription
	 * @return The requested status
	 */
	public function getGrade($name) {
		$query = 'SELECT id FROM place_types WHERE name = ' . $this->db->quote($name, \PDO::PARAM_INT);
		return $this->db->fetchColumn($query);
	}
}