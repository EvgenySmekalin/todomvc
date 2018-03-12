<?php
/**
 * Created by PhpStorm.
 * User: sea
 * Date: 12.03.2018
 * Time: 17:49
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Lists extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lists';
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
    protected $fillable = ['user_id', 'name'];

    public function listItems()
    {
        return $this->hasMany('App\Models\ListItem', 'list_id');
    }

    public function newListFromSession()
    {
        $user = Auth::user();
        $this->user_id = $user->id;
        $this->name    = $user->name . "'s list";
        $this->save();
    }

    public static function getUserLists()
    {
        return self::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();
    }
}