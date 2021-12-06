<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Form;
use Flash;
use Session;
use Response;
use Illuminate\Http\Request;
use App\Http\Requests\NoteRequest;

//Model
use App\Models\Note;

class NoteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$notes = Note::paginate(25);
        $notes = Note::paginate(25);
        return view('notes')->with('notes', $notes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $Note = new Note;
        $Note->user_id = Auth::user()->id;
        $Note->note = isset($request->note)?$request->note:NULL;
        if ($request->file('image')) {
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $path = $request->file('image')->storeAs('uploads/notes', $image_name, 'public');
            $Note->image = '/storage/'.$path;
        }
        $Note->save();
        return response()->json(['status'=>true,'message'=>'Note saved successfully!','data'=>$Note->toArray()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Note = Note::find($id);
        if (empty($Note)) {
            return response()->json(['status'=>false,'message'=>'Note not found!']);
        }
        return response()->json($Note);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Note = Note::find($id);
        if (empty($Note)) {
            return response()->json(['status'=>false,'message'=>'Note not found!']);
        }
        return response()->json($Note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Note = Note::find($id);
        if (empty($Note)) {
            return response()->json(['status'=>false,'message'=>'Note not found!']);
        }
        $Note->delete();
        return response()->json(['status'=>true,'message'=>'Note deleted!']);
    }
}
