<?php

namespace App\Http\Controllers;

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

    public function AddNoteForPuductionManager(NoteRequest $request){

            $note = new Note();
            $note->purchasing_manager_id = $request->user()->id;
            $note->detail = $request->detail;
            $note->sender = 'sales';
            $note->production_manager_id = Manager::where('managing_level','Production_Manager')->get()->last()->id;
            $note->save();
            return  response()->json(["status"=>true, "message"=>"تمت اضافة ملاحظة لمدير الانتاج بنجاح"]);
    }

    public function displayNote(Request $request){
        $displayNotes = Note::get();
        return response()->json($displayNotes, 200);
    }

    public function deleteNoteBySales(Request $request, $noteId){
        $note = Note::where(['id'=> $noteId], ['sender'=> 'sales'])->get();
        if($note[0]['sender']=='sales'){
            $note[0]->delete();
            return  response()->json(["status"=>true, "message"=>"تم حذف ملاحظة بنجاح"]);
        }

        return  response()->json(["status"=>false, "message"=>"لا يمكن حذف ملاحظة لم تقم بإضافتها"]);
    }

    public function AddNoteForSalesManager(Request $request){
        $note = new Note();
        $note->purchasing_manager_id = Manager::where('managing_level','Purchasing-and-Sales-manager')->get()->last()->id;;
        $note->detail = $request->detail;
        $note->sender = 'production';
        $note->production_manager_id = $request->user()->id;
        $note->save();
        return  response()->json(["status"=>true, "message"=>"تمت اضافة ملاحظة لمدير المستريات والمبيعات"]);

    }

    public function deleteNoteByProduction(Request $request, $noteId){
        $note = Note::where(['id'=> $noteId], ['sender'=> 'production'])->get();
        if($note[0]['sender']=='production'){
            $note[0]->delete();
            return  response()->json(["status"=>true, "message"=>"تم حذف ملاحظة بنجاح"]);
        }

        return  response()->json(["status"=>false, "message"=>"لا يمكن حذف ملاحظة لم تقم بإضافتها"]);
        
    }


}




