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

class companyNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

     public $from;
     public $date;
     public $time;
     public $is_read;
     public $owner;
     public $name;

    public function __construct($data)
    { 
        if($data['from']!=null)
            $this->from = $data['from'];

        if($data['is_read']!=null)
            $this->is_read = $data['is_read'];

        if($data['owner']!=null)
            $this->owner = $data['owner'];

        if($data['name']!=null)
            $this->name = $data['name'];

        $this->date = date("Y-m-d", strtotime(Carbon::now()));
        $this->time = date("h:i A", strtotime(Carbon::now()));
    }

   
    public function broadcastOn()
    {
        return ['register-farm-request-notification'];
    }

    public function broadcastAs()
    {
      return 'register-farm-request-notification';
    }
}
