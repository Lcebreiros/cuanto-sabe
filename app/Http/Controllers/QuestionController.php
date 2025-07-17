<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        return view('questions'); // Esta vista debe existir en resources/views/questions.blade.php
    }
}
