<?php

use Apithy\Evaluation;
use Apithy\ExperienceActivity;
use Apithy\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ReactiveSeeder extends Seeder
{

    CONST PATH = 'database/seeds/json/reactives';

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
                // Timestamps for attach
                $today = now()->toDayDateTimeString();
                // Get all json files as collection
                $this->makeData()
                    ->each(function ($v) use ($today) {
                        $evaluations = collect($v);
                        $evaluations->each(function ($item) use ($today) {
                            // Save evaluation
                            $evaluation = $this->storeEvaluation($item);
                            $questions = collect($item['questions']);
                            $questions->each(function ($i) use ($evaluation, $today) {
                                // Save Question with Answers
                                $question = $this->storeQuestion($i);
                                // Attach question to evaluation
                                $evaluation->questions()->attach($question->id, [
                                    'created_at' => $today,
                                    'updated_at' => $today
                                ]);
                            });
                            // Create evaluation as activity
                            $this->createExperienceActivity($evaluation, $item);
                        });
                    });
            });
        } catch (Exception $ex) {
            report($ex);
        }
    }

    private function storeEvaluation($item)
    {
        $evaluation = new Evaluation();
        $evaluation->company_id = 1;
        $evaluation->user_id = 1;
        $evaluation->fill([
            'name'          => $item['name'],
            'title'         => $item['title'],
            'description'   => $item['description'],
            'type'          => $item['type'],
            'difficulty'    => $item['difficulty'],
            'creation_type' => $item['creation_type']
        ]);
        $evaluation->saveOrFail();
        $evaluation->experiences()->attach($item['experience']);
        $evaluation->experienceSessions()->attach($item['experience_session']);
        return $evaluation;
    }

    private function storeQuestion($item)
    {
        $question = new Question();
        $question->company_id = 1;
        $question->fill([
            'title'         => $item['title'],
            'image'         => $item['image'],
            'type'          => $item['type'],
            'difficulty'    => $item['difficulty']
        ]);
        $question->saveOrFail();
        $question->answers()->createMany($item['answers']);
        return $question;
    }

    private function createExperienceActivity(Evaluation $evaluation, $item)
    {
        $experienceActivity = new ExperienceActivity();
        $experienceActivity->fill([
            'name'              => $evaluation->name,
            'title'             => $evaluation->title,
            'description'       => $evaluation->description,
            'type'              => $evaluation->type,
            'url'               => '',
            'is_iframe'         => 0,
            'is_scoreable'      => 1,
            'resolution_time'   => 0,
            'evaluation_id'     =>  $evaluation->id
        ]);
        $experienceActivity->saveOrFail();
        $experienceActivity->sessions()->attach($item['experience_session']);
    }

    private function makeData()
    {
        $data = $this->getDirectories()
            ->map(function ($item, $key) {
                $files = $this->getFiles($item);
                $data = $files->map(function ($file, $k) use ($item) {
                    return $this->getFileAsJson("{$item}/{$file}");
                });
                return $data;
            });
        return $data;
    }

    private function getDirectories()
    {
        $directories = File::directories(base_path(self::PATH));
        return collect($directories);
    }

    private function getFiles($directory)
    {
        $files = File::files($directory);
        return collect($files)->map(function ($item, $key) {
            return $item->getRelativePathname();
        })
            ->sort();
    }

    private function getFileAsJson($path)
    {
        return json_decode(File::get($path), true);
    }
}
