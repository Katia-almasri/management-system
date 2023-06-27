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

class dailyWarehouseReportReady
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $title;
    public $details;
    public $date;
    public $time;
    public function __construct($data)
    {
        if($data['title']!=null)
            $this->title = $data['title'];

       if($data['details']!=null)
           $this->details = $data['details'];

       $this->date = date("Y-m-d", strtotime(Carbon::now()));
       $this->time = date("h:i A", strtotime(Carbon::now()));
    }

   
    public function broadcastOn()
   {
       return ['daily-warehouse-report-ready'];
   }

   public function broadcastAs()
   {
     return 'daily-warehouse-report-ready';
   }
}
