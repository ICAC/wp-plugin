<?php // stub the ICU authentication methods for local testing

if( !function_exists( 'pam_auth' ) ) {
	function pam_auth( $username, $password ) {
		return $username === $password;
	}
}

if( !function_exists( 'ldap_get_name' ) ) {
	function ldap_get_name($username) {
		return 'Will Smith';
	}
}

if( !function_exists( 'ldap_get_names' ) ) {
	function ldap_get_names( $username ) {
		//TODO check this is the actual return format
		return array( 'Will', 'Smith' );
	}
}

if( !function_exists( 'ldap_get_mail' ) ) {
	function ldap_get_mail( $username ) {
		return 'wds12@imperial.ac.uk';
	}
}

if( !function_exists( 'ldap_get_info' ) ) {
	function ldap_get_info( $username ) {
		//TODO check this is the actual return format
		return array(
			'Course',
			'UG/PG Status',
			'Department',
			'Faculty',
			'Location'
		);
	}
}