<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContentsController extends Controller
{
    public function index()
    {
        return \Inertia\Inertia::render('Cms/Contents/Index');
    }
}
