<?php

namespace Apithy\Http\Resources\Dashboard;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Resources\Json\Resource;

class DashboardEnrollmentDetailsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'experience_id' => $this->experience_id,
            'user_id'       => $this->user_id,
            'full_name'     => $this->user->full_name,
            'status'        => $this->status,
            'success'       => $this->success,
            'score'         => $this->score,
            'evaluable'     => $this->evaluable_experience,
            'started_at'    => $this->started_at,
            'finished_at'   => $this->finished_at,
            'progress'      => $this->getProgress(),
            'duration'      => $this->getTime(),
            'unit_time'     => 'minutes'
        ];
    }

    private function getProgress()
    {
        if ($this->sessions_count && $this->finished_sessions_count) {
            return round(((100 /$this->sessions_count) * $this->finished_sessions_count));
        }
        return 0;
    }

    public function getTime(){
        $time=$this->getTotalTime();
        if($time) {
            $time=CarbonInterval::seconds($this->getTotalTime())->cascade()->forHumans();
        }

        return $time;
    }

    private function toMinutes()
    {
        if (!isset($this->started_at) || !isset($this->finished_at)) {
            return null;
        }
        $finish = new Carbon($this->finished_at);
        return $finish->diffInMinutes($this->started_at);
    }
}
