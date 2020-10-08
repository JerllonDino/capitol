@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">

        <button class="btn btn-sm btn-success "  style="margin:  10px;" onclick="$(this).newPCmac()"  >add pc</button>


            <table id="pc_mac_address" class="table table-striped table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>PC NAME</th>
                     <th>IP ADDRESS</th>
                     <th>TRANSACTION</th>
                     <th>FORM</th>
                     <th>Municipality</th>
                    <th>RECEIPT START</th>
                    <th>RECEIPT END</th>
                     <th>RECEIPT CURRENT</th>
                    <th>ACTION</th>
                </tr>
            </thead>
        </table>

</div>


 <!-- Modal -->

<div class="modal fade" id="new_pc" tabindex="-1" role="dialog" aria-labelledby="new_pcLabel">
  <div class="modal-dialog modal-lg " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="new_pcLabel"> <span></span></h4>
      </div>
      <div class="modal-body">

        <div class="status"></div>
        <div id="contents-menu">
            <form class="form-horizontal" id="new_pc_form">
              <div class="box-body">
                <div class="statusCI"></div>

                <div class="form-group">
                  <label for="pc_name" class="col-sm-5 control-label">PC NAME : </label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id="pc_name"  name="pc_name" placeholder="PC NAME" />
                  </div>
                </div>

                <div class="form-group">
                  <label for="pc_ip" class="col-sm-5 control-label">PC ASSIGNED IP </label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id="pc_ip"  name="pc_ip" placeholder="PC ASSIGNED IP : " />
                  </div>
                </div>



                <div class="form-group">
                  <label for="pc_process_type" class="col-sm-5 control-label">PC PROCESS TYPE </label>
                  <div class="col-sm-7">
                    <select class="form-control" id="pc_process_type"  name="pc_process_type" >
                        <option value="">SELECT PROCESS TYPE</option>
                        <option value="LANDTAX">Land Tax Collections</option>
                        <option value="FIELDLANDTAX">Field Land Tax Collections</option>
                    </select>

                  </div>
                </div>

                <div class="form-group">
                  <label for="pc_process_form" class="col-sm-5 control-label">PC PROCESS FORM </label>
                  <div class="col-sm-7">
                    <select class="form-control" id="pc_process_form"  name="pc_process_form" >
                    </select>
                  </div>
                </div>


                  <div id="serials">
                  </div>



              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
                <button type="button" class="btn btn-info pull-right" id="submit_empl" onclick="">Submit</button>
              </div>
              <!-- /.box-footer -->
              {{csrf_field()}}
              <input type="hidden" name="pc_mac_id" id="pc_mac_id" value="" />
            </form>

        </div>
      </div>

    </div>
  </div>
</div>

@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}
     @include('collection::pc/js/pc_set')
@endsection
