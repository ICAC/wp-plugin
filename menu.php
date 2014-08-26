<?php // register the icac top-level menu

function icac_admin_page() { ?>
<p> Welcome to the ICAC administration panel.</p>
<p>Please use the menu to select something to do</p>
<?php }

function icac_admin_menu() {
	add_menu_page('ICAC', 'ICAC', 'manage_options', 'icac-admin-menu', 'icac_admin_page');
}
add_action('admin_menu', 'icac_admin_menu');