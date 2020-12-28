
<script type="text/javascript">


  $.fn.checkerBrgy = function(){
    $("#municipality").trigger( 'change', []);
  };
  
  
  <?php
  if (!empty($base['receipt']->serial->municipality->name)){
    if($base['receipt']->col_municipality_id != $base['receipt']->serial->municipality->id){
    echo   '$.fn.checkerBrgy()';
    }
  }
  ?>
  
  
  $.fn.animateWidth = function(){
    $('#tablex td input,#tablex td select').focus(function()
    {
      $(this).attr('data-default-w', $(this).width());
      $(this).animate({ width: 150 }, 'slow');
    }).blur(function()
    {
      var w = $(this).attr('data-default-w');
      $(this).animate({ width: 90 }, 'slow');
    });
  }
  
  
  $.fn.animateWidth();
  var hostss = 'http://{{ $base['host'] }}';
  function zeroPad(num, places) {
      if(!isNaN(num)){
           var zero = places - num.toString().length + 1;
           return Array(+(zero > 0 && zero)).join("0") + num;
      }
  }
  
  
  $('.rem_row_existing').click(function(){
    var el = $(this);
  
    var index_el = $('.rem_row_existing').index(this);
  
    // if(index_el > 0){
      // var tablex = $('#tablex tbody tr').eq(index_el);
      var tablex = el.closest('tr');
      var deleted_c = tablex.find('.f56_detail_deleted');
      // console.log(deleted_c.val());
      if(tablex.hasClass('remove_existing_tdrp')){
          deleted_c.val('false');
          tablex.removeClass('remove_existing_tdrp');
          el.removeClass('btn-info').addClass('btn-warning ');
          el.find('i').removeClass('fa-undo').addClass('fa-minus');
      }else{
        deleted_c.val('true');
        tablex.addClass('remove_existing_tdrp');
          el.removeClass('btn-warning').addClass('btn-info ');
          el.find('i').removeClass('fa-minus').addClass('fa-undo');
      }
      $.fn.computeAmountTotal();
    // }else{
    //   swal({
    //           title: 'DONT DELETE!',
    //           text: 'CAN\'T DELETE FIRST COLOUMN. PLEASE UPDATE IT INSTEAD ',
    //           timer: 5000,
    //           type: 'warning',
    //         })
    // }
  
  });
  
  $.fn.tdarpno = function(){
      var mncpal_brgy_code_error = $('#mncpal_brgy_code_error');
  
      // OLD CODE
      // $('.tdarpno').on('keyup',function(){
      //     mncpal_brgy_code_error.html('');
      //     mncpal_brgy_code_error.removeClass('alert alert-danger alert-dismissible');
      //     var el = $(this);
      //     var el_index = $('.tdarpno').index(this);
      //     var el_val = el.val().split('-');
      //     var mncplty = el_val[1];
      //     var brgy = el_val[2];
      //         brgy = zeroPad(brgy,3);
      //     var barangay_code = $('option:selected','.tdrp_barangay').eq(el_index).attr('data-code');
      //         barangay_code = zeroPad(barangay_code,3);
      //     // var municipal_code = $('option:selected','#municipality').attr('data-code');
      //     var municipal_code = $('#municipality').attr('data-code');
      //     if(municipal_code == undefined){
      //       // municipal_code = $('#municipality').attr('data-code');
      //       municipal_code = $('#municipality_name').attr('data-code');
      //     }
      //     var error = '';
      //     // if(el_val[0] === '2010'){
      //         if( el_val.length >1 && mncplty != '' && mncplty !== municipal_code && municipal_code != undefined){
      //           error =' <strong> Error ! Wrong Municipality Code</strong>';
      //         } else if($('#serial_id').val() == '') {
      //           error = '<strong> Please select series</strong>'
      //         }
      //         // if(el_val.length >2 && brgy != '' && brgy !== barangay_code){
      //         //     error =  error +  ' <strong> Error ! Wrong Barangay Code</strong>';
      //         // }
      //         if(error!=''){
      //             mncpal_brgy_code_error.html(error);
      //             mncpal_brgy_code_error.addClass('alert alert-danger alert-dismissible');
      //         }
      //     // }
      // });
  
      // $('.tdarpno').on('change',function(){
      //    mncpal_brgy_code_error.html('');
      //    mncpal_brgy_code_error.removeClass('alert alert-danger alert-dismissible');
      //    var el = $(this);
      //    var el_index = $('.tdarpno').index(this);
      //    var el_val = el.val().split('-');
      //    var mncplty = el_val[1];
      //    var brgy = el_val[2];
      //    brgy = zeroPad(brgy,3);
      //    var barangay_code = $('option:selected','.tdrp_barangay').eq(el_index).attr('data-code');
      //    barangay_code = zeroPad(barangay_code,3);
      //    var error = '';
      //   if(el_val.length >2 && brgy != '' && brgy !== barangay_code && barangay_code != undefined){
      //     error =  error +  ' <strong> Error ! Wrong Barangay Code</strong>';
      //   } else if($('#serial_id').val() == '') {
      //     error = '<strong> Please select series</strong>'
      //   }
      //   if(error!=''){
      //     mncpal_brgy_code_error.html(error);
      //     mncpal_brgy_code_error.addClass('alert alert-danger alert-dismissible');
      //   }
      // });
  
      // $('.tdrp_barangay').change(function(){
      //     mncpal_brgy_code_error.html('');
      //     mncpal_brgy_code_error.removeClass('alert alert-danger alert-dismissible');
  
      //     var el_index = $('.tdrp_barangay').index(this);
      //     var el = $('.tdarpno').eq(el_index);
      //     var el_val = el.val().split('-');
      //     var mncplty = el_val[1];
          // var brgy = el_val[2];
          //     brgy = zeroPad(brgy,3);
          // var barangay_code = $('option:selected','.tdrp_barangay').eq(el_index).attr('data-code');
          //     barangay_code = zeroPad(barangay_code,3);
      //     // var municipal_code = $('option:selected','#municipality_name').attr('data-code');
      //     // if(municipal_code == undefined){
      //     //   municipal_code = $('#municipality_name').attr('data-code');
      //     //   console.log(municipal_code+'municipal_code');
      //     // }
      //     var municipal_code = $('#municipality').attr('data-code');
      //     if(municipal_code == undefined){
      //       municipal_code = $('#municipality_name').attr('data-code');
      //     }
      //     var error = '';
      //     // if(el_val[0] === '2010'){
      //         if( el_val.length > 1 && mncplty != ''&& mncplty !== municipal_code && municipal_code != undefined){
      //          error =' <strong> Error ! Wrong Municipality Code</strong>';
      //         }
      //         if(el_val.length > 2 && brgy != '' && brgy !== barangay_code && barangay_code != undefined){
      //           error =  error +  ' <strong> Error ! Wrong Barangay Code</strong>';
      //         }
      //         if(error!=''){
      //             mncpal_brgy_code_error.html(error);
      //             mncpal_brgy_code_error.addClass('alert alert-danger alert-dismissible');
      //         }
      //     // }
      // });
  
      // NEW
      $('.tdarpno').on('keyup',function(){
          mncpal_brgy_code_error.html('');
          mncpal_brgy_code_error.removeClass('alert alert-danger alert-dismissible');
  
          var el_index = $('.tdrp_barangay').index(this);
          var el = $('.tdarpno').eq(el_index);
          var el_val = el.val().split('-');
          // municipal and barangay code from arp no.
          var munic = el_val[1]; 
          var brgy = parseInt(el_val[2]);
  
          var select_munic_code = $('#municipality_name').attr('data-code');
          var error = '';
          if(select_munic_code != undefined && $('#municipality_name').length > 0) {
              $.ajax({
                  type: 'POST',
                  url: '{{ route("collection.ajax") }}',
                  data: {
                      '_token': '{{ csrf_token() }}',
                      'action': 'get_barangays',
                      'input': $('#municipality').val(),
                  },
                  success: function(response) {
                      // $('#brgy,.tdrp_barangay').find('option')
                      //     .remove()
                      //     .end()
                      //     .prop('disabled', false).append('<option value=""></option>');
                      $('#brgy').find('option')
                          .remove()
                          .end()
                          .prop('disabled', false).append('<option value=""></option>');
                      $.each( response, function(key, brgy) {
                          if(brgy.id == brgy) {
                              $('#brgy,.tdrp_barangay').append($('<option>', {
                                  'data-code':brgy.code,
                                  value: brgy.id,
                                  text: brgy.name,
                                  selected: true
                              }));
                          } else {
                              $('#brgy,.tdrp_barangay').append($('<option>', {
                                  'data-code':brgy.code,
                                  value: brgy.id,
                                  text: brgy.name
                              }));
                          }
                      });
                  },
                  error: function(response) {
  
                  },
              });
          }
  
          var edit_mnc_code = $('#municipality').attr('data-code'); // for editing f56 receipt
          if(el_val.length > 1 && select_munic_code != undefined) {
              if(munic != select_munic_code) {
                  error +=' <strong> Error ! Wrong Municipality Code.</strong><br>';
              } 
          } else if(select_munic_code == undefined && $('#municipality_name').length > 0) {
              error +=' <strong> Please select series.</strong><br>';
          } else if(edit_mnc_code != undefined && edit_mnc_code > 0 && $('#municipality_name').length == 0) {
              if(munic != edit_mnc_code) {
                  error +=' <strong> Error ! Wrong Municipality Code.</strong><br>';
              } 
          }
          if(error != ''){
            mncpal_brgy_code_error.html(error);
            mncpal_brgy_code_error.addClass('alert alert-danger alert-dismissible');
          }
      });
  
      $(document).on('change', '#serial_id', function() {
          mncpal_brgy_code_error.html('');
          mncpal_brgy_code_error.removeClass('alert alert-danger alert-dismissible');
          var el_index = $('.tdrp_barangay').index(this);
          var el = $('.tdarpno').eq(el_index);
          var el_val = el.val().split('-');
  
          var munic = el_val[1];
          var brgy = parseInt(el_val[2]);
  
          var error = '';
          $.ajax({
              type: 'POST',
              url: '{{ route("collection.ajax") }}',
              data: {
                  '_token': '{{ csrf_token() }}',
                  'action': 'get_municipality',
                  'input': $("#serial_id").val(),
              },
              success: function(response) {
                  $("#municipality").val(response.id);
                  if($("#municipality_name" ).length != 0) {
                        $("#municipality_name" ).val(response.name);
                        $("#municipality_name" ).attr('data-code',response.code);
                        $('#municipality_code').val(response.code);
                  }
  
                  $("#municipality").trigger( 'change', []);
  
                  if(el_val.length > 1 && response.code != undefined) {
                      if(munic != response.code) {
                          error +=' <strong> Error ! Wrong Municipality Code.</strong><br>';
                      }
                  } else if(response.code == undefined && $('#municipality_name').length > 0) {
                      error +=' <strong> Please select series.</strong><br>';
                  }
  
                  if(error != ''){
                      mncpal_brgy_code_error.html(error);
                      mncpal_brgy_code_error.addClass('alert alert-danger alert-dismissible');
                  }
              },
              error: function(response) {
  
              },
          });
  
          $.ajax({
              type: 'POST',
              url: '{{ route("collection.ajax") }}',
              data: {
                  '_token': '{{ csrf_token() }}',
                  'action': 'get_barangays',
                  'input': $('#municipality').val(),
              },
              success: function(response) {
                  $('#brgy,.tdrp_barangay').find('option')
                      .remove()
                      .end()
                      .prop('disabled', false).append('<option value=""></option>');
  
                  $.each( response, function(key, brgy) {
                      if(brgy.id == brgy) {
                          $('#brgy,.tdrp_barangay').append($('<option>', {
                              'data-code':brgy.code,
                              value: brgy.id,
                              text: brgy.name,
                              selected: true
                          }));
                          barangay_code = brgy.code;
                      } else {
                          $('#brgy,.tdrp_barangay').append($('<option>', {
                              'data-code':brgy.code,
                              value: brgy.id,
                              text: brgy.name
                          }));
                      }
                  });
                  var barangay_code = $('option:selected','.tdrp_barangay').eq(el_index).attr('data-code');
                      barangay_code = zeroPad(barangay_code,3);
                  if(el_val.length > 1 && barangay_code != undefined) {
                      if(parseInt(brgy) != parseInt(barangay_code)) {
                          error +=' <strong> Error ! Wrong Barangay Code.</strong><br>';
                      }
                  } else if (barangay_code == undefined) {
                      error +='<strong> Please select barangay.</strong><br>'
                  }
  
                  if(error != ''){
                      mncpal_brgy_code_error.html(error);
                      mncpal_brgy_code_error.addClass('alert alert-danger alert-dismissible');
                  }
              },
              error: function(response) {
  
              },
          });
      });
  
      $('.tdrp_barangay').on('change', function() {
          mncpal_brgy_code_error.html('');
          mncpal_brgy_code_error.removeClass('alert alert-danger alert-dismissible');
          var el_index = $('.tdrp_barangay').index(this);
          var el = $('.tdarpno').eq(el_index);
          var el_val = el.val().split('-');
  
          var munic = el_val[1];
          var brgy = parseInt(el_val[2]);
  
          var barangay_code = $('option:selected','.tdrp_barangay').eq(el_index).attr('data-code');
              barangay_code = zeroPad(barangay_code,3);
          var select_munic_code = $('#municipality_name').attr('data-code');
          var error = '';
  
          $.ajax({
              type: 'POST',
              url: '{{ route("collection.ajax") }}',
              data: {
                  '_token': '{{ csrf_token() }}',
                  'action': 'get_barangays',
                  'input': $('#municipality').val(),
              },
              success: function(response) {
                  var edit_mnc_code = $('#municipality').attr('data-code'); // for editing f56 receipt
  
                  if(el_val.length > 1 && barangay_code != undefined && select_munic_code != undefined) {
                      if(parseInt(brgy) != parseInt(barangay_code)) {
                          error +=' <strong> Error ! Wrong Barangay Code.</strong>';
                      }
                      if(munic != select_munic_code) {
                          error +=' <strong> Error ! Wrong Municipality Code.</strong><br>';
                      } 
                  } else if (barangay_code == undefined) {
                      error +='<strong> Please select barangay.</strong><br>'
                  } else if(select_munic_code == undefined && $('#municipality_name').length > 0) {
                      error +='<strong> Please select series.</strong><br>'; 
                  } else if($('#municipality_name').length == 0 && edit_mnc_code != undefined && edit_mnc_code > 0) {
                      if(munic != edit_mnc_code) {
                          error +=' <strong> Error ! Wrong Municipality Code.</strong><br>';
                      }
                      if(parseInt(brgy) != parseInt(barangay_code)) {
                          error +=' <strong> Error ! Wrong Barangay Code.</strong>';
                      }
                  }
  
                  if(error != ''){
                      mncpal_brgy_code_error.html(error);
                      mncpal_brgy_code_error.addClass('alert alert-danger alert-dismissible');
                  }
              },
              error: function(response) {
  
              },
          });
      });   
  };
  $.fn.tdarpno();
  
  
  $.fn.changeAmountTotal = function(){
      $(document).ready(function(){
          $.fn.computeAmountTotal();
      });
      $('.basic_current').change(function(){
          $.fn.computeAmountTotal();
      });
  };
  
  $('.basic_penalty_current').change(function(){
      $.fn.computeAmountTotal();
  });
  $('.basic_penalty_previous').change(function(){
      $.fn.computeAmountTotal();
  });
  $('.basic_discount').change(function(){
      $.fn.computeAmountTotal();
  });
  
  $.fn.computeAmountTotal = function(){
      var total = 0;
      $('.basic_current').each(function(index) {
      //   var el_deleted = $('.f56_detail_deleted').eq(index).val();
      //   console.log(el_deleted);
      //   if(el_deleted == undefined){
      //     total += parseFloat($('.basic_current').eq(index).val());
      //   }else{
      //     if(el_deleted == 'false'){
      //       total += parseFloat($('.basic_current').eq(index).val());
      //     }else{
      //       // total += parseFloat($('.basic_current').eq(index).val());
      //     }
      //   }
  
         total += parseFloat($('.basic_current').eq(index).val()) - parseFloat($('.basic_discount').eq(index).val())  + parseFloat($('.basic_previous').eq(index).val()) + parseFloat($('.basic_penalty_current').eq(index).val()) + parseFloat($('.basic_penalty_previous').eq(index).val()) ;
      });
  
      // $('.grand_total_net').each(function(index, amt) {
      //     var el = $(this);
      //     var el_value = Number(el.text());
      //     total += Number($(amt).html());
      // });
  
      $('.amounts').eq(0).val((total*2).toFixed(2));
      $('#total').val((total*2).toFixed(2));
  };
  
  $.fn.changeAmountTotal();
  
  $('#add_row_form56').click( function() {
      var xxx = $('.tdrp_barangay').html();
  
      var add_tdarp = '<tr>'+
                          '<td><input type="text" class="form-control declared_owner" name="declared_owner[]" required data-default-w="100"></td>'+
                          '<td><input type="text" class="form-control tdarpno" name="tdarpno[]" required data-default-w="74"></td>'+
                          '<td>'+
                               '<select class="form-control tdrp_barangay" name="tdrp_barangay[]" id="tdrp_barangay[]" data-default-w="80"  >'+
                                   xxx +
                               '</select>'+
                          '</td>'+
                          '<td>'+
                              '<select class="form-control f56_type" id="f56_type" name="f56_type[]" required data-default-w="113">'+
                                  '<option selected ></option>'+
                                  @foreach ($base['f56_types'] as $type)
                                      '<option value="{{ $type->id }}">{{ $type->name }}</option>'+
                                  @endforeach
                              '</select>'+
                          '</td>'+
                          '<td><input type="text" class="form-control tdrp_assedvalue" name="tdrp_assedvalue[]"  name="tdrp_assedvalue[]" data-default-w="83" ></td>'+
                          '<td><input type="text" class="form-control period_covered" name="period_covered[]" value="{{date('Y')}}" required data-default-w="67"></td>'+
                          '<td>'+
                              '<select class="form-control full_partial" id="full_partial[]" name="full_partial[]" required data-default-w="72">'+
                                  '<option value="0" selected >Full</option>'+
                                  '<option value="1" >Partial - 1st Quarter</option>'+
                                  '<option value="2" >Partial - 2nd Quarter</option>'+
                                  '<option value="3" >Partial - 3rd Quarter</option>'+
                                  '<option value="4" >Partial - 4th Quarter</option>'+
                                  '<option value="5" >Partial Advance</option>'+
                                  '<option value="6" >Balance Settlement</option>'+
                                  '<option value="7" >Backtax</option>'+
                                  '<option value="8" >Additional Payment</option>'+
                              '</select>'+
                          '</td>'+
                          '<td><input type="number" class="form-control basic_current" name="basic_current[]" value="0" min="0" step="0.01" required data-default-w="40"></td>'+
                          '<td><input type="number" class="form-control basic_discount" name="basic_discount[]" value="0" min="0" step="0.01" required data-default-w="40"></td>'+
                          '<td><input type="number" class="form-control basic_previous" name="basic_previous[]" value="0" min="0" step="0.01" required data-default-w="40"></td>'+
                          '<td><input type="number" class="form-control basic_penalty_current" name="basic_penalty_current[]" value="0" min="0" step="0.01" required data-default-w="40"></td>'+
                          '<td><input type="number" class="form-control basic_penalty_previous" name="basic_penalty_previous[]" value="0" min="0" step="0.01" required data-default-w="40"></td>'+
                          '<td class="sefxx"></td>'+
                          '<td class="basicxx"></td>'+
                          '<td class="grand_total_net"></td>'+
                          '<td><button type="button" class="btn btn-warning btn-sm rem_row" ><i class="fa fa-minus"></i></button></td>'+
                      '</tr>';
      // removed from add_tdarp
      // '<td><input type="text" class="form-control tdrp_taxdue" name="tdrp_taxdue[]" data-default-w="83" readonly></td>'+
  
      var tableCount = $('#tablex tr').length;
      var el = $(this);
      var index_el = $('.rem_row_existing').index(this);
      var tablex = $('#tablex tbody tr').eq(index_el);
  
      // if(tableCount == 4){
      //   document.getElementById('add_row_form56').disabled = 'true';
      // }
      /*else if(tablex.hasClass(add_tdarp)){
        document.getElementById('add_row_form56').disabled = 'false';
        $('#tablex').find('tbody').append(add_tdarp);
      }*/
      // else{
        $('#tablex').find('tbody').append(add_tdarp);
      // }
  
      $.fn.tdarpno();
      $.fn.bms_getTDRP();
      $.fn.changeAmountTotal();
      $.fn.computePayment();
      $.fn.animateWidth();
  
  });
  
  $.fn.bms_showTDRPclear = function(){
      $('#tdrp_tax_dec').html('');
  };
  
  $.fn.bms_showTDRP = function(){
      $.ajax({
              url: hostss+'/capitol_rpt/public/api/bms_get_tax_dec_info',
              type: 'POST',
              data:{
                'tax_dec' : $('#tax_dec_no_bms').val()
              },
              dataType: 'html',
              success: (data) => {
                  console.log(data);
                  $('#tdrp_tax_dec').html(data);
  
                  $('#myModal').modal('show');
  
              },
              error: function(response) {
                console.log('arp '+$('#tax_dec_no_bms').val());
                console.log(response);
              }
          });
  };
  
  $.fn_index_of_max_3 = function(el_index_x,el_index_y,el_index_z){
      if (el_index_x > el_index_y) {
          if (el_index_x >  el_index_z) return  el_index_x;
          else        return el_index_z ;
      } else {
          if (el_index_y >  el_index_z) return el_index_y ;
          else        return el_index_z ;
      }
  }
  
  $('#tablex').on('change','.basic_discount', function(){
    var dis_index = $('.basic_discount').index(this);
    var sef_new = parseFloat($('.basic_current').eq(dis_index).val());
    var total = sef_new + parseFloat($('.basic_previous').eq(dis_index).val()) + parseFloat($('.basic_penalty_current').eq(dis_index).val()) + parseFloat($('.basic_penalty_previous').eq(dis_index).val()) - parseFloat($('.basic_discount').eq(dis_index).val());
    var grand = total * 2;
  
    $('.sefxx').eq(dis_index).text(total.toFixed(2));
    $('.basicxx').eq(dis_index).text(total.toFixed(2));
    $('.grand_total_net').eq(dis_index).text(grand.toFixed(2));
  });
  
  $('#tablex').on('change','.basic_previous', function(){
    var dis_index = $('.basic_previous').index(this);
    var sef_new = parseFloat($('.basic_current').eq(dis_index).val());
    var total = sef_new + parseFloat($('.basic_previous').eq(dis_index).val()) +parseFloat($('.basic_penalty_current').eq(dis_index).val()) + parseFloat($('.basic_penalty_previous').eq(dis_index).val()) - parseFloat($('.basic_discount').eq(dis_index).val());
    var grand = total * 2;
  
    $('.sefxx').eq(dis_index).text(total.toFixed(2));
    $('.basicxx').eq(dis_index).text(total.toFixed(2));
    $('.grand_total_net').eq(dis_index).text(grand.toFixed(2));
  });
  
  $('#tablex').on('change','.basic_penalty_current', function(){
    var dis_index = $('.basic_penalty_current').index(this);
    var sef_new = parseFloat($('.basic_current').eq(dis_index).val());
    var total = sef_new + parseFloat($('.basic_previous').eq(dis_index).val()) +parseFloat($('.basic_penalty_current').eq(dis_index).val()) + parseFloat($('.basic_penalty_previous').eq(dis_index).val()) - parseFloat($('.basic_discount').eq(dis_index).val());
    var grand = total * 2;
  
    $('.sefxx').eq(dis_index).text(total.toFixed(2));
    $('.basicxx').eq(dis_index).text(total.toFixed(2));
    $('.grand_total_net').eq(dis_index).text(grand.toFixed(2));
  });
  
  $('#tablex').on('change','.basic_penalty_previous', function(){
    var dis_index = $('.basic_penalty_previous').index(this);
    var sef_new = parseFloat($('.basic_current').eq(dis_index).val());
    var total = sef_new + parseFloat($('.basic_previous').eq(dis_index).val()) +parseFloat($('.basic_penalty_current').eq(dis_index).val()) + parseFloat($('.basic_penalty_previous').eq(dis_index).val()) - parseFloat($('.basic_discount').eq(dis_index).val());
    var grand = total * 2;
  
    $('.sefxx').eq(dis_index).text(total.toFixed(2));
    $('.basicxx').eq(dis_index).text(total.toFixed(2));
    $('.grand_total_net').eq(dis_index).text(grand.toFixed(2));
  });
  
  $('#tablex').on('change','.basic_current', function(){
    var dis_index = $('.basic_current').index(this);
    var sef_new = parseFloat($('.basic_current').eq(dis_index).val());
    var total = sef_new + parseFloat($('.basic_previous').eq(dis_index).val()) +parseFloat($('.basic_penalty_current').eq(dis_index).val()) + parseFloat($('.basic_penalty_previous').eq(dis_index).val()) - parseFloat($('.basic_discount').eq(dis_index).val());
    var grand = total * 2;
  
    $('.sefxx').eq(dis_index).text(total.toFixed(2));
    $('.basicxx').eq(dis_index).text(total.toFixed(2));
    $('.grand_total_net').eq(dis_index).text(grand.toFixed(2));
  });
  
  // here dati
  
  $.fn.computePayment = function(){
      // $('.tdrp_assedvalue, .period_covered, .full_partial').change(function(){
      $('.tdrp_assedvalue, .period_covered, .full_partial, #date_timex').on('change keyup focusout', function(){
          var el_index_x = $('.tdrp_assedvalue').index(this);
          var el_index_y = $('.period_covered').index(this);
          var el_index_z = $('.full_partial').index(this);
  
          var el_index = $.fn_index_of_max_3(el_index_x,el_index_y,el_index_z);
  
          var arp = $('.tdarpno').eq(el_index).val();
          var split = arp.split("-");
  
          if($('.tdarpno').eq(el_index).val() != '' && $('.tdrp_assedvalue').eq(el_index).val() != '' && split[0] >= 94){
            $('.sefxx').eq(el_index).empty();
            $('.basicxx').eq(el_index).empty();
            $('.grand_total_net').eq(el_index).empty();
  
              var dx = [];
                  var period_covered = $('.period_covered').eq(el_index);
                  var assessed_value = $('.tdrp_assedvalue').eq(el_index);
                      $.ajax({
                          url: "{{route('form56.form56_compute_benedict')}}",
                          type: 'POST',
                          data:{
                            '_token' : '{{ csrf_token() }}',
                            'assessed_value' :  parseFloat(assessed_value.val()),
                            'type_p' :  $('.full_partial').eq(el_index).val(),
                            'p_years' :  period_covered.val(),
                            'tdrpno' :  $('.tdarpno').eq(el_index).val(),
                            'date_of_entry' : $('#date_timex').val(),
                            'isEdit' : $('#isEdit').val(),
                          },
                          dataType: 'json',
                          success: (data) => {
                               // var sef_basic = parseFloat(data['basic_current'].toFixed(2)) - parseFloat(data['basic_discount'].toFixed(2)) + parseFloat(data['basic_previous'].toFixed(2)) + parseFloat(data['basic_penalty_current'].toFixed(2)) + parseFloat(data['basic_penalty_previous'].toFixed(2));
                               var tax_due = parseFloat(assessed_value.val())*.01;
                               var full_partial_val = $('.full_partial').eq(el_index).val();
                               
                              $('.basic_current').eq(el_index).val(data['basic_current'].toFixed(2));
                              $('.basic_previous').eq(el_index).val(data['basic_previous'].toFixed(2));
  
                              if(full_partial_val == 5 || full_partial_val == 7 || full_partial_val == 8) {
                                var sef_basic = parseFloat(data['basic_current'].toFixed(2)) + parseFloat(data['basic_previous'].toFixed(2));
                                $('.basic_discount').eq(el_index).val(0.00);
                                $('.basic_penalty_current').eq(el_index).val(0.00);
                                $('.basic_penalty_previous').eq(el_index).val(0.00);
                              } else {
                                var sef_basic = parseFloat(data['basic_current'].toFixed(2)) - parseFloat(data['basic_discount'].toFixed(2)) + parseFloat(data['basic_previous'].toFixed(2)) + parseFloat(data['basic_penalty_current'].toFixed(2)) + parseFloat(data['basic_penalty_previous'].toFixed(2));
                                $('.basic_discount').eq(el_index).val(data['basic_discount'].toFixed(2));
                                $('.basic_penalty_current').eq(el_index).val(data['basic_penalty_current'].toFixed(2));
                                $('.basic_penalty_previous').eq(el_index).val(data['basic_penalty_previous'].toFixed(2));
                              }
                              
                              var grand_total_net = parseFloat(sef_basic.toFixed(2)) *2;
  
                              $('.sefxx').eq(el_index).text(sef_basic.toFixed(2));
                              $('.basicxx').eq(el_index).text(sef_basic.toFixed(2));
                              $('.grand_total_net').eq(el_index).text(grand_total_net.toFixed(2));
                              // $('.tdrp_taxdue').eq(el_index).val(tax_due.toFixed(2));
  
                              $.fn.computeAmountTotal();
                          }
                      });
  
                      // if(p_years.length > 1){
                      //         if(parseInt(p_years[0]) > parseInt(p_years[1])){
                      //             xxx = parseInt(p_years[0])-parseInt(p_years[1])+1;
                      //             y_start = parseInt(p_years[1]);
                      //         }else{
                      //             xxx =  parseInt(p_years[1])-parseInt(p_years[0])+1;
                      //             y_start = parseInt(p_years[0]);
                      //         }
                      // }else{
                      //     xxx = 1;
                      //     y_start = parseInt(p_years[0]);
                      // }
                      //        dx['type_p'] = $('.full_partial').eq(el_index).val();
                      //        dx['assessed_value'] = parseFloat(assessed_value.val());
                      //     var p_years = period_covered.val().split('-');
                      //     var total_x = 0;
                      //     var xxx = 0;
                      //     var y_start = 0;
  
                       // var current_year = moment().format('YYYY') ;
                       // var val_currentyear = 0;
                       // var val_prevuiosyear = 0;
                       // var val_advanceyear = 0;
                       // var val_discount = 0;
                       // var val_penaltyprevyear = 0;
                       // var val_penaltycurrentyear = 0;
                       // for( var x = 0; x < xxx; x++ ){
                       //    var new_date = moment('01/01/'+(y_start+x), "MM/DD/YYYY");
                       //    var monthDiff = get_month_diff(new Date( new_date.format('MM/DD/YYYY') ) , new Date() );
                       //    dx['monthDiff'] = monthDiff;
                       //    console.log(monthDiff);
                       //    var f;
                       //    if(current_year == (y_start+x)){
                       //          f =  $.fn.currentYear(dx);
                       //    }else if(current_year > (y_start+x) ){
                       //          f =  $.fn.previuosYear(dx);
                       //    }else if(current_year < (y_start+x)){
                       //          f =  $.fn.advanceYear(dx);
                       //    }
                       //    console.log(f);
                       // }
          } else if(split[0] < 94) {
            // $('.sefxx').eq(el_index).empty();
            // $('.basicxx').eq(el_index).empty();
            // $('.grand_total_net').eq(el_index).empty();
  
            // $('.sefxx').eq(el_index).append('<input type="number" class="form-control" name="manual_sef[]" required data-default-w="40">');
            // $('.basicxx').eq(el_index).append('<input type="number" class="form-control" name="manual_basic[]" required data-default-w="40">');
            // $('.grand_total_net').eq(el_index).append('<input type="number" class="form-control" name="gtotal_basic[]" required data-default-w="40">');
  
            $('.basic_current').eq(el_index).val(0);
            $('.basic_discount').eq(el_index).val(0);
            $('.basic_previous').eq(el_index).val(0);
            $('.basic_penalty_current').eq(el_index).val(0);
            $('.basic_penalty_previous').eq(el_index).val(0);
  
            $('.sefxx').eq(el_index).text(0);
            $('.basicxx').eq(el_index).text(0);
            $('.grand_total_net').eq(el_index).text(0);
          }
      });
  };
  
  $('.basic_current, .basic_discount, .basic_previous, .basic_penalty_current, .basic_penalty_previous').change(function() {
    var el_index = $('.tdarpno').index(this);
    var sef_basic = parseFloat($('.basic_current').eq(el_index).val()) - parseFloat($('.basic_discount').eq(el_index).val()) + parseFloat($('.basic_previous').eq(el_index).val()) + parseFloat($('.basic_penalty_current').eq(el_index).val()) + parseFloat($('.basic_penalty_previous').eq(el_index).val());
    $('.sefxx').eq(el_index).text(parseFloat(sef_basic).toFixed(2));
    $('.basicxx').eq(el_index).text(parseFloat(sef_basic).toFixed(2));
    $('.grand_total_net').eq(el_index).text(parseFloat(sef_basic*2).toFixed(2));
  });
  
  $.fn.computePayment();
  
  $(document).on('focusout', '#date_timex', function() {
      $.fn.computePayment();
  });
  
  $.fn.bms_getTDRP = function(){
  $('.tdarpno').change(function(){
      var el = $(this);
      var el_index_x = $('.tdarpno').index(this);
      var el_index_y = $('.period_covered').index(this);
      var el_index_z = $('.full_partial').index(this);
  
      var el_index = $.fn_index_of_max_3(el_index_x,el_index_y,el_index_z);
  
      if($('.tdarpno').eq(el_index).val() != ''){
              $.ajax({
                url: hostss+'/capitol_rpt/public/api/bms_get_tax_dec_details',
                type: 'POST',
                data:{
                  'tax_dec' : $('.tdarpno').eq(el_index).val(),
                  'period' : $('.period_covered').eq(el_index).val(),
                  'full_partial' : $('.full_partial').eq(el_index).val(),
                },
                dataType: 'json',
                success: (data) => {
                    $('.tdarpno').eq(el_index).siblings().remove();
                    if(data[0] == 'TAX DEC FOUND'){
                      //for(var ii = 0; ii < data[1].count_class_type; ii++) { // split AV/based on encoded data
                        $('.f56_type').eq(el_index).find('option').each(function(i) {
                          // if($(this).text() == data[1].f56_type[ii]){ // split AV/based on encoded data
                          if($(this).text() == data[1].f56_type){ 
                            $(this).prop('selected', true);
                          }
                        });
                        var owners = data[1].owner;
  
                        // if() {
                        //   var co_owners = data[1].co_owner.split('\r\n');
                        //   $(co_owners).each(function(key, name) {
                        //   if(co_owners.length > 0) {
                        //       owners += ',' + name;
                        //       console.log(owners);
                        //     }
                        //   });
                        // }
                        
                        // $('.declared_owner').eq(el_index).val(data[1].owner);
                        $('.declared_owner').eq(el_index).val(owners);
                        
                        // split AV/based on encoded data
                        // $('.tdrp_assedvalue').eq(el_index).val(data[1].assessed_value[ii]);
                        // $('.tdrp_assedvalue').eq(el_index).attr('value', data[1].assessed_value[ii]);
                        // $('.tdrp_assedvalue').eq(el_index).attr('data-value', data[1].assessed_value[ii]);
  
                        // lumped AV total
                        $('.tdrp_assedvalue').eq(el_index).val(data[1].assessed_value);
                        $('.tdrp_assedvalue').eq(el_index).attr('value', data[1].assessed_value);
                        $('.tdrp_assedvalue').eq(el_index).attr('data-value', data[1].assessed_value);
  
                        $('.tdrp_assedvalue').eq(el_index).trigger('change');
  
                        // $('.basic_current').eq(el_index).val(data[1].assessed_value_gross);
                        // $('.basic_discount').eq(el_index).val(data[1].discounted);
                        // $('.basic_penalty_current').eq(el_index).val(data[1].penalty);
                        $('.tdrp_barangay').eq(el_index).find('option').each(function(){
                          var opt_brgy = $(this);
                          if(opt_brgy.val() == data[1].brgy){
                              opt_brgy.attr('data-code', data[1].brgy_code);
                              opt_brgy.attr('selected', true);
                              $('.tdrp_barangay').eq(el_index).trigger('change');
                          }
                        });
                        $.fn.computeAmountTotal();
  
                        // split AV/based on encoded data  
                        // insert new row
                        //if(ii < data[1].count_class_type-1) {              
                          // var xxx = $('.tdrp_barangay').html();
                          // var add_tdarp = '<tr>'+
                          //     '<td><input type="text" class="form-control declared_owner" name="declared_owner[]" required data-default-w="100"></td>'+
                          //     '<td><input type="text" class="form-control tdarpno" name="tdarpno[]" required data-default-w="74" value="'+ $('.tdarpno').val() +'"></td>'+
                          //     '<td>'+
                          //          '<select class="form-control tdrp_barangay" name="tdrp_barangay[]" id="tdrp_barangay[]" data-default-w="80"  >'+
                          //              xxx +
                          //          '</select>'+
                          //     '</td>'+
                          //     '<td>'+
                          //         '<select class="form-control f56_type" id="f56_type" name="f56_type[]" required data-default-w="113">'+
                          //             '<option selected ></option>'+
                          //             @foreach ($base['f56_types'] as $type)
                          //                 '<option value="{{ $type->id }}">{{ $type->name }}</option>'+
                          //             @endforeach
                          //         '</select>'+
                          //     '</td>'+
                          //     '<td><input type="text" class="form-control tdrp_assedvalue" name="tdrp_assedvalue[]"  name="tdrp_assedvalue[]" data-default-w="83" ></td>'+
                          //     '<td><input type="text" class="form-control period_covered" name="period_covered[]" value="{{date('Y')}}" required data-default-w="67"></td>'+
                          //     '<td>'+
                          //         '<select class="form-control full_partial" id="full_partial[]" name="full_partial[]" required data-default-w="72">'+
                          //             '<option value="0" selected >Full</option>'+
                          //             '<option value="1" >Partial - 1st Quarter</option>'+
                          //             '<option value="2" >Partial - 2nd Quarter</option>'+
                          //             '<option value="3" >Partial - 3rd Quarter</option>'+
                          //             '<option value="4" >Partial - 4th Quarter</option>'+
                          //             '<option value="5" >Partial Advance</option>'+
                          //             '<option value="6" >Balance Settlement</option>'+
                          //             '<option value="7" >Backtax</option>'+
                          //             '<option value="8" >Additional Payment</option>'+
                          //         '</select>'+
                          //     '</td>'+
                          //     '<td><input type="number" class="form-control basic_current" name="basic_current[]" value="0" min="0" step="0.01" required data-default-w="40"></td>'+
                          //     '<td><input type="number" class="form-control basic_discount" name="basic_discount[]" value="0" min="0" step="0.01" required data-default-w="40"></td>'+
                          //     '<td><input type="number" class="form-control basic_previous" name="basic_previous[]" value="0" min="0" step="0.01" required data-default-w="40"></td>'+
                          //     '<td><input type="number" class="form-control basic_penalty_current" name="basic_penalty_current[]" value="0" min="0" step="0.01" required data-default-w="40"></td>'+
                          //     '<td><input type="number" class="form-control basic_penalty_previous" name="basic_penalty_previous[]" value="0" min="0" step="0.01" required data-default-w="40"></td>'+
                          //     '<td class="sefxx"></td>'+
                          //     '<td class="basicxx"></td>'+
                          //     '<td class="grand_total_net"></td>'+
                          //     '<td><button type="button" class="btn btn-warning btn-sm rem_row" ><i class="fa fa-minus"></i></button></td>'+
                          //   '</tr>';
                          //     // removed from add_tdarp
                          //     // '<td><input type="text" class="form-control tdrp_taxdue" name="tdrp_taxdue[]" data-default-w="83" readonly></td>'+
                          //   var tableCount = $('#tablex tr').length;
                          //   var el = $(this);
                          //   var index_el = $('.rem_row_existing').index(this);
                          //   var tablex = $('#tablex tbody tr').eq(index_el);
  
                          //   $('#tablex').find('tbody').append(add_tdarp);
  
                          //   $.fn.tdarpno();
                          //   $.fn.bms_getTDRP();
                          //   $.fn.changeAmountTotal();
                          //   $.fn.computePayment();
                          //   $.fn.animateWidth();
  
                          //   el_index++;
                        //} 
                      //} // split AV/based on encoded data
                      // end for
                    } else {
                      // swal({
                      //      title: 'NOT FOUND!',
                      //      text: 'TAX DEC NOT FOUND PLEASE ENTER DETAILS MANUALLY',
                      //      timer: 5000,
                      //      type: 'error',
                      //    })
  
                      $('.tdarpno').eq(el_index).closest('td').append('<small id="notd" style="color: #f01f1f;">TAX DEC NOT FOUND PLEASE ENTER DETAILS MANUALLY</small>');
                    }
                }
            });
      }
  
  
  });
  };
  $.fn.bms_getTDRP();
  
  $.fn.loadTable = function(){
    if ( $.fn.DataTable.isDataTable('#seriallist') ) {
       $('#seriallist').DataTable().destroy();
      }
      $('#seriallist').dataTable({
          dom: '<"dt-custom">frtip',
          processing: true,
          serverSide: true,
          ajax: { 'url' : '{{ route("collection.datatables", "form56") }}',
                  'data' : {'show_year' : $('#show_year').val() }
              },
          columns: [
              { data: 'realname', name: 'realname' },
              { data: 'mun_name', name: 'mun_name' },
              { data: 'brgy_name', name: 'brgy_name',
                      render: function(data) {
                      if(data)
                          return data;
                      else
                          return 'multiple barangay';
                  },
              },
              { data: 'serial_no', name: 'serial_no' },
             { data: 'date_of_entry',name : 'date_of_entry',
                    render: function(data) {
                        var d = moment(data);
                      return d.format('MMMM DD, YYYY HH:mm:ss');
                  },
                  bSortable: true,
                  searchable : true,
              },
              { data: 'name', name: 'name' },
              { data:
                  function(data) {
                      var status = '';
                      if (data.is_cancelled == 1) {
                          status = 'Cancelled';
                      } else if (data.is_printed == 1) {
                          status = 'Issued';
                      }
                      return status;
                  }
              },
              { data:
                  function(data) {
                      var view = '';
                      var write = '';
                      var cert = '';
                      var another = '';
                       var restore = '';
  
                      @if ( Session::get('permission')['col_field_land_tax'] & $base['can_read'] )
                      view = '<a href="{{ route('form56.view',"") }}/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="View"><i class="fa fa-eye"></i></a>';
                      @endif
  
                      @if ( Session::get('permission')['col_field_land_tax'] & $base['can_write'] )
                      write = (data.is_cancelled == 0) ? '<a href="{{ route('form56.edit',"") }}/'+data.id+'" class="btn btn-sm btn-info datatable-btn" title="Edit"><i class="fa fa-pencil-square-o"></i></a>' : '';
                      @endif
  
                       if(data.process_status == '0'){
  
                          }else if(data.process_status == '1'){
                               cert = (data.is_cancelled == 0) ? '<a href="{{ route('receipt.index') }}/'+data.id+'/certificate?types=field" class="btn btn-sm btn-green datatable-btn" title="'+data.cert_type+' Certificate"><i class="fa fa-certificate"></i></a>' : '';
                          }else if(data.col_rcpt_certificate_type_id != null){
                               cert = (data.is_cancelled == 0) ? '<a href="{{ route('receipt.index') }}/'+data.id+'/certificate?types=field" class="btn btn-sm btn-green datatable-btn" title="'+data.cert_typex+' Certificate"><i class="fa fa-certificate"></i></a>' : '';
                          }else{
                              cert = (data.is_cancelled == 0) ? '<a href="{{ route('receipt.index') }}/'+data.id+'/certificate?types=field" class="btn btn-sm btn-gray datatable-btn" title="Certificate"><i class="fa fa-certificate"></i></a>' : '';
                          }
  
                       @if ( Session::get('permission')['col_receipt'] & $base['can_write'] )
                         if(data.is_printed == 1){
                              if(data.col_receipt_serial_parent == null)
                                  another = (data.is_cancelled == 0) ? '<a href="{{ route('receipt.index') }}/'+data.id+'/another?types=field" class="btn btn-sm btn-another-none datatable-btn" title="ANOTHER RECEIPT"><i class="fa fa-plus"></i></a>' : '';
                              else
                                  if(data.col_receipt_serial_parent == data.serial_no ){
                                  another = (data.is_cancelled == 0) ? '<a href="{{ route('receipt.index') }}/'+data.id+'/another?types=field" class="btn btn-sm btn-another datatable-btn" title="PARENT : '+data.col_receipt_serial_parent+'"><i class="fa fa-plus"></i></a>' : '';
                              }else{
                                  another = '<button class="btn btn-sm btn-another datatable-btn" title="PARENT : '+data.col_receipt_serial_parent+'">  '+data.col_receipt_serial_parent+'</button>';
                              }
                         }
  
  
                      @endif
  
                      @if(session::get('user')->position == 'Administrator')
                           if (data.is_cancelled == 1) {
                              restore = '<a href="{{ route('receipt.index') }}/'+data.id+'/restore" class="btn btn-sm btn-warning datatable-btn" title="Restore : '+data.serial_no+'"><i class="fa fa-undo"></i></a>';
                          }
                           if (data.is_cancelled == 1) {
                              restore = '<button class="btn btn-sm btn-warning datatable-btn" title="Restore : '+data.serial_no+'" onclick="$(this).restore(\''+data.id+'\');" ><i class="fa fa-undo"></i></a>';
                          }
                      @endif
  
                      return view + write + restore;
  
                  },
                  bSortable: false,
                  searchable: false,
              },
          ],
          order : [[ 4, "desc" ]]
      });
   };
  $.fn.loadTable();
  </script>
  {{ Html::script('/vendor/autocomplete/jquery.autocomplete.js') }}
  <script type="text/javascript">
      var collection_type = 'show_in_fieldlandtax';
  
       $.fn.restore = function(id){
  
          swal({
                title: 'Are you sure?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#a22314',
                cancelButtonColor: '#c9bebe',
                confirmButtonText: 'Yes, restore it!'
              }).then((result) => {
                if (result.value) {
                      $.ajax({
                          url: '{{ route("receipt.restore") }}',
                          type: 'POST',
                          data:{
                            receipt: id,
                            _token: '{{ csrf_token() }}'
                          },
                          dataType: 'JSON',
                          success: (data) => {
                          }
                      });
                  swal({
                        title: 'Restored!',
                        text: 'RECEIPT RESTORED',
                        timer: 1000,
                        onOpen: () => {
                          swal.showLoading()
                        }
                      }).then((result) => {
                        if (result.dismiss === 'timer') {
                          $.fn.loadTable();
                        }
                      })
  
  
                }
              });
      };
  
      $(document).on('change', '.full_partial', function() {
        var fp_type = $(this).val();
        // var ref_num_block = document.getElementById('ref_num_input');
        var ref_num_block = $(this).closest('td').find('#ref_num_input');
  
        if(fp_type == 6) {
          if(ref_num_block != null) 
            $(ref_num_block).remove();
          $(this).closest('td').append('<div id="ref_num_input"><i class="fa fa-info-circle" title="For Balance Settlements, please specify the OR number with partial payment that this payment would settle"></i>&nbsp;Referred OR&nbsp;<input class="form-control" type="number" id="ref_num" name="ref_num[]"></input></div>');
        } else if(fp_type == 8) {
          if(ref_num_block != null) 
            $(ref_num_block).remove();
          $(this).closest('td').append('<div id="ref_num_input"><i class="fa fa-info-circle" title="For Additional Payments, please specify the OR number with incomplete payment that this payment would settle"></i>&nbsp;Referred OR&nbsp;<input class="form-control" type="number" id="ref_num" name="ref_num[]"></input></div>');
        } else {
          if(ref_num_block != null)  
            $(ref_num_block).remove();
        }
      });
  
      // $(document).on('change', '#customer', function() {
      //   $('#prev_rcpt_info').html('');
      //   $('#prev_receipt_no').val('');
      //   $('#prev_date').val('');
      //   $('#prev_for_the_year').val('');
      //   $.ajax({
      //     'url': "{{ route('f56.rcpt_prev') }}",
      //     'data': {
      //       'client_id' : $('#customer_id').val(),
      //       'tax_dec' : $('#prev_tdarp').val(),
      //     },
      //     'success': function(data) {
      //       if($('#customer_id').val() != "" || $('#prev_tdarp').val() != "") {
      //         // $('#prev_tdarp').val('');
  
      //         if($('#customer_id').val() != "" && $('#prev_tdarp').val() != "") {
      //           $('#prev_rcpt_info').append('<br> Note: For searching the previous receipt based on the payor name, please leave input field for Tax Declaration No. blank , otherwise the system will search for the previous receipt based on the Tax Declaration No. provided');
      //         }
      //         // if(($('#customer_id').val() != "" && $('#prev_tdarp').val() == "") || ($('#customer_id').val() == "" && $('#prev_tdarp').val() != "")) {
      //           if(data != null && data != undefined && data != "") {
      //             if(data.period_covered == "" && data.prev_receipt_no == "" && data.prev_date == "") {
      //               $('#prev_rcpt_info').append('<br> No previous Form 56 transaction found for the specified Tax Declaration number');
      //             } else {
      //               $('#prev_receipt_no').val(data.serial_no);
      //               $('#prev_date').val(data.date_of_entry);
      //               $('#prev_receipt_no').val(data.prev_receipt_no);
      //               $('#prev_date').val(data.prev_date);
  
      //               if(data.period_covered != null && data.period_covered != undefined)
      //                 $('#prev_for_the_year').val(data.period_covered);
      //             }
      //           } else {
      //             $('#prev_rcpt_info').append('<br> No previous Form 56 transaction found for the specified Tax Declaration number');
      //           }
      //         // }
      //       }
      //     }
      //   });
      // });
  
      $(document).on('focus', '#prev_receipt_no', function() {
        $('#prev_tdarp').empty();
        $('#prev_tdarp').css('box-shadow', '1px 1px 15px #0c94ef');
        $('#prev_tdarp').parent().append('<small>Search for the previous receipt by providing the Tax Declaration No. of the payor</small>');
      });
  
      $(document).on('focus', '#prev_date', function() {
        $('#prev_tdarp + small').remove();
        $('#prev_tdarp').css('box-shadow', '1px 1px 15px #0c94ef');
        $('#prev_tdarp').parent().append('<small>Search for the previous receipt by providing the Tax Declaration No. of the payor</small>');
      });
  
      $(document).on('focus', '#prev_for_the_year', function() {
        $('#prev_tdarp + small').remove();
        $('#prev_tdarp').css('box-shadow', '1px 1px 15px #0c94ef');
        $('#prev_tdarp').parent().append('<small>Search for the previous receipt by providing the Tax Declaration No. of the payor</small>');
      });
  
      $(document).on('change', '#customer', function() {
        $('#prev_tdarp + small').remove();
        $('#prev_tdarp').css('box-shadow', '1px 1px 15px #0c94ef');
        $('#prev_tdarp').parent().append('<small>Search for the previous receipt by providing the Tax Declaration No. of the payor</small>');
      });
  
      $(document).on('focusout', '#prev_receipt_no', function() {
        $('#prev_tdarp + small').remove();
        $('#prev_tdarp').css('box-shadow', 'none');
      });
  
      $(document).on('focusout', '#prev_date', function() {
        $('#prev_tdarp + small').remove();
        $('#prev_tdarp').css('box-shadow', 'none');
      });
  
      $(document).on('focusout', '#prev_for_the_year', function() {
        $('#prev_tdarp + small').remove();
        $('#prev_tdarp').css('box-shadow', 'none');
      });
  
      $(document).on('change', '#prev_tdarp', function() {
        $('#prev_rcpt_info').html('');
        $('#prev_receipt_no').val('');
        $('#prev_date').val('');
        $('#prev_for_the_year').val('');
        $.ajax({
          'url': "{{ route('f56.rcpt_prev') }}",
          'data': {
            'client_id' : $('#customer_id').val(),
            'tax_dec' : $('#prev_tdarp').val(),
          },
          'success': function(data) {
            if($('#customer_id').val() != "" || $('#prev_tdarp').val() != "") {
              // if($('#customer_id').val() != "" && $('#prev_tdarp').val() != "") {
              //   $('#prev_rcpt_info').append('<br> Note: For searching the previous receipt based on the payor name, please leave input field for Tax Declaration No. blank , otherwise the system will search for the previous receipt based on the Tax Declaration No. provided');
              // }
              // if(($('#customer_id').val() != "" && $('#prev_tdarp').val() == "") || ($('#customer_id').val() == "" && $('#prev_tdarp').val() != "")) {
                if(data != null && data != undefined && data != "") {
                  if(data.period_covered == "" && data.prev_receipt_no == "" && data.prev_date == "") {
                    $('#prev_rcpt_info').append('<br> No previous Form 56 transaction found for the specified Tax Declaration number');
                  } else {
                    $('#prev_receipt_no').val(data.serial_no);
                    $('#prev_date').val(data.date_of_entry);
                    $('#prev_receipt_no').val(data.prev_receipt_no);
                    $('#prev_date').val(data.prev_date);
  
                    if(data.period_covered != null && data.period_covered != undefined)
                      $('#prev_for_the_year').val(data.period_covered);
                  }
                } else {
                  $('#prev_rcpt_info').append('<br> No previous Form 56 transaction found for the specified Tax Declaration number');
                }
              // }
            }
          }
        });
      });
  
      // $.fn.prevno_autocomplete = function(receipts) {
      //   $('#prev_receipt_no').autocomplete({
      //     source: receipts,
      //     autoFocus: true,
      //     select: function (event, ui) {
      //       console.log('test1');
      //     }
      //   });
      // }
      
      $('#tablex').on('change', '.tdarpno', function() {
        var dis_index = $('.tdarpno').index(this);
        var arp = $('.tdarpno').eq(dis_index).val();
        var split = arp.split("-");
  
      // var el_index_x = $('.tdrp_assedvalue').index(this);
      // var el_index_y = $('.period_covered').index(this);
      // var el_index_z = $('.full_partial').index(this);
      // var el_index = $.fn_index_of_max_3(el_index_x,el_index_y,el_index_z);
      // $('.tdrp_assedvalue').eq(el_index).val()
  
        if(split[0] >= 94) {
          $('.sefxx').eq(dis_index).empty();
          $('.basicxx').eq(dis_index).empty();
          $('.grand_total_net').eq(dis_index).empty();
          // orig
          var sef_new = parseFloat($('.basic_current').eq(dis_index).val());
          var total = sef_new + parseFloat($('.basic_previous').eq(dis_index).val()) +parseFloat($('.basic_penalty_current').eq(dis_index).val()) + parseFloat($('.basic_penalty_previous').eq(dis_index).val()) - parseFloat($('.basic_discount').eq(dis_index).val());
          var grand = total * 2;
  
          $('.sefxx').eq(dis_index).text(total.toFixed(2));
          $('.basicxx').eq(dis_index).text(total.toFixed(2));
          $('.grand_total_net').eq(dis_index).text(grand.toFixed(2));
  
      // here
          if($('#tablex thead tr').children().length > 16) {
            $('#tablex thead tr').children().last().remove();
            var trow = $('.tdarpno').eq(dis_index).closest('tr');
            $(trow).children().last().remove();
          }
          $('.tdarpno').eq(dis_index).closest('tr').removeClass('warning');
          // $('.tdrp_taxdue').eq(dis_index).prop('readonly', true);
          // $('.tdrp_taxdue').eq(dis_index).prop('required', false);
        } else {
          $('.sefxx').eq(dis_index).empty();
          $('.basicxx').eq(dis_index).empty();
          $('.grand_total_net').eq(dis_index).empty();
  
          // $('.tdrp_taxdue').eq(dis_index).prop('readonly', false);
          // $('.tdrp_taxdue').eq(dis_index).prop('required', true);
  
          $('.tdarpno').eq(dis_index).closest('tr').addClass('warning');
          var trow = $('.tdarpno').eq(dis_index).closest('tr');
          $('#tablex thead tr').append('<th></th>');
          $(trow).append('<td><span class="glyphicon glyphicon-info-sign" title="Note: For ARP\'s starting with 93 and below, the values entered will be printed as it is and will not computed by the system" style="display: inline;"></span></td>');
        }
        $.fn.animateWidth();
      });
  </script>
  