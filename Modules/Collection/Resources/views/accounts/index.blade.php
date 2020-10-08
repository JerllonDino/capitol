@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
<style type="text/css">

.category,.category>h4,.group,.group>h5,.title, .subs{
      font-weight: bold;
}
.group>h5,.category>h4{
     margin-top: 2px;
    margin-bottom: 2px;
}

.category{
      background: rgba(0, 62, 128, 0.38);
}

.group{
    font-weight: bold;
    padding-left: 20px  !important;
    background: rgba(173, 216, 230, 0.45);
}
  .title{
    padding-left: 60px  !important;
  }
  .subs{
    padding-left: 90px  !important;
  }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">


            <table id="account_access" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th style="width: 48%;">ACCOUNT NAME</th>
                     <th>Land Tax Collections</th>
                    <th>Field Land Tax Collections</th>
                    <th>Cash Division Collections</th>
                     <th>FORM 51</th>
                   <th>FORM 56</th>
                </tr>
            </thead>

            <tbody>
              @foreach( $base['category'] as $category)
                    <tr>
                            <td colspan="6" class="category"><h4>{{$category->name}}</h4> </td>
                    </tr>
                    @foreach( $category->group as $group)
                           <tr>
                                    <td colspan="6"  class="group"><h5>{{$group->name}}</h5></td>
                            </tr>
                     @foreach( $group->title as $title)
                     <?php
                        $landtax = '';
                        $fieldlandtax = '';
                        $cashdivision = '';
                        $form51 = '';
                        $form56 = '';

                        if($title->acct_access){
                             $landtax = $title->acct_access->show_in_landtax == 1 ? 'checked="checked"' : '';
                             $fieldlandtax = $title->acct_access->show_in_fieldlandtax == 1 ? 'checked="checked"' : '';
                             $cashdivision = $title->acct_access->show_in_cashdivision == 1 ? 'checked="checked"' : '';
                             $form51 = $title->acct_access->show_in_form51 == 1 ? 'checked="checked"' : '';
                             $form56 = $title->acct_access->show_in_form56 == 1 ? 'checked="checked"' : '';
                        }


                     ?>
                          <tr>
                                    <td class="title">{{ $title->name }} <button type="button" class="btn btn-xs btn-info toggle pull-right all" nam="all[]" data-access="" ><i class="fa fa-check-square-o"></i></button>  <input type="hidden" name="title_id[]" class="title_id" value="{{ $title->id }}" /> </td>
                                    <td class="text-center"><input type="checkbox" {{ $landtax }} name="landtax[]" data-access="landtax"  class="landtax text-center"  /></td>
                                    <td class="text-center"><input type="checkbox" {{ $fieldlandtax }} name="fieldlandtax[]" data-access="fieldlandtax"  class="fieldlandtax text-center" /></td>
                                    <td class="text-center"><input type="checkbox" {{ $cashdivision }} name="cashdivision[]"  data-access="cashdivision"   class="cashdivision text-center" /></td>
                                    <td class="text-center"><input type="checkbox" {{ $form51 }} name="form51[]"  data-access="form51" class="form51 text-center" /></td>
                                    <td class="text-center"><input type="checkbox" {{ $form56 }} name="form56[]"  data-access="form56" class="form56 text-center" /></td>
                          </tr>
                          @foreach( $title->subs as $sub)
                            <?php
                              $sub_landtax = '';
                              $sub_fieldlandtax = '';
                              $sub_cashdivision = '';
                              $sub_form51 = '';
                              $sub_form56 = '';
                                   if($sub->acct_access){
                                         $sub_landtax = $sub->acct_access->show_in_landtax ==1 ? 'checked="checked"' : '';
                                         $sub_fieldlandtax = $sub->acct_access->show_in_fieldlandtax == 1 ? 'checked="checked"' : '';
                                         $sub_cashdivision = $sub->acct_access->show_in_cashdivision == 1 ? 'checked="checked"' : '';
                                         $sub_form51 = $sub->acct_access->show_in_form51 == 1 ? 'checked="checked"' : '';
                                         $sub_form56 = $sub->acct_access->show_in_form56 == 1 ? 'checked="checked"' : '';
                                    }


                            ?>

                          <tr>
                                    <td class="subs">{{ $sub->name }} <button type="button" class="btn btn-xs btn-info toggle pull-right sub_all" nam="sub_all[]" data-access="sub_" > <i class="fa fa-check-square-o"></i></button>  <input type="hidden" name="subtitle_id[]" class="subtitle_id" value="{{ $sub->id }}"  /> </td>
                                     <td class="text-center"><input type="checkbox" {{ $sub_landtax }} name="sub_landtax[]" data-access="sub_landtax"  class="sub_landtax" /></td>
                                    <td class="text-center"><input type="checkbox" {{ $sub_fieldlandtax }} name="sub_fieldlandtax[]" data-access="sub_fieldlandtax"  class="sub_fieldlandtax text-center" /></td>
                                    <td class="text-center"><input type="checkbox" {{ $sub_cashdivision }} name="sub_cashdivision[]" data-access="sub_cashdivision" class="sub_cashdivision text-center" /></td>
                                    <td class="text-center"><input type="checkbox" {{ $sub_form51 }} name="sub_form51[]" data-access="sub_form51" class="sub_form51 text-center" /></td>
                                    <td class="text-center"><input type="checkbox" {{ $sub_form56 }} name="sub_form56[]"  data-access="sub_form56" class="sub_form56 text-center" /></td>
                          </tr>
                    @endforeach
                    @endforeach
              @endforeach
              @endforeach
            </tbody>
        </table>

</div>


 <!-- Modal -->
@endsection

@section('js')
{{ Html::script('/datatables-1.10.12/js/jquery.dataTables.min.js') }}
{{ Html::script('/datatables-1.10.12/js/dataTables.bootstrap.min.js') }}
     @include('collection::accounts/js/account_access')
@endsection
