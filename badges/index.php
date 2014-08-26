<?php

function icac_badges_init() {
  register_post_type( 'icac_badge',
    array(
      'labels' => array(
        'name' => __( 'Badges', 'icac' ),
        'singular_name' => __( 'Badge', 'icac' )
      ),
    'public' => true,
    'has_archive' => true,
		'rewrite' => array( 'slug' => 'badge' ),
    )
  );
}
function icac_badges_link() {
	p2p_register_connection_type( array(
    'name' => 'badge_owners',
    'from' => 'icac_badge',
    'to' => 'user',
		'title' => array(
			'to' => __( 'Badges', 'icac' ),
			'from' => __( 'Owners', 'icac' )
		),
		'admin_column' => 'any'
	) );
}

add_action( 'init', 'icac_badges_init' );
add_action( 'p2p_init', 'icac_badges_link' );
