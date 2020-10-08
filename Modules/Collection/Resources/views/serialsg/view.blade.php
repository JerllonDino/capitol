@extends('nav')

@section('content')
<div class="row">
    <div class="form-group col-sm-12">
        <dl class="dl-horizontal">
            <dt>SG TYPE</dt>
            <dd>{{ strtoupper( $base['serial']->sgtype->sg_type ) }}</dd>
            <dt>Serial Begin</dt>
            <dd>{{ $base['serial']->serial_start }}</dd>
            <dt>Serial End</dt>
            <dd>{{ $base['serial']->serial_end }}</dd>
            <dt>Serial QTY</dt>
            <dd>{{ $base['serial']->serial_qty }}</dd>
            
        </dl>
    </div>
</div>

@endsection

@section('js')

@endsection
