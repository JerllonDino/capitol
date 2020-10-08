@extends('nav')

@section('css')
{{ Html::style('/datatables-1.10.12/css/dataTables.bootstrap.min.css') }}
{{ Html::style('/base/sweetalert/sweetalert2.min.css') }}
<style>
    .td_amt {
        width: 150px;
    }
    .td_nature {
        width: 450px;
    }
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
    #sand_blk {
        display: none;
    }

    .btn-pink{
        background-image: linear-gradient(to bottom,#f66adc 0,#b11faa 100%);
    }

    .btn-gray{
        color : #fff;
        background-image: linear-gradient(to bottom,#959294 0,#625e61 100%);
    }

    .btn-green{
        color : #fff;
        background-image: linear-gradient(to bottom,#73e641 0,#4a9c18 100%);
    }

    .btn-red{
        color : #fff;
        background-image: linear-gradient(to bottom,#ff0009 0,#9e1523 100%);
    }

    .btn-another{
        color:#fff;
        background-image: linear-gradient(to bottom,#229568 0,#0b470e 100%);
    }

    .btn-another-none{
        color:#fff;
        background-image: linear-gradient(to bottom,#5a755d 0,#435744 100%);
    }

    #sg_booklets{
        background: burlywood;
    }

    .bg-cert-ttc{
        background-image: linear-gradient(to bottom,#0b147d 0,#0c2768 100%);
    }

    .bg-cert-sg{
        background-image:linear-gradient(to bottom,#ae3bcb 0,#cd3bd1 100%);
    }

    .bg-cert-pp{
        background-image: linear-gradient(to bottom,#0a933c 0,#0f661d 100%);
    }
    .bg-cert-pp > a{
        color: #000;
    }


.autocomplete-suggestions { border: 1px solid #999; background: #FFF; overflow: auto; }
.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
.autocomplete-selected { background: #F0F0F0; }
.autocomplete-suggestions strong { font-weight: normal; color: #3399FF; }
.autocomplete-group { padding: 2px 5px; }
.autocomplete-group strong { display: block; border-bottom: 1px solid #000; }
.select2-container{ width:100% !important; }

</style>
@endsection

@section('content')
    <div>
        <div class="row">
            <button class="btn btn-danger pull-right" id="delete" data-toggle="modal" data-target="#delete_modal">Delete Receipt</button>
        </div>
        <table>
            <tr>
                <td width="300px"><b>User</b></td>
                <td>{{ $base['rcpt_user']['realname'] }}</td>
            </tr>
            <tr>
                <td><b>Receipt Number</b></td>
                <td>{{ $base['receipt']->rcpt_no }}</td>
            </tr>
            <tr>
                <td><b>Receipt Date</b></td>
                <td>{{ $base['receipt']->rcpt_date }}</td>
            </tr>
            <tr>
                <td><b>Payor/Customer</b></td>
                <td>{{ $base['receipt']->getCustomer->name }}</td>
            </tr>
            <tr>
                <td><b>Client Type</b></td>
                <td>{{ !is_null($base['ctype']) ? $base['ctype']->description : '' }}</td>
            </tr>
            <tr>
                <td><b>Municipality</b></td>
                <td>{{ $base['munic']->name }}</td>
            </tr>
            <tr>
                <td><b>Barangay</b></td>
                <td>{{ !empty($base['brgy']) ? $base['brgy']->name : '' }}</td>
            </tr>
            <tr>
                <td><b>Transaction Type</b></td>
                <td>{{ $base['transac_type']->name }}</td>
            </tr>
            <tr>
                <td><b>Bank Name</b></td>
                <td>{{ $base['receipt']->drawee_bank }}</td>
            </tr>
            <tr>
                <td><b>Bank Number</b></td>
                <td>{{ $base['receipt']->bank_no }}</td>
            </tr>
            <tr>
                <td><b>Bank Date</b></td>
                <td>{{ !is_null($base['receipt']->bank_no) && $base['receipt']->bank_no != "" ? $base['receipt']->bank_date : '' }}</td>
            </tr>
            <tr>
                <td><b>Remarks</b></td>
                <td>{{ $base['receipt']->remarks }}</td>
            </tr>
            @if($base['receipt']->is_cancelled == 1)
                <tr>
                    <td><b style="color: #d93529;">CANCELLED</b></td>
                </tr>
            @endif
        </table>
    </div>
    <br>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Account</th>
                    <th>Nature</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                @foreach($base['receipt']->getItems as $item)
                    <?php $total += $item->value; ?>
                    <tr>
                        <td>
                            @if($item->col_acct_title_id > 0)
                                {{ $item->getAccount->name }}
                            @else
                                {{ $item->getSubAccount->name }}
                            @endif
                        </td>
                        <td>{{ $item->nature }}</td>
                        <td>{{ number_format($item->value, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"><b>TOTAL</b></td>
                    <td>{{ number_format($total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="modal" id="delete_modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close pull-right" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <p>Delete OR number {{ $base['receipt']->rcpt_no }}?</p>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding: 0; padding-top: 8px;">
                        <div class="form-inline">
                            <button class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-warning pull-right" href="{{ route('mncpal.rcpt.delete', $base['receipt']->id) }}">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
