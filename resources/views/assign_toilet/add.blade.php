@extends('layouts.app')

@section('contentheader_title')
@endsection

@section('main-content')
    <?php
    date_default_timezone_set('Asia/Kolkata');
    ?>
    <div class="container-fluid spark-screen">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border text-center">
                    <h3 class="box-title"><?php echo "Assign Toilet"; ?></h3>

                </div>
                <div class="box-body with-border">
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            @if(Session::has('error'))
                                <div class="alert alert-danger alert-danger" id="alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                                    </button>
                                    <h4><i class="icon fa fa-check"></i> Alert!</h4>
                                    {{ Session::get('error') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger" id="alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                                    </button>
                                    <h4><i class="icon fa fa-check"></i> Alert!</h4>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('assign_toilet') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-sm-12">

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="assign_date">Date</label>
                                            <div class="col-md-3">
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control form-control-sm fa fa-calendar datepicker rtm-iex-dsm-datepicker" name="assign_date" id="assign_date" value="{{ date('d-m-Y') }}" readonly/>
                                                </div>
                                            </div><!--col-->
                                            <div class="col-md-3">
                                            </div>
                                        </div><!--form-group-->

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="vehicle_id">Vehicles</label>
                                            <div class="col-md-3">
                                                <select class="form-control" name="vehicle_id" id="vehicle_id">
                                                    <option disabled>--Select Vehicle--</option>
                                                    @foreach ($vehicles as $vehicle)
                                                        <option value='{{$vehicle->id}}'>{{$vehicle->number}}</option>
                                                    @endforeach
                                                </select>
                                            </div><!--col-->
                                            <div class="col-md-3">
                                            </div>
                                        </div><!--form-group-->

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="cleaning_type_id">Cleaning Type</label>
                                            <div class="col-md-3">
                                                <select class="form-control" name="cleaning_type_id" id="cleaning_type_id">
                                                    <option disabled>--Select Cleaning Type--</option>
                                                    @foreach (config('common.cleaning_types') as $id=>$type)
                                                        <option value='{{$id}}'>{{$type}}</option>
                                                    @endforeach
                                                </select>
                                            </div><!--col-->
                                            <div class="col-md-3">
                                            </div>
                                        </div><!--form-group-->

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="zone">Zone</label>
                                            <div class="col-md-3">
                                                <select class="form-control" name="zone" id="zone">
                                                    <option disabled>--Select Zone--</option>
                                                    @foreach (config('common.zones') as $zone=>$wards)
                                                        <option value='{{$zone}}'>{{$zone}}</option>
                                                    @endforeach
                                                </select>
                                            </div><!--col-->
                                            <div class="col-md-3">
                                            </div>
                                        </div><!--form-group-->

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="ward">Ward</label>
                                            <div class="col-md-3">
                                                <select class="form-control" name="ward" id="ward">
                                                </select>
                                            </div><!--col-->
                                            <div class="col-md-3">
                                            </div>
                                        </div><!--form-group-->

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="toilet_id">Toilet</label>
                                            <div class="col-md-3">
                                                <select class="form-control " name="toilet_id[]" id="toilet_id" multiple="multiple">

                                                </select>
                                            </div><!--col-->
                                            <div class="col-md-3">
                                            </div>
                                        </div><!--form-group-->

                                    </div><!--col-->
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-sm-5">
                                        <a class="btn btn-danger" href="{{ route("assign_toilet_home") }}">Cancel</a>
                                    </div><!--col-->

                                    <div class="col-sm-1 text-right">
                                        <button class="btn btn-success pull-right" type="submit">Save</button>
                                    </div><!--col-->
                                    <div class="col-md-3">
                                    </div>
                                </div><!--row-->
                            </form>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                </div>
            </div>
        </div>

    </div>
    <script>
        $(document).ready(function () {

            $('#assign_date').datepicker({
                autoclose: true,
                "startDate": "{{ date("d-m-Y") }}",
                "endDate": "{{ date("d-m-Y", strtotime("+1 day")) }}",
                "format": "dd-mm-yyyy",
                "maxDate": new Date()
            });

            showToiletWard();

            $("#zone").change(function () {
                showToiletWard();
            });
            $("#ward").change(function () {
                showToilets();
            });
            $("#assign_date").change(function () {
                showToilets();
            });
        });

        function showToiletWard() {
            let zone = $('#zone option:selected').val()
            let zones = '<?php echo json_encode(config('common.zones'));?>';
            zones = JSON.parse(zones);
            let wards = zones[zone];
            let content = '';
            let index = 0;

            $.each(wards, function (k,ward) {
                if (index === 0) {
                    content += '<option value="' + ward + '" selected>' + ward + '</option>';
                } else {
                    content += '<option value="' + ward + '">' + ward + '</option>';
                }
                index++;
            });
            $('#ward').html(content);
            showToilets();
        }

        function showToilets() {

            let ward = $('#ward option:selected').val();
            let date = $('#assign_date').val();

            let toilet_list = '<?php echo json_encode($toilet_list);?>';
            toilet_list = JSON.parse(toilet_list);
            let toilets = toilet_list[date][ward];
            let content = '';
            let index = 0;

            $.each(toilets, function (k,toilet) {
                if (index === 0) {
                    content += '<option value="' + k + '" selected>' + toilet['name'] + '</option>';
                } else {
                    content += '<option value="' + k + '">' + toilet['name'] + '</option>';
                }
                index++;
            });
            $("#toilet_id").html(content);

            $("#toilet_id").multiselect({
                nonSelectedText: 'Select Toilet',
                enableFiltering: true,
                includeSelectAllOption: true,
                enableCaseInsensitiveFiltering: true,
                buttonWidth:'420px'
            });

            $("#toilet_id").multiselect('rebuild');
            $("#toilet_id").multiselect('selectAll', false);
            $("#toilet_id").multiselect('updateButtonText');

        }

    </script>

@endsection

@include('common')
