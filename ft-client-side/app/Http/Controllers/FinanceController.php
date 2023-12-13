<?php

namespace App\Http\Controllers;

use App\Helper\TotalHelper;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FinanceController extends Controller
{
    public function index()
    {
        $client = new Client();
        $response = $client->get(env('APP_API_SERVER_1') . '/finance', [
            'headers' => [
                'Authorization' => 'Bearer ' . Cache::get('token')
            ],
        ]);

        $body = json_decode($response->getBody()->getContents(), true);

        $datas = [
            'year' => 0,
            'month' => 0,
            'datas' => $body['data']
        ];

        $responseCategory = $client->get(env('APP_API_SERVER_1') . '/categories', [
            'headers' => [
                'Authorization' => 'Bearer ' . Cache::get('token')
            ],
        ]);

        $bodyCategory = json_decode($responseCategory->getBody()->getContents(), true);

        $totalHelper = new TotalHelper();
        $month = $totalHelper->getMonth();
        $all = $totalHelper->getAll();

        return view('pages.finance', compact(['datas', 'month', 'all', 'bodyCategory']));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'needed' => 'required',
            'amount' => 'required|numeric',
            'category_id' => 'required|numeric'
        ]);

        $client = new Client();
        $response = $client->post(
            env(
                'APP_API_SERVER_1'
            ) . '/finance',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . Cache::get('token'),
                ],
                'json' => [
                    'needed' => $request->needed,
                    'amount' => $request->amount,
                    'category_id' => $request->category_id,
                ]
            ]
        );

        $body = json_decode($response->getBody()->getContents(), true);

        if ($body['status_error']) {
            return redirect()->back()->withErrors(['failed' => $body['message']]);
        } else {
            return redirect()->back()->with('success', $body['message']);
        }
    }

    public function destroy($uuid)
    {
        $client = new Client();
        $response = $client->delete(env('APP_API_SERVER_1') . '/finance/' . $uuid, [
            'headers' => [
                'Authorization' => 'Bearer ' . Cache::get('token'),
            ],
        ]);

        $body = json_decode($response->getBody()->getContents(), true);
        if ($body['status_error']) {
            return redirect()->back()->withErrors(['failed' => $body['message']]);
        } else {
            return redirect()->back()->with('success', $body['message']);
        }
    }
}
