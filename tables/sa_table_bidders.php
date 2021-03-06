<?php 
class SA_BiddersTable extends SA_Table
{
	function install(){
		$this->_createTable(
			"CREATE TABLE `".$this->name."` ( 
			`ID` INT(11) NOT NULL AUTO_INCREMENT,
			`eventID` INT DEFAULT '0' NOT NULL ,
			`contactID` INT DEFAULT '0' NOT NULL ,
			`bidderNumber` INT DEFAULT '0' NOT NULL ,
			`expressPay` INT DEFAULT '0' NOT NULL ,
			PRIMARY KEY ( `ID` )
			)" );
	}
	
	// [ ID ]
	function add( $eventID, $contactID, $bidderNumber, $expressPay ){
		global $wpdb; 
		$wpdb-> query(
		$wpdb-> prepare(
			"INSERT INTO `{$this->name}` (`eventID`, `contactID`, `bidderNumber`, `expressPay` ) VALUES ('%d', '%d', '%d', '%d')",
			$eventID, $contactID, $bidderNumber, $expressPay ) );
		$results = $wpdb->get_row( 'SELECT LAST_INSERT_ID() as `ID`;', ARRAY_A );
		return $results[ 'ID' ];
	}
	
	// true on success
	function update( $ID, $bidderNumber, $expressPay ){
		global $wpdb;
		$result = $wpdb-> query(
		$wpdb-> prepare( 
			"UPDATE `{$this->name}` SET `bidderNumber` = '%d', `expressPay` = '%d' WHERE `ID` = %d;",
			$bidderNumber, $expressPay, $ID ) );	
		if ( $result === false ) { return $result; }
		return true;
	}
	
	// [ [*], ... ]
	function getAll( $ascending = true ){
		return $this->_getAll( 'bidderNumber', $ascending );
	}
	
	function getCount( $eventID ){
		global $wpdb;
		$row = $wpdb->get_row(
				$wpdb->prepare( "SELECT COUNT(*) as `COUNT` FROM `{$this->name}` WHERE `eventID` = '%d'", $eventID ), ARRAY_A );
		return $row[ 'COUNT' ];
	}
	
	function getByBidderNumber( $bidderNumber ){
		global $wpdb;
		return $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM `{$this->name}` WHERE `bidderNumber` = %d;",
			$bidderNumber ), ARRAY_A );
	}
}
?>