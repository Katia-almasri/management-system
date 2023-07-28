<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\systemServices\notificationServices;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Http\Requests\NoteRequest;
use App\Models\Note;
use App\Models\Manager;
use Validator;
use Auth;

class NoteController extends Controller
{
    use validationTrait;

    protected $notificationService;

    public function __construct()
    {
        $this->notificationService = new notificationServices();
    }

    public function AddNoteForPuductionManager(NoteRequest $request)
    {

        $note = new Note();
        $note->purchasing_manager_id = $request->user()->id;
        $note->detail = $request->detail;
        $note->sender = 'sales';
        $note->production_manager_id = Manager::where('managing_level', 'Production_Manager')->get()->last()->id;
        $note->save();

        //add notification to sales manager
        $data = $this->notificationService->makeNotification(
            'production-channel',
            'App\\Events\\productionNotification',
            'تم إضافة ملاحظة من قبل مدير المشتريات والمبيعات',
            '',
            $request->user()->id,
            '',
            0,
            'مدير الإنتاج ',
            ''
        );



        $this->notificationService->productionNotification($data);

        return response()->json(["status" => true, "message" => "تمت اضافة ملاحظة لمدير الانتاج بنجاح"]);
    }

    public function displayNoteProduction(Request $request)
    {
        $displayNotes = Note::with('purchasingManager')->orderBy('id', 'DESC')->get();
        return response()->json($displayNotes, 200);
    }

    public function displayNoteSales(Request $request)
    {
        $displayNotes = Note::with('productionManager')->orderBy('id', 'DESC')->get();
        return response()->json($displayNotes, 200);
    }

    public function deleteNoteBySales(Request $request, $noteId)
    {
        $note = Note::where(['id' => $noteId], ['sender' => 'sales'])->get();
        if ($note[0]['sender'] == 'sales') {
            $note[0]->delete();
            return response()->json(["status" => true, "message" => "تم حذف ملاحظة بنجاح"]);
        }

        return response()->json(["status" => false, "message" => "لا يمكن حذف ملاحظة لم تقم بإضافتها"]);
    }

    public function AddNoteForSalesManager(Request $request)
    {
        $note = new Note();
        $note->purchasing_manager_id = Manager::where('managing_level', 'Purchasing-and-Sales-manager')->get()->last()->id;
        ;
        $note->detail = $request->detail;
        $note->sender = 'production';
        $note->production_manager_id = $request->user()->id;
        $note->save();

        //add notification to production manager

        $data = $this->notificationService->makeNotification(
            'sales-channel',
            'App\\Events\\salesNotification',
            'تم إضافة ملاحظة من قبل مدير الإنتاج ',
            '',
            $request->user()->id,
            '',
            0,
            'مدير المشتريات والمبيعات',
            ''
        );



        $this->notificationService->salesNotification($data);

        return response()->json(["status" => true, "message" => "تمت اضافة ملاحظة لمدير المستريات والمبيعات"]);

    }

    public function deleteNoteByProduction(Request $request, $noteId)
    {
        $note = Note::where(['id' => $noteId], ['sender' => 'production'])->get();
        if ($note[0]['sender'] == 'production') {
            $note[0]->delete();
            return response()->json(["status" => true, "message" => "تم حذف ملاحظة بنجاح"]);
        }

        return response()->json(["status" => false, "message" => "لا يمكن حذف ملاحظة لم تقم بإضافتها"]);

    }

    ///////////////////////////NOTIFICATION PART /////////////////////
    public function displayNotReadNotification(Request $request)
    {
        $notifications = Notification::where('channel', 'send-note')->where('is_seen', 0)
            ->where('act_id', '!=', $request->user()->id)->orderBy('created_at', 'DESC')->get();
        return response()->json($notifications);
    }

    public function displayNotReadNotificationSwitchState(Request $request)
    {
        $notifications = Notification::where([
            ['channel', '=', 'send-note'],
            ['is_seen', '=', 0],
            ['act_id', '!=', $request->user()->id]
        ])->orderBy('created_at', 'DESC')->get();
        
         Notification::where([
            ['channel', '=', 'send-note'],
            ['is_seen', '=', 0],
            ['act_id', '!=', $request->user()->id]
        ])->update(['is_seen' => 1]);
        return response()->json($notifications);

    }

    // public function displayNotReadNotificationProductionManager(Request $request)
    // {
    //     $notifications = Notification::where('channel', 'send-note')->where('is_seen', 0)
    //         ->where('act_id', $request->user()->id)->orderBy('created_at', 'DESC')->get();
    //     return response()->json($notifications);
    // }

    // public function displayNotReadNotificationSwitchStateProductionManager(Request $request)
    // {
    //     $notifications = Notification::where('channel', 'send-note')->where('is_seen', 0)->where('act_id', $request->user()->id)->get();
    //     $n = Notification::where([['channel', '=', 'send-note'], ['is_seen', '=', 0], ['act_id','=', $request->user()->id]])->update(['is_seen' => 1]);
    //     return response()->json($notifications);

    // }


}