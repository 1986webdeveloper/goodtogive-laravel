<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\GeneralController AS General;
class ProjectSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'field_name', 'field_value', 'status', 'is_deleted', 'created_at', 'updated_at'
    ];

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify user setting
     * @param integer $repeat  
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint developer
     * @return Data
     */ 
    public function projectSetting($user_id,$field_name){
        $data = array();
        $data =  $this::where('status','active')
                        ->where('user_id',$user_id)
                        ->where('field_name',$field_name)
                        ->where('is_deleted','0')
                        ->first();
        return $data;
    }
    
    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested project setting
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
     public function updateprojectSetting($project_id,$field_name,$field_value){
        $data = array();
         $count_data  =  $this::where('status','active')
                        ->where('project_id',$project_id)
                        ->where('field_name',$field_name)
                        ->where('is_deleted','0')
                        ->count();
         if($count_data > 0){
        $data =  $this::where('status','active')
                        ->where('project_id',$project_id)
                        ->where('field_name',$field_name)
                        ->where('is_deleted','0')
                        ->update(["field_value" => $field_value]);
        }else{
        $data =  $this::create(["project_id" => $project_id,"field_value" => $field_value,"field_name" => $field_name]);
        }
        return $data;
    }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify user setting
     * @param integer $repeat  
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint developer
     * @return Data
     */ 
    public function projectSettingList($project_id){
        $data = array();
        $data =  $this::where('status','active')
                        ->where('project_id',$project_id)
                        ->where('is_deleted','0')
                        ->get();
        foreach ($data as $key => $value) {
            if($value->field_name == 'BACKGROUND_IMAGE'){
                $value->field_value = General::get_file_src($value->field_value);
            }
        }
        return $data;
    }

    
}
