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

class manufactoringNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $title;
     public $route;
     public $act_id;
     public $details;
     public $weight;
     public $output_from;
     public $date;
     public $time;

    public function __construct($data)
    { 
        if($data['title']!=null)
            $this->title = $data['title'];

        if($data['route']!=null)
            $this->route = $data['route'];

        if($data['act_id']!=null)
            $this->act_id = $data['act_id'];

        if($data['details']!=null)
            $this->details = $data['details'];

        if($data['weight']!=null)
            $this->weight = $data['weight'];

        if($data['output_from']!=null)
            $this->output_from = $data['output_from'];

        $this->date = date("Y-m-d", strtotime(Carbon::now()));
        $this->time = date("h:i A", strtotime(Carbon::now()));
    }

    
    public function broadcastOn()
    {
        return ['manufactoring-channel'];
    }

    public function broadcastAs()
    {
      return 'manufactoring-channel';
    }
}
