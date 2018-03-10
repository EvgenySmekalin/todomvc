<?php
/**
 * Created by PhpStorm.
 * User: Evgeny
 * Date: 10.03.2018
 * Time: 12:25
 */

namespace App\Http\Controllers;


class ListController extends Controller
{
    public function index()
    {
        return view('index-list');
    }


}