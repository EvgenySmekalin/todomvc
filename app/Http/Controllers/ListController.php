<?php
/**
 * Created by PhpStorm.
 * User: Evgeny
 * Date: 10.03.2018
 * Time: 12:25
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ListItem;

class ListController extends Controller
{
    public function index(Request $request)
    {
        $list = $request->session()->get('list', []);
        return view('index-list', ['list' => $list, 'id' => 0]);
    }

    public function create(Request $request)
    {
        if (!$request->ajax()) {
            redirect('/');
        }

        $id = $request->input('id');
        $listId = (int)$request->input('list_id');
        $insert = [
            'title'     => $request->input('title'),
            'id'        => $id,
            'completed' => $request->input('completed')
        ];

        if ($listId) {
            DB::table('list_items')->insert(['list_id' => $listId, 'title' => $insert['title'], 'completed' => $insert['completed']]);
        } else {
            $request->session()->put('list.' . $id, $insert);
        }

        return response()->json(['msg'=> 'OK'], 200);
    }

    public function delete(Request $request)
    {
        if (!$request->ajax()) {
            redirect('/');
        }

        $listId = (int)$request->input('list_id');
        $id     = $request->input('id');

        if ($listId) {
            DB::table('list_items')->where('id', $id)->delete();
        } else {
            $request->session()->pull('list.'.$id);
        }

        return response()->json(['msg'=> 'OK'], 200);
    }

    public function patch(Request $request)
    {
        if (!$request->ajax()) {
            redirect('/');
        }

        $listId = (int)$request->input('list_id');
        $title     = $request->input('title');
        $completed = $request->input('completed');
        $id        = $request->input('id');

        if ($listId) {
            return $this->patchAuthUserList($title, $completed, $id);
        } else {
            return $this->patchSessionUser($request, $title, $completed, $id);
        }
    }

    protected function patchAuthUserList($title, $completed, $id)
    {
        $value = ListItem::where('id', $id)->first();

        if (empty($value)) {
            return response()->json(['msg'=> 'Not found'], 404);
        }

        if ($title) {
            $value->title = $title;
        }

        if ($completed) {
            $value->completed = $value->completed == true ? false : true;
        }

        $value->update();
        return response()->json(['msg'=> 'OK'], 200);
    }

    protected function patchSessionUser(Request $request, $title, $completed, $id)
    {
        $value = $request->session()->pull('list.' . $id);

        if (empty($value)) {
            return response()->json(['msg'=> 'Not found'], 404);
        }

        if ($title) {
            $value['title'] = $title;
        }

        if ($completed) {
            $value['completed'] = $value['completed'] === true ? false : true;
        }

        $request->session()->put('list.' . $id, $value);
        return response()->json(['msg'=> 'OK'], 200);
    }
}