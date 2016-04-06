<?php

namespace RNR\Repository;

/**
 * @author Rein Bauwens <rein.bauwens@student.odisee.be>
 */
class SchoolinfosRepository extends \Knp\Repository {

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

	public function deleteSchool($place_id) {
		return $this->db->delete('school_infos', array('place_id' => $place_id));
	}

	public function updateSchool($place) {
		$query = 'SELECT school_infos.* FROM school_infos WHERE place_id = ' . $this->db->quote($place['place_id'], \PDO::PARAM_INT);
		$school = $this->db->fetchColumn($query);
		if(empty($school)){
			return $this->db->insert('school_infos', $place);

		}
		else{
			$result = 'UPDATE school_infos SET '.
			'server_hostname = '. $this->db->quote($place['server_hostname'], \PDO::PARAM_STR)  .
			' WHERE place_id = '.$this->db->quote($place['place_id'], \PDO::PARAM_INT);
			return $this->db->executeUpdate($result);
		}
		
	}
}