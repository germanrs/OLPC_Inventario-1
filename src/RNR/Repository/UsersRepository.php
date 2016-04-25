<?php

namespace RNR\Repository;

/**
 * @author Rein Bauwens <rein.bauwens@student.odisee.be>
 */
class UsersRepository extends \Knp\Repository {

	/**
	 * [getTableName description]
	 * This function returns the table name
	 * @return [string] The name of the table
	 */
	public function getTableName() {
		return 'users';
	}

	public function fetchAll() {
		return $this->db->fetchAll(
				'SELECT users.id, users.usuario FROM statuses');
	}

	/**
	 * [findUserByEmail description]
	 * This function finds a user by e-mail
	 * @param  [string] $email The e-mail of the user
	 * @return [string] An associative array that represents the user that has been found
	 */
	public function findUserByName($usuario) {
		return $this->db->fetchAssoc('SELECT * FROM '. $this->getTableName() . ' WHERE usuario = ?', array($usuario));
	}

	public function getUsers($userid) {
		return $this->db->fetchAll(
				'SELECT users.id, usuario, profiles.description FROM users 
					INNER JOIN people ON person_id = people.id 
					INNER JOIN performs ON people.id = performs.person_id 
					INNER JOIN profiles ON performs.profile_id = profiles.id
					where people.id != '.$userid);
	}
	public function getUser($userid) {
		return $this->db->fetchAll(
				'SELECT users.id, usuario, profiles.description FROM users 
					INNER JOIN people ON person_id = people.id 
					INNER JOIN performs ON people.id = performs.person_id 
					INNER JOIN profiles ON performs.profile_id = profiles.id
					where users.id = '.$userid);
	}

	public function getUsersInfo($user){
		return $this->db->fetchAssoc('SELECT users.id, users.usuario FROM '. $this->getTableName() . ' WHERE usuario = ?', array($user));
	}

	public function deleteUser($userID) {
		return $this->db->delete('users', array('id' => $userID));
	}

	public function updateUser($user) {
		
		$result = 'UPDATE users SET '.
		'usuario = '.
		'school_name = '. $this->db->quote($people['school_name'], \PDO::PARAM_STR) .
		' WHERE id = '.$this->db->quote($people['id'], \PDO::PARAM_INT);
		return $this->db->executeUpdate($result);
	}

	public function Lastadded() {
		return $this->db->fetchAssoc('SELECT id FROM users order BY ID DESC LIMIT 1');
	}

}