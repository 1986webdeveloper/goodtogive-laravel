<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Facades\File;
use Storage;
use App\Service;
use App\Model;
use App\Mail\Quote;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;


class DynamicCSVController extends Controller
{
    function index()
    {
     $customer_data = $this->get_customer_data();
     return view('dynamic_pdf')->with('customer_data', $customer_data);
    }

    function get_customer_data()
    {

     $customer_data = DB::table('users')
         ->limit(10)
         ->get();
     return $customer_data;
    }

    function pdf($id)
    {
     $handal_date = date("d-m-Y"); 
     $pdf = \App::make('dompdf.wrapper');
     $pdf->loadHTML($this->convert_customer_data_to_html_pdf($id));
     return $pdf->download('GTG-'.$handal_date);
    }
  public static function convert_customer_data_to_html_pdf($id) {
        $userDetailObj  = new \App\User;
        $UserAddress = new \App\UserAddress;
        $user = $userDetailObj->find($id);
        $user->firstname = ucfirst($user->firstname);
        $user->lastname = ucfirst($user->lastname);
        $church_detail = $userDetailObj->find($user->church_id);
        $user->church_name = ucfirst($church_detail->firstname);
        $UserAddress = $UserAddress->user_address_detail_user_id($id);
        if($UserAddress){
        $address = ucfirst($UserAddress->billingAddress1);
        $address1 = ucfirst($UserAddress->billingAddress2);
        $address2 = ucfirst($UserAddress->billingState .' , '.$UserAddress->billingCity);
        $user->address = $address;
        $user->address1 = $address1;
        $user->address2 = $address2;
        $user->billingpostcode = $UserAddress->billingPostcode;
        }else{
            $user->address = '';
            $user->address1 = '';
            $user->address2 = '';
            $user->billingpostcode = '';  
        }
        $user->date = date('d-m-Y');
        /*echo "<pre>";
         print_r($UserAddress); exit;*/
        $cmsManagementDetailObj  = new \App\DeclarationCms;
        $cmsManagement = $cmsManagementDetailObj->get_declaration_form();
        
        $cmsManagement->extra_data = $user;
        
        $push_notification_data = collect($cmsManagement->title)
        ->put(
        "extra_data",
        collect($cmsManagement->extra_data)
        ->mapWithKeys(function ($cmsManagement, $key) { 
        return ["##" . strtoupper($key) . "##" => $cmsManagement];
        })
        ->all()
        )
        ->all();

        $search = array_keys($push_notification_data["extra_data"]);
        $replace = array_values($push_notification_data["extra_data"]);

        $cmsManagement->message = str_replace($search, $replace, $cmsManagement->description);
        return $cmsManagement->message;
  }
  public static function date_mysql_format($date, $format = "m/d/Y") {
    if ($date == "" || $date == "00-00-0000") {
        return "";
    }
    if ($format == 'd/m/Y') {
        return date('Y-m-d', strtotime(preg_replace('/(\d{2})\/(\d{2})\/(\d{4})/', '$3-$2-$1', $date)));
    } else if ($format == 'm/d/Y') {
        return date('Y-m-d', strtotime(preg_replace('/(\d{2})\/(\d{2})\/(\d{4})/', '$3-$1-$2', $date)));
    } else if ($format == 'Y/m/d') {
        return date('Y-m-d', strtotime(preg_replace('/(\d{4})\/(\d{2})\/(\d{2})/', '$1-$2-$3', $date)));
    }
    return date('Y-m-d', strtotime($date));
  }

   function csv(Request $request)
    {
      //$handal_date = date("d-m-Y"); 
      $handal_date = date("d-m-Y"); 
      $date = self::date_mysql_format($handal_date);
/*http://192.168.1.39/goodtogive-web/admin/csv/27-06-2019*/
      $filename = "file.csv";
      $handle = fopen($filename, 'w+');

      $report_list = DB::table('project_donation_payments')
      ->where('transaction_date',$date)->get()->all();
     
   if($report_list){
        $report_details = datatables()->of($report_list)->addIndexColumn()->toJson();

      fputcsv($handle, array("Number","Church Reference Id","Church Name","Project Name","Project Goal Amount","Received Amount","Admin Commission","Payment Type","Payment Transaction Id","QR Scanned Verified","Transaction Date"), ",",'"');  
      foreach($report_details->getData() as $key => $rows){
      if($key == "data") {
      foreach($rows as $type => $row){
      $projectDetailObj = new \App\Project;
      $userDetailObj = new \App\User;
      $project = $projectDetailObj->find($row->project_id);
      $church_detail = $userDetailObj->find($project->church_id);
      fputcsv($handle,array($row->DT_RowIndex,$church_detail->church_reference_id,$church_detail->firstname,$project->name,$project->goal_amount,$row->amount,$row->admin_commission,$row->payment_gateway_type,$row->payment_transaction_id,$row->qr_scanned_verified,$row->created_at), ",",'"');  
      }
      }
      }
    }else{
   fputcsv($handle, array("Number","Church Reference Id","Church Name","Project Name","Project Goal Amount","Received Amount","Admin Commission","Payment Type","Payment Transaction Id","QR Scanned Verified","Transaction Date"), ",",'"');  
    }
      fclose($handle);
      $headers = array(
      'Content-Type' => 'text/csv',
      );

      return response()->download($filename, 'GTG-'.$handal_date.'.csv', $headers);
 
    }

    function convert_customer_data_to_html()
    {
     $customer_data = $this->get_customer_data();
     $output = '
     <h3 align="center">Customer Data</h3>
     <table width="100%" style="border-collapse: collapse; border: 0px;">
      <tr>
    <th style="border: 1px solid; padding:12px;" width="20%">Name</th>
    <th style="border: 1px solid; padding:12px;" width="30%">Address</th>
    <th style="border: 1px solid; padding:12px;" width="15%">City</th>
    <th style="border: 1px solid; padding:12px;" width="15%">Postal Code</th>
    <th style="border: 1px solid; padding:12px;" width="20%">Country</th>
   </tr>
     ';  

     foreach($customer_data as $customer)
     {

      $output .= '
      <tr>
       <td style="border: 1px solid; padding:12px;">'.$customer->first_name.'</td>
       <td style="border: 1px solid; padding:12px;">'.$customer->last_name.'</td>
       <td style="border: 1px solid; padding:12px;">'.$customer->user_type.'</td>
       <td style="border: 1px solid; padding:12px;">'.$customer->email_id.'</td>
       <td style="border: 1px solid; padding:12px;">'.$customer->address_id.'</td>
      </tr>
      ';
     }
     $output .= '</table>';
     return $output;
    }
}