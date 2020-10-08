                <table class="table table-condensed">
                    <tr >
                        <td><b>SUMMARY OF COLLECTION</b></td>
                        <td >
                            <span class="hidden">
                            <?php $total = 0; ?>
                            </span>
                        </td>
                    </tr>
                    @foreach($accounts as $i => $account)
                        @foreach($account['titles'] as $j => $title)
                        <tr >
                            <td>{{ $j }}</td>
                            <td class="val text-right">
                                <?php $total += $title['total']; ?> 
                                {{ round($title['total'], 2) }}
                            </td>
                        </tr>
                        @endforeach

                        @foreach($account['subtitles'] as $j => $subtitle)
                        <tr >
                            <td>{{ $j }}</td>
                            <td class="val text-right">
                               <?php $total += $subtitle['total']; ?> 
                                {{ round($subtitle['total'], 2) }}
                            </td>
                        </tr>
                        @endforeach
                    @endforeach

                    @if ($bac_type_1 > 0 && $_GET['type'] == 1)
                    <tr >
                        <td>BAC Goods & Services</td>
                        <td class="val">
                            <?php $total += $bac_type_1; ?>
                            {{ round($bac_type_1, 2) }}
                        </td>
                    </tr>
                    @endif

                    @if ($bac_type_2 > 0 && $_GET['type'] == 1)
                    <tr >
                        <td>BAC INFRA</td>
                        <td class="val text-right">
                            <?php $total += $bac_type_2; ?>
                            {{ round($bac_type_2, 2) }}
                        </td>
                    </tr>
                    @endif

                    @if ($bac_type_3 > 0 && $_GET['type'] == 1)
                    <tr >
                        <td>BAC Drugs & Meds</td>
                        <td class="val text-right">
                            <?php $total += $bac_type_3; ?>

                            {{ round($bac_type_3, 2) }}
                        </td>
                    </tr>
                    @endif

                    <tr class="set-border-tb">
                        <td ><b>TOTAL</b></td>
                        <td class="val text-right">
                            <b>{{ round($total, 2) }}</b>
                        </td>
                    </tr>

                </table>
            <table class="table">
                <tr>
                    <td><b>Municipal/Barangay Share</b></td>
                    <td>
                       <?php $total = 0; ?>
                    </td>
                </tr>
                @foreach ($shares as $i => $share)
                    <tr >
                        <td><b>{{ $share['name'] }}</b></td>
                        <td class="val">
                            @if (isset($amusement_shares[$i]))
                                <?php  $share_value = $share['total_share'] - $amusement_shares[$i]['total_share']; ?>
                            @else
                                <?php $share_value = $share['total_share']; ?>
                            @endif
                            <?php $total += $share_value; ?>

                            {{ round($share_value, 2) }}
                        </td>
                    </tr>
                    @foreach ($share['barangays'] as $j => $barangay)
                        <tr >
                            <td><div class="brgy">{{ $barangay['name'] }}</div></td>
                            <td class="val text-right">
                               
                                <?php $total += $barangay['total_share']; ?>
                                
                                {{ round($barangay['total_share'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                <tr class="set-border-tb">
                    <td ><b>TOTAL</b></td>
                    <td class="val text-right">
                        <b>{{ round($total, 2) }}</b>
                    </td>
                </tr>
            </table>


            <table class="table">
                <tr>
                    <td><b>Amusement Share</b></td>
                    <td>
                        <?php $total = 0; ?>
                    </td>
                </tr>
                @foreach ($amusement_shares as $i => $share)
                    <tr>
                        <td><b>{{ $share['name'] }}</b></td>
                        <td class="val text-right">
                          <?php $total += $share['total_share']; ?>
                            {{ round($share['total_share'], 2) }}
                        </td>
                    </tr>
                @endforeach
                <tr class="set-border-tb">
                    <td class=""><b>TOTAL</b></td>
                    <td class="val text-right">
                        <b>{{ round($total, 2) }}</b>
                    </td>
                </tr>
            </table>
