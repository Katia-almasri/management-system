<?php
namespace App\systemServices;

use Illuminate\Support\Facades\DB;
use App\Exceptions\Exception;
use Auth;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Carbon\Carbon;

class notificationServices
{
    public function makePusherConnection(){
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
            );
        $pusher = new Pusher(
                        env('PUSHER_APP_KEY'),
                        env('PUSHER_APP_SECRET'),
                        env('PUSHER_APP_ID'), 
                        $options
                    );
        return $pusher;
    }

    public function registerFarmRequestNotification($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('register-farm-request-notification', 'App\\Events\\registerFarmRequestNotification', $data);
    }

    public function registerSellingPortRequestNotification($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('register-sellling-port-request-notification', 'App\\Events\\registerSellingPortRequestNotification', $data);
    }

    public function addOfferNotification($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('add-offer-notification', 'App\\Events\\addOfferNotification', $data);
    }

    public function addRequestToCompany($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('add-request-to-company-notification', 'App\\Events\\addRequestToCompanyNotification', $data);
    }

    public function addSalesPurchaseToCEONotif($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('add-sales-purchase-to-ceo-notification', 'App\\Events\\addSalesPurchaseToCeoNotification', $data);

    }
}
