<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGiftDeclarations extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','project_id', 'declaration_id','is_deleted', 'created_at', 'updated_at'
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
    public function check_project_gift($user_id,$project_id){
        
        $data = array();
        $data =  UserGiftDeclarations::where('user_id',$user_id)
                        ->where('project_id',$project_id)
                        ->where('is_deleted','0')
                        ->count();
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
     
  public function countdeclaration($user_id){
     $data = array();
         $data =  UserGiftDeclarations::where('user_id',$user_id)
                    ->where('is_deleted','0')->count();
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
    public function git_add_with_declaration($user_id,$project_id,$declaration_id,$pdfgenerate){
        $data = array();
        
            $declaration_detail = array(
            'user_id' => $user_id,
            'project_id' => $project_id,
            'declaration_id' => $declaration_id,
            'pdf_path' => $pdfgenerate,
            'created_at' => now(),
            );
            $data = \DB::table('user_gift_declarations')->insertGetId($declaration_detail);

        return $data;
    }
}
