<?php

namespace App\Console\Commands;

use App\Models\Notice;
use App\Models\PushJob;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CollectPushJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collect:push_job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collecting Push Job to send Noti';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get New Notices
        $notices = $this->getNotices();
        // Get Device Tokens
        $device_tokens = $this->getUserDeviceToken();

        echo "\n";
        echo "[START] : Collection Push Jobs\n";
        try {
            if ($notices->count() == 0) {
                echo " - There is no New Notice currently.\n";
            } else {
                DB::beginTransaction();
                foreach ($notices as $notice) {
                    echo " - Adding Push Jobs for Notice id:" . $notice->id . "\n";
                    $this->storePushJobs($notice, $device_tokens);

                    $this->updatePushNotiFlag($notice);
                }
                DB::commit();
            }
            echo "[FINISH] : Collection Push Jobs\n";
        } catch (Throwable $th) {
            DB::rollBack();
            echo "[ERROR] : Collection Push Jobs. Error : " . $th->getMessage();
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '[line: ' . __LINE__ . '][Collect Push Job Fail] Message: ' . $th->getMessage());
        }
    }

    /**
     * Get New Notices that didn't send as push Noti
     */
    protected function getNotices()
    {
        $today =  now()->toDateString();
        return Notice::where('public_flag', 1)
            ->where('push_flag', 0)
            ->where([
                ['distribution_start_date', '<=', $today],
                ['distribution_end_date', '>=', $today],
            ])
            ->get();
    }

    /**
     * Get User List with Device Token
     */
    protected function getUserDeviceToken()
    {
        return User::where('suspend_flag', 0)
            ->whereNotNull('device_token')
            ->pluck('device_token')->toArray();
    }

    protected function storePushJobs($notice, $device_tokens)
    {
        foreach ($device_tokens as $device_token) {
            PushJob::create([
                'noti_title' => $notice->title,
                'noti_body' => 'New notice is announced',
                'device_token' => $device_token,
                'link' => 'notices/' . $notice->id,
            ]);
        }
    }

    protected function updatePushNotiFlag($notice)
    {
        $notice->push_flag = 1;
        $notice->save();
    }
}
