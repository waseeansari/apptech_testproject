<?php

namespace App\Http\Controllers;

use Auth;
use Flash;
use Illuminate\Http\Request;

//Model
use App\Models\Note;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $notes = Note::paginate(25);
        return view('home')->with('notes', $notes);
    }
}
