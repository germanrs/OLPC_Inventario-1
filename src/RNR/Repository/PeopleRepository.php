<?php

namespace RNR\Repository;
/**
 * @author Robin Staes <robin.staes@student.odisee.be>
 */
class PeopleRepository extends \Knp\Repository {

	/**
	 * [getTableName description]
	 * This function returns the table name
	 * @return [string] The name of the table
	 */
	public function getTableName() {
		return 'people';
	}

	public function fetchAll() {
		return $this->db->fetchAll(
				'SELECT people.id as id, CONCAT(people.name," ",people.lastname) as name FROM people');
	}

	/**
	 * get the requested person
	 * @param String $PersonName
	 * @return The requested person
	 */
	public function getPerson($PersonName) {
		$query = 'SELECT id FROM people WHERE CONCAT(people.name," ",people.lastname) = ' . $this->db->quote($PersonName, \PDO::PARAM_INT);
		return $this->db->fetchColumn($query);
	}

	public function fetchAllPeople($curPage, $numItemsPerPage) {
		return $this->db->fetchAll(
				'SELECT people.*, profiles.description as profdescription, places.name as namedescription, place_types.name as typedescription  from people 
				INNER JOIN performs on performs.person_id = people.id
				INNER JOIN profiles on performs.profile_id = profiles.id
				INNER JOIN places on performs.place_id = places.id
				INNER JOIN place_types on place_types.id = places.place_type_id
				ORDER BY people.id DESC
        		LIMIT ' . (int) (($curPage - 1) * $numItemsPerPage) . ',' . (int) ($numItemsPerPage));
	}

	public function fetchTotalpeople() {
		return $this->db->fetchColumn('SELECT COUNT(*) FROM people');
	}

	public function fetchTotalFilterpeople($filter) {
		$extraWhere = '';

	    // Title set via Filter
	    if ($filter['searchstring'] != '') {
	        $extraWhere .= ' AND '.$filter['genres'].' LIKE ' . $this->db->quote('%'.$filter['searchstring'].'%', \PDO::PARAM_STR);

	    }

	    return $this->db->fetchColumn(
				'SELECT Count(*) FROM people 
				INNER JOIN performs on performs.person_id = people.id
				INNER JOIN profiles on performs.profile_id = profiles.id
				INNER JOIN places on performs.place_id = places.id
				INNER JOIN place_types on place_types.id = places.place_type_id
				'. $extraWhere .'
				ORDER BY '.$filter['genres'].' DESC

        		');
	}



	public function findFiltered($filter, $curPage, $numItemsPerPage ) {

	    $extraWhere = '';

	    // Title set via Filter
	    if ($filter['searchstring'] != '') {
	        $extraWhere .= ' AND '.$filter['genres'].' LIKE ' . $this->db->quote('%'.$filter['searchstring'].'%', \PDO::PARAM_STR);

	    }

	    return $this->db->fetchAll(
				'SELECT people.*, profiles.description as profdescription, places.name as namedescription, place_types.name as typedescription from people 
				INNER JOIN performs on performs.person_id = people.id
				INNER JOIN profiles on performs.profile_id = profiles.id
				INNER JOIN places on performs.place_id = places.id
				INNER JOIN place_types on place_types.id = places.place_type_id
				'. $extraWhere .'
				ORDER BY '.$filter['genres'].' DESC

        		LIMIT ' . (int) (($curPage - 1) * $numItemsPerPage) . ',' . (int) ($numItemsPerPage));
	}

	public function deleteperson($peopleID) {
		return $this->db->delete('people', array('id' => $peopleID));
	}

	public function updatePerson($people) {
		
		$result = 'UPDATE people SET '.
		'name = '. $this->db->quote($people['name'], \PDO::PARAM_STR) . ',' .
		'lastname = '. $this->db->quote($people['lastname'], \PDO::PARAM_STR) . ',' .
		'id_document = '. $this->db->quote($people['id_document'], \PDO::PARAM_STR) . ',' .
		'birth_date = '. $this->db->quote($people['birth_date'], \PDO::PARAM_STR) . ',' .
		'phone = '. $this->db->quote($people['phone'], \PDO::PARAM_STR) .',' .
		'email = '. $this->db->quote($people['email'], \PDO::PARAM_STR) .',' .
		'school_name = '. $this->db->quote($people['school_name'], \PDO::PARAM_STR) .',' .
		'barcode = '. $this->db->quote($people['barcode'], \PDO::PARAM_STR) .',' .
		'id_document_created_at = '. $this->db->quote($people['id_document_created_at'], \PDO::PARAM_STR) .',' .
		'notes = '. $this->db->quote($people['notes'], \PDO::PARAM_STR) .
		' WHERE id = '.$this->db->quote($people['id'], \PDO::PARAM_INT);
		
		return $this->db->executeUpdate($result);
	}

	public function updatesmallPerson($people) {
		
		$result = 'UPDATE people SET '.
		'school_name = '. $this->db->quote($people['places'], \PDO::PARAM_STR) .
		' WHERE id = '.$this->db->quote($people['id'], \PDO::PARAM_INT);
		return $this->db->executeUpdate($result);
	}

	public function FindPeopleId($people) {
		return $this->db->fetchColumn('SELECT id FROM people where name = '. $this->db->quote($people['name'], \PDO::PARAM_STR) .'AND lastname = '. $this->db->quote($people['lastname'], \PDO::PARAM_STR) .'AND barcode = '. $this->db->quote($people['barcode'], \PDO::PARAM_STR));
	}

	public function FindPeopleByBarcodeId($barcode) {
		return $this->db->fetchColumn('SELECT id FROM people where barcode = '. $this->db->quote($barcode, \PDO::PARAM_STR));
	}

	public function Lastadded() {
		return $this->db->fetchColumn('SELECT id FROM people order BY ID DESC');
	}


	public function fetchList2($obj) {

	    $extraWhere = '';
	    $orderby ='';/*
	    $new['coloms'] = str_replace("people.region, "," ",$obj['coloms']);
    	$new['coloms'] = str_replace("people.school_name, "," ",$new['coloms']);
    	$new['coloms'] = str_replace(", people.region"," ",$new['coloms']);
    	$new['coloms'] = str_replace(", people.school_name"," ",$new['coloms']);*/

    	if (strpos($obj['coloms'], 'people.region') !== false || strpos($obj['coloms'], 'people.school_name') !== false) {
    		$new['coloms'] = str_replace("people.region, "," ",$obj['coloms']);
    		$new['coloms'] = str_replace("people.school_name, "," ",$new['coloms']);
    		$new['coloms'] = str_replace(", people.region"," ",$new['coloms']);
    		$new['coloms'] = str_replace(", people.school_name"," ",$new['coloms']);
    		$new['coloms'] = $new['coloms'].', places.name as placename ';
    	}
    	else{
    		$new['coloms'] = $obj['coloms'];
    	}
	    // Title set via Filter
	    if ($obj['OrderByTerm'] != 'null') {
	        $orderby .= ' ORDER BY '.$obj['OrderByTerm'].' '.$obj['orderList'];
	    }

	    if ($obj['GroupByTerm'] != 'null' && $obj['inputfield']!='search field...'){
	    	$extraWhere .= ' AND '.$obj['GroupByTerm'].' LIKE ' . $this->db->quote('%'.$obj['inputfield'].'%', \PDO::PARAM_STR);
	    }

	    return $this->db->fetchAll(
				'SELECT people.id as peopleid, ' .$new['coloms'].' from people 
				INNER JOIN performs on performs.person_id = people.id
				INNER JOIN profiles on performs.profile_id = profiles.id
				INNER JOIN places on performs.place_id = places.id
				INNER JOIN place_types on place_types.id = places.place_type_id
				'. $extraWhere 
				. $orderby .
				' limit 100' );
	}

	public function fetchList($obj) {

	    $extraWhere = '';
	    $orderby ='';
	    // Title set via Filter
	    if ($obj['OrderByTerm'] != 'null') {
	        $orderby .= ' ORDER BY '.$obj['OrderByTerm'].' '.$obj['orderList'];
	    }

	    if ($obj['GroupByTerm'] != 'null' && $obj['inputfield']!='search field...'){
	    	$extraWhere .= ' AND '.$obj['GroupByTerm'].' LIKE ' . $this->db->quote('%'.$obj['inputfield'].'%', \PDO::PARAM_STR);
	    }

	    if(!empty($obj['OrderByTerm'])){
	    	if(strpos($obj['OrderByTerm'], 'school')!== false || strpos($obj['OrderByTerm'], 'region')!== false){
	    		$orderby = '';

	    	}
	    }
	    if(!empty($obj['GroupByTerm'])){
	    	if(strpos($obj['GroupByTerm'], 'people.school_name')!== false || strpos($obj['GroupByTerm'], 'people.region')!== false){
	    		$extraWhere = '';
	    	}
	    }

	    if(strpos($obj['coloms'], 'people.region') || strpos($obj['coloms'], 'people.school_name')){
	    	$new['coloms'] = str_replace("people.region, "," ",$obj['coloms']);
	    	$new['coloms'] = str_replace("people.school_name, "," ",$new['coloms']);
	    	$new['coloms'] = str_replace(", people.region"," ",$new['coloms']);
	    	$new['coloms'] = str_replace(", people.school_name"," ",$new['coloms']);
	    	$newarray = array();
	    	$items = $this->db->fetchAll(
	    		'SELECT people.id as peopleid, ' .$new['coloms'].' 
	    		from people 
				INNER JOIN performs on performs.person_id = people.id
				INNER JOIN profiles on performs.profile_id = profiles.id
				INNER JOIN places on performs.place_id = places.id
				INNER JOIN place_types on place_types.id = places.place_type_id
				'. $extraWhere . $orderby
				. ' LIMIT 10' );
	    	$teller = 0;

	    	foreach ($items as $item) {
	    		$teller++;
	    		$datadump = $this->db->fetchAll(
				'SELECT performs.* FROM performs where performs.person_id ='.  $this->db->quote($item['peopleid'], \PDO::PARAM_STR));
	    		

				$datadump = $this->db->fetchAll(
				'SELECT place_dependencies.ancestor_id, places.name, place_type_id, places.id FROM place_dependencies
				inner Join places on places.id = place_dependencies.ancestor_id
				where descendant_id ='.$datadump[0]['place_id'].' 
				And  (place_type_id = 2 OR place_type_id = 4 OR place_type_id = 1 OR place_type_id = 3)
				ORDER BY ancestor_id ASC');
				
				
				if(count($datadump)>1){
					foreach($datadump as $data) {
						if(empty($item['region'])){
							$item['region'] = ' ';
						}						
						if(2==$data['place_type_id'] || 1==$data['place_type_id']){
							$item['region'] = $data['name'];
						}
						if(empty($item['school'])){
							$item['school'] = ' ';
						}
				    	if(4==$data['place_type_id']){
						$item['school'] = $data['name'];
						}						
					}
				}
				else if (count($datadump)==1){
						
					if(empty($item['region'])){
						$item['region'] = ' ';
					}					
					if(2==$datadump[0]['place_type_id'] || 1==$datadump[0]['place_type_id'] || 3==$datadump[0]['place_type_id']){
						$item['region'] = $datadump[0]['name'];
						
					}
					if(empty($item['school'])){
						$item['school'] = ' ';
					}
			    	if(4==$datadump[0]['place_type_id']){
					$item['school'] = $datadump[0]['name'];
					}
					
				}
				array_push($newarray,$item);
				
	    	}
	    	return $newarray;
	    }
	    else{
	    	return $this->db->fetchAll(
	    		'SELECT '.$obj['coloms'].' from people 
				INNER JOIN performs on performs.person_id = people.id
				INNER JOIN profiles on performs.profile_id = profiles.id
				INNER JOIN places on performs.place_id = places.id
				INNER JOIN place_types on place_types.id = places.place_type_id'
				. $extraWhere . ' ' . $orderby
				. ' LIMIT 200');
	    }
	}

	public function fetchAdminPerson($id) {
		return $this->db->fetchAll(
	    		'SELECT people.id, profiles.access_level, people.name from people 
				INNER JOIN performs on performs.person_id = people.id
				INNER JOIN profiles on performs.profile_id = profiles.id
				where people.id ='.  $this->db->quote($id['ID'], \PDO::PARAM_STR));
	}
	


}

