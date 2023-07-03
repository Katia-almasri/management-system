<?php
namespace App\systemServices;

use App\Models\Notification;
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

    public function addStartCommandNotif($data){
        $pusher = $this->makePusherConnection();
        $data['date'] = date("Y-m-d", strtotime(Carbon::now()));
        $data['time'] = date("h:i A", strtotime(Carbon::now()));
        $pusher->trigger('add-start-command-notification', 'App\\Events\\addStartCommandNotif', $data);

    }

    public function addWeightRecieptAfterArriveNotif($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('add-reciept-after-arrive-notification', 'App\\Events\\addWeightRecieptAfterArriveNotif', $data);

    }

    public function addOutputExpiredNotif($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('add-output-to-expiration-warehouse-notification', 'App\\Events\\addOutputToExpirationWarehouseNotification', $data);

    }

    public function generateDailyWarehouseReport($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('daily-warehouse-report-ready', 'App\\Events\\dailyWarehouseReportReady', $data);

    }

    public function addRequestFromOfferNotif($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('add-request-from-offer-notification', 'App\\Events\\addRequestFromOfferNotification', $data);

    }

    public function acceptRefuseSalesPurchaseNotif($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('accept-refuse-sales-purchase-notification', 'App\\Events\\acceptRefuseSalesPurchaseNotification', $data);

    }
    //////////////////////////// NOTIFICATION SERVICE ////////////////////////////
    public function makeNotification($channel, $event, $title, $route, $act_id, $details, $weight, $output_from, $reson_of_notification){
        $newNotification = new Notification();
        $newNotification->channel = $channel;
        $newNotification->event = $event;
        $newNotification->title = $title;
        $newNotification->route = $route;
        $newNotification->act_id = $act_id;
        $newNotification->details = $details;
        $newNotification->is_seen = 0;
        $newNotification->weight = $weight;
        $newNotification->output_from = $output_from;
        $newNotification->reason_of_notification = $reson_of_notification;
        $newNotification->save();

        $data['title'] = $title;
        $data['route'] =  $route;
        $data['act_id'] =  $act_id;
        $data['details'] = $details;
        $data['weight'] = $weight;
        $data['output_from'] = $output_from;
        $data['date'] = date("Y-m-d", strtotime(Carbon::now()));
        $data['time'] = date("h:i A", strtotime(Carbon::now()));

        return $data;
    }

}
