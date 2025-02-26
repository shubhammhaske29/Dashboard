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
                    <h3 class="box-title"><?php echo "Edit Toilet"; ?></h3>

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
                            <form action="{{ route('edit_toilet', $toilet->id) }}" method="POST"
                                  enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group row">
                                    <div class="col-md-3">
                                    </div>
                                    <label class="col-md-3 form-control-label" for="zone">Zone</label>
                                    <div class="col-md-3">
                                        <select class="form-control" name="zone" id="zone">
                                            <option disabled>--Select Zone--</option>
                                            @foreach ($zones as $zone=>$wards)
                                                <option value='{{$zone}}' @if($toilet->zone == $zone) selected @endif>{{$zone}}</option>
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
                                    <label class="col-md-3 form-control-label" for="name">Name</label>
                                    <div class="col-md-3">
                                        <input class="form-control input-sm" type="text" name="name"
                                               value="{{ $toilet->name }}" id="name" placeholder="Toilet Name"
                                               maxlength="191" required="" autofocus="">
                                    </div><!--col-->
                                    <div class="col-md-3">
                                    </div>
                                </div><!--form-group-->
                                <div class="form-group row">
                                    <div class="col-md-3">
                                    </div>
                                    <label class="col-md-3 form-control-label" for="number">Number</label>
                                    <div class="col-md-3">
                                        <input class="form-control input-sm" type="text" name="number"
                                               value="{{ $toilet->number }}" id="number" placeholder="Toilet Number"
                                               maxlength="191" required="" autofocus="">
                                    </div><!--col-->
                                    <div class="col-md-3">
                                    </div>
                                </div><!--form-group-->
                                <div class="form-group row">
                                    <div class="col-md-3">
                                    </div>
                                    <label class="col-md-3 form-control-label" for="address">Address</label>
                                    <div class="col-md-3">
                                        <input class="form-control input-sm" type="text" name="address"
                                               value="{{ $toilet->address }}" id="address" placeholder="Toilet Address"
                                               maxlength="191" required="" autofocus="">
                                    </div><!--col-->
                                    <div class="col-md-3">
                                    </div>
                                </div><!--form-group-->
                                <div class="form-group row">
                                    <div class="col-md-3">
                                    </div>
                                    <label class="col-md-3 form-control-label" for="latitude">Latitude</label>
                                    <div class="col-md-3">
                                        <input class="form-control input-sm" type="text" name="latitude"
                                               value="{{ $toilet->latitude }}" id="latitude" placeholder="Latitude"
                                               maxlength="191" required="" autofocus="">
                                    </div><!--col-->
                                    <div class="col-md-3">
                                    </div>
                                </div><!--form-group-->
                                <div class="form-group row">
                                    <div class="col-md-3">
                                    </div>
                                    <label class="col-md-3 form-control-label" for="longitude">Longitude</label>
                                    <div class="col-md-3">
                                        <input class="form-control input-sm" type="text" name="longitude"
                                               value="{{ $toilet->longitude }}" id="longitude" placeholder="Longitude"
                                               maxlength="191" required="" autofocus="">
                                    </div><!--col-->
                                    <div class="col-md-3">
                                    </div>
                                </div><!--form-group-->

                                <div class="row">
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-sm-5">
                                        <a class="btn btn-danger" href="{{ route("toilet_home") }}">Cancel</a>
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


            </div>
            <div class="box-footer">
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            showEditWard();

            $("#zone").change(function () {
                showEditWard();
            });
        });

        function showEditWard() {
            let zone = $('#zone option:selected').val()
            let zones = '<?php echo json_encode($zones);?>';
            zones = JSON.parse(zones);
            let selectedWard = '{{$toilet->ward}}';

            let wards = zones[zone];
            let content = '';
            let index = 0;

            $.each(wards, function (k,ward) {
                if (selectedWard === ward) {
                    content += '<option value="' + ward + '" selected>' + ward + '</option>';
                } else {
                    content += '<option value="' + ward + '">' + ward + '</option>';
                }
                index++;
            });
            $('#ward').html(content);
        }

    </script>

@endsection

@include('common')