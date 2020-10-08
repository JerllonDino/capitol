<script type="text/javascript">

$.fn.loadAddedApplicants = function(){
if ( $.fn.dataTable.isDataTable( '#pc_mac_address' ) ) {
    $('#pc_mac_address').dataTable().fnDestroy();
}
$('#pc_mac_address').dataTable({
      processing: true,
      serverSide: true,
      ajax:{
        "type": 'POST',
        "url" : '{{route('collection.set_datatables')}}',
        data : {
              "dataTables"        : 'pc_macs',
              "_token"            : '{{csrf_token()}}'
            }
        },
      columns: [
                { data: 'pc_name',  name: 'col_pc_settings.pc_name'
                },
                { data: 'pc_ip',  name: 'col_pc_settings.pc_ip'
                },
                { data: 'process_type',  name: 'col_pc_settings.process_type'
                },
                { data: 'form_name',  name: 'col_acctble_form.name'
                },
                { data: 'mncpal_name',  name: 'col_municipality.name'
                },
                { data: 'serial_begin',  name: 'col_serial.serial_begin',
                },
                { data: 'serial_end',  name: 'col_serial.serial_end',
                },
                { data: 'serial_current',  name: 'col_serial.serial_current',
                },
                { data: null,  name: '', 'searchable' : false,
                    render: function (data, type, row, meta) {
                      if(data.form_type == 2){
                        return '<button class="btn btn-sm  bg-green " onclick="$(this).updatePc56('+data.pc_mac_id+',\''+data.mncpal_name+'\');" ><i class="glyphicon glyphicon-check"></i></button> <button class="btn btn-sm  bg-danger " onclick="$(this).deletePc('+data.pc_mac_id+');" ><i class="glyphicon glyphicon-trash"></i></button>';
                      }else{
                        return '<button class="btn btn-sm  bg-green " onclick="$(this).updatePc('+data.pc_mac_id+');" ><i class="glyphicon glyphicon-check"></i></button> <button class="btn btn-sm  bg-danger " onclick="$(this).deletePc('+data.pc_mac_id+');" ><i class="glyphicon glyphicon-trash"></i></button>';
                      }
                    }
                },
        ]
  });
};

$.fn.loadAddedApplicants();


$.fn.sentNewPCmac = function(){

  $.ajax({
          type: 'POST',
          url: '{{route('settings_access.set_pc')}}',
          data: $('#new_pc_form').serialize(),
          dataType: "json",
          error: function(){
              alert('error');
          },
          success: function(data) {
            var errors = '';
                          if(data['status']==0){
                             for(var key in data['errors']){
                                 errors += data['errors'][key]+'<br />';
                              }
                            $('#new_pc_form  .statusCI').html('<div class="alert alert-danger alert-dismissible"><h4><i class="icon fa fa-ban"></i> Alert!</h4>'+errors+'</div>').fadeIn().delay(18000).fadeOut(100);
                          }else{
                            $('#new_pc_form  .statusCI').html('<div class="alert alert-success alert-dismissible"><h4><i class="icon fa fa-ban"></i> Success!</h4>'+errors+'</div>').fadeIn().delay(2500).fadeOut(100);
                            $('#new_pc').modal('hide');
                              //hide the modal

                              $('body').removeClass('modal-open');
                              //modal-open class is added on body so it has to be removed

                              $('.modal-backdrop').remove();

                              $.fn.loadAddedApplicants();
                          }
          }
        });
};

$.fn.sentNewPCmacf56 = function(){

  $.ajax({
          type: 'POST',
          url: '{{route('settings_access.set_pcf56')}}',
          data: $('#new_pc_form').serialize(),
          dataType: "json",
          error: function(){
              alert('error');
          },
          success: function(data) {
            var errors = '';
                          if(data['status']==0){
                             for(var key in data['errors']){
                                 errors += data['errors'][key]+'<br />';
                              }
                            $('#new_pc_form  .statusCI').html('<div class="alert alert-danger alert-dismissible"><h4><i class="icon fa fa-ban"></i> Alert!</h4>'+errors+'</div>').fadeIn().delay(18000).fadeOut(100);
                          }else{
                            $('#new_pc_form  .statusCI').html('<div class="alert alert-success alert-dismissible"><h4><i class="icon fa fa-ban"></i> Success!</h4>'+errors+'</div>').fadeIn().delay(2500).fadeOut(100);
                            $('#new_pc').modal('hide');
                              //hide the modal

                              $('body').removeClass('modal-open');
                              //modal-open class is added on body so it has to be removed

                              $('.modal-backdrop').remove();

                              $.fn.loadAddedApplicants();
                          }
          }
        });
};

$.fn.sentUpdatePCmacf56 = function(){

  $.ajax({
          type: 'POST',
          url: '{{route('settings_access.update_pcf56')}}',
          data: $('#new_pc_form').serialize(),
          dataType: "json",
          error: function(){
              alert('error');
          },
          success: function(data) {
            var errors = '';
                          if(data['status']==0){
                             for(var key in data['errors']){
                                 errors += data['errors'][key]+'<br />';
                              }
                            $('#new_pc_form  .statusCI').html('<div class="alert alert-danger alert-dismissible"><h4><i class="icon fa fa-ban"></i> Alert!</h4>'+errors+'</div>').fadeIn().delay(18000).fadeOut(100);
                          }else{
                            $('#new_pc_form  .statusCI').html('<div class="alert alert-success alert-dismissible"><h4><i class="icon fa fa-ban"></i> Success!</h4>'+errors+'</div>').fadeIn().delay(2500).fadeOut(100);
                            $('#new_pc').modal('hide');
                              //hide the modal

                              $('body').removeClass('modal-open');
                              //modal-open class is added on body so it has to be removed

                              $('.modal-backdrop').remove();

                              $.fn.loadAddedApplicants();
                          }
          }
        });
};

$.fn.deletePc = function(pc_mac_id){
  $.ajax({
          type: 'POST',
          url: '{{route('settings_access.delete_pc_id')}}',
          data: {
              _token : '{{csrf_token()}}',
              pc_mac_id : pc_mac_id
          },
          dataType: "json",
          error: function(){
              alert('error');
          },
          success: function(data) {
            $.fn.loadAddedApplicants();
          }
        });
};


$.fn.updatePc = function(pc_mac_id){
  $.ajax({
          type: 'POST',
          url: '{{route('settings_access.get_pc_edit')}}',
          data: {
              _token : '{{csrf_token()}}',
              pc_mac_id : pc_mac_id
          },
          dataType: "json",
          error: function(){
              alert('error');
          },
          success: function(data) {
            $('#pc_mac_id').val(data.PCSettings.id);
            $('#pc_name').val(data.PCSettings.pc_name);
            $('#pc_ip').val(data.PCSettings.pc_ip);
            var optionsx = '';
            var option_first = '';


            

            $('#pc_process_type  > option').each(function(){
              var opt = $(this);
              if(opt.val() == data.serials[0].process_type){
                  opt.attr('selected',true);
              }
            });

            var form = '<option value="0">SELECT AF TYPE</option> <option value="1">Form 51</option>';
              if(data.serials[0].process_type==='FIELDLANDTAX'){
               form = form+'<option value="2">Form 56</option>';

              }
              $('#pc_process_form').html(form);

            $('#pc_process_form  > option').each(function(){
              var opt = $(this);
              if(opt.val() == data.serials[0].form_type){
                  opt.attr('selected',true);
                  if(data.serials[0].form_type  =='1'){
                     $.fn.getSerials(data.serials[0].id);
                   }else{
                       $.fn.getF56Serials(data.serials[0].id);
                   }
              }
            });
            $('#new_pc').modal({
              backdrop: 'static',
              keyboard: false,
              show : true
            });

          }
        });
};

$.fn.updatePc56 = function(pc_mac_id,mncpal_name){
  $.ajax({
          type: 'POST',
          url: '{{route('settings_access.get_pc_edit56')}}',
          data: {
              _token : '{{csrf_token()}}',
              pc_mac_id : pc_mac_id,
              mncpal_name : mncpal_name
          },
          dataType: "json",
          error: function(){
              alert('error');
          },
          success: function(data) {
            console.log(data.PCSettings.pc_receipt);
            $('#pc_mac_id').val(data.PCSettings.id);
            $('#pc_name').val(data.PCSettings.pc_name);
            $('#pc_ip').val(data.PCSettings.pc_ip);
            var optionsx = '';
            var option_first = '';


            $('#pc_process_type  > option').each(function(){
              var opt = $(this);
              if(opt.val() == data.serials[0].process_type){
                  opt.attr('selected',true);
              }
            });

            var form = '<option value="0">SELECT AF TYPE</option> <option value="1">Form 51</option>';
              if(data.serials[0].process_type==='FIELDLANDTAX'){
               form = form+'<option value="2">Form 56</option>';

              }
              $('#pc_process_form').html(form);

            var optionsx = '';
            var serial=  '';
            var idx = '';
            for(var x = 0 ; x<Object.keys(data.municipality).length ; x++){
                var optionsx = '';
                    idx = data.municipality[x];

               for(var y = 0 ; y<Object.keys(data['serials_d'][idx]['serials']).length ; y++){
                if(data.PCSettings.pc_receipt == data['serials_d'][idx]['serials'][y].id ){
                  optionsx = '<option value="'+data['serials_d'][idx]['serials'][y].id+'" selected >'+data['serials_d'][idx]['serials'][y].label+'</option> '+optionsx;
                }else{
                   optionsx = '<option value="'+data['serials_d'][idx]['serials'][y].id+'">'+data['serials_d'][idx]['serials'][y].label+'</option> '+optionsx;
                }
               }

              serial =   '<div class="form-group">'+
                          '  <label for="pc_receipt" class="col-sm-5 control-label">PC ASSIGNED RECEIPT '+data['serials_d'][idx].name+' </label>'+
                          '   <input type="hidden" name="municpality_id[]" value="'+idx+'" /> '+
                          '  <div class="col-sm-7">'+
                          '    <select class="form-control" id="municpality_receipt[]"  name="municpality_receipt[]" >'
                                  + optionsx +
                          '    </select>'+
                          '  </div>'+
                          '</div>'+serial;

            }
            $('#serials').html(serial);

            $('#new_pc').modal({
              backdrop: 'static',
              keyboard: false,
              show : true
            });
            $('#submit_empl').attr('onclick','$(this).sentUpdatePCmacf56();');

          }
        });
};



$.fn.newPCmac = function(){
  $('#pc_name').val('');
  $('#pc_ip').val('');
  $('#pc_mac_id').val('');
   $('#new_pc').modal({
              backdrop: 'static',
              keyboard: false
            });
 };
 $('#pc_process_type').attr('disabled',false);
 $('#pc_process_form').attr('disabled',false);

 $('#pc_process_type').on('change',function(){
    var el = $(this);
    var form = '<option value="0">SELECT AF TYPE</option> <option value="1">Form 51</option>';
    if(el.val()==='FIELDLANDTAX'){
     form = form+'<option value="2">Form 56</option>';
    }
    $('#pc_process_form').html(form);
     $('#pc_receipt').html('');
 });

  $('#pc_process_form').on('change',function(){
    if($(this).val()=='1'){
         $.fn.getSerials();
       }else{
           $.fn.getF56Serials();
       }

 });

$.fn.getSerials = function(serial_def = ''){
   $.ajax({
          type: 'POST',
          url: '{{route('settings_access.get_pc_serials')}}',
          data: {
              _token : '{{csrf_token()}}',
              form : $('#pc_process_form').val()
          },
          dataType: "json",
          error: function(){
              alert('error');
          },
          success: function(data) {
            var optionsx = '';

            for(var x = 0 ; x<data.length ; x++){
               var set_opt = '';
              if(serial_def == data[x].id){
                set_opt = 'selected';
              }
               optionsx = '<option value="'+data[x].id+'" '+set_opt+'>'+data[x].label+'</option> '+optionsx;
            }

            var serial=   '<div class="form-group">'+
                          '  <label for="pc_receipt" class="col-sm-5 control-label">PC ASSIGNED RECEIPT </label>'+
                          '  <div class="col-sm-7">'+
                          '    <select class="form-control" id="pc_receipt"  name="pc_receipt" >'
                                  + optionsx +
                          '    </select>'+
                          '  </div>'+
                          '</div>';

            $('#serials').html(serial);
            $('#submit_empl').attr('onclick','$(this).sentNewPCmac();');
          }
        });

};

$.fn.getF56Serials = function(serial_def = ''){
  $.ajax({
          type: 'POST',
          url: '{{route('settings_access.get_pc_serialsf56')}}',
          data: {
              _token : '{{csrf_token()}}',
              form : $('#pc_process_form').val()
          },
          dataType: "json",
          error: function(){
               console.log('error');
          },
          success: function(data) {

            var optionsx = '';
            var serial=  '';
            var idx = '';
            for(var x = 0 ; x<Object.keys(data.municipality).length ; x++){
                var optionsx = '';
                    idx = data.municipality[x];

               for(var y = 0 ; y<Object.keys(data['serials'][idx]['serials']).length ; y++){
                  optionsx = '<option value="'+data['serials'][idx]['serials'][y].id+'">'+data['serials'][idx]['serials'][y].label+'</option> '+optionsx;
               }

              serial =   '<div class="form-group">'+
                          '  <label for="pc_receipt" class="col-sm-5 control-label">PC ASSIGNED RECEIPT '+data['serials'][idx].name+' </label>'+
                          '   <input type="hidden" name="municpality_id[]" value="'+idx+'" /> '+
                          '  <div class="col-sm-7">'+
                          '    <select class="form-control" id="municpality_receipt[]"  name="municpality_receipt[]" >'
                                  + optionsx +
                          '    </select>'+
                          '  </div>'+
                          '</div>'+serial;

            }
            $('#serials').html(serial);
            $('#submit_empl').attr('onclick','$(this).sentNewPCmacf56();');
          }
        });
};
</script>