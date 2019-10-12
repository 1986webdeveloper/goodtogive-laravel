<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFamilyTree extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id','created_at','updated_at'
    ];

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify user card info from database and check event is deleted or not
     * @param integer $repeat Showing detail of requested event
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function find_user_family($scan_user_id){

        $data = array();
        $data =  self::where(function($query) use($scan_user_id){
                        $query->where('user_id',$scan_user_id);
                        })->first();
        
        return $data;
    }

 /**
     * Does something interesting
     *
     * @param Place   $where  Identify user card info from database and check event is deleted or not
     * @param integer $repeat Showing detail of requested event
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function find_user($user_id){

        $data = array();
        $data =  self::where('user_id',$user_id)->first();
        
        return $data;
    }
    /**
     * Does something interesting
     *
     * @param Place   $where  Identify user card info from database and check event is deleted or not
     * @param integer $repeat Showing detail of requested event
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function get_all_family_member($id,$user_id){

        $data = array();
        $data =  self::where('id',$id)
                  ->where('user_id','!=',$user_id)->get();
        
        return $data;
    }

      /**
     * Does something interesting
     *
     * @param Place   $where  Identify user card info from database and check event is deleted or not
     * @param integer $repeat Showing detail of requested event
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function delete_family_member($user_id){

        $data = array();
        $data =  self::where('user_id',$user_id)->delete();
        
        return $data;
    }

    
}
