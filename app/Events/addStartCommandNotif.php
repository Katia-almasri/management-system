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

class addStartCommandNotif
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $command_id;
    public $date;
    public $time;

    public function __construct($data)
    {

       if($data['type']!=null)
            $this->type = $data['type'];

       if($data['command_id']!=null)
           $this->command_id = $data['command_id'];

       $this->date = date("Y-m-d", strtotime(Carbon::now()));
       $this->time = date("h:i A", strtotime(Carbon::now()));
    }


    public function broadcastOn()
   {
       return ['add-start-command-notification'];
   }

   public function broadcastAs()
   {
     return 'add-start-command-notification';
   }
}
