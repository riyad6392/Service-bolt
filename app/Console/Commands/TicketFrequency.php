<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\Quote;
use Carbon\Carbon;

class TicketFrequency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ticketfrequency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
         
       $dateE = Carbon::now();
        $currentdate = date('Y-m-d', strtotime($dateE));
        //start logic for cron jobs
          $ticketData = Quote::select('quote.*','customer.email')->join('customer', 'customer.id', '=', 'quote.customerid')->where('quote.parentid',0)->whereIn('quote.ticket_status',['2','3','4'])->where('quote.frequency', '!=',"One Time")->where('quote.flag',1)->orderBy('quote.id','DESC')->get();
         
        foreach ($ticketData as $key => $value) {
            if($value['frequency'] == "Monthly") {
                if($value['count']==12){

                } else {
                    $newTime = strtotime('30 days'. $value['givenstartdate']);
                    $newDate = date('Y-m-d', $newTime);
                    if($value['givenstartdate'] == $currentdate) {
                       $data['userid'] = $value['userid'];
                       $data['workerid'] = $value['workerid'];
                       $data['customerid'] = $value['customerid'];
                       $data['customername'] = $value['customername'];
                       $data['address'] = $value['address'];
                       $data['latitude'] = $value['latitude'];
                       $data['longitude'] = $value['longitude'];
                       $data['serviceid'] = $value['serviceid'];
                       $data['pricetype'] = $value['pricetype'];
                       $data['servicename'] = $value['servicename'];
                       $data['product_id'] = $value['product_id'];
                       $data['product_name'] = $value['product_name'];
                       $data['personnelid'] = $value['personnelid'];
                       $data['radiogroup'] = $value['radiogroup'];
                       $data['frequency'] = $value['frequency'];
                       $data['time'] = $value['time'];
                       $data['minute'] = $value['minute'];

                       $data['giventime'] = $value['giventime'];
                       $data['givenendtime'] = $value['givenendtime'];
                       $data['givendate'] =  Carbon::createFromFormat('Y-m-d', $newDate)->format('l - F d, Y');
                       $data['givenenddate'] = $newDate;
                       $data['givenstartdate'] = $newDate;
                       $data['tickettotal'] = $value['tickettotal'];
                       $data['etc'] = $newDate;

                       $data['price'] = $value['price'];
                       $data['tax'] = "0.00";
                       $data['description'] = $value['description'];
                       $data['ticket_status'] = 1;
                       $data['checklist'] = $value['checklist'];
                       $data['customernotes'] = $value['customernotes'];
                       $data['count'] = $value['count']+1;
                       $quotedata = Quote::create($data);
                        DB::table('quote')->where('id','=',$value['id'])
                          ->update([ 
                              "flag"=>"0"
                        ]);
                    }  
                }
            }
            if($value['frequency'] == "Bi-Monthly") {
                $newTime = strtotime('60 days'. $value['givenstartdate']);
                    $newDate = date('Y-m-d', $newTime);
                    if($value['givenstartdate'] == $currentdate) {
                        if($value['count']==6) {
                        } else {
                           $data['userid'] = $value['userid'];
                           $data['workerid'] = $value['workerid'];
                           $data['customerid'] = $value['customerid'];
                           $data['customername'] = $value['customername'];
                           $data['address'] = $value['address'];
                           $data['latitude'] = $value['latitude'];
                           $data['longitude'] = $value['longitude'];
                           $data['serviceid'] = $value['serviceid'];
                           $data['pricetype'] = $value['pricetype'];
                           $data['servicename'] = $value['servicename'];
                           $data['product_id'] = $value['product_id'];
                           $data['product_name'] = $value['product_name'];
                           $data['personnelid'] = $value['personnelid'];
                           $data['radiogroup'] = $value['radiogroup'];
                           $data['frequency'] = $value['frequency'];
                           $data['time'] = $value['time'];
                           $data['minute'] = $value['minute'];

                           $data['giventime'] = $value['giventime'];
                           $data['givenendtime'] = $value['givenendtime'];
                           $data['givendate'] =  Carbon::createFromFormat('Y-m-d', $newDate)->format('l - F d, Y');
                           $data['givenenddate'] = $newDate;
                           $data['givenstartdate'] = $newDate;
                           $data['tickettotal'] = $value['tickettotal'];
                           $data['etc'] = $newDate;

                           $data['price'] = $value['price'];
                           $data['tax'] = "0.00";
                           $data['description'] = $value['description'];
                           $data['ticket_status'] = 1;
                           $data['checklist'] = $value['checklist'];
                           $data['customernotes'] = $value['customernotes'];
                           $data['count'] = $value['count']+1;
                           $quotedata = Quote::create($data);
                            DB::table('quote')->where('id','=',$value['id'])
                              ->update([ 
                                  "flag"=>"0"
                            ]);
                        }
                    }
            }
            if($value['frequency'] == "Weekly") {
                $newTime = strtotime('7 days'. $value['givenstartdate']);
                $newDate = date('Y-m-d', $newTime);
                if($value['givenstartdate'] == $currentdate) {
                    if($value['count']==52) {

                    } else {
                       $data['userid'] = $value['userid'];
                       $data['workerid'] = $value['workerid'];
                       $data['customerid'] = $value['customerid'];
                       $data['customername'] = $value['customername'];
                       $data['address'] = $value['address'];
                       $data['latitude'] = $value['latitude'];
                       $data['longitude'] = $value['longitude'];
                       $data['serviceid'] = $value['serviceid'];
                       $data['pricetype'] = $value['pricetype'];
                       $data['servicename'] = $value['servicename'];
                       $data['product_id'] = $value['product_id'];
                       $data['product_name'] = $value['product_name'];
                       $data['personnelid'] = $value['personnelid'];
                       $data['radiogroup'] = $value['radiogroup'];
                       $data['frequency'] = $value['frequency'];
                       $data['time'] = $value['time'];
                       $data['minute'] = $value['minute'];

                       $data['giventime'] = $value['giventime'];
                       $data['givenendtime'] = $value['givenendtime'];
                       $data['givendate'] =  Carbon::createFromFormat('Y-m-d', $newDate)->format('l - F d, Y');
                       $data['givenenddate'] = $newDate;
                       $data['givenstartdate'] = $newDate;
                       $data['tickettotal'] = $value['tickettotal'];
                       $data['etc'] = $newDate;

                       $data['giventime'] = $value['giventime'];
                       $data['givenendtime'] = $value['givenendtime'];
                       $data['givendate'] =  Carbon::createFromFormat('Y-m-d', $newDate)->format('l - F d, Y');
                       $data['givenenddate'] = $newDate;
                       $data['givenstartdate'] = $newDate;
                       $data['tickettotal'] = $value['tickettotal'];
                       $data['etc'] = $newDate;

                       $data['price'] = $value['price'];
                       $data['tax'] = "0.00";
                       $data['description'] = $value['description'];
                       $data['ticket_status'] = 1;
                       $data['checklist'] = $value['checklist'];
                       $data['customernotes'] = $value['customernotes'];
                       $data['count'] = $value['count']+1;
                       $quotedata = Quote::create($data);
                        DB::table('quote')->where('id','=',$value['id'])
                          ->update([ 
                              "flag"=>"0"
                        ]); 
                    }
                }
            }
            if($value['frequency'] == "Bi-Weekly") {
                $newTime = strtotime('15 days'. $value['givenstartdate']);
                    $newDate = date('Y-m-d', $newTime);
                    if($value['givenstartdate'] == $currentdate) {
                        if($value['count']==24) {

                        } else {
                       $data['userid'] = $value['userid'];
                       $data['workerid'] = $value['workerid'];
                       $data['customerid'] = $value['customerid'];
                       $data['customername'] = $value['customername'];
                       $data['address'] = $value['address'];
                       $data['latitude'] = $value['latitude'];
                       $data['longitude'] = $value['longitude'];
                       $data['serviceid'] = $value['serviceid'];
                       $data['pricetype'] = $value['pricetype'];
                       $data['servicename'] = $value['servicename'];
                       $data['product_id'] = $value['product_id'];
                       $data['product_name'] = $value['product_name'];
                       $data['personnelid'] = $value['personnelid'];
                       $data['radiogroup'] = $value['radiogroup'];
                       $data['frequency'] = $value['frequency'];
                       $data['time'] = $value['time'];
                       $data['minute'] = $value['minute'];

                       $data['giventime'] = $value['giventime'];
                       $data['givenendtime'] = $value['givenendtime'];
                       $data['givendate'] =  Carbon::createFromFormat('Y-m-d', $newDate)->format('l - F d, Y');
                       $data['givenenddate'] = $newDate;
                       $data['givenstartdate'] = $newDate;
                       $data['tickettotal'] = $value['tickettotal'];
                       $data['etc'] = $newDate;

                       $data['price'] = $value['price'];
                       $data['tax'] = "0.00";
                       $data['description'] = $value['description'];
                       $data['ticket_status'] = 1;
                       $data['checklist'] = $value['checklist'];
                       $data['customernotes'] = $value['customernotes'];
                       $data['count'] = $value['count']+1;
                       $quotedata = Quote::create($data);
                        DB::table('quote')->where('id','=',$value['id'])
                          ->update([ 
                              "flag"=>"0"
                        ]);
                    }
                }
            }
            if($value['frequency'] == "Quarterly") {
                $newTime = strtotime('90 days'. $value['givenstartdate']);
                $newDate = date('Y-m-d', $newTime);
                if($value['givenstartdate'] == $currentdate) {
                    if($value['count']==4) {

                    } else {
                       $data['userid'] = $value['userid'];
                       $data['workerid'] = $value['workerid'];
                       $data['customerid'] = $value['customerid'];
                       $data['customername'] = $value['customername'];
                       $data['address'] = $value['address'];
                       $data['latitude'] = $value['latitude'];
                       $data['longitude'] = $value['longitude'];
                       $data['serviceid'] = $value['serviceid'];
                       $data['pricetype'] = $value['pricetype'];
                       $data['servicename'] = $value['servicename'];
                       $data['product_id'] = $value['product_id'];
                       $data['product_name'] = $value['product_name'];
                       $data['personnelid'] = $value['personnelid'];
                       $data['radiogroup'] = $value['radiogroup'];
                       $data['frequency'] = $value['frequency'];
                       $data['time'] = $value['time'];
                       $data['minute'] = $value['minute'];

                       $data['giventime'] = $value['giventime'];
                       $data['givenendtime'] = $value['givenendtime'];
                       $data['givendate'] =  Carbon::createFromFormat('Y-m-d', $newDate)->format('l - F d, Y');
                       $data['givenenddate'] = $newDate;
                       $data['givenstartdate'] = $newDate;
                       $data['tickettotal'] = $value['tickettotal'];
                       $data['etc'] = $newDate;
                       
                       $data['price'] = $value['price'];
                       $data['tax'] = "0.00";
                       $data['description'] = $value['description'];
                       $data['ticket_status'] = 1;
                       $data['checklist'] = $value['checklist'];
                       $data['customernotes'] = $value['customernotes'];
                       $data['count'] = $value['count']+1;
                       $quotedata = Quote::create($data);
                        DB::table('quote')->where('id','=',$value['id'])
                          ->update([ 
                              "flag"=>"0"
                        ]);
                    }
                }
           }
        }
        //end here 
    }
}
