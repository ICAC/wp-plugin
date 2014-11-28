<?php

session_start();

function icac_get_theme() {
  if(isset($_GET['wptheme'])) {
    $_SESSION['wptheme'] = $_GET['wptheme'];
  } 
  
  return $_SESSION['wptheme'];
}

function icac_get_stylesheet($stylesheet = '') {
	$theme = icac_get_theme();

	if (empty($theme)) {
		return $stylesheet;
	}

	$theme = get_theme($theme);
  
	// Don't let people peek at unpublished themes.
	if (isset($theme['Status']) && $theme['Status'] != 'publish')
		return $stylesheet;		
	
	if (empty($theme)) {
		return $stylesheet;
	}

	return $theme['Stylesheet'];
}

function icac_get_template($template) {
	$theme = icac_get_theme();

	if (empty($theme)) {
		return $template;
	}

	$theme = get_theme($theme);

	if ( empty( $theme ) ) {
		return $template;
	}

	// Don't let people peek at unpublished themes.
	if (isset($theme['Status']) && $theme['Status'] != 'publish')
		return $template;		

	return $theme['Template'];
}

add_filter('stylesheet', 'icac_get_stylesheet');
add_filter('template', 'icac_get_template');