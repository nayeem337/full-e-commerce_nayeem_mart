<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Nayeemmartcontroller extends Controller
{
    public function index()
    {
        return view('website.home.index');
    }

    public function category()
    {
        return view('website.category.index');
    }

    public function detail()
    {
        return view('website.detail.index');
    }


}
