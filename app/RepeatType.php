<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepeatType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_deleted', 'created_at', 'updated_at'
    ];

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested event
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer 
     * @return Data
     */
    public function repeat_list(){
        $data = array();
        $data =  RepeatType::where('is_deleted','0')
                ->get();
        return $data;
    }
}
