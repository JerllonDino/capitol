{{ Html::script('/vendor/autocomplete/jquery.autocomplete.js') }}
<script type="text/javascript">


$.fn.dailyTimex = function(){
        var check_auto_timer = $('#auto_timer');
        if(!check_auto_timer.is(":checked")){

        }else{
            var timex = moment(  ).format('MM/DD/YYYY HH:mm') ;
            $('#date_timex').val(timex);
        }
};
 setInterval( $.fn.dailyTimex, 100 );

compute_total();

//for ada
@if (isset($base['ada_settings']))
    var ada_bank_name = '{{ $base['ada_settings'][0]->value }}';
    var ada_bank_number = '{{ $base['ada_settings'][1]->value }}';
@else
    var ada_bank_name = '';
    var ada_bank_number = '';
@endif

$('.datepicker').datetimepicker({
    format: 'MM/DD/YYYY HH:mm'
});

$('.datepicker2').datetimepicker({
    format: 'YYYY-MM-DD'
});

$('.datepicker3').datetimepicker({
    format: 'YYYY'
});

$('#transaction_type').change( function() {
    if ($(this).val() == 2 || $(this).val() == 3 || $(this).val() == 4 || $(this).val() == 5) {
        // check, money order or ADA
        $('.bank_input').attr('disabled', false)
            .attr('required', true);
        $('#bank_remark').attr('required', false);
        $('#bank_to_remarks').attr('disabled', false);
    } else {
        $('.bank_input').val('').attr('disabled', true);
        $('#bank_to_remarks').attr('disabled', true);
    }

    if ($(this).val() == 4) {
        $('#bank_name').val(ada_bank_name);
        $('#bank_number').val(ada_bank_number);
    } else {
        $('#bank_name').val('');
        $('#bank_number').val('');
    }
});

$('#bank_to_remarks').click( function() {
    tinymce.activeEditor.execCommand('mceInsertContent', false, $('#bank_name').val() +' '+ $('#bank_number').val() +' '+ $('#bank_date').val());
});

// ajax customer/payor name and id
var customers = [];
var customer_ids = [];
var orig_c_type = $('#customer_type').children();
$('#customer').keyup( function() {
    $.ajax({
        type: 'POST',
        url: '{{ route("collection.ajax") }}',
        data: {
            '_token': '{{ csrf_token() }}',
            'action': 'get_customer',
            'input': $('#customer').val(),
        },
        success: function(response) {
            customers = [];
            customer_ids = [];
            type = [];
            bank_remark = [];
            remark = []; 
            ctype = [];
            br = [];
            rem = [];
            bank_rem = [];
            acct_type = [];
            $('#customer_id').val('');
            var last_ctype = [];
            $.each(response, function(key, customer) {
                customers.push(customer.name);
                customer_ids.push(customer.id);

                ctype[customer.id] = [];
                br[customer.id] = [];
                rem[customer.id] = [];
                bank_rem[customer.id] = [];
                acct_type[customer.id] = [];
                last_ctype[customer.id] = [];
                // var ctype = customer.latest_receipt.length === 0 ? 0 : customer.latest_receipt[0]['client_type'] ;
                // var br = customer.latest_receipt.length === 0 ? "" : customer.latest_receipt[0]['bank_remark'] ;
                // var rem = customer.latest_receipt.length === 0 ?  "" : customer.latest_receipt[0]['remarks'] ;
                var all_rcpt_length = customer.all_receipt.length-1;

                for(var i = 0; i < customer.all_receipt.length; i++) {
                    ctype[customer.id].push(customer.all_receipt.length === 0 ? 0 : (customer.all_receipt[i]['client_type'] != null ? customer.all_receipt[i]['client_type'] : 0));
                    br[customer.id].push(customer.all_receipt.length === 0 ? "" : customer.all_receipt[i]['bank_remark']);
                    
                    // old - get all remarks
                    // rem[customer.id][customer.all_receipt[i]['client_type']] += customer.all_receipt.length === 0 ?  "" : "<b>Serial No. " + customer.all_receipt[i]['serial_no'] + ": </b>" + customer.all_receipt[i]['remarks'] + "/n/";
                    // bank_rem[customer.id][customer.all_receipt[i]['client_type']] += customer.all_receipt.length === 0 ?  "" : "<b>Serial No. " + customer.all_receipt[i]['serial_no'] + ": </b>" + customer.all_receipt[i]['bank_remark'] + "/n/";
                    // END old

                    // get latest remark w/ acct type 1,18 or ctype 9 only
                    if(customer.all_receipt[i]['client_type'] == 9 && $('#customer_type').val() == 9) {
                        // gets latest
                        // rem[customer.id][customer.all_receipt[i]['client_type']] = customer.all_receipt.length === 0 ?  "" : customer.all_receipt[all_rcpt_length]['remarks'] + "/n/";
                        // bank_rem[customer.id][customer.all_receipt[i]['client_type']] = customer.all_receipt.length === 0 ?  "" : customer.all_receipt[all_rcpt_length]['bank_remark'] + "/n/";
                        // END gets latest
                        rem[customer.id][customer.all_receipt[i]['client_type']] = customer.all_receipt.length === 0 ?  "" : customer.all_receipt[i]['remarks'] + "/n/";
                        bank_rem[customer.id][customer.all_receipt[i]['client_type']] = customer.all_receipt.length === 0 ?  "" : customer.all_receipt[i]['bank_remark'] + "/n/";
                        // get last ctype only
                        last_ctype[customer.id]['last_ctype'] = customer.all_receipt.length === 0 ? 0 : (customer.all_receipt[i]['client_type'] != null ? customer.all_receipt[i]['client_type'] : 0);
                    }

                    for(var k = 0; k < customer.all_receipt[i]['items'].length; k++) {
                        if(customer.all_receipt[i]['items'][k]['col_acct_title_id'] == 18 || customer.all_receipt[i]['items'][k]['col_acct_title_id'] == 1) {
                            acct_type[customer.id].push(customer.all_receipt[i]['items'][k]['col_acct_title_id']);

                            // gets latest
                            // rem[customer.id][customer.all_receipt[i]['client_type']] = customer.all_receipt.length === 0 ?  "" : customer.all_receipt[all_rcpt_length]['remarks'] + "/n/";
                            // bank_rem[customer.id][customer.all_receipt[i]['client_type']] = customer.all_receipt.length === 0 ?  "" : customer.all_receipt[all_rcpt_length]['bank_remark'] + "/n/";
                            // END gets latest
                            rem[customer.id][customer.all_receipt[i]['client_type']] = customer.all_receipt.length === 0 ?  "" : customer.all_receipt[i]['remarks'] + "/n/";
                            bank_rem[customer.id][customer.all_receipt[i]['client_type']] = customer.all_receipt.length === 0 ?  "" : customer.all_receipt[i]['bank_remark'] + "/n/";
                            // get last ctype only
                            last_ctype[customer.id]['last_ctype'] = customer.all_receipt.length === 0 ? 0 : (customer.all_receipt[i]['client_type'] != null ? customer.all_receipt[i]['client_type'] : 0);
                        }
                    }
                }
                // type.push(ctype);
                // bank_remark.push(br);
                // remark.push(rem);
            });

            $(document).on('change', '#customer', function() {
                var flag = false;
                var id = $("#customer_id").val(); 
                $('#info_rem').html('');
                $('#info_bank_rem').html('');

                // if(ctype[id] != undefined) {
                //     $.each(acct_type[id], function(key, val) {
                //         if($.inArray(val, [10,18]) > -1) {
                //             flag = true;
                //         }
                //     });
                // }

                // accounts Individual... exclude - PTR (10), Permit Fees (18),  exclude - Professional Tax(1)
                if(acct_type[id] != undefined) {
                    $.each(acct_type[id], function(key, val) { 
                        // if(val == 18) {
                        if($.inArray(val, [1,18]) > -1 && $('#customer_type').val() == 9) {
                            flag = true;
                        }
                    });
                }
                // client types Contractors, Professional Tax, Bidders
                if(ctype[id] != undefined) {
                    $.each(ctype[id], function(key, val) {
                        // if($.inArray(val, [2,9,41]) > -1) {
                        if(val == 9 && $('#customer_type').val() == 9) {
                            flag = true;
                        }
                    });
                }

                if(flag == true) {
                    receipts(ctype, acct_type, last_ctype);
                    receipts_remarks(rem, bank_rem, acct_type, ctype);
                } else {
                    var editor = tinymce.get('remarks');
                    if(editor != null && editor != undefined)
                        editor.setContent('');
                    $('#bank_remark').html('');
                }
                // if($('#customer') == "" || flag == false) {
                //     $('#client_type_msg').html('');
                //     $('#customer_type').empty();
                //     $.each(orig_c_type, function(key, type) {
                //         $('#customer_type').append(type);
                //     });
                //     $('#customer_type').append('<option disabled selected></option>');
                // } 
            });

            $(document).on('change', '#customer_type', function() {
                var flag = false;
                var id = $("#customer_id").val();
                $('#info_rem').html('');
                $('#info_bank_rem').html('');
                
                if(acct_type[id] != undefined) {
                    // accounts individual..PTR (10), Permit Fees (18), professional tax (1)
                    $.each(acct_type[id], function(key, val) { 
                        // if(val == 18) {
                        if($.inArray(val, [1,18]) > -1 && $('#customer_type').val() == 9) {
                            flag = true;
                        }
                    });
                }
                // client types Contractors, Professional Tax, Bidders
                if(ctype[id] != undefined) {
                    $.each(ctype[id], function(key, val) {
                        // if($.inArray(val, [2,9,41]) > -1) {
                        if(val == 9 && $('#customer_type').val() == 9) {
                            flag = true;
                        }
                    });
                }

                if(flag == true) {
                    receipts_remarks(rem, bank_rem, acct_type, ctype);
                } else {
                    var editor = tinymce.get('remarks');
                    if(editor != null && editor != undefined)
                        editor.setContent('');
                    $('#bank_remark').html('');
                }
            });
            
// autocomplete for payor name
            $('#customer').autocomplete('option', 'source', customers)
                .autocomplete('search', $('#customer').val());
        },
        error: function(response) {

        },
    });
});

$("#customer").autocomplete({
    source: customers,
    autoFocus: true,
    select: function (event, ui) {
        var idx = ($.inArray(ui.item.value, customers));
        $('#customer_id').val(customer_ids[idx]);
        $('#customer_type').val(type[idx]);
        $('#bank_remark').val(bank_remark[idx]);

        if(window.location.pathname == 'receipt'){
            tinymce.get('remarks').getBody().innerHTML = remark[idx];
        }
    }
});

// function receipts(ctype, acct_type) {
//     var id = $("#customer_id").val();
//     var unique = [];
//     var deleted_ctypes = [7, 8, 10, 12, 13, 14, 15, 43];
//     if($('#customer') == "") {
//         $('#client_type_msg').html('');
//         $('#customer_type').empty();
//         $.each(orig_c_type, function(key, type) {
//             $('#customer_type').append(type);
//         });
//         $('#customer_type').append('<option disabled selected></option>');
//     }
//     if(ctype[id] != undefined) {
//         if(ctype[id].length > 1) {
//             $('#client_type_msg').html('');
//             var client_type = [];

//             $.each(ctype[id], function(key, node) {
//                 if($.inArray(node, unique) < 0){
//                     unique.push(node);
//                 }
//             });

//             $.each($('#customer_type').children(), function(key, node) {
//                 if ($.inArray(parseInt($(node).attr('value')), unique) >= 0) {
//                     client_type.push(($(node).attr('value') + '=' + $(node).html()));
//                 } else {
//                     client_type.push("0=Payor has no client type/s set");
//                 }
//             });

//             $.each(unique, function(key, node) {
//                 if($.inArray(node, deleted_ctypes) >= 0) {
//                     switch(node) {
//                         case 7:
//                             client_type.push("7=Others");
//                             break;
//                         case 8:
//                             client_type.push("8=Professional Tax");
//                             break;
//                         case 10:
//                             client_type.push("10=Small Scale/Gold panning");
//                             break;
//                         case 12:
//                             client_type.push("12=Equipment Rental");
//                             break;
//                         case 13:
//                             client_type.push("13=Travel & Tours");
//                             break;
//                         case 14:
//                             client_type.push("14=Recruitment Agency");
//                             break;
//                         case 15:
//                             client_type.push("15=Provincial Permit Remittance from LGU");
//                             break;
//                         case 43:
//                             client_type.push("43=Computer Shop");
//                             break;
//                         default:
//                             break;
//                     }
//                 }
//             });
            
//             client_type = client_type.filter(function(s) {
//                 return s != undefined || s != null;
//             });

//             unique = [];
//             $.each(client_type, function(key, val) {
//                 if ($.inArray(val, unique) < 0) {
//                     unique.push(val);
//                 }
//             });

//             $('#customer_type').empty();
//             $.each(unique, function(key, val) {
//                 var slice =  val.split("=");
//                 $('#customer_type').append('<option value="'+slice[0]+'">'+slice[1]+'</option>');
//             });
//         } else if(ctype[id].length == 1 && (ctype[id][0] != null || ctype[id][0] != 0)) {
//             var ctype_name = '';
//             $.each($('#customer_type').children(), function(key, node) {
//                 if (parseInt($(node).attr('value')) == ctype[id][0]) {
//                     ctype_name = $(node).attr('value') + '=' + $(node).html();
//                     return false;
//                 }
//             });
//             $('#customer_type').empty();
//             var slice =  ctype_name.split("=");
//             $('#customer_type').append('<option value="'+slice[0]+'">'+slice[1]+'</option>');
//         } else if(ctype[id].length == 1 && (ctype[id][0] == null || ctype[id][0] == 0)) {
//             $('#client_type_msg').html('');
//             $('#client_type_msg').html('Payor has no client type/s set');
//             $('#customer_type').empty();

//             $.each(orig_c_type, function(key, type) {
//                 $('#customer_type').append(type);
//             });
//             $('#customer_type').append('<option disabled selected></option>');
//         } else {
//             $('#client_type_msg').html('');
//             $('#client_type_msg').html('Payor has no client type/s set');
//         }
//     } else {
//         $('#client_type_msg').html('');
//         $('#client_type_msg').html('Payor has no client type/s set');

//         $.each(orig_c_type, function(key, type) {
//             $('#customer_type').append(type);
//         });
//         $('#customer_type').append('<option disabled selected></option>');
//     }
// }

function receipts(ctype, acct_type, last_ctype) {
    var flag = false;
    var id = $("#customer_id").val();
    $('#client_type_msg').html('');
    if(acct_type[id] != undefined) {
        // accounts Indi..PTR (10), Permit Fees (18), Professional Tax (1)
        $.each(acct_type[id], function(key, val) { 
            // if(val == 18) {
            if($.inArray(val, [1,18]) > -1 && $('#customer_type').val() == 9) {
                flag = true;
            }
        });
    }
    // client types Contractors, Professional Tax, Bidders
    if(ctype[id] != undefined) {
        $.each(ctype[id], function(key, val) {
            // if($.inArray(val, [2,9,41]) > -1) {
            if(val == 9 && $('#customer_type').val() == 9) {
                flag = true;
            }
        });
    }


    if(flag == true) {
        var id = $("#customer_id").val();
        var count_obj = 0;
        // get object count
        for(i in last_ctype) {
            if(last_ctype.hasOwnProperty(i)) {
                count_obj++;
            }
        }
        if(count_obj > 0) {
            if(typeof(last_ctype[id]) != undefined && last_ctype[id] != null) {
                if(last_ctype[id]['last_ctype'] > 0) {
                    var client_last_ctype = last_ctype[id]['last_ctype'];
                    var ctype_option = [];

                    $.each($('#customer_type').children(), function(key, node) {
                        if (parseInt($(node).attr('value')) == client_last_ctype) {
                            $('#customer_type').val(client_last_ctype);
                        } 
                    });
                } else {
                    $('#client_type_msg').html('Previous transaction has no set client type. Please set manually');
                }
            } else {
                $('#client_type_msg').html('Error occurred');
            }
        } else {
            $('#client_type_msg').html('No previous transactions');
        }
    }
}

function receipts_remarks(rem, bank_rem, acct_type, ctype) {
// console.log('rem');
// console.log(rem);
// console.log('bank_rem');
// console.log(bank_rem);
    var ctype = $('#customer_type').val();
    var c_id = $('#customer_id').val();
    var rem_ctype_null = [];
    var remarks = [];
    var bank_remarks = [];
    var bank_rem_ctype_null = [];
    var flag = false;
    var rem_count = 0;
    var bank_rem_count = 0;

    // count rem
    for(i in rem) {
        if(rem.hasOwnProperty(i)) {
            rem_count++;
        }
    }
    // count bank rem
    for(i in bank_rem) {
        if(bank_rem.hasOwnProperty(i)) {
            bank_rem_count++;
        }
    }

    if(acct_type[c_id] != undefined) {
        // accounts Indi..PTR (10), Permit Fees (18), Professional Tax (1)
        $.each(acct_type[c_id], function(key, val) { 
            // if(val == 18) {
            if($.inArray(val, [1,18]) > -1 && $('#customer_type').val() == 9) {
                flag = true;
            }
        });
    }
    if(ctype[c_id] != undefined) {
        // client types Contractors(2), Professional Tax(9), Bidders(41)
        $.each(ctype[c_id], function(key, val) {
            // if($.inArray(val, [2,9,41]) > -1) {
            if(val == 9 && $('#customer_type').val() == 9) {
                flag = true;
            }
        });
    }

    if(flag == true || ((rem_count > 0 || bank_rem_count > 0) && $('#customer_type').val() == 9)) {
        if(ctype == 0 || ctype == null) {
            var rem_0 = rem[c_id][0] != undefined || rem[c_id][0] != null ? rem[c_id][0].replace("undefined", "") : "";
            var rem_null = rem[c_id][null] != null || rem[c_id][null] != undefined ? rem[c_id][null].replace("undefined", "") : "";
            // receipt remarks
            var trim_rem0 = rem_0.replace("undefined", "");
            var split_rem0 = trim_rem0.split('/n/');
            var trim_rem_null = rem_null.replace("undefined", "");
            var split_rem_null = trim_rem_null.split('/n/');

            for(var i = 0; i < split_rem0.length; i++) {
                if (split_rem0[i] != "" && split_rem0[i].length > 27) { // exclude Serial No. with no remarks
                    var clean1 = split_rem0[i].replace(/<\/?[A-Za-z]+\s?\/?>/g, " ");
                    var clean2 = clean1.replace(/&[A-Za-z]+;/g, "");
                    // rem_ctype_null += split_rem0[i].replace(/<\/?[A-Za-z]+\s?\/?>/g, " ") + '\n';
                    rem_ctype_null += clean2 + '\n';
                }
            }
            for(var i = 0; i < split_rem_null.length; i++) {
                if (split_rem_null[i] != "" && split_rem_null[i].length > 27) { // exclude Serial No. with no remarks
                    var clean1 = split_rem_null[i].replace(/<\/?[A-Za-z]+\s?\/?>/g, " ");
                    var clean2 = clean1.replace(/&[A-Za-z]+;/g, "");
                    // rem_ctype_null += split_rem_null[i].replace(/<\/?[A-Za-z]+\s?\/?>/g, " ") + '\n';
                    rem_ctype_null += clean2 + '\n';
                }
            }

            // bank remarks
            var bank_rem_0 = bank_rem[c_id][0] != undefined || bank_rem[c_id][0] != null ? bank_rem[c_id][0].replace("undefined", "") : "";
            var bank_rem_null = bank_rem[c_id][null] != undefined || bank_rem[c_id][null] != null ? bank_rem[c_id][null].replace("undefined", "") : "";
            var trim_bank_rem0 = bank_rem_0.replace("undefined", "");
            var trim_bank_rem_null = bank_rem_null.replace("undefined", "");
            var split_bank_rem0 = trim_bank_rem0.split("/n/");
            var split_bank_rem_null = trim_bank_rem_null.split("/n/");
            for(var i = 0; i < split_bank_rem0.length; i++) {
                // exclude Serial No. with no remarks
                // if (split_bank_rem0[i] != "" && split_bank_rem0[i].length > 27) { 
                if (split_bank_rem0[i] != "") { 
                    var clean1 = split_bank_rem0[i].replace(/<\/?[A-Za-z]+\s?\/?>/g, " ");
                    var clean2 = clean1.replace(/&[A-Za-z]+;/g, "");
                    // bank_rem_ctype_null += split_bank_rem0[i].replace(/<\/?[A-Za-z]+\s?\/?>/g, " ") + '\n';
                    if(clean2.toLowerCase() != "no remarks")
                        bank_rem_ctype_null += clean2 + '\n';
                }
            }
            for(var i = 0; i < split_bank_rem_null.length; i++) {
                // exclude Serial No. with no remarks
                // if (split_bank_rem_null[i] != "" && split_bank_rem_null[i].length > 27) { 
                if (split_bank_rem_null[i] != "") { 
                    var clean1 = split_bank_rem_null[i].replace(/<\/?[A-Za-z]+\s?\/?>/g, " ");
                    var clean2 = clean1.replace(/&[A-Za-z]+;/g, "");
                    // bank_rem_ctype_null += split_bank_rem_null[i].replace(/<\/?[A-Za-z]+\s?\/?>/g, " ") + '\n';
                    if(clean2.toLowerCase() != "no remarks")
                        bank_rem_ctype_null += clean2 + '\n';
                }
            }
        } else {
            $.each(rem[c_id], function(key, val) {
                // if(key == ctype) {
                    if(val != undefined) {
                        var trim = val.replace("undefined", "");
                        var split = trim.split("/n/");
                        for(var i = 0; i < split.length; i++) {
                            // exclude Serial No. with no remarks
                            // if (split[i] != "" && split[i].length > 27) { 
                            if (split[i] != "") { 
                                var clean1 = split[i].replace(/<\/?[A-Za-z]+\s?\/?>/g, " ");
                                var clean2 = clean1.replace(/&[A-Za-z]+;/g, "");
                                // remarks += split[i].replace(/<\/?[A-Za-z]+\s?\/?>/g, " ") + '\n';
                                if(clean2.toLowerCase() != "no remarks")
                                    remarks += clean2 + '\n';
                            }
                        }
                    }
                // }
            });
            $.each(bank_rem[c_id], function(key, val) {
                // if(key == ctype) {
                    if(val != undefined) {
                        var trim = val.replace("undefined", "");
                        var split = trim.split("/n/");
                        for(var i = 0; i < split.length; i++) {
                            // exclude Serial No. with no remarks
                            // if (split[i] != "" && split[i].length > 27) { 
                            if (split[i] != "") { 
                                var clean1 = split[i].replace(/<\/?[A-Za-z]+\s?\/?>/g, " ");
                                var clean2 = clean1.replace(/&[A-Za-z]+;/g, "");
                                // bank_remarks += split[i].replace(/<\/?[A-Za-z]+\s?\/?>/g, " ") + '\n';
                                if(clean2.toLowerCase() != "no remarks")
                                    bank_remarks += clean2 + '\n';
                            }
                        }
                    }
                // }
            });
        }
// console.log('rem_ctype_null');
// console.log(rem_ctype_null);
// console.log('bank_rem_ctype_null');
// console.log(bank_rem_ctype_null);
// console.log('remarks');
// console.log(remarks);
// console.log('bank_remarks');
// console.log(bank_remarks);
        // rpt and field land tax
        // remove no remarks, display beside label instead
        // (old) $('#bank_remark').val(ctype == 0 || ctype == null ? (rem_ctype_null != "" ? 'Receipt Remarks\n' + rem_ctype_null + 'Bank Remarks\n' + bank_rem_ctype_null : 'No remarks') : (remarks.length == 0 ? 'No remarks' : 'Receipt Remarks\n' + remarks + 'Bank Remarks\n' + bank_remarks));
        $('#bank_remark').val('');
        $('#bank_remark').val(ctype == 0 || ctype == null ? (rem_ctype_null != "" || bank_rem_ctype_null != "" ? rem_ctype_null + bank_rem_ctype_null : '') : (remarks.length == 0 && bank_remarks.length == 0 ? '' : remarks + bank_remarks));
        $('#info_bank_rem').html('');
        $('#info_bank_rem').html(ctype == 0 || ctype == null ? (rem_ctype_null != "" || bank_rem_ctype_null != "" ? '' : 'No previous remarks') : (remarks.length == 0 && bank_remarks.length == 0 ? 'No previous remarks' : ''));

        // landtax
        // remove no remarks, display beside label instead
        if($('#remarks').length) {
            $('#info_bank_rem').html('');
            $('#info_bank_rem').html(ctype == 0 || ctype == null ? (bank_rem_ctype_null != "" ? '' : 'No previous remarks') : (bank_remarks.length == 0 ? 'No previous remarks' : ''));
            $('#info_rem').html('');
            $('#info_rem').html(ctype == 0 || ctype == null ? (rem_ctype_null != "" ? '' : 'No previous remarks') : (remarks.length == 0 ? 'No previous remarks' : ''));

            $('#bank_remark').val('');
            $('#bank_remark').val(ctype == 0 || ctype == null ? (bank_rem_ctype_null != "" ? bank_rem_ctype_null : '') : (bank_remarks.length == 0 ? '' : bank_remarks));
            var editor = tinymce.get('remarks');
            editor.setContent(ctype == 0 || ctype == null ? (rem_ctype_null != "" ? rem_ctype_null : '') : (remarks.length == 0 ? '' : remarks));
        }
    }
}

// ajax for name and id for accounts
var accounts = [];
var account_ids = [];
var account_types = [];
var account_titles = [];
var account_rate_id = 0;
$(document).on('keyup', '.account', function(event) {
    arrowKeys = [38, 40, 39, 37];
    if ($.inArray(event.keyCode, arrowKeys) == 1) {
        return;
    }

    $(event.target).next('input').next('input').next('input').val(0);
    $(event.target).parent().next('td').children('input').first().val('');
    $(event.target).parent().next('td').children('input').first().next('input').val(0);
    $(event.target).parent().next('td').next('td').children('input').val('');
    $(event.target).parent().next('td').next('td').next('td').children('input').val('');
    $(event.target).parent().next('td').children('button').attr('disabled', true);
    compute_total();

    $.ajax({
        type: 'POST',
        url: '{{ route("collection.ajax") }}',
        data: {
            'transaction': $('#transaction').val(),
            '_token': '{{ csrf_token() }}',
            'action': 'get_accounts',
            'input': $(event.target).val(),
            'serial': $('#serial_id').val(),
            collection_type : collection_type,
            form_type : $('#form').val(),
        },
        success: function(response) {
            accounts = [];
            account_ids = [];
            account_types = [];
            account_titles = [];
            $.each( response, function(key, account) {
                accounts.push(account.name);
                account_ids.push(account.id);
                account_types.push(account.type);
                account_titles.push(account.title);

            });
            $(event.target).autocomplete('option', 'source', accounts)
                .autocomplete('search', $(event.target).val());
        },
        error: function(response) {

        },
    });
});

$(document).on('change', '#account_list', function(event) {
    var account_id = $(this).val();
    var title = 'subtitle';
    var selected = $(this).find('option:selected');
    $(this).next('input').val(account_id);
    
    if (typeof selected.data('title') !== 'undefined') {
        title = selected.data('title');
    }

    $(this).next('input').next('input').val(title);
    var element = $(this).parent().next('td').next('td');
    var shared_acc = $(this).next('input').next('input').next('input');

    $.ajax({
        type: 'POST',
        url: '{{ route("collection.ajax") }}',
        data: {
            '_token': '{{ csrf_token() }}',
            'action': 'get_rate',
            'account_id': account_id,
            'account_type': title,
        },
        success: function(response) {
            handle_rate(response, shared_acc,element, account_id, title);
            compute_total();
        },
        error: function(response) {

        },
    });
});

// autocomplete for account input
// starts here 
function account_auto() {
    $(".account").autocomplete({
        source: accounts,
        autoFocus: true,
        select: function (event, ui) { // clicking 'select' button does not trigger this function..
            var idx = ($.inArray(ui.item.value, accounts));
            console.log([ui.item.value, account_ids, account_types]);
            var element = $(this).parent().next('td').next('td');
            var shared_acc = $(this).next('input').next('input').next('input');

            $(this).next('input').val(account_ids[idx]);
            $(this).next('input').next('input').val(account_types[idx]);
            element.children('input').val(account_titles[idx]);
            var hasSandInput = false;
            $("input[name='account_id[]']").each(function() {
                if ($(this).val() == 4) {
                    hasSandInput = true;
                    var customer_type = $('#customer_type');
                    customer_type.attr('required',true);
                    // if( customer_type.val() == 5 || customer_type.val() == 6 ){
                        $('#sg_booklets').removeClass('hidden');
                    // }
                }else{
                    $('#sg_booklets').addClass('hidden');
                    $('#customer_type').attr('required',false);
                }
            });

            if (hasSandInput) {
                $('#sand_transaction').val(1);
                $('#sand_blk').css('display', 'block');
            } else {
                $('.sand_inputs').val(0);
                $('#sand_blk').css('display', 'none');
            }
            $.ajax({
                type: 'POST',
                url: '{{ route("collection.ajax") }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'action': 'get_rate',
                    'account_id': account_ids[idx],
                    'account_type': account_types[idx],
                },
                success: function(response) {
                    handle_rate(response, shared_acc,element, account_ids[idx], account_types[idx]);
                    compute_total();
                },
                error: function(response) {

                },
            });
        }
    });
}

// $(document).on('click', '.account_addtl', function(event, ui) {
//     console.log('testing');
//     console.log(ui);
// });

$('#add_booklet_row').on('click',function(){
    $('#booklets_sg').append('<tr>'+
        '<td><input type="number" class="form-control booklet_start" name="booklet_start[]"></td>'+
        '<td><input type="number" class="form-control booklet_end" name="booklet_end[]"></td>'+
        '<td></td>'+
    '</tr>');
});

function check_shared() {
    var municipal_share = 0;
    var barangay_share = 0;

    if ($('.municipal_share').length > 0) {
        municipal_share = 1;
    }

    if ($('.barangay_share').length > 0) {
        barangay_share = 1;
    }


    $('#municipality').attr('required', (municipal_share == 1 ? true : false));
    $('#brgy').attr('required', (barangay_share == 1 ? true : false));

}

function handle_rate(response, shared_acc, element, id, type) {
    console.log([response, shared_acc, element, id, type]);
    if(response[0]){
        var title_id = '<input type="hidden" id="col_acct_title_idx" value="'+response[0].col_acct_title_id+'" /> ';
        var subtitle_id = '<input type="hidden" id="col_acct_subtitle_idx" value="'+response[0].col_acct_subtitle_id+'" /> ';

        if(response[0].sharepct_municipal && response[0].sharepct_municipal > 0 && collection_type != 'show_in_cashdivision'){
            shared_acc.addClass('municipal_share');
        }else{
            shared_acc.removeClass('municipal_share');

        }

        if(response[0].sharepct_barangay && response[0].sharepct_barangay > 0 && collection_type != 'show_in_cashdivision'){
            shared_acc.addClass('barangay_share');
        }else{
            shared_acc.removeClass('barangay_share');
        }

    }else{
        shared_acc.val(0);
    }

    check_shared();

    // var title_id = '<input type="hidden" id="col_acct_title_idx" value="'+response[0].col_acct_title_id+'" /> ';
    // var subtitle_id = '<input type="hidden" id="col_acct_subtitle_idx" value="'+response[0].col_acct_subtitle_id+'" /> ';

    if (!response[0]) {
        return;
    } else if (response[0].type == 'fixed') {
        element.next('td').children('input').val(response[0].value);
    } else if (response[0].type == 'manual') {
        element.next('td').children('input').focus();
    } else if (response[0].type == 'percent') {
        var notary = '';
        var sum_given = '';
        var deadline = '';
        var deadline_datex = "{{ date('m/d/Y') }}";
        var with_surviving = '';

        if(response[0].col_acct_title_id == '3'){
            with_surviving =
            '<div class="form-group col-sm-4">' +
                '<label>With Surviving Spouse</label>' +
                '<input type="checkbox"  id="pct_with_surviving" value="1">' +
            '</div>';
        }
        if (response[0].pct_is_sum_given != 0) {
            sum_given =
            '<div class="form-group col-sm-4">' +
                '<label>Amount</label>' +
                '<input type="number" min="1" step="0.01" class="form-control" id="pct_dlg_amount" value="1">' +
            '</div>';
        } else {
            sum_given =
            '<div class="form-group col-sm-4">' +
                '<label>Amount (Total)</label>' +
                '<input type="number" min="1" step="0.01" class="form-control" id="pct_dlg_amount" value="' + $('#total').val() + '">' +
            '</div>';
        }

        if (response[0].pct_deadline == 1) {
            if(response[0].pct_deadline_date != null){
                 deadline_datex = response[0].pct_deadline_date+"/{{ date('Y') }}";
            }

            // console.log(deadline_datex);
            deadline =
            '<div class="row"> <div class="form-group col-sm-4">' +
                '<label>Deadline <br />(mm/dd/yyyy) </label>' +
                '<input type="text" class="form-control datepicker" id="pct_dlg_deadline" value="'+deadline_datex+'" >' +
            '</div>' +
            '<div class="form-group col-sm-6">' +
                '<label>Rate per month</label>' +
                '<input type="text" class="form-control datepicker" id="pct_dlg_rate_per_month" value="' + response[0].pct_rate_per_month + '" readonly>' +
            '</div>'+
            '</div>'
            ;
        }

        if(response[0].col_acct_title_id === 11 || response[0].col_acct_title_id === 12){
            notary =
            '<div class="row"><div class="form-group col-sm-4">' +
                '<label>Notary Date <br /> (mm/dd/yyyy) </label>' +
                '<input type="text" class="form-control" id="pct_notary_date" />' +
            '</div>'+
            '<div class="form-group col-sm-6">' +
                '<label>NO. Month/s</label>' +
                '<input type="text" class="form-control" id="pct_months" value="" readonly>' +
                '</div>'+
            '</div>';
        }


        // if(response[0].sharepct_barangay && response[0].sharepct_municipal){
        //     $('#municipal').attr('required',true);
        //     $('#brgy').attr('required',true);
        // }

        var pct_total = compute_pct_total(
            parseFloat(response[0].value),
            parseFloat(100),
            (response[0].pct_is_sum_given != 0) ? parseFloat(1) : parseFloat($('#total').val()),
            // '{{ date('m/d/Y') }}',
            deadline_datex,
            parseFloat(response[0].pct_rate_per_month)
        );

        element.prev('td').children('input').first().val(
            title_id+
            subtitle_id+
            '<div class="form-group col-sm-4">' +
                '<label>Rate (%)</label>' +
                '<input type="text" class="form-control" id="pct_dlg_rate" value="' + response[0].value + '" readonly>' +
            '</div>' +
            '<div class="form-group col-sm-4">' +
                '<label>Of (%)</label>' +
                '<input type="number" min="0" max="100" step="0.01" class="form-control" id="pct_dlg_rateof" value="0">' +
            '</div>' +
            sum_given +
            notary     +
            deadline +
            with_surviving +
            '<div class="form-group col-sm-12">' +
                '<label>Value</label>' +
                '<input type="text" class="form-control" id="addtl_rate" value="' + pct_total + '" readonly>' +
            '</div>' +
            '<div class="form-group col-sm-6">' +
                '<button type="button" class="btn btn-success" id="go">Go</button>' +
            '</div>'
        );
        element.prev('td').children('button').attr('disabled', false);
         element.prev('td').children('button').trigger('click');

         if(response[0].col_acct_title_id === 11){
             $('#pct_dlg_rateof').val('100');
         }

         compute_pct_totalx();

    } else if (response[0].type == 'schedule') {
        // account_rate
        var options = '';
        $.each (response, function(i, option) {
            options += '<option value="' + option.id + '">' + option.label + '</option>';
        });
        element.prev('td').children('input').first().val(
            title_id+
            subtitle_id+
            '<div class="form-group col-sm-12">' +
                '<input type="hidden" id="acct_id" value="' + id + '">' +
                '<input type="hidden" id="acct_type" value="' + type + '">' +
                '<label>Schedule</label>' +
                '<input class="form-control" id="sched_type">' +
            '</div>' +
            '<div class="form-group col-sm-4">' +
                '<label>Volume</label>' +
                '<input type="number" min="1" step="1" class="form-control" id="sched_multiple" disabled>' +
                '<input type="hidden" id="sched_multiple_rate">' +
            '</div>' +
            '<div class="form-group col-sm-4">' +
                '<label>Unit Cost</label>' +
                '<input type="text" class="form-control" id="sched_unit" disabled>' +
            '</div>' +
            '<div class="form-group col-sm-1">' +
                '<label>&nbsp; </label>' +
            '</div>' +
            '<div class="form-group col-sm-12">' +
                '<label>Value</label>' +
                '<input type="text" class="form-control" id="addtl_rate" readonly>' +
            '</div>' +
            '<div class="form-group col-sm-6">' +
                '<button type="button" class="btn btn-success" id="go">Go</button>' +
            '</div>'
        );
        element.prev('td').children('button').attr('disabled', false);
        element.prev('td').children('button').trigger('click');

        // sched_multiple();
    }

}

$('body').on('focus', '#pct_dlg_deadline', function() {
    $(this).datepicker({
        changeMonth:true,
        changeYear:true,
        showAnim:'slide',
        onSelect: function() {
            compute_pct_total(
                parseFloat($('#pct_dlg_rate').val()),
                parseFloat($('#pct_dlg_rateof').val()),
                parseFloat($('#pct_dlg_amount').val()),
                $('#pct_dlg_deadline').val(),
                parseFloat($('#pct_dlg_rate_per_month').val())
            );
        }
    });
});

$('body').on('focus', '#pct_notary_date', function() {
    $(this).datepicker({
        changeMonth:true,
        changeYear:true,
        showAnim:'slide',
        onSelect: function() {
            var el = $(this);
            var new_date = moment(el.val(), "MM/DD/YYYY").add( 60 , 'days');

            // var monthDiff = parseInt(get_month_diff(new Date(new_date.format('MM/DD/YYYY')), new Date()));
            var monthDiff = get_month_diff(new Date( new_date.format('MM/DD/YYYY') ) , new Date() );
            if(monthDiff > 0 ){
                console.log(monthDiff);
                if(monthDiff > 36){
                    monthDiff = 36;
                }
                $('#pct_months').val(monthDiff);
                $('#pct_dlg_deadline').val(new_date.format('MM/DD/YYYY'));
                compute_pct_total(
                    parseFloat($('#pct_dlg_rate').val()),
                    parseFloat($('#pct_dlg_rateof').val()),
                    parseFloat($('#pct_dlg_amount').val()),
                    $('#pct_dlg_deadline').val(),
                    parseFloat($('#pct_dlg_rate_per_month').val())
                );
            }

        }
    });
});
function compute_pct_totalx(){
    $('#pct_dlg_rateof').keyup( function() {
        compute_pct_total(
            parseFloat($('#pct_dlg_rate').val()),
            parseFloat($('#pct_dlg_rateof').val()),
            parseFloat($('#pct_dlg_amount').val()),
            $('#pct_dlg_deadline').val(),
            parseFloat($('#pct_dlg_rate_per_month').val())

        );
    });

    $('#pct_dlg_amount').keyup( function() {
        compute_pct_total(
            parseFloat($('#pct_dlg_rate').val()),
            parseFloat($('#pct_dlg_rateof').val()),
            parseFloat($('#pct_dlg_amount').val()),
            $('#pct_dlg_deadline').val(),
            parseFloat($('#pct_dlg_rate_per_month').val())
        );
    });

    $('#pct_with_surviving').change( function() {
        compute_pct_total(
            parseFloat($('#pct_dlg_rate').val()),
            parseFloat($('#pct_dlg_rateof').val()),
            parseFloat($('#pct_dlg_amount').val()),
            $('#pct_dlg_deadline').val(),
            parseFloat($('#pct_dlg_rate_per_month').val())
        );
    });
}


compute_pct_totalx();

function compute_pct_total(rate, pct_of, amount, deadline, rate_per_month) {
    var acct_title =  $('input[type=hidden]#col_acct_title_idx').val();
    deadline = deadline || '';
    rate_per_month = rate_per_month || '';
    var addtl_rate = pct_of > 0 ? ((rate / 100) * (pct_of / 100) * amount) : ((rate / 100) * amount);
    if($('#pct_with_surviving').is(':checked')){
        addtl_rate = pct_of > 0 ? ((rate / 100) * (pct_of / 100) * amount) : ((rate / 100) * amount);
        addtl_rate = addtl_rate/2;
    }else{
        addtl_rate = pct_of > 0 ? ((rate / 100) * (pct_of / 100) * amount) : ((rate / 100) * amount);
    }

    if (deadline == '' || rate_per_month == '' || acct_title == '8') {
        if(acct_title == '3' || acct_title == '7' || acct_title == '8') {
            rate = .005; // exclusively for franchise, publication, and transfer tax accounts only
            // var total = pct_of != 0 || pct_of != "" ? (amount * (rate/100 * pct_of/100)) : (amount * (rate/100));
            var total = amount * rate;
            if($('#pct_with_surviving').is(':checked')) {
                total = total / 2;
            }
            $('#addtl_rate').val((total).toFixed(2));
            return total.toFixed(2);
        }
        $('#addtl_rate').val((addtl_rate).toFixed(2));
        return (addtl_rate).toFixed(2);
    }

    if(acct_title == '11'){
        var monthDiff = parseInt(get_month_diff(new Date(deadline), new Date()));
        if(monthDiff > 36){
            monthDiff = 36;
        }
        var rate_per_month = monthDiff * rate_per_month;
        var addtl_charge = (rate_per_month / 100) * (addtl_rate + amount);
        $('#addtl_rate').val((addtl_rate + addtl_charge).toFixed(2));
        return (addtl_rate + addtl_charge).toFixed(2);
    }else{
        if(acct_title == '8' || acct_title == '7' || acct_title == '3') {
            rate = .005; // exclusively for franchise, publication, and transfer tax accounts only
            // addtl_rate = ((rate / 100) * (pct_of / 100) * amount);
            var datex = moment( new Date()).format("M");
            var datexx = moment( new Date()).format("D");
            var rate_per_month2 = datex * rate_per_month;
            var addtl_charge = (rate_per_month2 / 100) * (addtl_rate + amount);

            var deadline_m = moment(new Date(deadline)).format('M');
            // var total_interest = datex * rate_per_month;
            // total_interest = deadline_m != datex ? total_interest : rate_per_month;
            var total_interest = parseInt(get_month_diff(new Date(deadline), new Date())) * rate_per_month;
            var surcharge = amount * (rate);
            var interest = (surcharge + amount) * (total_interest/100);
            var total = parseFloat(surcharge.toFixed(2)) + parseFloat(interest.toFixed(2));

            if($('#pct_with_surviving').is(':checked')) {
                total = total / 2;
            }

            // $('#addtl_rate').val((addtl_rate + addtl_charge).toFixed(2));
            // return (addtl_rate + addtl_charge).toFixed(2);
            $('#addtl_rate').val(parseFloat(total).toFixed(2));
            return parseFloat(total).toFixed(2);
        }

        var datex = moment( new Date()).format("M");
        var datexx = moment( new Date()).format("D");
        var rate_per_month2 = datex * rate_per_month;
        var addtl_charge = (rate_per_month2 / 100) * (addtl_rate + amount);
        var deadline_m = moment(new Date(deadline)).format('M');

        // var total_interest = datex * rate_per_month;
        // if(datex < deadline_m)
        //     var total_interest = Math.abs(((parseInt(datex)+12)-deadline_m)+1) * rate_per_month; // current month less than deadline, ex: jan 2020 - dec 2019
        // else
        // var total_interest = Math.abs((datex-deadline_m)+1) * rate_per_month; // get month difference, add 1 (for current month)
        // total_interest = total_interest > 0 ? total_interest : rate_per_month; // old
        // total_interest = deadline_m != datex ? total_interest : rate_per_month; // 01/13/2020

        var total_interest = parseInt(get_month_diff(new Date(deadline), new Date())) * rate_per_month;
        var surcharge = amount * (rate/100);
        var interest = (surcharge + amount) * (total_interest/100);
        var total = parseFloat(surcharge.toFixed(2)) + parseFloat(interest.toFixed(2));
        // $('#addtl_rate').val((addtl_rate + addtl_charge).toFixed(2));
        // return (addtl_rate + addtl_charge).toFixed(2);
        $('#addtl_rate').val(parseFloat(total).toFixed(2));

        return parseFloat(total).toFixed(2);
    }

}


function get_month_diff(date1, date2) {
    var date11 = moment(date1);
    var date22 = moment(date2);
    var diffxx = date22.diff(date11, 'days');

    // console.log(diffxx);
    // console.log(diffxx%30);
    // console.log(~~(diffxx/30));

    var div_diffxx = ~~(diffxx/30);
    if( (diffxx%30) > 0 ){
        div_diffxx++;
    }

    return div_diffxx;

    // console.log(div_diffxx);
    // 'user strict';

    // var start_date = date1;
    // var end_date = date2;
    // var inverse = false;

    // if (date1 > date2) {
    //     start_date = date2;
    //     end_date = date1;
    //     inverse = true;
    // }

    // end_date = new Date(end_date);
    // end_date.setDate(end_date.getDate() + 1);

    // var diff_year = end_date.getFullYear() - start_date.getFullYear();
    // var diff_month = end_date.getMonth() - start_date.getMonth();
    // var diff_day = end_date.getDate() - start_date.getDate();

    // return (inverse ? -1 : 1) * (diff_year * 12 + diff_month + diff_day / 30);
}

var scheds = [];
var scheds_ids = [];
$(document).on('keyup', '#sched_type', function() {
    $.ajax({
        type: 'POST',
        url: '{{ route("collection.ajax") }}',
        data: {
            '_token': '{{ csrf_token() }}',
            'action': 'get_schedule',
            'input': $('#sched_type').val(),
            'acct_id': $('#acct_id').val(),
            'acct_type': $('#acct_type').val(),
        },
        success: function(response) {
            scheds = [];
            scheds_ids = [];
            $.each( response, function(key, sched) {
                if(sched.col_acct_title_id == '4'){
                    volume = sched.sched_unit ? '1'+sched.sched_unit : '';
                    scheds.push(sched.label+' ('+volume+' @ '+sched.value+' )');
                    // scheds.push(sched.label+' (1 @ '+sched.value+' )');
                }else{
                    scheds.push(sched.label+' ( '+sched.value+' )'); // here
                }

                scheds_ids.push(sched.id);
            });
            $('#sched_type').autocomplete('option', 'source', scheds)
                .autocomplete('search', $('#sched_type').val());
        },
        error: function(response) {
        },
    });
});

// collect account rate ids here...
$(document).on('autocompleteselect', '#sched_type', function(event, ui) {
    var reg = /(\[.*?\])/gi;

    $.ajax({
        type: 'POST',
        url: '{{ route("collection.ajax") }}',
        data: {
            '_token': '{{ csrf_token() }}',
            'action': 'get_sched_settings',
            'rate_id': scheds_ids[($.inArray(ui.item.value, scheds))],
        },
        success: function(response) {
            var value = parseFloat(response[0].value);

            if (response[0].sched_is_perunit == 0) {
                console.log('notunit');
                $('#addtl_rate').val((value).toFixed(2));
                $('#sched_multiple').attr('disabled','disabled').val('');
                $('#sched_unit').val('');
                $('#sched_unit').data('unit', '');
                return;
            }
            $('#addtl_rate').val((value).toFixed(2));
            $('#sched_multiple_rate').val(response[0].value);
            $('#sched_multiple').removeAttr('disabled').val(1);
            $('#sched_unit').val(response[0].value+''+response[0].sched_unit);
            $('#sched_unit').data('unit', response[0].sched_unit);

// PROBLEM LOCATED HERE
// console.log('test response');
// console.log(response);
// console.log('nature');
// var nature = $('input[name="nature[]"]');
// $(nature).each(function(key, val) {
//     console.log($(val).val());
// });

// get current index of account being entered
// account_rate[index].val(response[0].id) // id of collection rate
// console.log('close');
// console.log($('#sched_type').parent().siblings('.account_rate')); // oks itu
            account_rate_id = response[0].id;
        },
        error: function(response) {

        },
    });
});

// copy sched_multiple and sched_unit to nature
$(document).on('keyup', '#sched_type', function() {
    var sched_type = $('#sched_type').val();
    var split_sched = sched_type.split("(");

    if(split_sched[1] != undefined && split_sched[1] != "") {
        var split_sched2 = split_sched[1].split("@");
        if(split_sched2[0] != undefined && split_sched2[0] != "") {
            $('#sched_multiple').val("");
            $('#sched_multiple').val(parseFloat(split_sched2[0]));
        }

        compute_sched_total(
            parseFloat($('#sched_multiple_rate').val()),
            parseFloat($('#sched_multiple').val()),
        );
    }
});

$(document).on('keyup', '#sched_multiple', function() {
    var sched_mult = $('#sched_multiple').val();
    var sched_type = $('#sched_type').val();
    var split_type = sched_type.split("(");

    if(split_type[1] != undefined && split_type[1] != "" && sched_mult != "") {
        var split_type2 = split_type[1].split("@");   
        if(isNaN($('#sched_unit').val())) {
            var strval = split_type[0] + "(" + parseFloat(sched_mult) + $('#sched_unit').data('unit') + " @" + split_type2[split_type2.length-1];
        } else {
            var strval = split_type[0] + "(" + parseFloat(sched_mult) + " @" + split_type2[split_type2.length-1];
        }
        $('#sched_type').val("");
        $('#sched_type').val(strval);

        compute_sched_total(
            parseFloat($('#sched_multiple_rate').val()),
            parseFloat($('#sched_multiple').val()),
        );
    }
});
// END

// function sched_multiple(){

    // console.log('sched_mult');

    $(document).on('keyup', '#sched_multiple', function() {
        compute_sched_total(
            parseFloat($('#sched_multiple_rate').val()),
            parseFloat($('#sched_multiple').val()),
        );
    });
// }


function compute_sched_total(rate, multiple) {
    var amt = rate * multiple;
    $('#addtl_rate').val((amt).toFixed(2));
}

// only moves addtl rate to 'main' amount
$(document).on('click', '#go', function() {
    var acct_title =  $('input[type=hidden]#col_acct_title_idx').val();
    if(acct_title == 3 || acct_title == 8 || acct_title == 7) { 
        // copy amount to nature for transfer tax, publication, and franchise accounts 
        var nature_val = reference_nature.val();

        reference_nature.val("");
        if(acct_title == 3) {
            var nature_split = nature_val.split("of");
            var pct_dlg_amount = parseFloat($('#pct_dlg_amount').val()).toFixed(2);
            reference_nature.val(nature_split[0] + "of " + parseFloat(pct_dlg_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }) + ")");
        } else if(acct_title == 8 || acct_title == 7) {
            var nature_split = nature_val.split("(");
            var pct_dlg_amount = parseFloat($('#pct_dlg_amount').val()).toFixed(2);
            reference_nature.val(nature_split[0] + "(w/ amount " + parseFloat(pct_dlg_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }) + ")");
        }
    } else {
        reference_nature.val( ($('#sched_type').val() == null) ? reference_nature.val() : $('#sched_type').val());
    }

    var sched_id = scheds_ids[($.inArray($('#sched_type').val(), scheds))];
    reference.val($('#addtl_rate').val()); 
    reference_rate_id.val( (sched_id == null) ? 0 : sched_id );
    // reference_nature.val( ($('#sched_type').val() == null) ? reference_nature.val() : $('#sched_type').val());
    reference_coll_rate.val(account_rate_id); // move account_rate_id to account_rate input
    $('#account_panel').dialog('close');
    compute_total();

    // for sand and gravel accounts, check if client type is set
    // if(acct_title == 4) {
    //     console.log('testinggggg');

    //     var parent = $('input[type=hidden]#col_acct_title_idx').parent().parent().parent();
    //     console.log(parent);
    // }
});

<?php if($base['sub_header'] != 'Edit'){ ?>
$("#form").on('change', function() {
    var formx = $(this).val();
    $.ajax({
        type: 'POST',
        url: '{{ route("collection.ajax") }}',
        data: {
            '_token': '{{ csrf_token() }}',
            'action': 'get_serial',
            'form': $('#form').val(),
            'user_id': $('#user_id').val(),
            'collection_type' : collection_type
        },
        success: function(response) {
            $('.toggle-col').removeAttr('disabled');
            var serial_id = '{{ Session::get('serial_id') }}';
            $('#serial_id').html('<option value=""></option>');
            if (response.length > 1) {

                $('#confirm').attr('disabled', false);
                if( $('#form').val() == 1 ){
                    $.each(response, function(key, val) {
                        var getSplit = val.label.split(' ');
                        var getEndBal = getSplit[0].split('-');
                        // if(getEndBal[1] != val.current) {
                            if (serial_id == val.id) {
                                $('#serial_id')
                                    .append($('<option>')
                                        .attr('value', val.id)
                                        .attr('selected', 'selected')
                                        .html(val.label)
                                    );
                                    $("label[for='serial_id']").html(' Series <strong> :  '+val.current+'</strong>');
                            } else {
                                $('#serial_id')
                                    .append($('<option>')
                                        .attr('value', val.id)
                                        .html(val.label)
                                    );
                            }
                        // }
                    });
                }else{
                    $.each(response, function(keyx, valx) {
                        $.each(valx, function(key, val) {
                            var label = val.label;
                            var getSplit = label.split(' ');
                            var getEndBal = getSplit[0].split('-');
                            // console.log(getEndBal, ' ', val.current);
                            // if(getEndBal[1] != val.current) {
                                if (serial_id == val.id) {
                                    $('#serial_id')
                                    .append($('<option>')
                                        .attr('value', val.id)
                                        .attr('selected', 'selected')
                                        .html(val.label)
                                        );
                                    $('#municipality_name').attr({'value':val.municipality_name,'data-code':val.municipality_code});
                                    $('#municipality').attr({'data-code':val.municipality_code});
                                    $('#municipality option[value="'+val.municipality+'"]').prop("selected", true);
                                    changeMunicipality();
                                    $("label[for='serial_id']").html(' Series <strong> :  '+val.current+'</strong>');
                                    $('#municipality_code').val(val.municipality_code);
                                } else {
                                    $('#serial_id')
                                    .append($('<option>')
                                        .attr('value', val.id)
                                        .html(val.label)
                                        );
                                }
                            // }
                        });
                    });
                }

            $('#serial_id').attr('disabled', false);
            $('#serial_id').attr('required', true);
            $('.account').attr('disabled', false);
                if(formx==2){
                      f56_auto($('#form').val());
                  }else{
                    $('.account').val('');
                    $('input[name="nature[]"]').val('');
                     $("#municipality").val('');
                     $('#brgy').find('option')
                                                            .remove()
                                                            .end()
                                                            .prop('disabled', false);
                  }
                return;
            }else if (response.length == 1){
                $('#confirm').attr('disabled', false);
                $('#serial_id')
                            .append($('<option>')
                                .attr('value', response[0].id)
                                .attr('selected', 'selected')
                                .html(response[0].label)
                            );
                            $("label[for='serial_id']").html(' Series <strong> :  '+response[0].current+'</strong>');
                            $('#serial_id').attr('disabled', false);
            $('#serial_id').attr('required', true);
            $('.account').attr('disabled', false);
                if(formx==2){
                      f56_auto($('#form').val());
                  }else{
                    $('.account').val('');
                    $('input[name="nature[]"]').val('');
                     $("#municipality").val('');
                     $('#brgy').find('option')
                                                            .remove()
                                                            .end()
                                                            .prop('disabled', false);
                  }
                return;
            }
            $('#serial_id').attr('disabled', true);
            $('#confirm').attr('disabled', true);
        },
        error: function(response) {

        },
    });
});
<?php } ?>
$('#with_cert').change(function(){
    var el = $(this);
    if(el.val() == 'Transfer Tax'){
            $('.account:eq(0)').val('Real Property Transfer Tax');
            $('.account:eq(0)').trigger('keyup');
    }else if( el.val() == 'Sand & Gravel'){
            $('.account:eq(0)').val('Tax on Sand, Gravel & Other Quarry Prod.');
            $('.account:eq(0)').trigger('keyup');
    }else if( el.val() == 'Provincial Permit'){
            $('.account:eq(0)').val('Permit Fees');
            $('.account:eq(0)').trigger('keyup');
    }

});


function f56_auto(form) {
    if (form != 2) {
        return;
    }

    $.ajax({
        type: 'POST',
        url: '{{ route("collection.ajax") }}',
        data: {
            '_token': '{{ csrf_token() }}',
            'action': 'get_f56_accts',
        },
        success: function(response) {
            $('#table').find('tbody').html('');

            $('#table').find('tbody')
                .append($('<tr>')
                    .append($('<td>')
                        .append($('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control account')
                            .attr('required', 'true')
                            .attr('disabled', 'true')
                            .val(response.account.name)
                        )
                        .append($('<input>')
                            .attr('type', 'hidden')
                            .attr('class', 'form-control')
                            .attr('name', 'account_id[]')
                            .val(response.account.id)
                        )
                        .append($('<input>')
                            .attr('type', 'hidden')
                            .attr('class', 'form-control')
                            .attr('name', 'account_type[]')
                            .val(response.type)
                        )
                        .append($('<input>')
                            .attr('type', 'hidden')
                            .attr('class', 'form-control')
                            .attr('name', 'account_is_shared[]')
                            .val(response.shared)
                        )
                    )
                    .append($('<td>')
                        .append($('<button>')
                            .attr('type', 'button')
                            .attr('class', 'btn btn-sm btn-info account_addtl')
                            .attr('disabled', 'true')
                            .text('Select')
                        )
                        .append($('<input>')
                            .attr('type', 'hidden')
                            .attr('class', 'form-control')
                        )
                        .append($('<input>')
                            .attr('type', 'hidden')
                            .attr('class', 'form-control account_rate')
                            .attr('name', 'account_rate[]')
                            .val(0)
                        )
                    )
                    .append($('<td>')
                        .append($('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control')
                            .attr('name', 'nature[]')
                            .attr('required', 'true')
                            .attr('maxlength', '300')
                            .val(response.account.name)
                        )
                    )
                    .append($('<td>')
                        .append($('<input>')
                            .attr('type', 'number')
                            .attr('min', '0')
                            .attr('step', '.01')
                            .attr('class', 'form-control amounts')
                            .attr('name', 'amount[]')
                            .attr('required', 'true')
                        )
                    )
                    .append($('<td>')

                    )
                )
                    account_auto();
        },
        error: function(response) {

        },
    });
}

$("#form").trigger( 'change', []);

$("#serial_id").change( function() {
    if($(this).val()!==''){
        $.fn.getCurrrentReceipt($(this).val());

         changeMunicipality();
    }
});

$.fn.getCurrrentReceipt = function(serial_id){
        $.ajax({
            type: 'POST',
            url: '{{ route("serial.get_serial_current") }}',
            data: {
                '_token': '{{ csrf_token() }}',
                'serial_id': serial_id
            },
            error: function(response) {
                console.log(error);
            },
             success: function(response) {
                $("label[for='serial_id']").html(' Series <strong> :  '+response+'</strong>');
            },
        });
};

function changeMunicipality() {
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
        },
        error: function(response) {

        },
    });
}

$("#municipality").change( function() {
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
                $('#brgy,.tdrp_barangay').append($('<option>', {
                    'data-code':brgy.code,
                    value: brgy.id,
                    text: brgy.name
                }));
            });
        },
        error: function(response) {

        },
    });
});

$('#account_panel').dialog({
    autoOpen: false,
    draggable:false,
    modal: true,
    resizable: false,
    title: 'Select',
    width: 600,
});

var reference;
var reference_rate_id;
var reference_nature;
var reference_coll_rate;
$(document).on('click', '.account_addtl', function() {
    var content = $(this).next('input').val();

    // append account_rate here..
    content += '<input type="hidden" class="form-control account_rate" name="account_rate[]" value="0">';

    $('#account_panel').html(content);
    reference = $(this).parent().next('td').next('td').children('input');
    reference_rate_id = $(this).parent().children('input').next('input');
    reference_nature = $(this).parent().next('td').children('input');
    reference_coll_rate = $(this).siblings('.account_rate');
    $('#account_panel').dialog('open');
    $("#sched_type").autocomplete({
        source: scheds,
        autoFocus: true
    });
});


$('#add_row').click( function() {
    var type = $(this).data('transactiontype');
    var inputHtml = "";
    switch (type) {
        case 1:
            inputHtml = '<input type="text" class="form-control account" required>';
            break;
        case 2:
            inputHtml = `
                <input type="hidden" class="form-control account" required>
                <select name="account_list" id="account_list" class="form-control" required>
                    <option></option>
                    <option value="10">Lodging (OPAG)</option>
                    <option value="3">Sales on Agricultural Products (OPAG)</option>
                </select>
            `;
            break;
        case 3:
            inputHtml = `
                <select name="account_list" id="account_list" class="form-control">
                    <option></option>
                    <option value="5">Sales on Veterinary Products</option>
                    <option data-title="title" value="61">Supervision and Regulation, Enforcement Fees (Quarantine Fees)</option>
                </select>
            `;
            break;
        case 4:
            inputHtml = `
                <select name="account_list" id="account_list" class="form-control" required>
                    <option></option>
                    <option data-title="subtitle" value="7">Gain on Sale of Drugs and Medicines-5 District Hospitals</option>
                    <option data-title="subtitle" value="12">Medical, Dental & Laboratory Fees</option>
                    <option data-title="title" value="26">Hospital Fees</option>
                    <option data-title="title" value="22">Other Service Income</option>
                </select>
            `;
            break;
        default:
            inputHtml = '<input type="text" class="form-control account" required>';
            break;
    }
    var html = `
    <tr>
        <td>
            `
            +inputHtml+
            `
            <input type="hidden" class="form-control" name="account_id[]">
            <input type="hidden" class="form-control" name="account_type[]">
            <input type="hidden" class="form-control account_is_shared" value="0" name="account_is_shared[]">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-info account_addtl" disabled>Select</button>
            <input type="hidden" class="form-control">
            <input type="hidden" class="form-control account_rate" name="account_rate[]" value="0">
        </td>
        <td>
            <input type="text" class="form-control" name="nature[]" maxlength="300" required>
        </td>
        <td class="td_amt">
            <input type="number" class="form-control amounts" name="amount[]"  step="0.01" required>
        </td>
        <td>
            <button type="button" class="btn btn-warning btn-sm rem_row"><i class="fa fa-minus"></i></button>
        </td>
    </tr>
    `;
    $('#table').find('tbody').append(html);
    $.fn.natureAutoComplete();
    account_auto();
});

$(document).on('change', '.amounts', function() {
    compute_total();
});

$(document).on('click', '.rem_row', function() {
    $(this).parent().parent().remove();
    compute_total();
    check_shared();
    if ($.fn.computeAmountTotal) {
         $.fn.computeAmountTotal();
    }

    $('#add_row_form56').attr('disabled',false);
});

$.fn.natureAutoComplete = function(){
     $('.nature').autocompleteX({
        serviceUrl: '{{route("collection.autocomplete") }}',
        dataType: 'json',
        type: 'POST',
        params : {
                _token : '{{csrf_token()}}',
                 'action': 'get_nature',
        },
        onSelect: function (suggestion) {

        }
    });
};

function compute_total() {
    var total_amount = 0;
    $.each( $('.amounts'), function(key, item) {
        var val = parseFloat($(this).val());
        total_amount = ($.isNumeric(val)) ? (total_amount + val) : total_amount;
    });
    $('#total').val(total_amount.toFixed(2));
}

account_auto();
$.fn.natureAutoComplete();

$(document).on('change', '#transaction_type', function() {
    console.log($(this).val());
    if($(this).val() == 2) { // for check only
        $('#bank_name').prop('required');
        $('#bank_number').prop('required');
        $('#bank_date').prop('required');
    }

    if($(this).val() == 5) {
        $('#bank_name').prop('required');
    }
});

// require client type
$(document).on('click', '.btnf51', function(e) {
    e.preventDefault();
    $('#client_type_msg').html('');
    $('#table > tbody > *').css('background-color', '');

    var accounts = $('input[name="account_id[]"]');
    var table = $('#table > tbody').children();
    var c_type = $('#customer_type').val();
    var sg_index = [];
    $.each(accounts, function(index, val) {
        // for Tax on Sand, Gravel & Other Quarry Prod., Professional Tax, and Permit Fees
        if($(val).val() == 4 || $(val).val() == 1 || $(val).val() == 18) {
            sg_index.push(index);
        }
    });
    if(sg_index.length > 0 && c_type == "") {
    // if(c_type == "") {
        $.each(table, function(index, val) {
            if($.inArray(index, sg_index) >= 0) {
                $(table[index]).css('background-color', '#e0c371');
            }
        });
        $('#client_type_msg').html('Client Type required for Sand and Gravel, Professional Tax, and Permit Fees accounts.');
        // document.getElementById('customer_type').focus();
        $('#client_type_msg').html('Client Type required.');
    } else {
        $('#store_form').submit();
    }
});
</script>