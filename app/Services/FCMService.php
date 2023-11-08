<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FCMService
{
    protected const FCM_API = 'https://fcm.googleapis.com/fcm/send';
    /**
     * Send Push Noti to Single Device
     * @return result {bool} success:true , fail:false
     */
    public static function sendOne($deviceToken, $data): bool
    {
        /**
         * with custom data
         */
        $body_data = [
            'to' => $deviceToken,
            'data' => $data,
        ];

        /**
         * // with default title & body
         */
        // $body_data = [
        //     'to' => $deviceToken,
        //     'notification' => $data,
        // ];

        $response = self::send($body_data);

        $jsonRes = json_decode($response);

        if ($jsonRes->success == 1 && $jsonRes->failure == 0) {
            return true;
        }

        return false;
    }

    /**
     * Send Push Noti to Multiple Device
     * @return result {Json}
     */
    public static function sendMulti($deviceTokens, $data)
    {
        $body_data = [
            'registration_ids' => $deviceTokens,
            'data' => $data,
        ];

        $response = self::send($body_data);

        return json_decode($response);
    }

    /**
     * Send Push Noti via FCM
     */
    protected static function send($bodyData)
    {
        return Http::acceptJson()->withToken(config('fcm.token'))->post(
            self::FCM_API,
            $bodyData,
        );
    }
}
