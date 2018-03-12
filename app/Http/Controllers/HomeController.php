<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lists;
use App\Models\ListItem;

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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($todoList = $request->session()->pull('list')) {
            $listInstance = new Lists();
            $listInstance->newListFromSession();

            $listItem = new ListItem();
            $listItem->insertListItems($todoList, $listInstance->id);
        }

        $userLists = Lists::getUserLists();
//        var_dump($userLists[0]->listItems->toArray());
//        var_dump(Lists::first()->list_items);
//        exit;

        return view('home', compact('userLists'));
    }

    public function userList($id, $filter)
    {
        $list = ListItem::where('list_id', $id);
        if ($filter === 'active') {
            $list->where('completed', false);
        }

        if ($filter === 'completed') {
            $list->where('completed', true);
        }

        $list = $list->get()->toArray();
        return view('index-list', compact('list', 'id'));
    }
}
