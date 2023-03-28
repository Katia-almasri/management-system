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
                $note->production_manager_id = Manager::where('managing_level','production_manager')->get()->last()->id;
                $note->save();
            return  response()->json(["status"=>true, "message"=>"note created successfully"]);
    }

    public function displayNote(Request $request){
        $displayNotes = Note::get();
        return response()->json($displayNotes, 200);
    }

    public function deleteNote(Request $request, $noteId){
        $fineNote = Note::find($noteId)->delete();
        return  response()->json(["status"=>true, "message"=>"note deleted successfully"]);
    }
}




