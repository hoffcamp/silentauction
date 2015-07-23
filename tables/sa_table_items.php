<?php

class SA_ItemsTable extends SA_Table
{
	function install(){
		$this->_createTable(
			"CREATE TABLE `".$this->name."` ( 
			`ID` INT(11) NOT NULL AUTO_INCREMENT,
			`eventID` INT(11) DEFAULT '0' NOT NULL ,
			`title` VARCHAR( 255 ) DEFAULT '' NOT NULL ,
			`description` TEXT DEFAULT '' NOT NULL ,
			`value` FLOAT DEFAULT '0.0' NOT NULL ,
			`startBid` FLOAT DEFAULT '0.0' NOT NULL ,
			`minIncrease` FLOAT DEFAULT '0.0' NOT NULL ,
			`paid` TINYINT(1) DEFAULT '0' NOT NULL ,
			`winningBidderID` INT(11) DEFAULT '0' NOT NULL ,
			`winningBid` FLOAT DEFAULT '0.0' NOT NULL ,
			PRIMARY KEY ( `ID` )
			)" );
	}
	
	// [ ID ]
	function add( $eventID, $title, $description, $value, $startBid, $minIncrease ){
		global $wpdb; 
		$wpdb-> query(
		$wpdb-> prepare(
			"INSERT INTO `{$this->name}` (`eventID`, `title`, `description`, `value`, `startBid`, `minIncrease` ) VALUES ('%d', '%s', '%s', '%f', '%f', '%f' )",
			$eventID, $title, $description, $value, $startBid, $minIncrease ) );
		$results = $wpdb->get_row( 'SELECT LAST_INSERT_ID() as `ID`;', ARRAY_A );
		return $results[ 'ID' ];
	}
	
	// true
	function update( $ID, $title, $description, $value, $startBid, $minIncrease ){
		global $wpdb;
		$result = $wpdb-> query(
		$wpdb-> prepare( 
			"UPDATE `{$this->name}` SET `title` = '%s', `description` = '%s', `value` = '%f', `startBid` = '%f', `minIncrease` = '%f' WHERE `ID` = %d;",
			$title, $description, $value, $startBid, $minIncrease, $ID ) );	
		if ( $result === false ) { return $result; }
		return true;
	}
	
	// [ [*], ... ]
	function getAll( $ascending ){
		return $this->_getAll( 'title', $ascending );
	}
	
	// true; set winning bid information. fail if already marked as won.
	function close( $ID, $winningBidderID, $winningBid ){
		global $wpdb;
		$result = $wpdb-> query(
		$wpdb-> prepare( 
			"UPDATE `{$this->name}` SET `winningBidderID` ='%d', `winningBid` ='%f' WHERE `ID` = %d;",
			$winningBidderID, $winningBid, $ID ) );	
		if ( $result === false ) { return $result; }
		return true;
	}
	
	// true; clears the winning bid
	function reopen( $ID ){
		global $wpdb;
		$result = $wpdb-> query(
		$wpdb-> prepare( 
			"UPDATE `{$this->name}` SET `winningBidderID` ='0', `winningBid` ='0.0' WHERE `ID` = %d;",
			$ID ) );	
		if ( $result === false ) { return $result; }
		return true;
	}
	
	// true
	function setPaid( $ID, $paid ){
		global $wpdb;
		$result = $wpdb-> query(
		$wpdb-> prepare( 
			"UPDATE `{$this->name}` SET `paid` ='%d' WHERE `ID` = %d;",
			$paid, $ID ) );	
		if ( $result === false ) { return $result; }
		return true;
	}
	
	// [ [*], ... ]
	function getWinningBidsByBidderID( $bidderID ){
		global $wpdb;
		return $wpdb->get_results(
			$wpdb->prepare( "SELECT * FROM `{$this->name}` WHERE `winningBidderID` = '%d'",
				$bidderID ), ARRAY_A );
	}
}

?>