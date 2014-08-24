<?php // import members from eActivities CSV file

if ( !defined('WP_LOAD_IMPORTERS') )
	return;

require( ICAC_BASE . '/member-parser.php' );

require_once ABSPATH . 'wp-admin/includes/import.php';

if ( !class_exists( 'WP_Importer' ) ) {
	$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	if ( file_exists( $class_wp_importer ) )
		require_once $class_wp_importer;
}

if ( class_exists( 'WP_Importer' ) ) {
class ICAC_Import extends WP_Importer {
	function header() {
		echo '<div class="wrap">';
		screen_icon();
		echo '<h2>'.__('Import eActivities members', 'icac').'</h2>';
	}
	
	function footer() {
		echo '</div>';
	}
	
	function greet() {
		echo '<div class="narrow">';
		echo '<p>'.__('Howdy! This importer allows you to import members from eActivities into your WordPress site. Pick an eActivities CSV file to upload and click Import.', 'icac').'</p>';
		wp_import_upload_form("admin.php?import=icac&amp;step=1");
		echo '</div>';
	}

	function import_members($members) {
		foreach( $members as $member ) {	
			$user_id = username_exists( $member['login'] );
			if ( $user_id !== NULL ) {
				continue;
			}
			
			$userdata = array( 
				'user_login' => $member['login'],
				'user_pass' => wp_generate_password(),
				'user_nicename' => $member['login'],
				'user_email' => $member['email'],
				'display_name' => $member['full-name'],
				'nickname' => $member['full-name'],
				'first_name' => $member['first-name'],
				'last_name' => $member['last-name']			
			);
		
			$user_id = wp_insert_user( $userdata );
			if( is_wp_error($user_id) ) {
				return $user_id;
			}
		}
	}
	
	function import() {
		$file = wp_import_handle_upload();
		if ( isset($file['error']) ) {
			echo $file['error'];
			return;
		}

		$file_path = $file['file'];
		$lines = file($file_path);
		
		$members = icac_parse_members($lines);
		$result = $this->import_members($members);
				
		if ( is_wp_error( $result ) )
						return $result;
		wp_import_cleanup($file['id']);
		do_action('import_done', 'icac');

		echo '<h3>';
		printf(__('All done. <a href="%s">Have fun!</a>', 'icac'), get_option('home'));
		echo '</h3>';
	}

	function dispatch() {
		if (empty ($_GET['step'])) {
			$step = 0;
		} else {
			$step = (int) $_GET['step'];
		}

		$this->header();

		switch ($step) {
			case 0 :
				$this->greet();
				break;
			case 1 :
				check_admin_referer('import-upload');
				$result = $this->import();
				if ( is_wp_error( $result ) )
					echo $result->get_error_message();
					break;
		}
	
		$this->footer();
	}
}

$icac_import = new ICAC_Import();

register_importer('icac', __('ICAC', 'icac'), __('Import posts from an eActivities CSV file.', 'icac'), array ($icac_import, 'dispatch'));

} // class_exists( 'WP_Importer' )