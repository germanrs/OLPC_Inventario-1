<?php

namespace Ikdoeict\Repository;

class MusicRepository extends \Knp\Repository {

	public function getTableName() {
		return 'authors';
	}

	public function fetchAllAlbums($curPage, $numItemsPerPage) {
		return $this->db->fetchAll(
				'SELECT albums.*, artists.title as naam from albums INNER JOIN artists ON albums.artist_id = artists.id
				ORDER BY id DESC
        		LIMIT ' . (int) (($curPage - 1) * $numItemsPerPage) . ',' . (int) ($numItemsPerPage));
	}

	public function fetchAantalAlbums() {
		return $this->db->fetchColumn('SELECT COUNT(*) FROM albums');
	}

	public function fetchAantalFilterAlbums($filter) {
		$extraJoins = '';
	    $extraWhere = '';

	    // Title set via Filter
	    if ($filter['title'] != '') {
	        $extraWhere .= ' AND albums.title LIKE ' . $this->db->quote('%'.$filter['title'].'%', \PDO::PARAM_STR);
	        //$extraWhere .= ' OR artists.title LIKE ' . $this->db->quote('%'.$filter['title'].'%', \PDO::PARAM_STR);
	    }

	    // Type set via Filter
	    if ($filter['genres'] != '') {
	        $extraJoins .= ' INNER JOIN genres ON albums.genre_id = genres.id';
	        $extraWhere .= ' AND genres.id = ' . $this->db->quote($filter['genres']+1, \PDO::PARAM_INT);
	    }

	    // Brand set via filter
	    if ($filter['year'] != '') {
	        $extraWhere .= ' AND released = ' . $this->db->quote($filter['year'], \PDO::PARAM_INT);
	    }

	    return $this->db->fetchColumn(
	    	'SELECT Count(*) from albums INNER JOIN artists ON albums.artist_id = artists.id' . $extraJoins .$extraWhere);
	}



	public function findFiltered($filter, $curPage, $numItemsPerPage ) {

	    $extraJoins = '';
	    $extraWhere = '';

	    // Title set via Filter
	    if ($filter['title'] != '') {
	        $extraWhere .= ' AND albums.title LIKE ' . $this->db->quote('%'.$filter['title'].'%', \PDO::PARAM_STR);
	        //$extraWhere .= ' OR artists.title LIKE ' . $this->db->quote('%'.$filter['title'].'%', \PDO::PARAM_STR);

	    }

	    // genres set via Filter
	    if ($filter['genres'] != '') {
	        $extraJoins .= ' INNER JOIN genres ON albums.genre_id = genres.id';
	        $extraWhere .= ' AND genres.id = ' . $this->db->quote($filter['genres']+1, \PDO::PARAM_INT);
	    }

	    // year set via filter
	    if ($filter['year'] != '') {
	        $extraWhere .= ' AND released = ' . $this->db->quote($filter['year'], \PDO::PARAM_INT);
	    }

	    return $this->db->fetchAll('
	        SELECT albums.*, artists.title as naam from albums INNER JOIN artists ON albums.artist_id = artists.id' . $extraJoins .
	        $extraWhere . '
	        ORDER BY id DESC
	        LIMIT ' . (int) (($curPage - 1) * $numItemsPerPage) . ',' .
	        (int) ($numItemsPerPage),
	        array('N')
	    );
	}
}