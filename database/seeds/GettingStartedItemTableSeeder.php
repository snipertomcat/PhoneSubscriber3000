<?php

use Apithy\GettingStartedItem;
use Illuminate\Database\Seeder;

class GettingStartedItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('getting_started_item')->delete();
        GettingStartedItem::insert([
            [
                'activity_name' => 'welcome_1',
                'title' => 'Apithy, el aliado perfecto para tu formación',
                'status' => 0,
                'type' => 'video',
                'content' => 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/video-contents/Getting2_baja.mp4'
            ],
            [
                'activity_name' => 'welcome_2',
                'title' => '¿Cómo vivir las experiencias digitales en Apithy?',
                'status' => 0,
                'type' => 'video',
                'content' => 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/video-contents/Getting1_baja2.mp4'
            ]
        ]);
    }
}
