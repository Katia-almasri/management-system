<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class addSalesPurchaseToCeoNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $selling_port_id;
    public $farm_id;
    public $date;
    public $time;
    public $is_read;
    public $total_amount;
    public $type;


   public function __construct($data)
   { 
       if($data['selling_port_id']!=null)
           $this->selling_port_id = $data['selling_port_id'];

        if($data['farm_id']!=null)
            $this->farm_id = $data['farm_id'];

       if($data['is_read']!=null)
           $this->is_read = $data['is_read'];

       if($data['total_amount']!=null)
           $this->total_amount = $data['total_amount'];

        if($data['type']!=null)
            $this->type = $data['type'];

       $this->date = date("Y-m-d", strtotime(Carbon::now()));
       $this->time = date("h:i A", strtotime(Carbon::now()));
   }

   
   public function broadcastOn()
   {
       return ['add-sales-purchase-to-ceo-notification'];
   }

   public function broadcastAs()
   {
     return 'add-sales-purchase-to-ceo-notification';
   }

}
