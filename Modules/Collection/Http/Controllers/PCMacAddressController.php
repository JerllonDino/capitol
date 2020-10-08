<?php

namespace Modules\Collection\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use Modules\Collection\Entities\PCSettings;
use Modules\Collection\Entities\Serial;
use Modules\Collection\Entities\Municipality;


use App\Http\Controllers\Controller;

class PCMacAddressController extends Controller
{
   public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->base['page_title'] = 'Set Pc Mac Addressess';
        $this->base['request_ip'] = $request->ip();
    }


    public function get_pc_mac(Request $request)
    {
         $serials = Serial::where('serial_current', '!=', '0')
            ->get();
            $data = [];
        foreach ($serials as $serial) {
          if($serial->serial_current != 0){
              $addtl_data = '';
            if ($serial->municipality_id !== null) {
                $addtl_data = ' ' . $serial->municipality->name;
            } else {
                $addtl_data = ' ' . $serial->unit . ' (' . $serial->fund->name . ')';
            }
            array_push($data, [
                'id' => $serial->id,
                'label' => $serial->serial_begin .'-'. $serial->serial_end . $addtl_data,
            ]);
          }
            
        }

         $this->base['serials'] = $data;

        return view('collection::pc.index')->with('base', $this->base);
    }

    public function get_pc_serials(Request $request){
        $serials = Serial::where('serial_current', '<>', 0)
            ->where('acctble_form_id', $request['form'])
            ->get();
            $datax = [];
        foreach ($serials as $serial) {
            $addtl_data = '';
            if ($serial->municipality_id !== null) {
                $addtl_data = ' ' . $serial->municipality->name;
            } else {
                $addtl_data = ' ' . $serial->unit . ' (' . $serial->fund->name . ')';
            }
            array_push($datax, [
                'id' => $serial->id,
                'label' => $serial->serial_begin .'-'. $serial->serial_end . $addtl_data,
            ]);
        }

        $data['serials'] = $datax;
        return $datax;
    }

    public function get_pc_serialsf56(Request $request){
        $serials = Serial::where('serial_current', '<>', 0)
            ->where('acctble_form_id', $request['form'])
            ->orderby('municipality_id','desc')
            ->get();
            $datax = [];
            $municipality_id = '';
            $municipality_idx = [];
        foreach ($serials as $serial) {
            $datax[$serial->municipality_id]['name'] = $serial->municipality->name;
            $datax[$serial->municipality_id]['municipality_id'] = $serial->municipality_id;

            if($municipality_id != $serial->municipality_id ){
              $municipality_id = $serial->municipality_id;
              $count = 0;
            }else{
              $count++;
            }

            if ($serial->municipality_id !== null) {
              if(!in_array($serial->municipality_id, $municipality_idx)){
                  $municipality_idx[] = $serial->municipality_id;
              }

                $datax[$serial->municipality_id]['serials'][$count] =  [ 'id'=>$serial->id ,'municipality' => $serial->municipality->name, 'label' => $serial->serial_begin .'-'. $serial->serial_end .' ' . $serial->municipality->name];
            }


        }

        $data['municipality'] = $municipality_idx;

        $data['serials'] = $datax;
        return $data;
    }

    public function get_pc_edit(Request $request){
         $data['PCSettings'] = PCSettings::find($request->input('pc_mac_id'));
         $data['serials'] = $data['PCSettings']->pc_receipt()->get();
         return $data;
    }

    public function get_pc_edit56(Request $request){
      $municipal = Municipality::where('name',$request->input('mncpal_name'))->first();

      $serials = Serial::where('serial_current', '<>', 0)
            ->where('acctble_form_id', 2)
            ->where('municipality_id', $municipal->id)
            ->get();
      $data['PCSettings'] = PCSettings::find($request->input('pc_mac_id'));
      $data['serials'] = $data['PCSettings']->pc_receipt()->get();

            $datax = [];
            $municipality_id = '';
            $municipality_idx = [];
        foreach ($serials as $serial) {
            $datax[$serial->municipality_id]['name'] = $serial->municipality->name;
            $datax[$serial->municipality_id]['municipality_id'] = $serial->municipality_id;

            if($municipality_id != $serial->municipality_id ){
              $municipality_id = $serial->municipality_id;
              $count = 0;
            }else{
              $count++;
            }

            if ($serial->municipality_id !== null) {
              if(!in_array($serial->municipality_id, $municipality_idx)){
                  $municipality_idx[] = $serial->municipality_id;
              }

                $datax[$serial->municipality_id]['serials'][$count] =  [ 'id'=>$serial->id ,'municipality' => $serial->municipality->name, 'label' => $serial->serial_begin .'-'. $serial->serial_end .' ' . $serial->municipality->name];
            }


        }

        $data['municipality'] = $municipality_idx;

        $data['serials_d'] = $datax;


         
         return $data;
    }

    public function set_pc_mac(Request $request)
    {
        if($request->input('pc_mac_id')!==''){
                    $PCSettings = PCSettings::find($request->input('pc_mac_id'));
                    $data['pc_idx'] = $request->input('pc_mac_id');
                    $fields = [
                                'pc_name' => 'required',
                                'pc_ip' => 'required|ip',
                                'pc_process_type' => 'required',
                                'pc_process_form' => 'required',
                            ];
              }else{
                    $PCSettings = new PCSettings;
                     $fields = [
                                'pc_name' => 'required',
                                'pc_ip' => 'required|ip',
                                // 'pc_receipt' => 'required|unique:col_pc_settings,pc_receipt',
                                'pc_process_type' => 'required',
                                'pc_process_form' => 'required',
                            ];
              }


          $validator = Validator::make($request->all(),
                            $fields,
                            [
                                'pc_name.required' => 'The PC NAME cant be empty.',
                                'pc_ip.required' => 'The PC IP ADDRESS.',
                                'pc_receipt.required' => 'The RECEIPT cant be empty.',
                                'pc_receipt.unique' => 'The RECEIPT is already assigned to another PC',
                            ] );
         if ($validator->fails()) {
              $data['status'] = 0;
              $data['errors'] = $validator->messages();
        }else{
             $data['status'] = 1;
              $data['errors'] = 'success adding PC';



               $PCSettings->pc_name = $request->input('pc_name');
               $PCSettings->pc_ip = $request->input('pc_ip');
               $PCSettings->pc_receipt = $request->input('pc_receipt');
               $PCSettings->process_type = $request->input('pc_process_type');
               $PCSettings->form_type = $request->input('pc_process_form');
               $PCSettings->save();
        }
        return $data;
    }

        public function set_pc_macf56(Request $request)
    {
        if($request->input('pc_mac_id')!==''){
                    $PCSettings = PCSettings::find($request->input('pc_mac_id'));
                    $data['pc_idx'] = $request->input('pc_mac_id');
                    $fields = [
                                'pc_name' => 'required',
                                'pc_ip' => 'required|ip',
                                'pc_process_type' => 'required',
                                'pc_process_form' => 'required',
                            ];
              }else{
                    $PCSettings = new PCSettings;
                     $fields = [
                                'pc_name' => 'required',
                                'pc_ip' => 'required|ip',
                                'pc_process_type' => 'required',
                                'pc_process_form' => 'required',
                            ];
              }


          $validator = Validator::make($request->all(),
                            $fields,
                            [
                                'pc_name.required' => 'The PC NAME cant be empty.',
                                'pc_ip.required' => 'The PC IP ADDRESS.',
                                'municpality_receipt.*.required' => 'The RECEIPT cant be empty.',
                                'pc_receipt.unique' => 'The RECEIPT is already assigned to another PC',
                            ] );
         if ($validator->fails()) {
              $data['status'] = 0;
              $data['errors'] = $validator->messages();
        }else{
             $data['status'] = 1;
             $data['errors'] = count($request->input('municpality_id'));


             for($x = 0 ; $x<count($request->input('municpality_id')) ; $x++ ){
                if($request->input('pc_mac_id')!==''){
                    $PCSettings = PCSettings::find($request->input('pc_mac_id'));
                 }else{
                    $PCSettings = new PCSettings;
                }

               $PCSettings->pc_name = $request->input('pc_name');
               $PCSettings->pc_ip = $request->input('pc_ip');
               $PCSettings->pc_receipt = $request->input('municpality_receipt')[$x];
               $PCSettings->process_type = $request->input('pc_process_type');
               $PCSettings->form_type = $request->input('pc_process_form');
               $PCSettings->save();
             }

        }
        return $data;
    }

    public function delete_pc_id(Request $request){
         $PCSettings = PCSettings::find($request->input('pc_mac_id'))->delete();

         return response()->json('Deleted');
    }


    public function update_pcf56(Request $request){
      $PCSettings = PCSettings::find($request->input('pc_mac_id'));
      $PCSettings->pc_receipt = $request->input('municpality_receipt')[0];
      $PCSettings->save();
        return $request->all();
    }


}
