@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
    <div class="form-group col-sm-6 col-sm-offset-3">
        <h3>PERIOD : 
        <strong>
                {{ date('F', mktime(0, 0, 0, $month, 1)) }}-
                {{ $year }}
        </strong>
       </h3>
    </div>
    
 <div class="col-sm-6 col-md-offset-3">

        <table id="mpi" class="table table-striped table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Munipality</th>
                    <th>Value</th>
                </tr>
            </thead>

            <tbody>

                 @foreach( $municipality as $key => $value )
                 <?php $xx =  $SandandGravelMnthly->where('municipality',$value->id)->first(); ?>
                    @if( $value->id != 14)
                    <tr>
                        <td>{{ $value->name}}</td>
                        <td> 
                            {{$xx->mcpal_value}}
                        </td>
                    </tr>
                    @else
                        <tr>
                        <td>PROVINCE</td>
                        <td> {{$xx->mcpal_value}} </td>
                    </tr>
                    @endif
                @endforeach
               
            </tbody>
        </table>
    </div>


    </div>

</div>



@endsection

@section('js')

@endsection
