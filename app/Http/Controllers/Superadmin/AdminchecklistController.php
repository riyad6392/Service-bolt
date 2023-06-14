<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\manageChecklist;
use DB;
use Image;

class AdminchecklistController extends Controller
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

        $checklistdata = DB::table('adminchecklist')->where('status','Active')->orderBy('id','desc')->get();
        return view('superadmin.managechecklist',compact('checklistdata'));
    }

    public function create(Request $request)
    {
        $auth_id = auth()->user()->id;
      
      foreach($request->point as $key => $value) {
        
        $data['checklist'] = $value;

        //image logic start
        $imagefiles = $request->file('image');
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
          
          $data['image'] = $postImage;
        //image logic end
        manageChecklist::create($data);
      }
      
      $request->session()->flash('success', 'Checklist point added successfully');
            
      return redirect()->route('superadmin.manageChecklist');
    }

    public function vieweditchecklistmodal(Request $request)
    {
      $json = array();
      $auth_id = auth()->user()->id;
      $checklist = manageChecklist::where('id', $request->cid)->get()->first();
      if($checklist->image != null) {
        $userimage = url('uploads/checklist/thumbnail/'.$checklist->image);
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
     
      $checklist = manageChecklist::where('id', $request->checklistid)->get()->first();
      $checklist->checklist = $request->checklist;
      
      

      $logofile = $request->file('image');
      if(isset($logofile)) {
        $datetime = date('YmdHis');
        $image = $request->image->getClientOriginalName();
        $imageName = $datetime . '_' . $image;
        $logofile->move(public_path('uploads/checklist/'), $imageName);
        $checklist->image = $imageName;

        Image::make(public_path('uploads/checklist/').$imageName)->fit(300,300)->save(public_path('uploads/checklist/thumbnail/').$imageName);
      }
      $checklist->save();
      $request->session()->flash('success', 'Checklist has been updated successfully');
      return redirect()->route('superadmin.manageChecklist');
    }

    public function ckdelete(Request $request)
    {
      //$auth_id = auth()->user()->id;
      $id = $request->id;
      DB::table('adminchecklist')->delete($id);
      echo "1";
    }
}
