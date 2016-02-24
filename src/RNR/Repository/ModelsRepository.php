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

	/**
	 * get the requested model
	 * @param String $modelName
	 * @return The requested model
	 */
	public function getModel($modelName) {
		$query = 'SELECT id FROM models WHERE name = ' . $this->db->quote($modelName, \PDO::PARAM_INT);
		return $this->db->fetchColumn($query);
	}
}