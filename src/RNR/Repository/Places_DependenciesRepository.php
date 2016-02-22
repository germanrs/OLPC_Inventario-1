<?php

namespace RNR\Repository;

/**
 * @author Robin Staes <robin.staes@student.odisee.be>
 */
class Places_DependenciesRepository extends \Knp\Repository {

	/**
	 * [getTableName description]
	 * This function returns the table name
	 * @return [string] The name of the table
	 */
	public function getTableName() {
		return 'places_dependencies';
	}

	public function fetchAll() {
		return $this->db->fetchAll(
				'SELECT places_dependencies.* FROM places_dependencies');
	}
}