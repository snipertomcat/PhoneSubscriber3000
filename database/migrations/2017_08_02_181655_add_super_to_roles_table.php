<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddSuperToRolesTable
 */
class AddSuperToRolesTable extends Migration
{
    /**
     * @var array
     */
    protected $data = [
        ['remote_id' => null, 'code' => 'SA', 'name' => 'Super administrador', 'super' => true],
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = date('Y-m-d H:i:s');

        collect($this->data)->map(function ($item) use ($now) {
            return array_merge($item, [
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        })->each(function ($item) {
            DB::table('roles')->insert($item);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $codes = collect($this->data)->pluck('code');

        DB::table('roles')->whereIn('code', $codes)->delete();
    }
}
