<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SMSController extends Controller
{
    const TPOA_SENDER = "SENDER";

    const ACTIVE_STATUS = "ACTIVE";
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function send(Request $request): JsonResponse
    {
        $apiURL = config('external.sms');
        $recipients = Array();
        foreach ($request->input('recipients') as $recipient)
        {
            array_push($recipients, [
                "msisdn" => $recipient['phone']
            ]);
        }

        $apiRequest = [
            "message" => $request->input('textMessage'),
            "tpoa" => self::TPOA_SENDER,
            "test" => $request->input('isTest'),
            "label" => sprintf("%s%s","[user]=", $request->user()->username),
            "recipient" => $recipients
        ];

        if ($request->user()->status == self::ACTIVE_STATUS) {
            $client = new Client();
            $response = $client->post(
                $apiURL,
                [
                    'headers' => ['Content-type' => 'application/json'],
                    'auth' => [
                        'KevinAndrey@InstanceShape.com',
                        'ssvxwK2t4pTD'
                    ],
                    'json' => $apiRequest,
                ]
            );
            return response()->json(json_decode($response->getBody()->getContents()));
        } else {
            return response()->json("NOVALIDO");
        }
    }
}
