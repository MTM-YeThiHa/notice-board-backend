<?php

namespace App\Console\Commands;

use App\Models\PushJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class DeletePushJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:push_job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Old Push data that had been send';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo "\n";
        echo "[START] : Delete Old Push Jobs\n";
        try {
            // Get List
            $push_jobs = $this->getOldPushJobs();
            if ($push_jobs->count() == 0) {
                echo " - There is no Push Job to delete currently.\n";
            }else{
                // Delete List
                DB::beginTransaction();
                $this->removePushJobs($push_jobs);
                DB::commit();
            }
            echo "[FINISH] : Delete Old Push Jobs\n";
        } catch (Throwable $th) {
            DB::rollBack();
            echo "[ERROR] : Delete Old Push Jobs. Error : " . $th->getMessage();
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '[line: ' . __LINE__ . '][Delete Old Push Job Fail] Message: ' . $th->getMessage());
        }
    }

    /**
     * Get Old Push Job that had send
     */
    protected function getOldPushJobs()
    {
        return PushJob::where('send_flag', 1)->get();
    }

    /**
     * Get Old Push Job that had send
     */
    protected function removePushJobs($push_jobs)
    {
        foreach ($push_jobs as $push_job) {
            echo " - Deleting Push Jobs id:" . $push_job->id . "\n";
            $push_job->delete();
        }
    }
}
