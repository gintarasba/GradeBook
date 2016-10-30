<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class GradeBookController extends Controller
{
    public function showUserMarks()
    {
        return view('pages.gradeBook');
    }
}
