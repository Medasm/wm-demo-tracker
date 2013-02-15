<?php

class Alter_Demo_Add_Counsellor {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
        //add counsellor column to database
        Schema::table('demos', function ($table) {
            $table->string('counsellor')->nullable();
        });
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
        //add counsellor column to database
        Schema::table('demos', function ($table) {
            $table->drop_column('counsellor');
        });
	}

}