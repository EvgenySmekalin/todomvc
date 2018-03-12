<?php
/**
 * Created by PhpStorm.
 * User: Evgeny
 * Date: 10.03.2018
 * Time: 12:25
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function index(Request $request)
    {
        $list = $request->session()->get('list', []);
        return view('index-list', ['list' => $list]);
    }

    public function create(Request $request)
    {
        if (!$request->ajax()) {
            redirect('/');
        }

        $id = $request->input('id');
        $insert = [
            'title'     => $request->input('title'),
            'id'        => $id,
            'completed' => $request->input('completed')
        ];
        $request->session()->put('list.' . $id, $insert);
        return response()->json(['msg'=> 'OK'], 200);
    }

    public function delete(Request $request)
    {
        if (!$request->ajax()) {
            redirect('/');
        }

        $request->session()->pull('list.'.$request->input('id'));
        return response()->json(['msg'=> 'OK'], 200);
    }

    public function patch(Request $request)
    {
        if (!$request->ajax()) {
            redirect('/');
        }

        $id    = $request->input('id');
        $value = $request->session()->pull('list.' . $id);

        if (empty($value)) {
            return response()->json(['msg'=> 'Not found'], 404);
        }

        if ($title = $request->input('title')) {
            $value['title'] = $title;
        }

        if ($completed = $request->input('completed')) {
            $value['completed'] = $value['completed'] === true ? false : true;
        }

        $request->session()->put('list.' . $id, $value);
        return response()->json(['msg'=> $value], 200);
    }
}