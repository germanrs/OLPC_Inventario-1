<?php

namespace RNR\Repository;

/**
 * @author Robin Staes <robin.staes@student.odisee.be>
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

	/**
	 * [findUserByEmail description]
	 * This function finds a user by e-mail
	 * @param  [string] $email The e-mail of the user
	 * @return [string] An associative array that represents the user that has been found
	 */
	public function findUserByName($usuario) {
		return $this->db->fetchAssoc('SELECT * FROM '. $this->getTableName() . ' WHERE usuario = ?', array($usuario));
	}
}