<?php

class Init
{

    /**
     * Make changes to the database.
     *
     * @return void
     */
    public function up()
    {
        //create table users
        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('email');
            $table->string('password', 64);
            $table->timestamps();
        });

        //create table roles
        Schema::create('roles', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        //create table branches
        Schema::create('branches', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        //create table demos
        Schema::create('demos', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('mobile', 20);
            $table->date('demoDate');
            $table->string('program');
            $table->string('faculty')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('branch_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->timestamps();
        });

        //create table demo status
        Schema::create('demoStatus', function ($table) {
            $table->increments('id');
            $table->date('followupDate')->nullable();
            $table->date('joiningDate')->nullable();
            $table->string('status');
            $table->string('remarks', 4000)->nullable();
            $table->integer('demo_id')->unsigned();
            $table->foreign('demo_id')->references('id')->on('demos');
            $table->timestamps();
        });

        Schema::create('user_role', function ($table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->timestamps();
        });

        Schema::create('branch_user', function ($table) {
            $table->increments('id');
            $table->integer('branch_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->timestamps();
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demos', function ($table) {
            $table->drop_foreign('demos_user_id_foreign');
            $table->drop_foreign('demos_branch_id_foreign');
        });

        Schema::table('users_roles', function ($table) {
            $table->drop_foreign('users_roles_user_id_foreign');
            $table->drop_foreign('users_roles_role_id_foreign');
        });

        Schema::table('branches_users', function ($table) {
            $table->drop_foreign('branches_users_user_id_foreign');
            $table->drop_foreign('branches_users_branch_id_foreign');
        });

        Schema::table('demoStatus', function ($table) {
            $table->drop_foreign('demoStatus_demo_id_foreign');
        });

        //drop users
        Schema::drop('users');
        Schema::drop('branches');
        Schema::drop('roles');
        Schema::drop('demos');
        Schema::drop('demoStatus');
        Schema::drop('users_roles');
        Schema::drop('branches_users');
    }
}