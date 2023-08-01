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
        $pusher->trigger('ceo-channel', 'App\\Events\\acceptRefuseSalesPurchaseNotification', $data);

    }

    public function addStartCommandNotif($data){
        $pusher = $this->makePusherConnection();
        $data['date'] = date("Y-m-d", strtotime(Carbon::now()));
        $data['time'] = date("h:i A", strtotime(Carbon::now()));
        $pusher->trigger('mechanism-channel', 'App\\Events\\addStartCommandNotif', $data);

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

    public function salesNotification($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('sales-channel', 'App\\Events\\salesNotification', $data);

    }

    public function predictionsNotification($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('predictions', 'App\\Events\\predictionsNotification', $data);
        
    }

    public function addNoteNotif($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('add-note-notification', 'App\\Events\\addNoteNotification', $data);

    }

    public function  addTripNotif($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('add-trip', 'App\\Events\\addTripNotification', $data);
        
    }

    public function addOutputFromWarehouseNotification($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('output-from-warehouse-to-sell', 'App\\Events\\outputFromWarehouseToSell', $data);

    }

    public function commandSalesDoneNotif($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('command-sales-done', 'App\\Events\\commandSalesDoneNotif', $data);

    }

    public function generateDailyCEOReport($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('daily-ceo-report-ready', 'App\\Events\\dailyCEOReportReady', $data);
        
    }

    public function generateDailyProductionReport($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('daily-production-report-ready', 'App\\Events\\dailyProductionReportReady', $data);
        
    }

    public function generateDailyMechanismReport($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('daily-mechanism-report-ready', 'App\\Events\\dailyMechanismReportReady', $data);
        
    }

    public function generateDailyLibraReport($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('daily-libra-report-ready', 'App\\Events\\dailyLibraReportReady', $data);
        
    }

    public function generateDailySalesReport($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('daily-sales-report-ready', 'App\\Events\\dailySalesReportReady', $data);

    }

    public function productionNotification($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('production-channel', 'App\\Events\\productionNotification', $data);
        
    }

    public function warehouNotification($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('warehouse-channel', 'App\\Events\\warehouNotification', $data);
        
    }

    public function slaughterNotification($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('slaughter-channel', 'App\\Events\\slaughterNotification', $data);

    }

    public function manufactoringNotification($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('manufactoring-channel', 'App\\Events\\manufactoringNotification', $data);
        
    }

    public function farmNotification($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('farm-channel', 'App\\Events\\farmNotification', $data);
        
    }

    public function sellingPortNotification($data){
        $pusher = $this->makePusherConnection();
        $pusher->trigger('selling-port-channel', 'App\\Events\\sellingPortNotification', $data);
        
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
