<?php

namespace App\Jobs;

use App\Models\DetonatorFrige1Detail;
use App\Models\DetonatorFrige2Detail;
use App\Models\DetonatorFrige3Detail;
use App\Models\LakeDetail;
use App\Models\Notification;
use App\Models\StoreDetail;
use App\Models\ZeroFrigeDetail;
use App\systemServices\notificationServices;
use App\systemServices\warehouseServices;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class checkExpirationDate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notificationService;
    protected $warehouseService;

    public function __construct()
    {
        $this->notificationService = new notificationServices();
        $this->warehouseService = new warehouseServices();
    }

  
    public function handle()
    {
        // 1. loop over each x_detail and check if expiration date not null and less than today
        // if it is : then fill the destructed date and add notification to indicates that this material is outputed now
        //////// lake ///////////////
        $lakeDetails = LakeDetail::where([['cur_weight', '!=', 0], ['date_of_destruction', '=', null], ['expiration_date', '!=', null]])->get();
        foreach ($lakeDetails as $_lakeDetail) {
            if($_lakeDetail->expiration_date < Carbon::now()){
                //1. update the date_of_destruction
                $_lakeDetail->update(['date_of_destruction' => Carbon::now()]);

                //2. send notification to expiration part
                $newNotification = new Notification();
                $newNotification->channel = 'add-output-to-expiration-warehouse-notification';
                $newNotification->event = 'App\\Events\\addOutputToExpirationWarehouseNotification';
                $newNotification->title = 'تم إخراج مادة إلى الإتلاف';
                $newNotification->route = 'App\Models\LakeDetail';
                $newNotification->act_id = $_lakeDetail->id;
                $output_production_name = $_lakeDetail->lake->warehouse->outPut_Type_Production->type;
                $newNotification->details = $output_production_name;
                $newNotification->is_seen = 0;
                $newNotification->weight = $_lakeDetail->cur_weight;
                $newNotification->output_from = 'مستودع البحرات';
                $newNotification->reason_of_notification = 'انتهاء مدة الصلاحية';
                $newNotification->save();

                //إرسال إشعار لمشرف المخازن
                $data['title'] = 'تم إخراج مادة إلى الإتلاف';
                $data['output_from'] = 'App\Models\LakeDetail';
                $data['output_detail_id'] = $_lakeDetail->id;
                $data['details'] = ' تم إخراج المادة ' . $output_production_name . ' بنجاح ';
                $data['date'] = date("Y-m-d", strtotime(Carbon::now()));
                $data['time'] = date("h:i A", strtotime(Carbon::now()));

                $this->notificationService->addOutputExpiredNotif($data);

                //edit the weight in x_detail => in x => in warehouse
                $outputTypeProduction = $this->warehouseService->subtractFromWarehouseAfterExpiration('App\Models\LakeDetail', $_lakeDetail->id, $_lakeDetail->cur_weight);

            }
        }

        /////// zero frige //////////////
        $zeroFrigeDetails = ZeroFrigeDetail::where([['cur_weight', '!=', 0], ['date_of_destruction', '=', null], ['expiration_date', '!=', null]])->get();
        foreach ($zeroFrigeDetails as $_zeroFrigeDetails) {
            if($_zeroFrigeDetails->expiration_date != null && $_zeroFrigeDetails->expiration_date < Carbon::now()){
                //1. update the date_of_destruction
                $_zeroFrigeDetails->update(['date_of_destruction' => Carbon::now()]);

                //2. send notification to expiration part
                $newNotification = new Notification();
                $newNotification->channel = 'add-output-to-expiration-warehouse-notification';
                $newNotification->event = 'App\\Events\\addOutputToExpirationWarehouseNotification';
                $newNotification->title = 'تم إخراج مادة إلى الإتلاف';
                $newNotification->route = 'App\Models\ZeroFrigeDetail';
                $newNotification->act_id = $_zeroFrigeDetails->id;
                $output_production_name = $_zeroFrigeDetails->zeroFrige->warehouse->outPut_Type_Production->type;
                $newNotification->details = $output_production_name;
                $newNotification->is_seen = 0;
                $newNotification->weight = $_zeroFrigeDetails->cur_weight;
                $newNotification->output_from = 'مستودع البراد الصفري';
                $newNotification->reason_of_notification = 'انتهاء مدة الصلاحية';
                $newNotification->save();

                //إرسال إشعار لمشرف المخازن
                $data['title'] = 'تم إخراج مادة إلى الإتلاف';
                $data['output_from'] = 'App\Models\ZeroFrigeDetail';
                $data['output_detail_id'] = $_zeroFrigeDetails->id;
                $data['details'] = ' تم إخراج المادة ' . $output_production_name . ' بنجاح ';
                $data['date'] = date("Y-m-d", strtotime(Carbon::now()));
                $data['time'] = date("h:i A", strtotime(Carbon::now()));

                $this->notificationService->addOutputExpiredNotif($data);
                $outputTypeProduction = $this->warehouseService->subtractFromWarehouseAfterExpiration('App\Models\ZeroFrigeDetail', $_zeroFrigeDetails->id, $_zeroFrigeDetails->cur_weight);
            }
        }

         /////// det1 frige //////////////
         $det1FrigeDetail = DetonatorFrige1Detail::where([['cur_weight', '!=', 0], ['date_of_destruction', '=', null], ['expiration_date', '!=', null]])->get();
         foreach ($det1FrigeDetail as $_det1FrigeDetail) {
             if($_det1FrigeDetail->expiration_date != null && $_det1FrigeDetail->expiration_date < Carbon::now()){
                 //1. update the date_of_destruction
                 $det1FrigeDetail->update(['date_of_destruction' => Carbon::now()]);
 
                 //2. send notification to expiration part
                 $newNotification = new Notification();
                 $newNotification->channel = 'add-output-to-expiration-warehouse-notification';
                 $newNotification->event = 'App\\Events\\addOutputToExpirationWarehouseNotification';
                 $newNotification->title = 'تم إخراج مادة إلى الإتلاف';
                 $newNotification->route = 'App\Models\DetonatorFrige1Detail';
                 $newNotification->act_id = $det1FrigeDetail->id;
                 $output_production_name = $det1FrigeDetail->detonatorFrige1->warehouse->outPut_Type_Production->type;
                 $newNotification->details = $output_production_name;
                 $newNotification->is_seen = 0;
                 $newNotification->weight = $det1FrigeDetail->cur_weight;
                 $newNotification->output_from = 'مستودع الصاعقة 1';
                 $newNotification->reason_of_notification = 'انتهاء مدة الصلاحية';
                 $newNotification->save();
 
                 //إرسال إشعار لمشرف المخازن
                 $data['title'] = 'تم إخراج مادة إلى الإتلاف';
                 $data['output_from'] = 'App\Models\DetonatorFrige1Detail';
                 $data['output_detail_id'] = $det1FrigeDetail->id;
                 $data['details'] = ' تم إخراج المادة ' . $output_production_name . ' بنجاح ';
                 $data['date'] = date("Y-m-d", strtotime(Carbon::now()));
                 $data['time'] = date("h:i A", strtotime(Carbon::now()));
 
                 $this->notificationService->addOutputExpiredNotif($data);
                 $outputTypeProduction = $this->warehouseService->subtractFromWarehouseAfterExpiration('App\Models\DetonatorFrige1Detail', $det1FrigeDetail->id, $det1FrigeDetail->cur_weight);
             }
         }

         /////// det2 frige //////////////
         $det2FrigeDetail = DetonatorFrige2Detail::where([['cur_weight', '!=', 0], ['date_of_destruction', '=', null], ['expiration_date', '!=', null]])->get();
         foreach ($det2FrigeDetail as $_det2FrigeDetail) {
             if($_det2FrigeDetail->expiration_date != null && $_det2FrigeDetail->expiration_date < Carbon::now()){
                 //1. update the date_of_destruction
                 $det2FrigeDetail->update(['date_of_destruction' => Carbon::now()]);
 
                 //2. send notification to expiration part
                 $newNotification = new Notification();
                 $newNotification->channel = 'add-output-to-expiration-warehouse-notification';
                 $newNotification->event = 'App\\Events\\addOutputToExpirationWarehouseNotification';
                 $newNotification->title = 'تم إخراج مادة إلى الإتلاف';
                 $newNotification->route = 'App\Models\DetonatorFrige2Detail';
                 $newNotification->act_id = $det2FrigeDetail->id;
                 $output_production_name = $det2FrigeDetail->detonatorFrige2->warehouse->outPut_Type_Production->type;
                 $newNotification->details = $output_production_name;
                 $newNotification->is_seen = 0;
                 $newNotification->weight = $det2FrigeDetail->cur_weight;
                 $newNotification->output_from = 'مستودع الصاعقة 2';
                 $newNotification->reason_of_notification = 'انتهاء مدة الصلاحية';
                 $newNotification->save();
 
                 //إرسال إشعار لمشرف المخازن
                 $data['title'] = 'تم إخراج مادة إلى الإتلاف';
                 $data['output_from'] = 'App\Models\DetonatorFrige2Detail';
                 $data['output_detail_id'] = $det2FrigeDetail->id;
                 $data['details'] = ' تم إخراج المادة ' . $output_production_name . ' بنجاح ';
                 $data['date'] = date("Y-m-d", strtotime(Carbon::now()));
                 $data['time'] = date("h:i A", strtotime(Carbon::now()));
 
                 $this->notificationService->addOutputExpiredNotif($data);
                 $outputTypeProduction = $this->warehouseService->subtractFromWarehouseAfterExpiration('App\Models\DetonatorFrige2Detail', $det2FrigeDetail->id, $det2FrigeDetail->cur_weight);
             }
         }

         /////// det3 frige //////////////
         $det3FrigeDetail = DetonatorFrige3Detail::where([['cur_weight', '!=', 0], ['date_of_destruction', '=', null], ['expiration_date', '!=', null]])->get();
         foreach ($det3FrigeDetail as $_det3FrigeDetail) {
             if($_det3FrigeDetail->expiration_date != null && $_det3FrigeDetail->expiration_date < Carbon::now()){
                 //1. update the date_of_destruction
                 $det3FrigeDetail->update(['date_of_destruction' => Carbon::now()]);
 
                 //2. send notification to expiration part
                 $newNotification = new Notification();
                 $newNotification->channel = 'add-output-to-expiration-warehouse-notification';
                 $newNotification->event = 'App\\Events\\addOutputToExpirationWarehouseNotification';
                 $newNotification->title = 'تم إخراج مادة إلى الإتلاف';
                 $newNotification->route = 'App\Models\DetonatorFrige3Detail';
                 $newNotification->act_id = $det3FrigeDetail->id;
                 $output_production_name = $det3FrigeDetail->detonatorFrige3->warehouse->outPut_Type_Production->type;
                 $newNotification->details = $output_production_name;
                 $newNotification->is_seen = 0;
                 $newNotification->weight = $det3FrigeDetail->cur_weight;
                 $newNotification->output_from = 'مستودع الصاعقة 3';
                 $newNotification->reason_of_notification = 'انتهاء مدة الصلاحية';
                 $newNotification->save();
 
                 //إرسال إشعار لمشرف المخازن
                 $data['title'] = 'تم إخراج مادة إلى الإتلاف';
                 $data['output_from'] = 'App\Models\DetonatorFrige3Detail';
                 $data['output_detail_id'] = $det3FrigeDetail->id;
                 $data['details'] = ' تم إخراج المادة ' . $output_production_name . ' بنجاح ';
                 $data['date'] = date("Y-m-d", strtotime(Carbon::now()));
                 $data['time'] = date("h:i A", strtotime(Carbon::now()));
 
                 $this->notificationService->addOutputExpiredNotif($data);
                 $outputTypeProduction = $this->warehouseService->subtractFromWarehouseAfterExpiration('App\Models\DetonatorFrige3Detail', $det3FrigeDetail->id, $det3FrigeDetail->cur_weight);
             }
         }

         ///////store frige //////////////
         $storeDetail = StoreDetail::where([['cur_weight', '!=', 0], ['date_of_destruction', '=', null], ['expiration_date', '!=', null]])->get();
         foreach ($storeDetail as $_storeDetail) {
             if($_storeDetail->expiration_date != null && $_storeDetail->expiration_date < Carbon::now()){
                 //1. update the date_of_destruction
                 $_storeDetail->update(['date_of_destruction' => Carbon::now()]);
 
                 //2. send notification to expiration part
                 $newNotification = new Notification();
                 $newNotification->channel = 'add-output-to-expiration-warehouse-notification';
                 $newNotification->event = 'App\\Events\\addOutputToExpirationWarehouseNotification';
                 $newNotification->title = 'تم إخراج مادة إلى الإتلاف';
                 $newNotification->route = 'App\Models\StoreDetail';
                 $newNotification->act_id = $_storeDetail->id;
                 $output_production_name = $_storeDetail->store->warehouse->outPut_Type_Production->type;
                 $newNotification->details = $output_production_name;
                 $newNotification->is_seen = 0;
                 $newNotification->weight = $_storeDetail->cur_weight;
                 $newNotification->output_from = 'المستودع النهائي';
                 $newNotification->reason_of_notification = 'انتهاء مدة الصلاحية';
                 $newNotification->save();
 
                 //إرسال إشعار لمشرف المخازن
                 $data['title'] = 'تم إخراج مادة إلى الإتلاف';
                 $data['output_from'] = 'App\Models\StoreDetail';
                 $data['output_detail_id'] = $_storeDetail->id;
                 $data['details'] = ' تم إخراج المادة ' . $output_production_name . ' بنجاح ';
                 $data['date'] = date("Y-m-d", strtotime(Carbon::now()));
                 $data['time'] = date("h:i A", strtotime(Carbon::now()));
 
                 $this->notificationService->addOutputExpiredNotif($data);
                 $outputTypeProduction = $this->warehouseService->subtractFromWarehouseAfterExpiration('App\Models\StoreDetail', $_storeDetail->id, $_storeDetail->cur_weight);
             }
         }

    }
}
