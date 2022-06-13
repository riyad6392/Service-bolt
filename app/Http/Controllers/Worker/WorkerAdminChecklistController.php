<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Personnel;
use App\Models\Inventory;
use App\Models\Checklist;
use DB;
use Illuminate\Support\Str;
use Image;

class WorkerAdminChecklistController extends Controller
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
        if(auth()->user()->role == 'worker') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }

        $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

        $serviceData = Service::where('userid',$worker->userid)->where('workerid',$worker->workerid)->get();
        $productData = Inventory::where('user_id',$worker->userid)->where('workerid',$worker->workerid)->get();
        
        $checklistData = DB::table('checklist')->select('checklist.*', 'services.servicename')->join('services', 'services.id', '=', 'checklist.serviceid')->where('checklist.workerid',$worker->workerid)->orderBy('checklist.id','DESC')->groupBy('checklist.serviceid')->get();

        //Checklist::where('userid',$auth_id)->get();
        return view('personnel.checklist.index',compact('auth_id','serviceData','checklistData','productData'));
    }

    public function create(Request $request)
    {
    	$auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
      foreach($request->point as $key => $value) {
        $data['userid'] = $worker->userid;
        $data['workerid'] = $worker->workerid;
        $data['serviceid'] = $request->serviceid;
        $data['checklist'] = $value;

        //image logic start
        $imagefiles = $request->file('checklistimage');
        if($imagefiles!=null) {
          if(isset($imagefiles[$key])) {
           $postImage = date('YmdHis') . "." . $imagefiles[$key]->getClientOriginalExtension();
           $imagefiles[$key]->move(public_path('uploads/checklist/thumbnail/'), $postImage);
         } else {
           $postImage = null;
         }
       } else {
          $postImage = null;
       }
          
          $data['checklistimage'] = $postImage;
        //image logic end
        Checklist::create($data);
      }
      
      $request->session()->flash('success', 'Checklist point added successfully');
            
      return redirect()->route('worker.checklist');
    }

    public function leftbarchecklistdata(Request $request)
    {
      $auth_id = auth()->user()->id;

      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $targetid =  $request->targetid;
      $serviceid = $request->serviceid;
      
      $json = array();
      if($targetid == 0) {
        $checklistdata = DB::table('checklist')->select('checklist.*', 'services.servicename', 'services.image')->join('services', 'services.id', '=', 'checklist.serviceid')->where('checklist.userid',$worker->userid)->where('checklist.serviceid',$serviceid)->get();
       
        if($checklistdata[0]->image!=null) {
            $imagepath = url('/').'/uploads/services/'.$checklistdata[0]->image;
        } else {
          $imagepath = url('/').'/uploads/servicebolt-noimage.png';
        }

         $html = "";
         $html .='<div class="product-card">
                  <img src="'.$imagepath.'" alt="" style="object-fit: cover;width:auto!important;">
                  <h2>'.$checklistdata[0]->servicename.'</h2>';

        $html .='<div class="product-info-list time-sheet">
            <div class="mb-4 points-div-list">
              <ul>';
              foreach($checklistdata as $key => $value) {
                if($value->checklistimage!=null) {
                    $imagepathc = url('/').'/uploads/checklist/thumbnail/'.$value->checklistimage;
                 } else {
                  $imagepathc = url('/').'/uploads/servicebolt-noimage.png';
                }
                  $html .='<li class="list-data"><img src="'.$imagepathc.'" style="width:20px;height:20px;"><span>'.$value->checklist.'</span> <div><a href="javascript:void(0);" class="info_link1" dataval="'.$value->id.'"><i class="fa fa-trash"></i></a>&nbsp;<a class="" data-bs-toggle="modal" data-bs-target="#edit-checklist" id="editchecklist" data-id="'.$value->id.'" data-name="'.$value->checklist.'"><i class="fa fa-edit"></i></a></div></li>';
              }
        $html .='</ul>    
            </div>
            <a class="btn  w-100 p-3  btn-block btn-schedule" data-bs-toggle="modal" data-bs-target="#edit-product " style="display:none;">Add Point</a>
          </div>
        </div>';      
      } else {

        $checklistdata = DB::table('checklist')->select('checklist.*', 'services.servicename', 'services.image')->join('services', 'services.id', '=', 'checklist.serviceid')->where('checklist.userid',$worker->userid)->where('checklist.serviceid',$request->serviceid)->get();

         if($checklistdata[0]->image!=null) {
            $imagepath = url('/').'/uploads/services/'.$checklistdata[0]->image;
         } else {
            $imagepath = url('/').'/uploads/servicebolt-noimage.png';
         }
         $html = "";
         $html .='<div class="product-card">
                  <img src="'.$imagepath.'" alt="" style="object-fit: cover;width:auto!important;">
                  <h2>'.$checklistdata[0]->servicename.'</h2>';

        $html .='<div class="product-info-list time-sheet">
            <div class="mb-4 points-div-list">
              <ul>';
              foreach($checklistdata as $key => $value) {
                if($value->checklistimage!=null) {
                  $imagepathc = url('/').'/uploads/checklist/thumbnail/'.$value->checklistimage;
                } else {
                 $imagepathc = url('/').'/uploads/servicebolt-noimage.png';
                }
                  $html .='<li class="list-data"><img src="'.$imagepathc.'" style="width:20px;height:20px;"><span>'.$value->checklist.' </span><div><a href="javascript:void(0);" class="info_link1" dataval="'.$value->id.'"><i class="fa fa-trash"></i></a>&nbsp;<a class="" data-bs-toggle="modal" data-bs-target="#edit-checklist" id="editchecklist" data-id="'.$value->id.'" data-name="'.$value->checklist.'"><i class="fa fa-edit"></i></a></div></li>';
              }
        $html .='</ul>    
            </div>
            <a class="btn  w-100 p-3  btn-block btn-schedule" data-bs-toggle="modal" data-bs-target="#edit-product " style="display:none;">Add Point</a>
           </div>
        </div>'; 
       }
      
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function deleteChecklist(Request $request)
    {
      $auth_id = auth()->user()->id;
      $id = $request->id;
      DB::table('checklist')->delete($id);
      echo "1";
    }

    public function vieweditchecklistmodal(Request $request)
    {
      $json = array();
      $auth_id = auth()->user()->id;
      $checklist = Checklist::where('id', $request->cid)->get()->first();
      if($checklist->checklistimage != null) {
        $userimage = url('uploads/checklist/thumbnail/'.$checklist->checklistimage);
        } else {
          $userimage = url('/').'/uploads/servicebolt-noimage.png';
        }   
      $html ='<div class="add-customer-modal">
                  <h5>Edit Checklist</h5>
                 </div><input type="hidden" name="checklistid" id="checklistid" value="'.$request->cid.'">
            <div class="col-md-12 mb-2">
             <div class="input_fields_wrap">
                
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="checklistname" name="checklist" id="checklist" value="'.$request->cname.'" required="">
                  </div>
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

    public function updatechecklist(Request $request)
    {
     
      $checklist = Checklist::where('id', $request->checklistid)->get()->first();
      $checklist->checklist = $request->checklist;
      
      

      $logofile = $request->file('image');
      if(isset($logofile)) {
        $datetime = date('YmdHis');
        $image = $request->image->getClientOriginalName();
        $imageName = $datetime . '_' . $image;
        $logofile->move(public_path('uploads/checklist/'), $imageName);
        $checklist->checklistimage = $imageName;

        Image::make(public_path('uploads/checklist/').$imageName)->fit(300,300)->save(public_path('uploads/checklist/thumbnail/').$imageName);
      }
      $checklist->save();
      $request->session()->flash('success', 'Checklist has been updated successfully');
      return redirect()->route('worker.checklist');
    }
}
