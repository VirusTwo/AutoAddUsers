<?php
form_security_validate( 'plugin_format_config_edit' );

auth_reauthenticate( );
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

$f_user_list_text = gpc_get_string( 'userslist');

if( plugin_config_get( 'list_of_users' ) != $f_user_list_text ) {
	plugin_config_set( 'list_of_users', $f_user_list_text );
}

form_security_purge( 'plugin_format_config_edit' );

print_successful_redirect( plugin_page( 'config', true ) );
