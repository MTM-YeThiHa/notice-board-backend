<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoticeResource;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class NoticeController extends Controller
{
    public function index()
    {
        try {
            $today =  now()->toDateString();
            $notices = Notice::where('public_flag', '1')
                ->where([
                    ['distribution_start_date', '<=', $today],
                    ['distribution_end_date', '>=', $today],
                ])
                ->latest()
                ->get();

            return response()->json([
                'message' => 'Getting data Successful.',
                'data' => NoticeResource::collection($notices),
            ], 200);
        } catch (Throwable $th) {
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '[line: ' . __LINE__ . '][Getting Notice List failed] Message: ' . $th->getMessage());
            return response()->json([
                'error' => 'Getting Notice List failed.',
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $today =  now()->toDateString();
            $notice = Notice::where('public_flag', '1')
                ->where([
                    ['distribution_start_date', '<=', $today],
                    ['distribution_end_date', '>=', $today],
                ])->findOrFail($id);

            if (($notice->public_flag == 1)) {
                return response()->json([
                    'message' => 'Getting data Successful.',
                    'data' => NoticeResource::make($notice),
                ], 200);
            } else {
                return response()->json([
                    'error' => 'Notice data is not Exist.',
                ], 404);
            }
        } catch (Throwable $th) {
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '[line: ' . __LINE__ . '][Getting Notice [id:' . $id . '] failed] Message: ' . $th->getMessage());
            return response()->json([
                'error' => 'Getting Notice [id:' . $id . '] failed.',
            ], 500);
        }
    }
}
