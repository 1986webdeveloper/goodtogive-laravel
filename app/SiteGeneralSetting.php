<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteGeneralSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'option_name', 'option_value', 'status', 'type','created_at', 'updated_at', 'is_deleted'
    ];

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify general setting
     * @param integer $repeat Showing detail of requested project 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */
    public function siteGeneralSetting($title){
        $data = array();
        if( $title != null )
            $data =  siteGeneralSetting::where('option_name',$title)
                        ->where('is_deleted','0')
                        ->first();
        return $data;
    }
}
