<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lists;
use App\Models\ListItem;
use Illuminate\Support\Facades\DB;

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
            ksort($todoList);

            $listInstance = new Lists();
            $listInstance->newListFromSession();

            $listItem = new ListItem();
            $listItem->insertListItems($todoList, $listInstance->id);
        }

        $userLists = Lists::getUserLists();
        return view('home', compact('userLists'));
    }

    public function userList($id, $filter)
    {
        $listItem = ListItem::where('list_id', $id);
        $listName = Lists::find($id)->name;
        if ($filter === 'active') {
            $listItem->where('completed', false);
        }

        if ($filter === 'completed') {
            $listItem->where('completed', true);
        }

        $list = $listItem->get()->toArray();
        return view('index-list', compact('list', 'listName'));
    }

    public function changeName(Request $request)
    {
        $request->validate([
            'list-name' => 'required|max:255',
            'list-id' => 'required'
        ]);

        $listId   = $request->input('list-id');
        $listName = $request->input('list-name');

        if ($list = Lists::find($listId)) {
            $list->name = $listName;
            $list->update();
        }

        return redirect('home');
    }

    public function deleteList(Request $request)
    {
        $listId = $request->input('list-id');

        if ($listId) {
            DB::table('list_items')->where('list_id', $listId)->delete();
            DB::table('lists')->where('id', $listId)->delete();
        }

        return redirect('home');
    }

    public function addList(Request $request)
    {
        $request->validate([
            'list-name' => 'required|max:255',
        ]);

        if ($todoList = $request->session()->pull('list')) {
            ksort($todoList);

            $listName = $request->input('list-name');
            $list = new Lists();
            $list->newListWithName($listName);

            $listItem = new ListItem();
            $listItem->insertListItems($todoList, $list->id);

            return redirect('home');
        } else {
            return redirect()->route('/');
        }
    }
}
