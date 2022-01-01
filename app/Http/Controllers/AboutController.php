<?php

namespace App\Http\Controllers;

class AboutController extends Controller
{
    public function privacy()
    {
        return $this->view('About/Privacy');
    }
}
