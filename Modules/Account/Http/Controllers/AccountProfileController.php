<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\User\Http\Requests\UpdateProfileRequest;

use Illuminate\Support\Facades\Hash;


class AccountProfileController extends Controller
{
    /**AccountProfileController
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $my = auth()->user();

        return view('public.account.profile.edit', compact('my'));
    }
    
     public function app_signup(){
        
        
        if($_POST){
         
         $user = DB::table('users')->where('email', $email);
         if($user){
            $response=array(
                'status' => 1,
                'message' => 'User Already exist',
                'data' => $user, 
            );
         }
         else{

                $first_name = $_POST['user_fname'] ;
                $last_name = $_POST['user_lname'] ;
                $email = $_POST['email'] ;
                $password = $_POST['password'] ;
                
                // if($first_name){
                    
                // }
                
                                
         }
         
         
        
            
        }
        
        $all_users = DB::table('users')->get();
        echo "<pre>";  print_r($all_users); 
        exit('here');
        
    }
    
    public function app_signin(Request $request){
        
        //  $json = file_get_contents($request->getContent());
        
        print_r($request->input());
        exit('ioio');
        $dam = $request->all();
        
        
        //print_r($dam);
        print_r($dam);
        
        //print_r($_GET); 
        
        exit('hheheh');        
        
        
        if($_REQUEST){
            print_r($_REQUEST);  
            exit('hheheh');
            
         $email = $_POST['email'] ;
         $password = $_POST['password'] ;
        
            
        }
        else{
            $email = 'zeeshananweraziz@gmail.com';
            $password = '123456';
        }
        
        
        
        $user = DB::table('users')->where('email', $email)->first();
        
        if (!Hash::check($password, $user->password)) {
            //echo "<pre>"; print_r($user); exit();
            
            $response=array(
                'status' => 1,
                'message' => 'User Not Found',
                'data' => $user, 
            );
        }
        else{
            
            
            $response=array(
                'status' => 1,
                'message' => 'User logged in successfully',
                'data' => $user, 
            );
            
            
            
        }   
        
        echo json_encode($response);
        //print_r($response); //return $user;
        exit('out'); 
        
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param \Modules\User\Http\Requests\UpdateProfileRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request)
    {
        $this->bcryptPassword($request);

        auth()->user()->update($request->all());

        return back()->withSuccess(trans('account::messages.profile_updated'));
    }

    /**
     * Bcrypt user password.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    private function bcryptPassword($request)
    {
        
        //   print_r($request);
        // exit('kokoko');
      
        
        if ($request->filled('password')) {
            
          
              
            
            return $request->merge(['password' => bcrypt($request->password)]);
        }

        unset($request['password']);
    }
}
