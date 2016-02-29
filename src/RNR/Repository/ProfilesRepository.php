<?php

namespace RNR\Repository;

/**
 * @author Robin Staes <robin.staes@student.odisee.be>
 */
class ProfilesRepository extends \Knp\Repository {

	/**
	 * [getTableName description]
	 * This function returns the table name
	 * @return [string] The name of the table
	 */
	public function getTableName() {
		return 'profiles';
	}

	public function fetchAll() {
		return $this->db->fetchAll(
				'SELECT profiles.id as id, profiles.description as name FROM profiles');
	}

	/**
	 * get the requested person
	 * @param String $PersonName
	 * @return The requested person
	 */
	public function getProfile($profiledescription) {
		$query = 'SELECT id FROM profiles WHERE description = ' . $this->db->quote($profiledescription, \PDO::PARAM_INT);
		return $this->db->fetchColumn($query);
	}

}