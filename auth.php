<?php // handles authentication against Imperial Users

require( ICAC_BASE . '/auth-fakes.php' );

function icac_authenticate ( $user, $username, $password ) {
	if ( is_a( $user, 'WP_User' ) ) {
		// another filter before us has already authenticated the user
		// so we should just return that
		return $user;
	}
	
	if ( empty($username) || empty($password) ) {
		if ( is_wp_error( $user ) ) {
			return $user;
		}
			
		$error = new WP_Error();
		
		if ( empty($username) )
			$error->add('empty_username', __('<strong>ERROR</strong>: The username field is empty.'));
			
		if ( empty($password) )
			$error->add('empty_password', __('<strong>ERROR</strong>: The password field is empty.'));
			
		return $error;
	}
	
	if( pam_auth( $username, $password ) ) {
		// login is valid
		
		// transform the username into a wp safe one
		$ic_username = $username;
		$username = sanitize_user( $username, true );

		$user = get_user_by( 'login', $username );
		
		if( $user !== false ) {
			// user exists so just return it
			return $user;
		}
		
		// user doesn't exist, create it
		$ic_name = ldap_get_name($ic_username);
		$ic_names = ldap_get_names($ic_username);
		$ic_email = ldap_get_mail($ic_username);
		
		$userdata = array( 
			'user_login' => $username,
			// use a random password because we aren't allow to store any derivative of the users password
			'user_pass' => wp_generate_password(),
			'user_nicename' => $username,
			'user_email' => $ic_email,
			'display_name' => $ic_name,
			'nickname' => $ic_name,
			'first_name' => $ic_names[0],
			'last_name' => $ic_names[1]			
		);
		
		$user_id = wp_insert_user( $userdata );
		if( is_wp_error($user_id) ) {
			return $user_id;
		}
		
		$user = get_user_by( 'id', $user_id );
		
		return $user;
	} else {
		// login failed return an error response
		
		//TODO check error response on ldap_get_name
		if ( ldap_get_name( $username ) === NULL ) {
			return new WP_Error( 'invalid_username', sprintf( __( '<strong>ERROR</strong>: Invalid username. <a href="%s">Lost your password</a>?' ), wp_lostpassword_url() ) );
		} else {
			return new WP_Error( 'incorrect_password', sprintf( __( '<strong>ERROR</strong>: The password you entered for the username <strong>%1$s</strong> is incorrect. <a href="%2$s">Lost your password</a>?' ), $username, wp_lostpassword_url() ) );
		}
	}
}

add_filter( 'authenticate', 'icac_authenticate', 30, 3 );