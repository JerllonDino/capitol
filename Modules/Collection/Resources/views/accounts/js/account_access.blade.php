<script type="text/javascript">
$('.all,.sub_all').on('click',function(){
    var el = $(this);
     var data_access  = el.attr('data-access');
     var el_index = $('.'+data_access+'all').index($(this));

      $('input[name="'+data_access+'landtax[]"] ').eq(el_index).attr('checked',true);
      $('input[name="'+data_access+'fieldlandtax[]"] ').eq(el_index).attr('checked',true);
      $('input[name="'+data_access+'cashdivision[]"] ').eq(el_index).attr('checked',true);
      $('input[name="'+data_access+'form51[]"] ').eq(el_index).attr('checked',true);
      $('input[name="'+data_access+'form56[]"] ').eq(el_index).attr('checked',true);
      if(data_access=='sub_'){
              $.fn.sentSubTitle(el_index);
      }else{
              $.fn.sentTitle(el_index);
      }
});
$('.landtax,.fieldlandtax,.cashdivision,.form51,.form56 ').on('change',function(){
    var el = $(this);
     var el_index = $('.'+el.attr('data-access')).index($(this));
     $.fn.checkType(el,el_index);
     $.fn.sentTitle(el_index);
});

$('.sub_landtax,.sub_fieldlandtax,.sub_cashdivision,.sub_form51,.sub_form56 ').on('change',function(){
    var el = $(this);
     var el_index = $('.'+el.attr('data-access')).index($(this));
          $.fn.checkType(el,el_index);
           $.fn.sentSubTitle(el_index);
});

$.fn.sentTitle = function(el_index){
var title_id = $('input[name="title_id[]"] ').eq(el_index).val();
     var landtax = $('input[name="landtax[]"] ').eq(el_index).prop('checked')?1:0;
     var fieldlandtax = $('input[name="fieldlandtax[]"] ').eq(el_index).prop('checked')?1:0;
     var cashdivision = $('input[name="cashdivision[]"] ').eq(el_index).prop('checked')?1:0;
     var form51 = $('input[name="form51[]"] ').eq(el_index).prop('checked')?1:0;
     var form56 = $('input[name="form56[]"] ').eq(el_index).prop('checked')?1:0;

     $.ajax({
                  type: 'POST',
                  url: '{{route('account_access.set_account')}}',
                  data: {
                    type : 'title',
                    _token : '{{csrf_token()}}',
                    title_id : title_id,
                    subtitle_id : null,
                    landtax : landtax,
                    fieldlandtax : fieldlandtax,
                    cashdivision : cashdivision,
                    form51 : form51,
                    form56 : form56,
                  },
                  dataType: "json",
                  error: function(){
                      alert('error');
                  },
                  success: function(data) {
                  }
      });
};

$.fn.sentSubTitle = function(el_index){
   var subtitle_id = $('input[name="subtitle_id[]"] ').eq(el_index).val();
     var landtax = $('input[name="sub_landtax[]"] ').eq(el_index).prop('checked')?1:0;
     var fieldlandtax = $('input[name="sub_fieldlandtax[]"] ').eq(el_index).prop('checked')?1:0;
     var cashdivision = $('input[name="sub_cashdivision[]"] ').eq(el_index).prop('checked')?1:0;
     var form51 = $('input[name="sub_form51[]"] ').eq(el_index).prop('checked')?1:0;
     var form56 = $('input[name="sub_form56[]"] ').eq(el_index).prop('checked')?1:0;
         $.ajax({
                  type: 'POST',
                  url: '{{route('account_access.set_account')}}',
                    data: {
                    type : 'subtitle',
                    _token : '{{csrf_token()}}',
                    title_id : null,
                    subtitle_id : subtitle_id,
                    landtax : landtax,
                    fieldlandtax : fieldlandtax,
                    cashdivision : cashdivision,
                    form51 : form51,
                    form56 : form56,
                  },
                  dataType: "json",
                  error: function(){
                      alert('error');
                  },
                  success: function(data) {
                  }
      });
};

$.fn.checkType = function(el,el_index){
  var data_access  = el.attr('data-access');
        data_access  = data_access.substr(0,4)=='sub_' ? 'sub_':'';
  var landtax = data_access+'landtax';
  var fieldlandtax = data_access+'fieldlandtax';
if( el.attr('data-access') === landtax){
    $('input[name="'+data_access+'form51[]"] ').eq(el_index).attr('checked',true);
}else if(el.attr('data-access') === fieldlandtax){
  $('input[name="'+data_access+'form51[]"] ').eq(el_index).attr('checked',true);
  $('input[name="'+data_access+'form56[]"] ').eq(el_index).attr('checked',true);
}

// if( el.attr('data-access') === 'landtax'){
//     $('input[name="form51[]"] ').eq(el_index).attr('checked',true);
// }

};
</script>