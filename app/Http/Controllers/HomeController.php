<?php

namespace App\Http\Controllers;

use App\User;
use App\Category;
use App\Question;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'user']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::count();
        $categories = Category::where("status", "active")->count();
        $questions = Question::where("status", "active")->count();
        $reports = Report::where("status", "!=", "done")->count();

        $widget = [
            'users' => $users,
            'categories' => $categories,
            'questions' => $questions,
            'reports'   => $reports,
            //...
        ];

        return view('home', compact('widget'));
    }

}
