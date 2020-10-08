<?php

namespace Modules\Collection\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Modules\Collection\Imports\RPTImport;

use File;

use Maatwebsite\Excel\Facades\Excel;

class RPTController extends Controller
{


    public function import(Request $request) 
    {
        config(['excel.import.startRow' => 7 ]);
        config(['excel.import.heading' => 'false' ]);
       
        if($request->hasFile('file')){
            $extension = File::extension($request->file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
 
                $path = $request->file->getRealPath();
                $data = Excel::load($path, function($reader) {
                    $array = $reader->toArray();
                    dd($array);
                    if(!empty($array) && count($array)){
                       
                        foreach ($array as $value) {
                            
                            foreach($value as $row){

                            if( empty($row[0]) && empty($row[1]) && empty($row[2]) && empty($row[3]) && empty($row[4]) && empty($row[5])){
                                break;
                            }

                            $insert[] = [
                            'date' => $row[0]    ,
                            'payor' => $row[1]   ,
                            'period_covered' => $row[2]  ,
                            'or_no' => $row[3]   ,
                            'tdarp_no' => $row[4]    ,
                            'barangay' => $row[5]    ,
                            'classification' => $row[6] ,
                            'basic_current_gross_amt' => $row[7] ,
                            'basic_discount' => $row[8] ,
                            'basic_prev_year' => $row[9] ,
                            'basic_current_penalties' => $row[10] ,
                            'basic_prev_penalties' => $row[11] ,
                            'basic_subtotal_gross' => $row[12] ,
                            'basic_total_gross' => $row[13] ,
                            'sef_subnet_collection' => $row[14] ,
                            'sef_current_gross_amt' => $row[15] ,
                            'sef_discount' => $row[16] ,
                            'sef_prev_year' => $row[17] ,
                            'sef_current_penalties' => $row[18] ,
                            'sef_prev_penalties' => $row[19] ,
                            'sef_subtotal_gross' => $row[20] ,
                            'sef_total_gross' => $row[21] ,
                            'sef_subnet_collection' => $row[22] ,
                            'gross_collection' => $row[23] ,
                            'net_collection' => $row[24] 
                            ];
                        }

                        
                    }

                    // dd($data);
                    dd($insert);
                    
                        if(!empty($insert)){
                    
                            $insertData = DB::table('municipal_rpt')->insert($insert);
                            if ($insertData) {
                                Session::flash('success', 'Your Data has successfully imported');
                            }else {                        
                                Session::flash('error', 'Error inserting the data..');
                                return back();
                            }
                        }
                    }


                    // dd($reader->toArray());
                })->get();
                
 
                return back();
 
            }else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return back();
            }
        }
    }
 }

