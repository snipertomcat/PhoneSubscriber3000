<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddLmsProfilesToRolesTable
 */
class AddLmsProfilesToRolesTable extends Migration
{
    /**
     * @var array
     */
    protected $data = [
        ['remote_id' => 16, 'code' => 'SP', 'name' => 'Soporte', 'super' => false],
        ['remote_id' => 17, 'code' => 'SUP', 'name' => 'Supervisor', 'super' => false],
        ['remote_id' => 18, 'code' => 'GJ', 'name' => 'Gestor de Jerarquia', 'super' => false],
        ['remote_id' => 19, 'code' => 'PC', 'name' => 'Proveedor de contenido', 'super' => false],
        ['remote_id' => 20, 'code' => 'AU', 'name' => 'Autor', 'super' => false],
        ['remote_id' => 21, 'code' => 'ER', 'name' => 'Enroller', 'super' => false],
        ['remote_id' => 23, 'code' => 'STU', 'name' => 'Estudiante', 'super' => false],
        ['remote_id' => 24, 'code' => 'PT', 'name' => 'Patner', 'super' => false],
        ['remote_id' => 26, 'code' => 'AD', 'name' => 'Administrador', 'super' => false],
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
