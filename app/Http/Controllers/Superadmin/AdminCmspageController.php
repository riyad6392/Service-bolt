<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\CMSpage;
use DB;

class AdminCmspageController extends Controller
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

        $cmspage = CMSpage::get();

          
          return view('superadmin.cmspage',compact('auth_id','cmspage'));
    }

    public function cmspagestatus(Request $request) {
        $cmspage = CMSpage::where('id',$request->userid)->first();
        $cmspage->status = $request->status;
        $cmspage->save();
        echo "1"; 
    }

    public function viewcmspagemodal(Request $request) {
        $json = array();
        $cpage = CMSpage::where('id', $request->id)->first();
        $html ='<div class="modal-header">
                    <h5 id="offcanvasRightLabel" class="">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <input type="hidden" name="cid" id="cid" value="'.$request->id.'">
                  </div> 
            <div class="modal-body">
                <div class="filter">
                 <div class="row">
                    <div class="col-md-12 mb-3">
                    Page Name
                    <input type="text" class="form-control" placeholder="Page name" value="'.$cpage->pagename.'" id="pagename" name="pagename" required="" readonly>
                    </div>

                    <div class="col-md-12 mb-3">Description
                        <textarea class="form-control" name="description" id="description" required cols="10" rows="5">'.$cpage->description.'</textarea>
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

    public function cmspageupdate(Request $request) {
        $cpage = CMSpage::where('id',$request->cid)->first();
        $cpage->pagename = $request->pagename;
        $cpage->description = $request->description;
        $cpage->save();
        $request->session()->flash('success', 'Page Updated successfully');
        return redirect()->route('superadmin.manageCmspages');
    }
}
