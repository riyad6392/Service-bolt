<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Service;
use App\Models\Inventory;
use App\Models\Category;
use App\Models\Managefield;
use DB;
use Image;

class WorkerAdminCategoryController extends Controller
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
      
        $category = Category::where('workerid',$worker->workerid)->get();

        return view('personnel.category.categories',compact('auth_id','category'));
    }

    public function create(Request $request)
    {
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

      $data['userid'] = $worker->userid;
      $data['workerid'] = $worker->workerid;
      $data['category_name'] = $request->category_name;
      Category::create($data);
      $request->session()->flash('success', 'Category added successfully');
      return redirect()->route('worker.categories');
    }

    public function viewcategorymodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;
       
      $category = Category::where('id', $request->id)->get()->first();
      
      $html ='<div class="add-customer-modal">
                  <h5>Edit Category</h5>
                </div>';
       $html .='<div class="row customer-form" id="product-box-tabs">
       <input type="hidden" value="'.$request->id.'" name="categoryid">
          <div class="col-md-12 mb-2">
            <div class="form-group">
            <label>Category Name</label>
            <input type="text" class="form-control" placeholder="Category Name" name="category_name" id="category_name" value="'.$category->category_name.'" required>
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
      $category = Category::where('id', $request->categoryid)->get()->first();
      $category->category_name = $request->category_name;
      $category->save();
      $request->session()->flash('success', 'Category Updated successfully');
      return redirect()->route('company.categories');
    }

    public function deleteCategory(Request $request)
    {
      $auth_id = auth()->user()->id;
      $id = $request->id;
      DB::table('category')->delete($id);
      echo "1";
    }

}