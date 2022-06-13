<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Models\Workerhour;
use App\Models\User;
use DateTime;

class SuperadminUserController extends Controller
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

        $date = Carbon::now()->subDays(7);
  
        $users = User::select('*')->where('role','company')->orderBy('id','DESC')->get();

        return view('superadmin.user',compact('auth_id','users'));
    }

    public function viewusermodal(Request $request) {
        $json = array();
        
        $users = User::where('id', $request->userid)->first();
        $totalcustomer = DB::table('customer')->where('userid',$request->userid)->count();
        $totalworker = DB::table('personnel')->where('userid',$request->userid)->count();
        $time = strtotime($users->created_at);
        $html ='<div class="modal-header">
                    <h5 id="offcanvasRightLabel" class="mb-0 text-primary">'.$users->firstname.' '.$users->lastname.'</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                <div class="offcanvas-body p-0">
                  <div class="table-responsive">
                  <table class="table table-centered table-nowrap table-hover mb-0 table-package">
                  <tbody>
                    <tr>
                        <td>Email</td>
                        <td>'.$users->email.'</td>
                    </tr>
                    <tr>
                      <td>Phone Number</td>
                      <td>'.$users->phone.'</td>
                    </tr>
                    <tr>
                      <td>Status</td>
                      <td>
                        <div class="form-switch"style="pointer-events:none;">';
                    if($users->status == "Active") {
                        $html .='<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>';
                    } else {
                        $html .='<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">';   
                    }
                        $html .='</div>
                      </td>
                      <tr>
                        <td>Total customers</td>
                        <td>'.$totalcustomer.'</td>
                      </tr>
                      <tr>
                          <td>Total Personnal</td>
                          <td>'.$totalworker.'</td>
                      </tr>
                    </tr>
                    <tr>
                      <td colspan="2" class="bg-light">
                        <h5 class="mb-0">Subscription (Active/Expired)</h5>
                      </td>
                      </tr>
                      <tr>
                        <td>Paid on</td>
                        <td>'.date('j M Y', strtotime($users->created_at)).'</td>
                      </tr>
                      
                      <tr>
                        <td>Expiring on</td>
                        <td>'.date("j M Y", strtotime("+1 year", $time)).'
</td>
                      </tr>
                      <tr>
                        <td colspan="2">
                          <button class="btn btn-danger w-100" id="deleteuser" data-id="'.$request->userid.'">Delete Customer</button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>';
        return json_encode(['html' =>$html]);
        die;
    }

    public function userstatus(Request $request) {
      $users = User::where('id', $request->userid)->first();
      $users->status = $request->status;
      $users->save();
      echo "1";
    }

    public function userdelete(Request $request) {
      $id = $request->userid;
      DB::table('users')->delete($id);
      echo "1";
    }
}
