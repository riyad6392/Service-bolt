<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Tenture;
use DB;

class AdmintentureController extends Controller
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

        $tentureData = DB::table('tenture')->orderBy('id','desc')->get();
        return view('superadmin.tenture',compact('tentureData'));
    }

    public function create(Request $request)
    {
        $data['tenturename'] = $request->tenturename;
        $data['day'] = $request->day;
        Tenture::create($data);
        $request->session()->flash('success', 'Tenture added successfully');
        return redirect()->route('superadmin.manageTenture');
    }

    public function viewtenturemodal(Request $request) {
      $json = array();
        
        $tenture = DB::table('tenture')->where('id', $request->id)->first();
        $html ='<div class="modal-header">
                    <h5 id="offcanvasRightLabel" class="">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <input type="hidden" name="tid" id="tid" value="'.$request->id.'">
                  </div> 
            <div class="modal-body">
                <div class="filter">
                    <div class="row">
                    <div class="col-md-12 mb-3">
                    Tenure name
                    <input type="text" class="form-control" placeholder="Tenure name" value="'.$tenture->tenturename.'" id="tenturename" name="tenturename" required="">
                    </div>

                    <div class="col-md-12 mb-3">Days
                        <input type="text" class="form-control" placeholder="Days" id="day" name="day" value="'.$tenture->day.'" required="" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" onpaste="return false">
                    </div>
                    <div style="text-align: -webkit-center;">
                    <div class="col-lg-6">
                    <button type="submit" class="btn btn-add btn-block">Save</button>
                    </div>
                    </div>
                </div>
                </div>
            </div>';
        return json_encode(['html' =>$html]);
        die;  
    }

    public function tentureupdate(Request $request) {
        $tenture = Tenture::where('id',$request->tid)->first();
        $tenture->tenturename = $request->tenturename;
        $tenture->day = $request->day;
        $tenture->save();
        $request->session()->flash('success', 'Tenture Updated successfully');
        return redirect()->route('superadmin.manageTenture');
    }

    public function tenturestatus(Request $request) {
        $tenture = Tenture::where('id',$request->userid)->first();
        $tenture->status = $request->status;
        $tenture->save();
        echo "1";
    }

    public function tenturedelete(Request $request) {
      $id = $request->id;
      DB::table('tenture')->delete($id);
      echo "1";
    }
}
