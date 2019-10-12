<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectDonationSlab extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'amount', 'created_at', 'updated_at', 'is_deleted'
    ];

    // public function project_donation_slab()
    // {
    //     return $this->belongsTo('App\Project');
    // }
    
    /**
     * Does something interesting
     *
     * @param Place   $where  Identify project wise donation slab
     * @param integer $repeat Showing detail of requested project 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function delte_slab($project_id){
       $data = array();
        $data =  self::where('project_id',$project_id)
                ->update(['is_deleted' => '1']);
        return $data;
    }

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify event from database respective id and check event is deleted or not
     * @param integer $repeat Showing detail of requested report
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Bhawani Singh Chouhan <bhawani@aquaintsoft.com>
     * @return Data
     */
    public function donation_slab($project_id){
        $data = array();
        if( $project_id != null )
            $data =  ProjectDonationSlab::where('project_id',$project_id)
                        ->where('is_deleted','0')
                        ->get();
        return $data;
    }
}
