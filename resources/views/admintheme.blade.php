@extends("la.layouts.app")

@section("contentheader_title", "Admin Team Listing")
@section("htmlheader_title", "Admin Team Listing")

@section("main-content")

<div class="container-fluid">
    <div class="col-md-12">
        <div class="box box-warning">
            <div>
                <table style="width:1024px;" class="table table-bordered">
                    <tr style="background-color: darkseagreen;">
                        <th colspan="6" class="p-0" style="text-align: center; line-height: 10px;">ADMIN TEAM</th>
                    </tr>
                    <tr style="background-color: darkseagreen;">
                        <th class="p-1" style="text-align: center;">NO</th>
                        <th class="p-1" style="text-align: center;">Work Description</th>
                        <th class="p-1" style="text-align: center;">Time Frame</th>
                        <th class="p-1" style="text-align: center;">PIC(who)</th>
                        <th class="p-1" style="text-align: center;">Supporting</th>
                        <th class="p-1" style="text-align: center;">Report To(whom)</th>
                    </tr>
                    @foreach($SOP_Management_Types as $SOP_Management_Type)
                        <tr class="p-0" style="background-color: #fff;">
                            <th style="border:solid white;" class="">{{ $SOP_Management_Type->id }}</th>
                            <th style="border:solid white;" colspan="5" class="">{{ $SOP_Management_Type->name }}</th>
                        </tr> 
                        <tr class="p-0" style="background-color: lavenderblush;">
                            <td colspan="2" class="text-center">
                            <?php

                            $descriptions = DB::table('sop_set_ups')
                                                    ->join('sop_management_types', 'sop_set_ups.sop_management_type', '=', 'sop_management_types.id')
                                                    ->select('sop_set_ups.description', 'sop_set_ups.name')
                                                    ->where('sop_set_ups.sop_management_type', '=', $SOP_Management_Type->id)
                                                    ->get();
                            ?>
                                @foreach($descriptions as $description)
                                    {{ $description->name }}
                                    <br><bR>
                                @endforeach
                            </td>

                            <?php

                            $query = DB::table('sop_set_ups')
                                        ->join('frames', 'sop_set_ups.frame', '=', 'frames.id')
                                        ->select('frames.name')
                                        ->where('sop_set_ups.sop_management_type', '=', $SOP_Management_Type->id)
                                    ->orderBy('sop_set_ups.id')
                                        ->get();

                            ?>
                            
                            <td class="">
                                @foreach($query as $q)
                                    {{ $q->name }}
                                    <br><br>
                                @endforeach
                            </td> 

                            <?php

                            $pices = DB::table('sop_set_ups')
                                        ->join('users', 'sop_set_ups.pic', '=', 'users.id')
                                        ->select('users.name')
                                        ->where('sop_set_ups.sop_management_type', '=', $SOP_Management_Type->id)
                                        ->orderBy('sop_set_ups.id')
                                        ->get();

                            ?>

                            <td class="">
                                @foreach($pices as $pic)
                                    {{ $pic->name }}
                                    <br><br>
                                @endforeach
                            </td>

                            <?php

                            $supportings = DB::table('sop_set_ups')
                                            ->join('users', 'sop_set_ups.supporting', '=', 'users.id')
                                            ->select('users.name')
                                            ->where('sop_set_ups.sop_management_type', '=', $SOP_Management_Type->id)
                                            ->orderBy('sop_set_ups.id')
                                            ->get();
                            ?>

                            <td class="">
                                @foreach($supportings as $support)
                                    {{ $support->name }}
                                    <br><br>
                                @endforeach
                            </td>

                            <?php

                            $reports = DB::table('sop_set_ups')
                                            ->join('users', 'sop_set_ups.report_to', '=', 'users.id')
                                            ->select('users.name')
                                            ->where('sop_set_ups.sop_management_type', '=', $SOP_Management_Type->id)
                                            ->orderBy('sop_set_ups.id')
                                            ->get();
                            ?>

                            <td class="">
                                @foreach($reports as $report)
                                    {{ $report->name }}
                                    <br><br>
                                @endforeach
                            </td>        
                        </tr>
                    @endforeach                
                    
                </table>
            </div>
        </div>
    </div>
</div>
@endsection