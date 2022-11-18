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

class WorkerAdminProductController extends Controller
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
        $inventoryData = Inventory::where('user_id',$worker->userid)->where('workerid',$worker->workerid)->get();
        $countdata = count($inventoryData);
        $datacount = $countdata-1;
        $categoryData = Category::where('userid',$worker->userid)->where('workerid',$worker->workerid)->get();
        $table="products";
        $fields = DB::getSchemaBuilder()->getColumnListing($table);

        return view('personnel.inventory.index',compact('auth_id','serviceData','inventoryData','datacount','categoryData','fields'));
    }

    public function create(Request $request)
    {
          $auth_id = auth()->user()->id;

          $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

          $validate = Validator($request->all(), [
                'productname' => 'required',
            ]);
            if ($validate->fails()) {
                return redirect()->route('worker.inventorycreate')->withInput($request->all())->withErrors($validate);
            }
            $logofile = $request->file('image');
            if (isset($logofile)) {
                $new_file = $logofile;
              $path = 'uploads/inventory/';
              $imageName = custom_fileupload($new_file,$path);

              $data['image'] = $imageName;
            }
                $data['user_id'] = $worker->userid;
                $data['workerid'] = $worker->workerid;
                $data['productname'] = $request->productname;
                if(isset($request->serviceid)) {
                  $data['serviceid'] = implode(',', $request->serviceid);
                } else {
                   $data['serviceid'] = null;
                }

                $data['quantity'] = $request->quantity;
                $data['pquantity'] = $request->pquantity;
                $data['sku'] = $request->sku;
                $data['price'] = $request->price;
                $data['category'] = $request->category;
                $data['description'] = $request->description;
           
            Inventory::create($data);
            $request->session()->flash('success', 'Product added successfully');
            if(isset($request->cid)) {
            
            return redirect()->back();
           }
            return redirect()->route('worker.manageproducts');
    }

    public function editviewinventorymodal(Request $request)
    {
       $json = array();
       $auth_id = auth()->user()->id;

       $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();

       $inventory = Inventory::where('id', $request->id)->get();
       $services = Service::where('userid',$worker->userid)->where('workerid',$worker->workerid)->get();
       if($inventory[0]->image != null) {
          $userimage = url('uploads/inventory/'.$inventory[0]->image);
        } else {
          $userimage = url('uploads/servicebolt-noimage.png'); 
        }
        if($inventory[0]->category =="Dynamic Category Content 1") {
            $selected = "selected";
        } else {
            $selected = "";
        }
       if($inventory[0]->category =="Dynamic Category Content 2") {
            $selected1 = "selected";
        } else {
            $selected1 = "";
        }


       $html ='<div class="add-customer-modal">
               <h5>Edit Product/Part</h5>
               </div><div class="tabs-product row mb-4">
               <div class="col-lg-5">
               <span class="btn btn-product information-tabs-1" id="info1">Information</span>
               </div>
               <div class="col-lg-7">
               <span class="btn btn-desc description-product-1" id="descrip1">Description and Images</span>
               </div>
               </div>';
       $html .='<div class="row customer-form" id="product-box-tabs-1">
     <div class="col-md-12 mb-3">
     <label>Product/Name</label>
     <input type="hidden" value="'.$request->id.'" name="productid">
        <input type="text" class="form-control" placeholder="Product Name" name="productname" id="productname" value="'.$inventory[0]->productname.'" required></div>
        <div class="col-md-12 mb-2">
            <label>Select Service</label>
            <select class="form-control selectpicker" multiple aria-label="Default select example" data-live-search="true" name="serviceid[]" id="serviceid" style="height:auto;">';

              foreach($services as $key => $value) {
                $serviceids =explode(",", $inventory[0]->serviceid);
                 if(in_array($value->id, $serviceids)){
                  $selectedp = "selected";
                 } else {
                  $selectedp = "";
                 }
                $html .='<option value="'.$value->id.'" '.@$selectedp.'>'.$value->servicename.'</option>';
              }
        $html .='</select>
          </div>
       <div class="col-md-6 mb-3">
        <label>Quantity</label>
        <input type="text" class="form-control" placeholder="Quantity" value="'.$inventory[0]->quantity.'" name="quantity" id="quantity" required>
       </div>
       <div class="col-md-6 mb-3">
      <label>Preferred Quantity</label>
      <input type="text" class="form-control" placeholder="Preferred Quantity" value="'.$inventory[0]->pquantity.'" name="pquantity" id="pquantity" required>
     </div>
     <div class="col-md-12 mb-3">
      <label>SKU</label>
     <input type="text" class="form-control" placeholder="SKU #" value="'.$inventory[0]->sku.'" name="sku" id="sku" required>
     </div>
     <div class="col-md-12 mb-3">
      <label>Price</label>
     <input type="text" class="form-control" placeholder="Price" class="form-control" placeholder="Price" value="'.$inventory[0]->price.'" name="price" id="price" required>
     </div>
      <div class="col-md-12 mb-3">
    <label>Category</label>
            <select class="form-select" name="category" id="category"><option value="">Select a Category</option>';
            $categorydata = Category::where('userid',$worker->userid)->where('workerid',$worker->workerid)->get();

              foreach($categorydata as $key => $value) {
                  if($value->category_name == $inventory[0]->category) {
                    $selectecp = "selected";
                  } else {
                    $selectecp = "";
                }
                $html .='<option value="'.$value->category_name.'" '.@$selectecp.'>'.$value->category_name.'</option>';
              }
        $html .='</select>
          </div>
          <div class="col-lg-6 ">
     <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
     </div>
     <div class="col-lg-6">
     <button class="btn btn-add btn-block description-product-1" id="next1" name="next1" value="next1">Next</button>
     </div>
     </div>
     
     <div class="row customer-form" id="product-desc-tabs-1" style="display:none;">
      <div class="col-lg-12 mb-3">
    <textarea class="form-control height-180" name="description" id="description" required>'.$inventory[0]->description.'</textarea>
    </div>
    <div style="color: #999999;margin-bottom: 6px;position: relative;">Approximate Image Size : 285 * 195</div>
      <div class="col-lg-12 mb-2 relative">
        <input type="file" class="dropify" name="image" id="image" data-max-file-size="2M" data-allowed-file-extensions="jpg jpeg png gif svg bmp" accept="image/png, image/gif, image/jpeg, image/bmp, image/jpg, image/svg" data-default-file="'.$userimage.'" data-show-remove="false">
      </div>
     
     <div class="col-lg-6 mb-3">
     <span class="btn btn-cancel btn-block" data-bs-dismiss="modal">Cancel</span>
     </div>
     <div class="col-lg-6 mb-3">
     <button class="btn btn-add btn-block" type="submit">Complete</button>
     </div>
     
      </div>';
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function update(Request $request)
    {
          $inventory = Inventory::where('id', $request->productid)->get()->first();
          $inventory->productname = $request->productname;
          $inventory->quantity = $request->quantity;
          $inventory->pquantity = $request->pquantity;
          $inventory->sku = $request->sku;
          $inventory->price = $request->price;
          $inventory->category = $request->category;
          $inventory->description = $request->description;
          if(isset($request->serviceid)) {
           $inventory->serviceid = implode(',', $request->serviceid);
         } else {
            $inventory->serviceid = null;
         }
          //$inventory->serviceid = $request->serviceid;
          $logofile = $request->file('image');
            if (isset($logofile)) {
              $new_file = $logofile;
             $path = 'uploads/inventory/';
             $old_file_name = $inventory->image;
           $imageName = custom_fileupload($new_file,$path,$old_file_name);
           $inventory->image = $imageName;
            }
          $inventory->save();
          $request->session()->flash('success', 'Product Updated successfully');
          return redirect()->route('worker.manageproducts');
    }

    public function leftbarinventorydata(Request $request)
    {
      $targetid =  $request->targetid;
      $serviceid = $request->serviceid;
      $auth_id = auth()->user()->id;
      $worker = DB::table('users')->select('userid','workerid')->where('id',$auth_id)->first();
      $json = array();
      if($targetid == 0) {
         $inventory = Inventory::where('user_id',$worker->userid)->where('workerid',$worker->workerid)->get();
         $countdata = count($inventory);
         $datacount = $countdata-1;
         if($inventory[$datacount]->image!=null) {
         $imagepath = url('/').'/uploads/inventory/'.$inventory[$datacount]->image;
       } else {
          $imagepath = url('/').'/uploads/servicebolt-noimage.png';
       }
      $html ='<div class="product-card targetDiv" id="div1">
              <img src="'.$imagepath.'" alt="">
              <h2>'.$inventory[$datacount]->productname.'</h2>
              <div class="product-info-list">
                <div class="mb-4">
                  <p class="number-1">Price</p>
                  <h6 class="heading-h6">'.$inventory[$datacount]->price.'</h6>
                </div>
                <div class="mb-4">
                 <p class="number-1">Category</p>
                 <h6 class="heading-h6">'.$inventory[$datacount]->category.'</h6>
                </div>
                <a class="btn btn-edit w-100 p-3" data-bs-toggle="modal" data-bs-target="#edit-product" id="editProduct" data-id="'.$inventory[$datacount]->id.'">Edit</a>
                <a href="javascript:void(0);" class="info_link1 btn btn-edit w-100 p-3" dataval="'.$inventory[$datacount]->id.'">Delete</a>
                </div>
              </div>';
      } else {
        
        $inventory = Inventory::where('id',$request->serviceid)->get();
        if($inventory[0]->image!=null) {
          $imagepath = url('/').'/uploads/inventory/'.$inventory[0]->image;
        } else {
          $imagepath = url('/').'/uploads/servicebolt-noimage.png';
        }
      $html ='<div class="product-card targetDiv" id="div1">
              <img src="'.$imagepath.'" alt="">
              <h2>'.$inventory[0]->productname.'</h2>
              <div class="product-info-list">
                <div class="mb-4">
                  <p class="number-1">Price</p>
                  <h6 class="heading-h6">'.$inventory[0]->price.'</h6>
                </div>
                <div class="mb-4">
                 <p class="number-1">Category</p>
                 <h6 class="heading-h6">'.$inventory[0]->category.'</h6>
                </div>
                <a class="btn btn-edit w-100 p-3" data-bs-toggle="modal" data-bs-target="#edit-product" id="editProduct" data-id="'.$inventory[0]->id.'">Edit</a>
                <a href="javascript:void(0);" class="info_link1 btn btn-edit w-100 p-3" dataval="'.$inventory[0]->id.'">Delete</a>
                </div>
              </div>';
      }
      
        return json_encode(['html' =>$html]);
        die;
       
    }

    public function deleteProduct( Request $request)
    {      
      $id = $request->id;
      DB::table('products')->delete($id);
      echo "1";
    }

    public function savefieldproduct(Request $request)
    {
      $auth_id = auth()->user()->id;
      $id = $request->id;

      if($request->checkcolumn==null) {
        DB::table('tablecolumnlist')->where('page',"companyproduct")->where('userid',$auth_id)->delete();
      } else {
        DB::table('tablecolumnlist')->where('page',"companyproduct")->where('userid',$auth_id)->delete();
        $fieldlist = $request->checkcolumn;
        foreach($fieldlist as $key => $value) {
          $data['userid'] = $auth_id;
          $data['page'] = $request->page;
          $data['columname'] = $value;
          Managefield::create($data);  
        }
      }
      echo "1";
    }  
}
