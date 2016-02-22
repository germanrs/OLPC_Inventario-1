<?php

namespace RNR\Repository;

/**
 * @author Robin Staes <robin.staes@student.odisee.be>
 */
class StatusesRepository extends \Knp\Repository {

	/**
	 * [getTableName description]
	 * This function returns the table name
	 * @return [string] The name of the table
	 */
	public function getTableName() {
		return 'statuses';
	}

	public function fetchAll() {
		return $this->db->fetchAll(
				'SELECT statuses.id as id, statuses.description as name FROM statuses');
	}
}