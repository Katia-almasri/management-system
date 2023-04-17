<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class addOfferNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $from;
     public $date;
     public $time;
     public $is_read;
     public $total_amount;

    public function __construct($data)
    { 
        if($data['from']!=null)
            $this->from = $data['from'];

        if($data['is_read']!=null)
            $this->is_read = $data['is_read'];

        if($data['total_amount']!=null)
            $this->total_amount = $data['total_amount'];

        $this->date = date("Y-m-d", strtotime(Carbon::now()));
        $this->time = date("h:i A", strtotime(Carbon::now()));
    }

    
    public function broadcastOn()
    {
        return ['add-offer-notification'];
    }

    public function broadcastAs()
    {
      return 'add-offer-notification';
    }
}
