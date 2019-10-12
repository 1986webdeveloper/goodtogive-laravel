<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskPriority extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'color_code', 'status', 'is_deleted', 'created_at', 'updated_at'
    ];

    public function task_priority_list(){
        $data = array();
        $data =  TaskPriority::where('is_deleted','0')
                    ->where('status','active')
                    ->get();
        return $data;
    }
}
