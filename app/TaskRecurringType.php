<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskRecurringType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'status', 'created_at', 'updated_at'
    ];

    public function task_Recurring_list(){
        $data = array();
        $data =  TaskRecurring::where('status','active')
                    ->get();
        return $data;
    }
}
