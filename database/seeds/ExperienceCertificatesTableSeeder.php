<?php

use Apithy\GettingStartedItem;
use Illuminate\Database\Seeder;

class ExperienceCertificatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company= \Apithy\Company::where('account_name','jumex')->first();

        DB::table('experience_certificates')->where('company_id',$company->id)->delete();
        DB::table('settings')->where('company_id',$company->id)->delete();

        $experience_ids = DB::table('experience_company')
            ->select('experience_id')->where('company_id',$company->id)
            ->pluck('experience_id');

        $experiences= \Apithy\Experience::whereIn('id',$experience_ids)->get();


        foreach($experiences as $experience){
            \Apithy\Certificates::insert([
                [
                    'title' => 'Jumex Manufactura',
                    'description' => 'Certificado manufactura Jumex',
                    'status' => 1,
                    'bg_file_url' => 'certificates/certificado_jumex.jpg',
                    'company_id' => $company->id,
                    'experience_id'=>$experience->id,
                    'creator'=>1,
                    'json_data'=>json_encode([]),
                ]
            ]);
        }


        \Apithy\Setting::insert(
            [
                'company_id' => $company->id,
                'user_id' => 1,
                'setting' =>'show_register',
                'value' => '1',
                'module'=>'login',
                'created_at'=>now(),
                'updated_at'=>now(),
                'product'=>''
            ]
        );

        \Apithy\Setting::insert(
            [
                'company_id' => $company->id,
                'user_id' => 1,
                'setting' =>'send_enrollment_finished_certificates',
                'value' => '1',
                'module'=>'enrollment',
                'created_at'=>now(),
                'updated_at'=>now(),
                'product'=>''
            ]
        );

        \Apithy\Setting::insert(
            [
                'company_id' => $company->id,
                'user_id' => 1,
                'setting' =>'evaluation_score_min',
                'value' => '.10',
                'module'=>'evaluation',
                'created_at'=>now(),
                'updated_at'=>now(),
                'product'=>''
            ]
        );

        \Apithy\Setting::insert(
            [
                'company_id' => $company->id,
                'user_id' => 1,
                'setting' =>'min_enrollment_score',
                'value' => '.10',
                'module'=>'enrollment',
                'created_at'=>now(),
                'updated_at'=>now(),
                'product'=>''
            ]
        );
    }
}
