<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserAccessTokens extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table){
			$table->string('google_access_token');
			$table->string('google_refresh_token');
			$table->string('google_token_life');
			$table->timestamp('google_token_created');			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users',function($table){
			$table->dropColumn('google_token_created');
			$table->dropColumn('google_token_life');
			$table->dropColumn('google_refresh_token');
			$table->dropColumn('google_access_token');
		});
	}

}
