<?php

namespace RNR\Repository;

/**
 * @author Rein Bauwens <rein.bauwens@student.odisee.be>
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
				'SELECT place_dependencies.* FROM place_dependencies where descendant_id ='.$ancestor );
	}

	public function DeleteALL($id) {
		$this->db->delete('place_dependencies', array('ancestor_id' => $id));
		return $this->db->delete('place_dependencies', array('descendant_id' => $id));
	}



	public function fetchAllAncestorsFromSchool($ancestor) {
		return $this->db->fetchAll(
				'SELECT place_dependencies.ancestor_id, places.name, place_type_id, places.id FROM place_dependencies
				inner Join places on places.id = place_dependencies.ancestor_id
				where descendant_id ='.$ancestor);
	}

	public function fetchAllChildren($ancestor) {
		return $this->db->fetchAll(
			'SELECT descendant_id FROM place_dependencies 
			inner Join places on places.id = place_dependencies.descendant_id 
			inner Join place_types on places.place_type_id = place_types.id 
			where ancestor_id = '.$ancestor.' 
			ORDER by place_type_id <> 11, 
				place_types.id <> 5,
                place_types.id <> 6,
                place_types.id <> 7,
                place_types.id <> 8,
                place_types.id <> 9,
                place_types.id <> 10,
                place_types.id <> 13,
                place_types.id <> 14,
                place_types.id <> 16,
                place_types.id <> 17,
                place_types.id <> 19,
				place_type_id <> 12, 
				place_type_id <> 4, 
				place_type_id <> 3, 
				descendant_id DESC');
	}
}

