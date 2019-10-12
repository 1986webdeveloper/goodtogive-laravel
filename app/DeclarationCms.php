<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeclarationCms extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title','description', 'is_deleted', 'created_at', 'updated_at'
    ];
    

     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
    public function delete_prev(){
        $data = array();
        
            $data =  \DB::table('declaration_cms')
                ->update(['is_deleted' => '1']);
            
        return $data;
    }

     /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 
    public function get_declaration_form(){
        $data = array();
            $data =  \DB::table('declaration_cms')->where('is_deleted','0')->get()->first();
        return $data;
    }
}
