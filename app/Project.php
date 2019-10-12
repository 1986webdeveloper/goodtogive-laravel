<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\GeneralController AS General;

/**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @copyright  2019 acquaintsoft Pvt. Ltd
 * @license    http://example.com   PHP License 1.0
 * @version    Release: @package_version@
 * @link       http://example.com
 * @since      Class available since Release 1.0.0
 */ 

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id', 'church_fund_id', 'name', 'description', 'startdate', 'enddate', 'goal_amount', 'qrcode', 'need_to_scan_qr', 'donation_slab_custom_amount', 'is_deleted','is_goal_amount','is_donated_amount', 'created_at', 'updated_at','is_project_archive','is_project_custom','status'
    ];


    protected $forcedNullFields = [
        'enddate'
    ];


    public static function boot()
    {
        
        parent::boot();

        static::retrieved(function($model)
        {

            if (count($model->forcedNullFields) > 0) {

                foreach ($model->toArray() as $fieldName => $fieldValue) {
                    if ( empty($fieldValue) && in_array($fieldName,$model->forcedNullFields)) {
                       $model->attributes[$fieldName] = '';
                    }else if($fieldName == 'enddate' && $fieldValue == '0000-00-00 00:00:00'){
                      $model->attributes[$fieldName] = '';  
                    }
                }
            }

            return true;
        });
        static::saving(function($model)
        {

            if (count($model->forcedNullFields) > 0) {

                foreach ($model->toArray() as $fieldName => $fieldValue) {
                    if ( empty($fieldValue) && in_array($fieldName,$model->forcedNullFields)) {
                       $model->attributes[$fieldName] = '';
                    }else if($fieldName == 'enddate' && $fieldValue == '0000-00-00 00:00:00'){
                      $model->attributes[$fieldName] = '';  
                    }
                }
            }

            return true;
        });

    }

    /**
     * Does something interesting
     *
     * Showing relationship between project donation slab and project
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function project_donation_slabs(){
        return $this->hasMany('App\ProjectDonationSlab','project_id','id')->where('is_deleted','0');
    }

    public function project_settings_detail(){
        return $this->hasMany('App\ProjectSetting','project_id','id');
    }

    /**
     * Does something interesting
     *
     * Showing relationship between project image and project
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function project_images(){
        return $this->hasMany('App\ProjectImage','project_id','id')->where('is_deleted','0');
    }
    
    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of project 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 


    public function project(){
        return $this->belongsTo('App\Project','id','church_id');
    }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective user id and check event is deleted or not
     * @param integer $repeat Showing list of project 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */ 

    public function project_list($church_id){
        $data = array();
        if( $church_id  !=  null )
            $data =  Project::with(['project'])
                        ->where('church_id',$church_id)
                        ->where('is_deleted','0')
                        ->where('status','active')
                        ->get();
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
    public function projectListDonation($page,$getdata,$church_id,$role_id,$is_archive){
        $data = array();
        if( $church_id  !=  null )
            $data =  Project::with(['project_settings_detail','project','project_donation_slabs','project_images'])
                        ->where('church_id',$church_id)
                        ->where('is_deleted','0')
                        ->where('status','active');
                if($is_archive == '0'){
                      $data = $data->where(function($query) {
                        $query->where('enddate','>=',date('Y-m-d'))
                        ->orWhere('enddate','0000-00-00 00:00:00');
                        })->where('is_project_archive','0');
                 //$data = $data->where('enddate','>=',date('Y-m-d'));
                }else if($is_archive == '1'){
                  $data = $data->where(function($query) {
                        $query->where('enddate','!=','0000-00-00 00:00:00')
                        ->where('enddate','<',date('Y-m-d'))->orWhere('is_project_archive','1');
                        });
                 //$data = $data->where('enddate','<',date('Y-m-d'));
                }
                if($role_id == '2'){
                $data = $data->where('startdate','<=',date('Y-m-d'))->where(function($query) {
                        $query->where('enddate','>=',date('Y-m-d'))
                        ->orWhere('enddate','0000-00-00 00:00:00');
                        })->where('is_project_archive','0');
                }
                if($getdata == '1'){
                    $data = $data->paginate(10)->toArray();  
                }else{
                    $data = $data->orderBy('id','DESC')->skip($page)->take(10)->get();
                }
            
            
            
            return $data;
    }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested event
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
    public function project_detail($id){
        $data = array();
        if( $id  !=  null )
            $data =  Project::with(['project_settings_detail','project','project_donation_slabs','project_images'])
                        ->where('id',$id)
                        ->where('is_deleted','0')
                        ->where('status','active')
                        ->first();
             
        return $data;
    }

    

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify total number of project
     * @param integer $repeat Showing count of total project
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer 
     * @return Data
     */
    public function project_count(){
        $data = array();
        $data =  Project::with(['project'])
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
    public function project_list_filter($church_id){
        $data = array();
        if( $church_id  !=  null )
            $data =  Project::select('id','name')->where('church_id',$church_id)
                        ->where('is_deleted','0')
                        ->where('status','active')
                        ->get();
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
    public function project_list_filter_user($project_lists){
        
        $data = array();
        if($project_lists  !=  null )
            $data =  Project::select('id','name')
                        ->whereIn('id',$project_lists)
                       // ->whereIn('church_id',$church_id)
                        //->where('is_deleted','0')
                        //->where('status','active')
                        ->get();
            
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
    public function archieve_project_list(){
        $data = array();

        $data =  Project::where('is_deleted','0')
                        ->where('status','active');
         $data = $data->where(function($query) {
                        $query->where('enddate','!=','0000-00-00 00:00:00')
                        ->where('enddate','<',date('Y-m-d'))->where('is_project_archive','!=','1');
                        })->get();
         
        return $data;
       
    }

}
