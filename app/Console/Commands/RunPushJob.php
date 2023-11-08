<?php

namespace App\Console\Commands;

use App\Models\PushJob;
use App\Services\FCMService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class RunPushJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:push_job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Push Notification to User Devices';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo "\n";
        echo "[START] : Run Push Jobs\n";
        try {
            // Get List
            $push_jobs = $this->getPushJobs();

            if ($push_jobs->count() == 0) {
                echo " - There is no Push Job to send currently.\n";
            } else {
                DB::beginTransaction();
                $this->sendPushNotiToDevices($push_jobs);
                DB::commit();
            }
            echo "[FINISH] : Run Push Jobs\n";
        } catch (Throwable $th) {
            DB::rollBack();
            echo "[ERROR] : Run Push Jobs. Error : " . $th->getMessage();
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '[line: ' . __LINE__ . '][Run Push Job Fail] Message: ' . $th->getMessage());
        }
    }

    /**
     * Get Old Push Job that had send
     */
    protected function getPushJobs()
    {
        return PushJob::where('send_flag', 0)->get();
    }

    /**
     * Send Push Noti to Deivices
     */
    protected function sendPushNotiToDevices($push_jobs)
    {
        foreach ($push_jobs as $push_job) {
            echo " - Send Push Noti of Push Job id:" . $push_job->id . "\n";
            $notiData = [
                "title" => $push_job->noti_title,
                "body" => $push_job->noti_body,
                "link" => $push_job->link,
            ];
            $res = FCMService::sendOne($push_job->device_token, $notiData);

            $push_job->send_at = now();
            if ($res) {
                $this->completePushNoti($push_job);
            } else {
                $this->errorPushNoti($push_job, '');
            }
        }
    }

    /**
     * Mark as Complete
     * Set send_flag = 1
     */
    protected function completePushNoti($push_job)
    {
        $push_job->send_flag = 1;
        $push_job->save();
    }

    /**
     * Add Error Message for Push Noti that having error in sending
     */
    protected function errorPushNoti($push_job, $error)
    {
        $push_job->error = 'Error in sending Noti';
        $push_job->save();
    }
}
