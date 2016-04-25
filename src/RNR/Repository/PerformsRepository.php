<?php

namespace RNR\Repository;

/**
 * @author Rein Bauwens <rein.bauwens@student.odisee.be>
 */
class PerformsRepository extends \Knp\Repository {

	/**
	 * [getTableName description]
	 * This function returns the table name
	 * @return [string] The name of the table
	 */
	public function getTableName() {
		return 'performs';
	}

	public function fetchAll() {
		return $this->db->fetchAll(
				'SELECT performs.* FROM performs');
	}

	public function deleteperson($peopleID) {
		return $this->db->delete('performs', array('person_id' => $peopleID));
	}

	public function updatePerform($perform) {
		
		$result = 'UPDATE performs SET '.
		'place_id = '. $this->db->quote($perform['place_id'], \PDO::PARAM_STR) . ',' .
		'profile_id = '. $this->db->quote($perform['profile_id'], \PDO::PARAM_STR) .
		' WHERE person_id = '.$this->db->quote($perform['person_id'], \PDO::PARAM_INT);
		
		return $this->db->executeUpdate($result);
	}

	public function updatePerformProfile($perform) {
		
		$result = 'UPDATE performs SET '.
		'profile_id = '. $this->db->quote($perform['profile_id'], \PDO::PARAM_STR) .
		' WHERE person_id = '.$this->db->quote($perform['person_id'], \PDO::PARAM_INT);
		
		return $this->db->executeUpdate($result);
	}

	public function fetchAllByPersonId($person_id) {
		return $this->db->fetchAll(
				'SELECT performs.* FROM performs where performs.person_id ='.  $this->db->quote($person_id, \PDO::PARAM_STR));
	}

	public function fetchAllByPlaceid($placeid) {
		return $this->db->fetchAll(
				'SELECT performs.person_id FROM performs where performs.place_id ='.  $this->db->quote($placeid, \PDO::PARAM_STR));
	}


}