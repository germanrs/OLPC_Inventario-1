<?php

namespace RNR\Repository;

/**
 * @author Robin Staes <robin.staes@student.odisee.be>
 */
class ModelsRepository extends \Knp\Repository {

	/**
	 * [getTableName description]
	 * This function returns the table name
	 * @return [string] The name of the table
	 */
	public function getTableName() {
		return 'models';
	}

	public function fetchAll() {
		return $this->db->fetchAll(
				'SELECT models.id as id, models.name as name FROM models');
	}
}