@extends('nav')

@section('content')
<div class="row">
    <div class="form-group col-sm-6">
        <label for="month">Month</label>
        <select name="month" class="form-control" required disabled>
            @foreach ($base['months'] as $i => $month)
                @if ($base['month'] == ($i + 1))
                    <option value="{{ $i + 1 }}" selected>{{ $month }}</option>
                @else
                    <option value="{{ $i + 1 }}">{{ $month }}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-6">
        <label for="year">Year</label>
        <input type="number" class="form-control" step="1" name="year" value="{{ $base['year'] }}" readonly>
    </div>

    <div class="col-sm-12">
        <table class="table">

        @foreach ($data['category'] as $category)
            <tr>
                <td><div class="col-sm-12"><b>{{ $category->name }}</b></div></td>
                <td><div class="col-sm-12 text-center">Value</div></td>
                <td style="width:20px; max-width:20px;"></td>
                <td><div class="col-sm-12 text-center">Reconciliation</div></td>
                <td></td>
                <td><div class="col-sm-12 text-center">Total</div></td>
            </tr>

            @foreach ($category->group as $group)
                <tr>
                    <td><div class="col-sm-12">{{ $group->name }}</div></td>
                    <td><div class="col-sm-12"></div></td>
                      <td></td>
                     <td><div class="col-sm-12"></div></td>
                     <td></td>
                     <td><div class="col-sm-12"></div></td>
                </tr>

                @foreach ($group->title as $title)
                 <?php
                    $total_value = 0;
                ?>
                    <tr>
                        <td><div class="col-sm-11 col-sm-offset-1">{{ $title->name }}</div></td>
                        <td>
                            <div class="col-sm-12">
                                <?php
                                    $title_mnthly_prov_incme = $title->mnhtly_prov_income()->where('year','=',$base['year'])->where('month','=',$base['month'])->first();
                                ?>
                                @if ( $title_mnthly_prov_incme )
                                     <input class="form-control" type="number" step="0.01" name="title_value[]" value="{{ $title_mnthly_prov_incme->value }}" readonly>
                                    <input class="form-control" type="hidden" name="title_id[]" value="{{ $title->id }}">
                                @else
                                    <input class="form-control" type="number" step="0.01" name="title_value[]" value="0" readonly>
                                    <input class="form-control" type="hidden" name="title_id[]" value="{{ $title->id }}">
                                @endif
                            </div>
                        </td>
                        <td>
                            <strong>+</strong>
                        </td>
                         <td>
                            <div class="col-sm-12">

                                @if ( $title_mnthly_prov_incme )
                                <?php $total_value = $title_mnthly_prov_incme->reconciliation_value + $title_mnthly_prov_incme->value ; ?>
                                     <input class="form-control title_reconciliation" type="number" step="0.01" name="title_reconciliation[]" value="{{ $title_mnthly_prov_incme->reconciliation_value }}" readonly>
                                @else
                                    <input class="form-control title_reconciliation" type="number" step="0.01" name="title_reconciliation[]" value="0" readonly>
                                @endif
                            </div>
                        </td>
                        <td>
                            <strong>=</strong>
                        </td>
                        <td>
                            <input type="text"  class="form-control" name="total_value[]" readonly value="{{$total_value}}" />
                        </td>
                    </tr>

                    @foreach ($title->subs as $subs)
                     <?php
                                     $subtotal_value = 0;
                    ?>
                        <tr>
                            <td><div class="col-sm-10 col-sm-offset-2">{{ $subs->name }}</div></td>
                            <td>
                                <div class="col-sm-12">
                                <?php
                                    $substitle_mnthly_prov_incme = $subs->mnhtly_prov_income()->where('year','=',$base['year'])->where('month','=',$base['month'])->first();
                                ?>

                                @if ( $substitle_mnthly_prov_incme )
                                    <input class="form-control" type="number" step="0.01" name="subtitle_value[]" value="{{ $substitle_mnthly_prov_incme->value }}" readonly>
                                    <input class="form-control" type="hidden" name="subtitle_id[]" value="{{ $subs->id }}">
                                @else
                                    <input class="form-control" type="number" step="0.01" name="subtitle_value[]" value="0">
                                    <input class="form-control" type="hidden" name="subtitle_id[]" value="{{ $subs->id }}">
                                @endif
                                </div>
                            </td>
                            <td>
                            <strong>+</strong>
                        </td>
                            <td>
                                <div class="col-sm-12">
                                @if ( $substitle_mnthly_prov_incme )
                                <?php $subtotal_value = $substitle_mnthly_prov_incme->reconciliation_value + $substitle_mnthly_prov_incme->value;  ?>
                                    <input class="form-control subtitle_reconciliation" type="number" step="0.01" name="subtitle_reconciliation[]" value="{{ $substitle_mnthly_prov_incme->reconciliation_value }}">
                                @else
                                    <input class="form-control subtitle_reconciliation" type="number" step="0.01" name="subtitle_reconciliation[]" value="0">
                                @endif
                                </div>
                            </td>

                            <td>
                            <strong>=</strong>
                        </td>

                        <td>
                            <input type="text"  class="form-control" name="subtotal_value[]" readonly value="{{ $subtotal_value }}" />
                        </td>
                        </tr>

                    @endforeach
                @endforeach
            @endforeach
        @endforeach

        </table>
    </div>
</div>
@endsection