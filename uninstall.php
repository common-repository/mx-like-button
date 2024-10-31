<?php

// uninstall
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) die();
           
global $wpdb;

// table name
$table_names = array();

$table_names[] = $wpdb->prefix . 'mx_like_options';

$table_names[] = $wpdb->prefix . 'mx_like_store';

// drop table(s);
foreach( $table_names as $table_name ){

    $sql = 'DROP TABLE IF EXISTS ' . $table_name . ';';

    $wpdb->query( $sql );

}