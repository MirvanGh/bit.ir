<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        DB::unprepared(file_get_contents(database_path('sql/account_balance_update_trigger.sql')));
    }

    public function down()
    {
        $sql = <<<SQL
        drop trigger if exists account_balance_update_trigger on transactions;
        drop function if exists account_balance_update_trigger();
    SQL;
        DB::unprepared($sql);
    }
};
