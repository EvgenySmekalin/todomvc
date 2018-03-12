<?php
/**
 * Created by PhpStorm.
 * User: sea
 * Date: 12.03.2018
 * Time: 17:34
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ListItem extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['list_id', 'title', 'completed'];

    public function insertListItems($todoList, $listId)
    {
        $insertArray = [];
        foreach ($todoList as $item) {
            $insertArray[] = ['list_id' => $listId, 'title' => $item['title'], 'completed' => $item['completed']];
        }
        DB::table('list_items')->insert($insertArray);
    }
}