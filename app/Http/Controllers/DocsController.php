<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocsController extends Controller
{
    public function queueDoc()
    {
        return view('docs.queue');
    }

    public function queueGuideDoc()
    {
        return view('docs.queue_guide');
    }

    public function eventDoc()
    {
        return view('docs.event');
    }

    public function eventGuideDoc()
    {
        return view('docs.event_guide');
    }

    public function authDoc()
    {
        return view('docs.auth');
    }

    public function authGuideDoc()
    {
        return view('docs.auth_guide');
    }
}
