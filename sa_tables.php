<?php

/*
All table query methods return boolean false on failure,
or an associative array on success. Keys are listed above the method.

install() and uninstall() return no values.
*/

require_once 'tables/sa_table.php';
require_once 'tables/sa_table_contacts.php';
require_once 'tables/sa_table_bidders.php';
require_once 'tables/sa_table_donations.php';
require_once 'tables/sa_table_items.php';
require_once 'tables/sa_table_events.php';

class SA_Tables
{
	var $contacts;
	var $bidders;
	var $donations;
	var $items;
	var $events;
	
	function __construct(){
		$this-> contacts = new SA_ContactsTable( 'contacts' );
		$this-> bidders = new SA_BiddersTable( 'bidders' );
		$this-> donations = new SA_DonationsTable( 'donations' );
		$this-> items = new SA_ItemsTable( 'items' );
		$this-> events = new SA_EventsTable( 'events' );
	}
	
	function install(){
		$this-> contacts-> install();
		$this-> bidders-> install();
		$this-> donations-> install();
		$this-> items-> install();
		$this-> events-> install();
	}
	
	function uninstall(){
		$this-> contacts-> uninstall();
		$this-> bidders-> uninstall();
		$this-> donations-> uninstall();
		$this-> items-> uninstall();
		$this-> events-> uninstall();
	}
	
	//////////////////////////////////////////////////////
	
	function getBidderList( $eventID ){
		global $wpdb;
		$result = $wpdb-> get_results(
			$wpdb->prepare( "SELECT `{$this-> bidders-> name}`.`ID` as `ID`,
			`{$this-> contacts-> name}`.firstName, `{$this-> contacts-> name}`.lastName, `{$this-> contacts-> name}`.email
			FROM `{$this-> bidders-> name}` LEFT OUTER JOIN `{$this-> contacts-> name}` ON `{$this-> bidders-> name}`.`contactID` = `{$this-> contacts-> name}`.`ID`
			WHERE `{$this-> bidders-> name}`.`eventID` = '%d'; ", $eventID ), ARRAY_A );
		return $result;
	}
	
	function getBidderInfo( $eventID, $bidderID ){
		global $wpdb;
		$result = $wpdb-> get_row(
			$wpdb-> prepare( "SELECT `{$this-> bidders-> name}`.`ID` as `ID`, `{$this-> bidders-> name}`.`contactID`,
			`{$this-> contacts-> name}`.firstName, `{$this-> contacts-> name}`.lastName, `{$this-> contacts-> name}`.email
			FROM `{$this-> bidders-> name}` LEFT OUTER JOIN `{$this-> contacts-> name}` ON `{$this-> bidders-> name}`.`contactID` = `{$this-> contacts-> name}`.`ID`
			WHERE `{$this-> bidders-> name}`.`eventID` = '%d' AND `{$this-> bidders-> name}`.`ID` = '%d'; ",
			$eventID, $bidderID ), ARRAY_A );
		return $result;
	}
	
	function updateBidderInfo( $eventID, $bidderID, $firstName, $lastName, $email ){
		global $wpdb;
		$bidderInfo = $this-> getBidderInfo( $eventID, $bidderID );
		$this-> contacts-> update( $bidderInfo[ 'contactID' ], $firstName, $lastName, $email );
	}
}