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

class addWeightRecieptAfterArriveNotif
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $reciept_id;
    public $date;
    public $time;
    public function __construct($data)
    {
        if($data['type']!=null)
            $this->type = $data['type'];

       if($data['reciept_id']!=null)
           $this->reciept_id = $data['reciept_id'];

       $this->date = date("Y-m-d", strtotime(Carbon::now()));
       $this->time = date("h:i A", strtotime(Carbon::now()));
    }

   
    public function broadcastOn()
   {
       return ['add-reciept-after-arrive-notification'];
   }

   public function broadcastAs()
   {
     return 'add-reciept-after-arrive-notification';
   }
}
