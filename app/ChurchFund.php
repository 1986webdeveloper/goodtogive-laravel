<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class ChurchFund extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'church_id', 'name', 'is_deleted', 'created_at', 'updated_at'
    ];


     /**
     * Does something interesting
     *
     * @param Place   $where  Identify user list with pagination from database respective church id and user role
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */ 

     
    public function church_fund_detail($church_id){
        $data = array();
        if( $church_id  !=  null )
            $data =  ChurchFund::where('church_id',$church_id)
                        ->where('is_deleted','0')
                        ->get();
        return $data;
    }

     /**
     * Does something interesting
     *
     * @param Place   $where  Identify user list with pagination from database respective church id and user role
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */ 


    public function single_fund_detail($id){
        $data = array();
        if( $id  !=  null )
            $data =  ChurchFund::where('id',$id)
                        ->where('is_deleted','0')
                        ->first();
        return $data;
    }
    /**
     * Does something interesting
     *
     * @param Place   $where  Identify user list with pagination from database respective church id and user role
     * @param integer $repeat Showing list of event 
     * 
     * @throws Some_Exception_Class If something interesting cannot happen
     * @author Acquaint Soft Developer
     * @return Data
     */ 
    public function churchFundDetailPagination($page,$getdata,$church_id){
        $data = array();
        if( $church_id  !=  null )
        if($page >= 0){
            $data =  ChurchFund::where('church_id',$church_id)
                        ->where('is_deleted','0');
                        if($getdata == '1'){
                    $data = $data->paginate(10)->toArray();  
            }else{
                    $data = $data->skip($page)->take(10)->get();
            }
        }else{
            $data =  ChurchFund::where('church_id',$church_id)
                        ->where('is_deleted','0')
                        ->get();
        }
        
        if(count($data) > 0)
            return $data;
        else
            return (object)array();
    }
}
