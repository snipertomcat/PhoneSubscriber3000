<?php

use Apithy\Taxonomy;
use Illuminate\Database\Seeder;

class TaxonomyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Throwable
     */
    public function run()
    {
        try {
            DB::transaction(function () {
                // Store in Taxonomy Table
                $abilities = $this->getAbilities();
                Taxonomy::whereIn('id', $abilities->pluck('id'))
                    ->delete();
                Taxonomy::insert($abilities->toArray());

                // Experience relation with Taxonomy
                $relations = $this->getTaxonomyExperienceRelation();
                foreach ($relations as $key => $relation) {
                    DB::table('taxonomy_experiences')
                        ->where([
                            ['taxonomy_id', $relation['taxonomy_id']],
                            ['experience_id', $relation['experience_id']]
                        ])
                        ->delete();
                }
                DB::table('taxonomy_experiences')
                    ->insert($relations->toArray());

                // Session relation with Taxonomy
                $sessions = $this->getTaxonomySessionRelation();
                foreach ($sessions as $key => $session) {
                    DB::table('taxonomy_sessions')
                        ->where([
                            ['taxonomy_id', $session['taxonomy_id']],
                            ['session_id', $session['session_id']]
                        ])
                        ->delete();
                }
                DB::table('taxonomy_sessions')
                    ->insert($sessions->toArray());
            });
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    private function getAbilities()
    {
        $path = base_path('database/seeds/json/abilities.json');
        $file = File::get($path);
        return collect(json_decode($file, true));
    }

    private function getTaxonomyExperienceRelation()
    {
        $path = base_path('database/seeds/json/taxonomy_experience.json');
        $file = File::get($path);
        return collect(json_decode($file, true));
    }

    private function getTaxonomySessionRelation()
    {
        $path = base_path('database/seeds/json/taxonomy_session.json');
        $file = File::get($path);
        return collect(json_decode($file, true));
    }
}
