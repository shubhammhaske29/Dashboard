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
                    <h3 class="box-title"><?php echo "Ward"; ?></h3>

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
                            <form action="{{ route('add_ward') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="zone">Zone</label>
                                            <div class="col-md-3">
                                                <select class="form-control" name="zone" id="zone">
                                                    <option disabled>--Select Zone--</option>
                                                    @foreach (config('common.temp_zones') as $zone=>$wards)
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
                                                <input class="form-control input-sm" type="text" name="ward"
                                                       value="{{ old('number') }}" id="ward" placeholder="Ward"
                                                       maxlength="191" required="" autofocus="">
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
                                        <a class="btn btn-danger" href="{{ route("ward_home") }}">Cancel</a>
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
            showWard();

            $("#zone").change(function () {
                showWard();
            });
        });

    </script>

@endsection

@include('common')
