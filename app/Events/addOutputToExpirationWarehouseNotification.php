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

class addOutputToExpirationWarehouseNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $output_detail_id;
     public $date;
     public $time;
     public $is_read;
     public $output_from;
     public $title;
     public $details;

    public function __construct($data)
    { 
        if($data['output_detail_id']!=null)
            $this->output_detail_id = $data['output_detail_id'];

        if($data['is_read']!=null)
            $this->is_read = $data['is_read'];

        if($data['output_from']!=null)
            $this->output_from = $data['output_from'];

        if($data['title']!=null)
            $this->title = $data['title'];
        
        if($data['details']!=null)
            $this->details = $data['details'];  

        $this->date = date("Y-m-d", strtotime(Carbon::now()));
        $this->time = date("h:i A", strtotime(Carbon::now()));
    }

    
    public function broadcastOn()
    {
        return ['add-output-to-expiration-warehouse-notification'];
    }

    public function broadcastAs()
    {
      return 'add-output-to-expiration-warehouse-notification';
    }
}
