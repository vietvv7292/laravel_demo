<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocsController extends Controller
{
    public function queueDoc()
    {
        return view('docs.queue');
    }

    public function eventDoc()
    {
        return view('docs.event');
    }

    public function authDoc()
    {
        return view('docs.authentication');
    }

    public function broadcastingDemo()
    {
        return view('docs.broadcasting');
    }


}
