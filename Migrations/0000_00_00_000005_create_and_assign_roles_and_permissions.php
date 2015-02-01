<?php

use Illuminate\Database\Migrations\Migration;

class CreateAndAssignRolesAndPermissions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		PermissionsManager::createRole('admin', 'Administrator');
		PermissionsManager::createRole('user', 'User');
		PermissionsManager::createRole('banned', 'Banned user');

		PermissionsManager::createPermission('login', 'Allow login');
		PermissionsManager::createPermission('panel', 'Allow access to admin panel');



		PermissionsManager::attach(['admin', 'user'], [
			'login',
		]);

		PermissionsManager::attach('admin', [
			'panel',
		]);

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		PermissionsManager::deleteRole('admin', 'Administrator');
		PermissionsManager::deleteRole('user', 'User');
		PermissionsManager::deleteRole('banned', 'Banned user');

		PermissionsManager::deletePermission('login', 'Allow login');
		PermissionsManager::deletePermission('panel', 'Allow access to admin panel');
	}

}
