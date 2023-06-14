<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Feature;
use App\Models\ProductFeature;
use App\Models\HomePageContent;
use App\Models\User;
use DB;
use Image;

class AdminfeatureimgController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $auth_id = auth()->user()->id;
        if(auth()->user()->role == 'superadmin') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }

        $featureData = DB::table('users')->select('feature_img')->where('role','superadmin')->get();
        return view('superadmin.managepfeatureimage',compact('featureData'));
    }

    public function vieweditmodal(Request $request)
    {
      $json = array();
      $auth_id = auth()->user()->id;
      $checklist = User::where('role', 'superadmin')->get()->first();
        if($checklist->feature_img != null) {
           $userimage = url('uploads/featureimg/thumbnail/'.$checklist->feature_img);
        } else {
           $userimage = url('/').'/uploads/servicebolt-noimage.png';
        }   
      $html ='<div class="add-customer-modal">
                  <h5>Edit Image</h5>
                 </div>
            <div class="col-md-12 mb-2">
             <div class="input_fields_wrap">
                <div class="mb-3">
                    <input type="file" class="form-control" style="margin-bottom: 5px" name="image" id="image" accept="image/png, image/gif, image/jpeg">
                </div>
                <div class="mb-3 text-center">
                    <img src="'.$userimage.'" class="defaultimage" style="width:30%;height:100px;">
                </div>
            </div>
          </div>
        <div class="col-lg-6 mb-3" style="display:none;">
          <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
        </div><div class="col-lg-6 mb-3 mx-auto">
          <button class="btn btn-add btn-block" type="submit">Update</button>
        </div>';
        return json_encode(['html' =>$html]);
          die;
    }

    public function updatepimage(Request $request)
    {
      $fimage = User::where('role', 'superadmin')->get()->first();
      
      $logofile = $request->file('image');
      if(isset($logofile)) {
        $datetime = date('YmdHis');
        $image = $request->image->getClientOriginalName();
        $imageName = $datetime . '_' . $image;
        $logofile->move(public_path('uploads/featureimg/'), $imageName);
        $fimage->feature_img = $imageName;

        Image::make(public_path('uploads/featureimg/').$imageName)->fit(540,450)->save(public_path('uploads/featureimg/thumbnail/').$imageName);
      }
      $fimage->save();
      $request->session()->flash('success', 'Image has been updated successfully');
      return redirect()->route('superadmin.manageProductimg');
    }

    public function create(Request $request)
    {
        $data['description'] = $request->description;
        Feature::create($data);
        $request->session()->flash('success', 'Feature added successfully');
        return redirect()->route('superadmin.manageFeature');
    }

    public function viewfeaturemodal(Request $request) {
      $json = array();
        
        $tenture = DB::table('feature')->where('id', $request->id)->first();
        $html ='<div class="modal-header">
                    <h5 id="offcanvasRightLabel" class="">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <input type="hidden" name="tid" id="tid" value="'.$request->id.'">
                  </div> 
            <div class="modal-body">
                <div class="filter">
                    <div class="row">
                    <div class="col-md-12 mb-3">
                    Feature Description
                    <textarea class="form-control" name="description" id="description" required="" cols="10" rows="5">'.$tenture->description.'</textarea>
                    </div>

                    <div style="text-align: -webkit-center;">
                    <div class="col-lg-6">
                    <button type="submit" class="btn btn-add btn-block">Update</button>
                    </div>
                    </div>
                </div>
                </div>
            </div>';
        return json_encode(['html' =>$html]);
        die;  
    }

    public function featureupdate(Request $request) {
        $tenture = Feature::where('id',$request->tid)->first();
        $tenture->description = $request->description;
        $tenture->save();
        $request->session()->flash('success', 'Feature Updated successfully');
        return redirect()->route('superadmin.manageFeature');
    }

    public function featurestatus(Request $request) {
        $tenture = Feature::where('id',$request->userid)->first();
        $tenture->status = $request->status;
        $tenture->save();
        echo "1";
    }

    public function featuredelete(Request $request) {
      $id = $request->id;
      DB::table('feature')->delete($id);
      echo "1";
    }

    public function productfeature(Request $request)
    {
        $auth_id = auth()->user()->id;
        if(auth()->user()->role == 'superadmin') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }

        $productfeature = DB::table('adminproductfeature')->orderBy('id','desc')->get();
        return view('superadmin.productfeature',compact('productfeature'));
    }

    public function store(Request $request)
    {

        $logofile = $request->file('image');
        $data = array();
        if(isset($logofile)) {
           $new_file = $logofile;
           $path = 'uploads/productchecklist/';
           $thumbnailpath = 'uploads/productchecklist/thumbnail/';
           $imageName = custom_fileupload1($new_file,$path,$thumbnailpath);

           $data['image'] = $imageName; 
        }
            $data['productfeature'] = $request->description;
            
           ProductFeature::create($data);

        $request->session()->flash('success', 'Product Feature added successfully');
        
        return redirect()->route('superadmin.productfeature');
    }

    public function viewproductfeaturemodal(Request $request) {
      $json = array();
        
        $tenture = DB::table('adminproductfeature')->where('id', $request->id)->first();
        
        if($tenture->image != null) {
            $userimage = url('uploads/productchecklist/thumbnail/'.$tenture->image);
        } else {
            $userimage = url('/').'/uploads/servicebolt-noimage.png';
        }

        $html ='<div class="modal-header">
                    <h5 id="offcanvasRightLabel" class="">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <input type="hidden" name="tid" id="tid" value="'.$request->id.'">
                  </div> 
            <div class="modal-body">
                <div class="filter">
                    <div class="row">
                    <div class="col-md-12 mb-3">
                    Product Feature Description
                    <textarea class="form-control" name="description" id="description" required="" cols="10" rows="5">'.$tenture->productfeature.'</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <input type="file" class="form-control" style="margin-bottom: 5px" name="image" id="image" accept="image/png, image/gif, image/jpeg">
                    </div>
                    <div class="mb-3 text-center">
                        <img src="'.$userimage.'" class="defaultimage" style="width:30%;height:100px;">
                    </div>

                    <div style="text-align: -webkit-center;">
                    <div class="col-lg-6">
                    <button type="submit" class="btn btn-add btn-block">Update</button>
                    </div>
                    </div>
                </div>
                </div>
            </div>';
        return json_encode(['html' =>$html]);
        die;  
    }

    public function productfeatureupdate(Request $request) {
        $tenture = ProductFeature::where('id',$request->tid)->first();
        $tenture->productfeature = $request->description;

        $logofile = $request->file('image');
        if(isset($logofile)) {
           $new_file = $logofile;
           $path = 'uploads/productchecklist/';
           $thumbnailpath = 'uploads/productchecklist/thumbnail/';
           $imageName = custom_fileupload1($new_file,$path,$thumbnailpath);

           $tenture->image = $imageName; 
        }

        $tenture->save();
        $request->session()->flash('success', 'Feature Updated successfully');
        return redirect()->route('superadmin.productfeature');
    }

    public function productfeaturedelete(Request $request) {
      $id = $request->id;
      DB::table('adminproductfeature')->delete($id);
      echo "1";
    }

    public function managehomepagecontent(Request $request) 
    {
        $auth_id = auth()->user()->id;
        if(auth()->user()->role == 'superadmin') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }

        $homepagecontent = DB::table('homepagecontent')->get();
        return view('superadmin.homepagecontent',compact('homepagecontent'));
    }

    public function viewhomepagemodal(Request $request)
    {
       $json = array();
        
        $homepagedata = DB::table('homepagecontent')->where('id', $request->id)->first();
        $html ='<div class="modal-header">
                    <h5 id="offcanvasRightLabel" class="">Edit '.$homepagedata->title.'</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <input type="hidden" name="tid" id="tid" value="'.$request->id.'">
                  </div> 
            <div class="modal-body">
                <div class="filter">
                    <div class="row">
                    <div class="col-md-12 mb-3">
                    Title <br>
                    <input type="text" name="title1" id="title1" value="'.$homepagedata->title1.'" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                    Descriotion
                    <textarea class="form-control" name="description" id="description" required="" cols="10" rows="5">'.$homepagedata->content.'</textarea>
                    </div>

                    <div style="text-align: -webkit-center;">
                    <div class="col-lg-6">
                    <button type="submit" class="btn btn-add btn-block">Update</button>
                    </div>
                    </div>
                </div>
                </div>
            </div>';
        return json_encode(['html' =>$html]);
        die;       
    }

    public function homepagecontentupdate(Request $request) {
        $HomePageContent = HomePageContent::where('id',$request->tid)->first();
        $HomePageContent->title1 = $request->title1;
        $HomePageContent->content = $request->description;
        $HomePageContent->save();
        $request->session()->flash('success', 'content Updated successfully');
        return redirect()->route('superadmin.managehomepagecontent');
    }
}
