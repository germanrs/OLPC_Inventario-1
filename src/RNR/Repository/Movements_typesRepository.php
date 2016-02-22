<?php

namespace RNR\Repository;

/**
 * @author Robin Staes <robin.staes@student.odisee.be>
 */
class Movements_typesRepository extends \Knp\Repository {

	/**
	 * [getTableName description]
	 * This function returns the table name
	 * @return [string] The name of the table
	 */
	public function getTableName() {
		return 'movements_types';
	}

	public function fetchAll() {
		return $this->db->fetchAll(
				'SELECT movements_types.* FROM movements_types');
	}
}