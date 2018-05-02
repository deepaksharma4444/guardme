<?php

namespace Responsive;

use Illuminate\Database\Eloquent\Model;
use DB;

class JobApplication extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_applications';

    /**
     * @param $job_id
     * @return mixed
     */
    public function is_applied($job_id) {
        $user_id = auth()->user()->id;
        $res = DB::table($this->table)
            ->where('applied_by', $user_id)
            ->where('job_id', $job_id)->get();
        return count($res);
    }

    /**
     * @param $job_id
     * @return mixed
     */
    public function is_hired($job_id) {
        $user_id = auth()->user()->id;
        $res = DB::table($this->table)
            ->where('applied_by', $user_id)
            ->where('is_hired', 1)
            ->where('job_id', $job_id)->get();
        return count($res);
    }

    public function getJobApplications($id) {
        $user_id = auth()->user()->id;
        $res = DB::table($this->table. ' as ja')
            ->select('u.name as user_name',
                'ja.application_description as description',
                'ja.id',
                'ja.is_hired'
            )
            ->join('security_jobs as sj', 'sj.id', '=', 'ja.job_id')
            ->join('users as u', 'u.id', '=', 'ja.applied_by')
            ->where('ja.job_id', $id)
            ->where('sj.created_by', $user_id)->get();
        return $res;
    }

    public function getApplicationDetails($application_id) {
        $user_id = auth()->user()->id;
        $res = DB::table($this->table. ' as ja')
            ->select('u.name as user_name',
                'ja.application_description as description',
                'ja.id',
                'sj.title as job_title',
                'sj.description as job_description',
                'ja.is_hired'
            )
            ->join('security_jobs as sj', 'sj.id', '=', 'ja.job_id')
            ->join('users as u', 'u.id', '=', 'ja.applied_by')
            ->where('ja.id', $application_id)
            ->where('sj.created_by', $user_id)->get()->first();
        return $res;
    }

    public function isEligibleToMarkHired($application_id) {
        $user_id = auth()->user()->id;
        $results = DB::table($this->table . ' as ja')
            ->join('security_jobs as sj', 'sj.id', '=', 'ja.job_id')
            ->where('sj.created_by', $user_id)
            ->where('ja.id', $application_id)
            ->where('sj.status', 1)->get();
        return count($results);
    }

    public function isHired($application_id) {
        $results = DB::table($this->table)
            ->where('id', $application_id)
            ->where('is_hired', 1)->get();
        return count($results);
    }
    public function getMyProposals() {
        $user_id = auth()->user()->id;
        $res = DB::table($this->table .' as ja')
            ->select(
                'sj.title',
                'ja.application_description',
                'ja.is_hired'
            )
            ->join('security_jobs as sj', 'sj.id', '=', 'ja.job_id')
            ->where('ja.applied_by', $user_id)
        ->get();
        return $res;
    }
}