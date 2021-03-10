<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        //DB::transaction(function () {

        $positions = [];

        $user_id = DB::table('users')->insert([
            'name' => 'Admin',
            'surname' => 'Admin',
            'email' => 'admin@apithy.com',
            'password' => bcrypt('secret'),
            'activated' => 1,
            'access' => 'admin@apithy.com',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
            'confirmed_at' => \Carbon\Carbon::now()
        ]);


        $root = \Apithy\User::where('email', '=', 'admin@apithy.com')->first()->id;


        DB::table('role_user')->insert([
            'role_id' => 10,
            'user_id' => $root
        ]);

        DB::table('companies')->insert([
            'country_id' => 484,
            'company_province' => 'Mexico',
            'name' => 'Apithy',
            'short_name' => 'Apithy',
            'legal_name' => 'Apithy',
            'rfc' => 'APT040404TY4',
            'account_name' => 'apithy',
            'site_url' => 'http://www.apithy.com',
            'contact_email' => 'admin@apithy.com',
            'contact_phone' => '555-325-6810',
            'status' => 1,
            'zip' => 12345,
            'num_employees_min' => 1,
            'num_employees_max' => 10,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        $apithy = \Apithy\Company::where('name','=','Apithy')->first()->id;

        DB::table('company_user')->insert([
            'company_id' => $apithy,
            'user_id' => $root
        ]);

        factory(Apithy\Company::class, 5)->create()->each(function ($c) use ($root, $positions) {
            $admins = factory(Apithy\User::class, 2)->create(['registration_method' => Apithy\User::REGISTRATION_ADMIN,'confirmed_at' => \Carbon\Carbon::now()])->each(function ($u) use ($c) {
                $u->roles()->attach([9]);
                $u->company()->attach($c->id);
                $u->save();
            });


            $areas = factory(Apithy\CompanyArea::class, mt_rand(1, 3))->create(['company_id' => $c->id])->each(function ($u) use ($positions, $c) {
                $positions = factory(Apithy\CompanyPosition::class, mt_rand(1, 3))->create(['area_id' => $u->id])->each(function ($p) use ($c) {
                    $students = factory(Apithy\User::class, mt_rand(5, 10))->create(['registration_method' => Apithy\User::REGISTRATION_INVITATION,'confirmed_at' => \Carbon\Carbon::now()])->each(function ($s) use ($p, $c) {
                        $s->position()->attach([$p->id]);
                        $s->roles()->attach([7]);
                        $s->company()->attach($c->id);
                        $s->save();
                    });

                    $students = factory(Apithy\User::class, mt_rand(1, 5))->create(['registration_method' => Apithy\User::REGISTRATION_ON_SITE,'confirmed_at' => \Carbon\Carbon::now()])->each(function ($s) use ($p, $c) {
                        $s->position()->attach([$p->id]);
                        $s->roles()->attach([7]);
                        $s->company()->attach($c->id);
                        $s->save();
                    });
                });
            });


            $abilities = factory(Apithy\Ability::class, mt_rand(10, 15))->create(['company_id' => $c->id]);


            $adventures = factory(Apithy\Experience::class, mt_rand(2, 6))
                ->create(
                    [
                        'type' => 'adventure',
                        'author' => $root,
                        'video_intro' => 'http://evaluations.dev.apithy.com/h5p/46/embed',
                        'price_default'=> mt_rand(100,1500)
                    ])
                ->each(function ($u) use ($c, $root) {
                    $u->companies()->attach([$c->id]);
                    $u->abilities()->attach(\Apithy\Ability::All()->random(mt_rand(2, 4)));
                    $u->save();


                    $explore_contents = [
                        ['url' => 'http://evaluations.dev.apithy.com/h5p/44/embed', 'type' => 'exploration'],
                        ['url' => 'http://evaluations.dev.apithy.com/h5p/45/embed', 'type' => 'exploration'],
                        ['url' => 'http://evaluations.dev.apithy.com/h5p/36/embed', 'type' => 'exploration'],
                        ['url' => 'http://evaluations.dev.apithy.com/h5p/3/embed', 'type' => 'practice'],
                    ];

                    foreach ($explore_contents as $index => $session) {
                        $explore = factory(Apithy\Session::class)->create(
                            [
                                'experience_id' => $u->id,
                                'type' => $session['type'],
                                'resource_url' => $session['url'],
                                'author' => $root,
                                'weight' => $index
                            ]);
                    }
            });

/*
            $journeys = factory(Apithy\Experience::class, mt_rand(4, 6))->create(['type' => 'journey', 'author' => $root, 'video_intro' => 'http://evaluations.dev.apithy.com/h5p/46/embed'])->each(function ($u) use ($c, $root) {
                foreach ($c->experiences()->where('type', 'adventure') as $e) {
                    if($e->id % 2) {
                        $u->adventures()->attach($e->id);
                    }
                }
                $u->companies()->attach([$c->id]);
                $u->save();
            });
*/
        });

        //});
    }
}
