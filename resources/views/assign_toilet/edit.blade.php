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
                    <h3 class="box-title"><?php echo "Edit Checker Assignment"; ?></h3>

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
                            <form action="{{ route('edit_user_checker', $user_checker->id) }}" method="POST"
                                  enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group row">
                                    <div class="col-md-3">
                                    </div>
                                    <label class="col-md-3 form-control-label" for="user_id">Users</label>
                                    <div class="col-md-3">
                                        <select class="form-control" name="user_id" id="user_id">
                                            <option value='{{$user->id}}'>{{$user->name}}</option>
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
                                                <option value='{{$zone}}' @if($user_checker->zone == $zone) selected @endif>{{$zone}}</option>
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
                                <div class="row">
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-sm-5">
                                        <a class="btn btn-danger" href="{{ route("user_checker_home") }}">Cancel</a>
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
            let zones = '<?php echo json_encode(config('common.zones'));?>';
            zones = JSON.parse(zones);
            let selectedWard = '{{$user_checker->ward}}';

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