<?php

/* 
	Expected "csv" format:

	"Full Members"
	"Date","Order No","CID","Login","First Name","Last Name","Email"
	"Data1", "Data2, ...

	"Life / Associate
	"Date","Order No","CID/Card Number","Login","First Name","Last Name","Email"
	"Data1", "Data2, ...
*/

function icac_parse_members( $lines ) {	
	// csv parse data
	$data = array_map( 'str_getcsv', $lines );
	$data_count = count( $data );
		
	$members = array();
	
	if( $data[0][0] !== 'Full Members' ) {
		return null;
	}
	
	// $data[1] is header
	
	$i = 2;
	$format = 1;
	
	while( true ) {
		// reached end of data?
		if( $i >= $data_count ) {
			break;
		}
		
		// blank line?
		if( $data[$i][0] == '' ) {
			$i++;			
			continue;
		}

		
		// switch to associate members?
		if( $data[$i][0] === 'Life / Associate' ) {
			$i += 2;
			$format = 2;
			
			continue;
		}
		
		// process the row differently depending on the format
		switch( $format ) {
			case 1 :
			case 2 : // happens that they are the same format
				$members[] = array(
					'login' => $data[$i][3],
					'first-name' => $data[$i][4],
					'last-name' => $data[$i][5],
					'full-name' => $data[$i][4] . ' ' . $data[$i][4],
					'email' => $data[$i][6],
				);
				
				break;
		}
		
		// move onto next row
		$i++;
	}
	
	return $members;
}
