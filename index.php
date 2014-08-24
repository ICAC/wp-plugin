<?php
/**
 * Plugin Name: ICAC
 * Plugin URI: http://www.toxon.co.uk
 * Description: Supporting functionality for Imperial College Archery Club
 * Version: 0.1b
 * Author: Will Smith
 * Author URI: http://www.toxon.co.uk
 * License: MIT
 */

defined('ABSPATH') or die("external access denied");

define( 'ICAC_BASE', dirname( __FILE__ ) );

require( ICAC_BASE . '/auth.php' );

require( ICAC_BASE . '/member-importer.php' );