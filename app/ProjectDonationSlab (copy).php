<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectDonationPayments extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'project_id', 'amount', 'payment_status', 'payment_gateway_type','payment_gateway_response','payment_transaction_id','qr_scanned_verified','is_deleted','created_at','updated_at'
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
    public function delte_donation($id){
       $data = array();
        $data =  self::where('id',$id)
                ->update(['is_deleted' => '1']);
        return $data;
    }
    public function donation_payment($user_id){
        $data = array();
        if( $project_id != null )
            $data =  ProjectDonationSlab::where('user_id',$user_id)
                        ->where('is_deleted','0')
                        ->get();
        return $data;
    }
}
