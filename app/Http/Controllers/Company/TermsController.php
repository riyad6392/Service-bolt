<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Terms;
use DB;

class TermsController extends Controller
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
        $terms = Terms::where('userid',$auth_id)->get();
        return view('terms.term',compact('auth_id','terms'));
    }

    public function create(Request $request)
    {
      $auth_id = auth()->user()->id;
      $data['userid'] = $auth_id;
      $data['term_name'] = $request->term_name;
      Terms::create($data);
      $request->session()->flash('success', 'Term added successfully');
      return redirect()->route('company.terms');
    }

    public function viewtermmodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       
      $term = Terms::where('id', $request->id)->get()->first();
      
      $html ='<div class="add-customer-modal">
                  <h5>Edit Term</h5>
                </div>';
       $html .='<div class="row customer-form" id="product-box-tabs">
       <input type="hidden" value="'.$request->id.'" name="termid">
          <div class="col-md-12 mb-2">
            <div class="form-group">
            <label>Term Name</label>
            <input type="text" class="form-control" placeholder="Term Name" name="term_name" id="term_name" value="'.$term->term_name.'" required>
          </div>
          </div>';
        $html .= '<div class="row"><div class="col-lg-6 mb-2">
            <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
          </div>
          <div class="col-lg-6">
            <button type="submit" class="btn btn-add btn-block">Update</button>
          </div>
        </div></div>';
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function update(Request $request)
    {
      $term = Terms::where('id', $request->termid)->get()->first();
      $term->term_name = $request->term_name;
      $term->save();
      $request->session()->flash('success', 'Term Updated successfully');
      return redirect()->route('company.terms');
    }

    public function deleteTerm(Request $request)
    {
      $auth_id = auth()->user()->id;
      $id = $request->id;
      DB::table('terms')->delete($id);
      echo "1";
    }

}
