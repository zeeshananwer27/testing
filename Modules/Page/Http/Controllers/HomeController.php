<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->active();
        $this->directory();
        return view('public.home.index');
    }
    
     public function active(){
         
         $oldtimezone = date_default_timezone_get(); 
         
         $current_date = date('Y-m-d h:i:s'); 
         // date_default_timezone_set("Pacific/Pitcairn");
         // $current_date = date('Y-m-d h:i:s'); 
         // date_default_timezone_set($oldtimezone);    
        
         $count = 0;
         
         $end_events = DB::table('products')->where('is_active',1)->where('deleted_at',NULL)->get()->toArray();
         
         $updateable = array();
         
         foreach($end_events as $event){
             $end_date = date('Y-m-d', strtotime($event->end_event) );
             $end_date = $end_date .' 18:00:00';
             
             if(strtotime($end_date) < strtotime($current_date) ){
                 array_push( $updateable , $event->id ); 
             }
         }
         
        foreach($updateable as $updateab){
            $updated = DB::table('products')->where('id',$updateab)->update( ['is_active'=> 0 ]);    
            if($updated){
                $count++;
            }
         }
         
        //  echo "<pre>"; print_r($end_events); exit('lplplp');  
         
          
         
        //  $active_events = DB::table('products')->where('is_active',1)->where('end_event' ,'<' ,$current_date)->where('deleted_at',NULL)->get()->toArray();
        //  foreach($active_events as $active){
        //      if($active->is_active == 1){
        //             $updated = DB::table('products')->where('id',$active->id)->update( ['is_active'=> 0 ]);    
        //             if($updated){
        //                 $count++;
        //             }
        //      }
        //  }
        
        
  
        }
        
    public function directory(){
        
        $dir =  public_path('/storage/pdfs');
        $current_date = date("Y-m-d");
        $dh  = opendir($dir);
        $files = array(); 
        while (false !== ($filename = readdir($dh))) {
            if($filename){
                $exp = explode('.',$filename);
                $expo = explode('_',$exp[0]);
                if(isset($expo[1])){
                    $file_date = date("Y-m-d", filemtime(public_path('/storage/pdfs/'.$filename)));
                    if( strtotime($file_date)  > strtotime($current_date) ){
                        unlink(public_path('/storage/pdfs/'.$filename));
                        array_push($files, $filename);
                    }
                }
            }
        }
    }    
}
