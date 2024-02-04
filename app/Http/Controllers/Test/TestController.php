<?php

namespace App\Http\Controllers\Test;

use App\Helpers\Push;
use App\Http\Controllers\Controller;
use App\Jobs\TestEmailJob;
use App\Services\DriverLoginHistoryService;
use App\Traits\Communication\PushFcm;
use Illuminate\Http\Request;

class TestController extends Controller
{
    use PushFcm;
    public function test()
    {
        $response = [
            'custId' => 1234,
            'custName' => 'Anand',
            'custType' => '1'
        ];
        return response()->success($response, 'E_NO_ERRORS');
    }


    public function sendPush(Request $request)
    {

        $customerLogin = new DriverLoginHistoryService();
        $session = $customerLogin->getCurrentSession(90);

        if(!$session) {
            return response()->fail(['msg' => 'Invalid Request'], 'E_NO_CONTENT');
        }

        $title = 'MickaiDo Test Push';
        $body = 'Greeting is an act of communication in which human beings intentionally make their presence known to each other, to show attention to, and to suggest a type of relationship or social status between individuals or groups of people coming in contact with each other.';

        Push::sendPushNotification(90, $body, $title);
        $response = $this->sendPushRequest($session->fcmToken, $title, $body);

        return response()->success($response, 'E_NO_ERRORS');
    }

    public function sendEmail()
    {
        $details = ['email' => 'anand.akurathi@gmail.com', 'name' => 'anand kumar', 'title' => 'test user'];
        TestEmailJob::dispatch($details);
    }
}
