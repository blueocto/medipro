<?php
/*
	Plugin Name:	Administrate
	Plugin URI:		http://www.GetAdministrate.com/
	Description:	A plugin to integrate event listing, details & ordering with the Administrate backend.
	Version:		3.6.3
	Author:			Administrate
	Author URI:		http://www.GetAdministrate.com/
	Text Domain:	administrate
	License:		GPL2
*/

/*
	Copyright 2013  Eaglewood System Ltd

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//  Get access to wordpress database abstraction
global $wpdb;

//  Include the configuration variables ($_ADMINISTRATE_CONFIG)
$path = dirname(__FILE__);
require_once($path . '/config.php');

//  Include plugin update checker
require_once($path . '/updater/plugin-update-checker.php');
$updateChecker = new PluginUpdateChecker(
	$_ADMINISTRATE_CONFIG['plugin']['urls']['update'],
	__FILE__,
	'administrate'
);

//  Include Administrate plugin
require_once($path . '/AdministratePlugin.php');
$_ADMINISTRATE = new AdministratePlugin($_ADMINISTRATE_CONFIG, $wpdb, $_ADMINISTRATE_CONFIG['plugin']['debug']);
global $_ADMINISTRATE;

//  Register activation / deactivation hooks
register_activation_hook(__FILE__, array($_ADMINISTRATE, 'activate'));
register_deactivation_hook(__FILE__, array($_ADMINISTRATE, 'deactivate'));
