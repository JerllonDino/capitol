@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h2>DATE {{$data['start_date']->format('F, d Y') }} - {{ $data['end_date']->format('F, d Y') }}</h2>
    <table id="mpi" class="table table-striped table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ENCODER NAME</th>
                    <th>Value</th>
                </tr>
            </thead>

            <tbody>
                <?php $total_x = 0; ?>
                @foreach( $user_table as $key => $value )
                        <tr>
                            <td>{{ $value->realname }} </td>
                            <td>{{ $table[$value->id] }} </td>
                        </tr>
                        <?php $total_x += $table[$value->id]; ?>
                @endforeach
               
            </tbody>

            <tfoot>
                <tr>
                    <td><strong>TOTAL</strong></td>
                    <td><strong>{{ $total_x }}</strong></td>
                </tr>
            </tfoot>
        </table>
</div>
</div>


@endsection

@section('js')
<script>
    $('.date').datepicker({
        changeMonth:true,
        changeYear:true,
        showAnim:'slide'
    });
</script>
@endsection
