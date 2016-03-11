<?php

namespace RNR\Repository;

/**
 * @author Robin Staes <robin.staes@student.odisee.be>
 */
class PlacesDependenciesRepository extends \Knp\Repository {

	/**
	 * [getTableName description]
	 * This function returns the table name
	 * @return [string] The name of the table
	 */
	public function getTableName() {
		return 'place_dependencies';
	}

	public function fetchAll() {
		return $this->db->fetchAll(
				'SELECT place_dependencies.* FROM place_dependencies');
	}

	public function fetchAllAncestors($ancestor) {
		return $this->db->fetchAll(
				'SELECT place_dependencies.* FROM place_dependencies where descendant_id ='.$ancestor.' AND ancestor_id <='.$ancestor.' ORDER BY ancestor_id DESC' );
	}

	public function DeleteALL($id) {
		return $this->db->delete('place_dependencies', array('descendant_id' => $id));
	}

	public function fetchAllAncestorsFromSchool($ancestor) {
		return $this->db->fetchAll(
				'SELECT place_dependencies.ancestor_id, places.name, place_type_id, places.id FROM place_dependencies
				inner Join places on places.id = place_dependencies.ancestor_id
				where descendant_id ='.$ancestor.' AND ancestor_id <='.$ancestor.' 
				And  (place_type_id = 2 OR place_type_id = 4)
				ORDER BY ancestor_id DESC');
	}
}

