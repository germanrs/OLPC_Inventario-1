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

	public function fetchAllgrades() {
		return $this->db->fetchAll(
				'SELECT name as name, id as id  FROM place_types where place_types.id = 5 OR
																		place_types.id = 6 OR
																		place_types.id = 7 OR
																		place_types.id = 8 OR
																		place_types.id = 9 OR
																		place_types.id = 10 OR
																		place_types.id = 13 OR
																		place_types.id = 14 OR
																		place_types.id = 16 OR
																		place_types.id = 17 OR
																		place_types.id = 18
																		 ');
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