<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Feature;
use DB;

class AdminfeatureController extends Controller
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

        $tentureData = DB::table('feature')->orderBy('id','desc')->get();
        return view('superadmin.feature',compact('tentureData'));
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
}
