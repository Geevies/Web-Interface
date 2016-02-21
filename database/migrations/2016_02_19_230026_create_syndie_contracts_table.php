<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSyndieContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('server')->create('syndie_contracts', function (Blueprint $table) {
            $table->increments('contract_id');
            $table->integer('contractee_id'); // ID of the person offering the contract
            $table->string('contractee_name'); // Name of the Entity offering the contract
            $table->string('status');
            /* Status Codes:
             * new -> Newly created
             * open -> Contracted confirmed by contract mod
             * mod-nok -> Contract denied by contract mod
             * assigned -> Contract assigned to a player
             * completed -> Completion report submitted by player
             *  -> confirmed -> Completion confirmed
             *   -> closed -> Rewards assigned -> Contract closed
             *  -> reopened -> Not completed successfully -> Contract reopened
             * canceled -> The contract has been canceled by the contractee
             */
            $table->string('title');
            $table->string('description');
            $table->integer('reward_credits')->nullable();
            $table->text('reward_other')->nullable();
            $table->integer('completer_id')->nullable();
            $table->text('completer_name')->nullable();
            $table->timestamps();
        });

        Schema::connection('server')->create('syndie_contracts_comments', function (Blueprint $table) {
            $table->increments('comment_id');
            $table->integer('contract_id');
            $table->integer('commentor_id');
            $table->string('commentor_name');
            $table->string('title');
            $table->text('comment');
            $table->string('image_name');
            $table->enum('type',['mod-author','mod-occ','ic','ooc']);
            /* Message Types:
             * mod-author: Only visible to mods and authors
             * mod-ooc: OOC comment of a contract mod
             * ic: IC Comment
             * ic-comprep: Report of contract completion
             * ic-failrep: Report of failed operation
             * ooc: OOC Comment
             */
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('server')->drop('syndie_contracts');
        Schema::connection('server')->drop('syndie_contracts_comments');
    }
}
