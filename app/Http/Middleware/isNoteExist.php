<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Note;
use App\Traits\validationTrait;

class isNoteExist
{

    use validationTrait;
   
    public function handle(Request $request, Closure $next)
    {
        $noteId = $request->noteId;
        $isExistNote = Note::find($noteId);
        if($isExistNote!=null)
            return $next($request);
        return  $this -> returnError('error', 'الملاحظة غير متوفرة');
    }
}
