<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectDefaultImages extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'image', 'is_deleted', 'created_at', 'updated_at'
    ];

    /**
     * Does something interesting
     *
     * @param Place   $where  Identify project wise image
     * @param integer $repeat Showing detail of project image requested project
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Response
     */
    public function projectImages(){

        $data = array();
            $data =  ProjectDefaultImages::where('is_deleted','0')
                        ->get();
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
    public function projectImagesFirst(){
         $data = array();
          $data =  ProjectDefaultImages::where('is_deleted','0')
                        ->get()->first();

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
    public function getprojectImages($id){
         $data = array();
          $data =  ProjectDefaultImages::where('id',$id)
                        ->where('is_deleted','0')
                        ->get()->first();

        return $data;
    }
}
