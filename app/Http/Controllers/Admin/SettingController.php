<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
class SettingController extends Controller{
 
    public function __construct() {
           $this->middleware('auth'); 
    }
    
	//show admin page 
	public function general_setting(){
		 $data['data'] = DB::table('settings')->first();
         return view('admin/frontpage.general',$data);
	}
   
 	//show admin page 
	public function social_setting(){
		 $data['data'] = DB::table('settings')->first();
         return view('admin/frontpage.social',$data);
	}
	 
    //show admin page 
	public function seo_setting(){
		 $data['data'] = DB::table('settings')->first();
         return view('admin/frontpage.seo',$data);
	}
	
	 
	//show admin page
	public function update_settings(Request $request){
		  $input = $request->all();
          $data['setting'] = DB::table('settings')->first();
			//Update logo
		   if ($request->hasFile('logo')) {
				$file = $request->file('logo');
				$destinationPath = public_path('uploads');
				if (!File::isDirectory($destinationPath)) {
					File::makeDirectory($destinationPath, 0777, true, true);
				}
				$filename = $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension();
				$image_name = 'logo'.time() . '.' . $extension;
				if ($file->move($destinationPath, $image_name)) {
					$logo = 'uploads/' . $image_name;
					/* Unlink file on update new file*/
					if(!empty($data['setting']->logo)){
						$file_with_path = public_path('/').$data['setting']->logo; 
						if (file_exists($file_with_path)) {
									unlink($file_with_path);
					 } }
				 } else {
					$logo = $data['setting']->logo;
				 }
			}else{
				 $logo = $data['setting']->logo;
			}
		

		//Update footer_logo
		   if ($request->hasFile('footer_logo')) {
				$file = $request->file('footer_logo');
				$destinationPath = public_path('uploads');
				if (!File::isDirectory($destinationPath)) {
					File::makeDirectory($destinationPath, 0777, true, true);
				}
				$filename = $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension();
				$image_name = 'footer_logo'.time() . '.' . $extension;
				if ($file->move($destinationPath, $image_name)) {
					$footer_logo = 'uploads/' . $image_name;
					/* Unlink file on update new file*/
					if(!empty($data['setting']->footer_logo)){
						$file_with_path = public_path('/').$data['setting']->footer_logo; 
						if (file_exists($file_with_path)) {
									unlink($file_with_path);
					 } }
				 } else {
					$footer_logo = $data['setting']->footer_logo;
				 }
			} else {
				 $footer_logo = $data['setting']->footer_logo;
			}


			 //Update icon
			 if ($request->hasFile('icon')) {
				$file = $request->file('icon');
				$destinationPath = public_path('uploads');
				if (!File::isDirectory($destinationPath)) {
					File::makeDirectory($destinationPath, 0777, true, true);
				}
				$filename = $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension();
				$image_name = 'icon'.time() . '.' . $extension;
				if ($file->move($destinationPath, $image_name)) {
					$icon = 'uploads/' . $image_name;
					/* Unlink file on update new file*/
					if(!empty($data['setting']->icon)){
						$file_with_path = public_path('/').$data['setting']->icon; 
						if (file_exists($file_with_path)) {
									unlink($file_with_path);
					 } }
				 } else {
					$icon = $data['setting']->icon;
				 }
				  }else{
						 $icon = $data['setting']->icon;
			   }
		  
        $updated = DB::table('settings')->where('id',1)->update([
			'site_title'=>$input['site_title'],
			'phone'=>$input['phone'],
			'whatsapp'=>$input['whatsapp'], 
			'whatsapp_message'=>$input['whatsapp_message'], 
			'email'=>$input['email'], 
			'address'=>$input['address'], 
			'city'=>$input['city'], 
			'state'=>$input['state'], 
			'country'=>$input['country'], 
			'country_code'=>$input['country_code'], 
			'currency_sign'=>$input['currency_sign'],   
			'logo'=>$logo,
			'footer_logo'=>$footer_logo,
			'icon'=>$icon]);
        if ($updated) { 
            return redirect()->back()->with('success','Record is updated successfully!');
        }else{
            return redirect()->back()->with('danger','Oops something went wrong.');
        }
    }
	
	//show admin page
	public function update_social(Request $request){
		  $input = $request->all();
          $updated = DB::table('settings')->where('id',1)->update([
			'facebook'=>$input['facebook'],
			'youtube'=>$input['youtube'],
			'twitter'=>$input['twitter'],
			'instagram'=>$input['instagram'], 
			'google'=>$input['google'], 
			'skype'=>$input['skype'], 
			'pinterest'=>$input['pinterest']]);
        if ($updated) {
            return redirect()->back()->with('success','Record is updated successfully!');
        }else{
            return redirect()->back()->with('danger','Oops something went wrong.');
        }
    }
	
	//show admin page
	public function update_seo(Request $request){
		  $input = $request->all();
          $updated = DB::table('settings')->where('id',1)->update([
			'seo_title'=>$input['seo_title'],
			'seo_description'=>$input['seo_description'],
			'seo_tag'=>$input['seo_tag']]);
        if ($updated) {
            return redirect()->back()->with('success','Record is updated successfully!');
        }else{
            return redirect()->back()->with('danger','Oops something went wrong.');
        }
    }
	
	  

}
