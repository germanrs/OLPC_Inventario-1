<?php

namespace RNR\Repository;

/**
 * @author Robin Staes <robin.staes@student.odisee.be>
 */
class School_infosRepository extends \Knp\Repository {

	/**
	 * [getTableName description]
	 * This function returns the table name
	 * @return [string] The name of the table
	 */
	public function getTableName() {
		return 'school_infos';
	}

	public function fetchAll() {
		return $this->db->fetchAll(
				'SELECT school_infos.* FROM school_infos');
	}
}