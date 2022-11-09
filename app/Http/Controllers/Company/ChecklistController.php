<?php

namespace App\Http\Controllers\Company;

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

class ChecklistController extends Controller
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
        if(auth()->user()->role == 'company') {
            $auth_id = auth()->user()->id;
        } else {
           return redirect()->back();
        }
        
        $days = $request->get('f');
        $dateS = Carbon::now()->subDay($days);
        $dateE = Carbon::now();
        $serviceData = Service::where('userid',$auth_id)->get();
        $productData = Inventory::where('user_id',$auth_id)->get();
        
        // $PersonnelData = Personnel::where('userid',$auth_id)->get();

        // $todaydate = date('l - F d, Y');
        // $scheduleData = DB::table('quote')->select('quote.*', 'customer.image')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.userid',$auth_id)->where('quote.ticket_status',"2")->where('quote.givendate',$todaydate)->orderBy('quote.id','ASC')->get();
        $checklistData = DB::table('checklist')->select('checklist.*', 'services.servicename')->join('services', 'services.id', '=', 'checklist.serviceid')->where('checklist.userid',$auth_id)->orderBy('checklist.id','DESC')->groupBy('checklist.serviceid')->get();

         $adminchecklist = DB::table('adminchecklist')->get();
        //Checklist::where('userid',$auth_id)->get();
        return view('checklist.index',compact('auth_id','serviceData','checklistData','productData','adminchecklist'));
    }

    public function create(Request $request)
    {
    	$auth_id = auth()->user()->id;
      
      foreach($request->point as $key => $value) {
        $data['userid'] = $auth_id;
        $data['serviceid'] = $request->serviceid;
        $data['checklist'] = $value;
        $data['checklistname'] = $request->checklistname;


        //image logic start
        $imagefiles = $request->file('checklistimage');
        if($imagefiles!=null) {
          if(isset($imagefiles[$key])) {
           
           $path = 'uploads/checklist/thumbnail/';
           $postImage = custom_fileupload($imagefiles[$key],$path);
         } else {
           $postImage = null;
         }
       } else {
          $postImage = null;
       }
        $data['checklistimage'] = $postImage;
        Checklist::create($data);
      }
      if($request->adminck) {
        foreach($request->adminck as $key => $value) {
          $admind = DB::table('adminchecklist')->where('id',$value)->first();
          $data['userid'] = $auth_id;
          $data['serviceid'] = $request->serviceid;
          $data['checklist'] = $admind->checklist;
          $data['checklistimage'] = $admind->image;
          Checklist::create($data);
        }
      }
      
      $request->session()->flash('success', 'Checklist point added successfully');
            
      return redirect()->route('company.checklist');
    }

    public function leftbarchecklistdata(Request $request)
    {
      $auth_id = auth()->user()->id;
      $targetid =  $request->targetid;
      $serviceid = $request->serviceid;
      
      $json = array();
      if($targetid == 0) {
        $checklistdata = DB::table('checklist')->select('checklist.*', 'services.servicename','services.id as serviceid', 'services.image')->join('services', 'services.id', '=', 'checklist.serviceid')->where('checklist.userid',$auth_id)->where('checklist.serviceid',$serviceid)->get();
       
         if($checklistdata[0]->image!=null) {
            $imagepath = url('/').'/uploads/services/'.$checklistdata[0]->image;
         } else {
          $imagepath = url('/').'/uploads/servicebolt-noimage.png';
        }

         $html = "";
         $html .='<div class="product-card">
                  <img src="'.$imagepath.'" alt="" style="object-fit: cover;width:auto!important;">
                  <a href="javascript:void(0);" class="" id="updatednew" data-sid="'.$checklistdata[0]->serviceid.'" data-sname="'.$checklistdata[0]->servicename.'" style="color:#000;">Edit Checklist Item</a>
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

        $checklistdata = DB::table('checklist')->select('checklist.*', 'services.servicename','services.id as serviceid', 'services.image')->join('services', 'services.id', '=', 'checklist.serviceid')->where('checklist.userid',$auth_id)->where('checklist.serviceid',$request->serviceid)->get();

         if($checklistdata[0]->image!=null) {
            $imagepath = url('/').'/uploads/services/'.$checklistdata[0]->image;
         } else {
            $imagepath = url('/').'/uploads/servicebolt-noimage.png';
         }
         $html = "";
         $html .='<div class="product-card">
                  <img src="'.$imagepath.'" alt="" style="object-fit: cover;width:auto!important;">
                  <a class="" id="updatednew" data-sid="'.$checklistdata[0]->serviceid.'" data-sname="'.$checklistdata[0]->servicename.'" style="color:#000;disply">Edit Checklist Item</a>
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
                  <h5>Edit Checklist Item</h5>
                 </div><input type="hidden" name="checklistid" id="checklistid" value="'.$request->cid.'">
            <div class="col-md-12 mb-2">
             <div class="input_fields_wrap">
                
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="Checklist Item" name="checklist" id="checklist" value="'.$request->cname.'" required="">
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
      return redirect()->route('company.checklist');
    }

    public function vieweditallchecklistmodal(Request $request)
    {
      $json = array();
      $auth_id = auth()->user()->id;
      $html='';
      $checklist = Checklist::where('serviceid', $request->sid)->get();
          $html .='<p>Service Name: '.$request->sname.'</p><div class="row"><div class="col-md-12">
                <label>Checklist Name</label>
                <input type="text" class="form-control" name="checklistname" id="checklistname" value="'.$checklist[0]->checklistname.'" style="box-shadow: 0 0 0 0.25rem rgb(13 110 253 / 25%);" required>
              </div></div>';
          foreach($checklist as $key => $value) {
            if($value->checklistimage != null) {
              $userimage = url('uploads/checklist/thumbnail/'.$value->checklistimage);
             } else {
              $userimage = url('/').'/uploads/servicebolt-noimage.png';
            }
             $html .='<div class="col-md-12 dynamic-fieldnew" style="
    border-top: 1px solid #ccc;
    padding-top: 10px;" id="dynamic-fieldnew-1">
              <div class="row">
                <div class="col-md-12">
                <div class="staresd">
                 <div class="imgup d-flex w-100 justify-content-between align-items-start">
                 <div>
                  <input type="text" class="form-control" placeholder="Checklist Item" name="pointed[]" id="point" value="'.$value->checklist.'" required="">
                  <input type="hidden" name="pointids[]" id="pointids" value="'.$value->id.'">

                  <input type="file" class="form-control" style="margin-bottom: 5px;margin-top: 10px;" name="checklistimageed[]" accept="image/png, image/gif, image/jpeg, image/bmp, image/jpg, image/svg"></div>

                  <img src="'.$userimage.'" class="defaultimage" style="width:30px;height:30px;border-radius:100%">

                 </div>
                
                </div>
               </div>
              </div>
             </div><input type="hidden" name="serviceid" value="'.$request->sid.'">';
             }

            
      return json_encode(['html' =>$html]);
          die;
    }

    public function updateallchecklist(Request $request)
    {
      $auth_id = auth()->user()->id;
    
      foreach($request->pointids as $idkey => $idvalue) {
        $pointids = isset($request->pointids[$idkey]);
        $checklist=Checklist::find($idvalue);
        if(empty($checklist)) {
          
          $checklist= new Checklist;
          $checklist->userid = $auth_id;
          $checklist->serviceid = $request->serviceid;
          
        }
        $imagefiles = $request->file('checklistimageed');
        if($imagefiles!=null) {
            if(isset($imagefiles[$idkey])) {
              $path = 'uploads/checklist/thumbnail/';
              $checklist->checklistimage = custom_fileupload($imagefiles[$idkey],$path);
           }
        }
        $checklist->checklist=$request->pointed[$idkey];
        $checklist->checklistname = $request->checklistname;
        $checklist->save();  
      } 
      $request->session()->flash('success', 'Checklist point updated successfully');
      return redirect()->route('company.checklist');
    }
}
