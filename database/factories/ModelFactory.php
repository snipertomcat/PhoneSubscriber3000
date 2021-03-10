<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;

$factory->define(Apithy\Company::class, function (Faker\Generator $faker) {
    return [
        'name' =>$faker->company,
        'short_name' =>$faker->company,
        'legal_name'=>$faker->company,
        'country_id'=>\Apithy\Country::where('iso_3166_2',"=",$faker->countryCode)->first()->id,
        'account_name'=>$faker->domainWord,
        'site_url'=>$faker->url,
        'contact_email'=>$faker->companyEmail,
        'contact_phone'=>$faker->phoneNumber,
        'status'=>1
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Apithy\CompanyArea::class, function (Faker\Generator $faker) {
    return [
        'name'=>$faker->catchPhrase,
        'short_name'=>'',
        'description'=>$faker->realText(250),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Apithy\CompanyPosition::class, function (Faker\Generator $faker) {
    return [
        'name'=>$faker->jobTitle,
        'short_name'=>'',
        'description'=>$faker->realText(250),
    ];
});


/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Apithy\User::class, function (Faker\Generator $faker) {
    static $password;
    $email=$faker->unique()->safeEmail;

    return [
        'name' => $faker->name,
        'surname' => $faker->name,
        'email' => $email,
        'access'=>$email,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => Str::random(10),
        'phone'=>$faker->phoneNumber,
        'activated'=>1
    ];
});


/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Apithy\Ability::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(2),
        'description' => $faker->realText(),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Apithy\Experience::class, function (Faker\Generator $faker) {
    return [
        'title'=>$faker->sentence(),
        'summary'=>$faker->realText(),
        'description'=>$faker->realText(600),
        'code'=>$faker->isbn10,
        'company_objectives'=>$faker->realText(),
        'area_objectives'=>$faker->realText(),
        'visibility'=>1,
        'status'=>1
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Apithy\Session::class, function (Faker\Generator $faker) {
    return [
        'title'=>$faker->sentence(),
        'summary'=>$faker->realText(),
        'description'=>$faker->realText(600),
        'visibility'=>1,
        'status'=>1
    ];
});
