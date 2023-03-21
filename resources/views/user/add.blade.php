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
                    <h3 class="box-title"><?php echo "Add User"; ?></h3>

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
                            <form action="{{ route('add_user') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="name">Name</label>
                                            <div class="col-md-3">
                                                <input class="form-control input-sm" type="text" name="name"
                                                       value="{{ old('name') }}" id="name" placeholder="User Name"
                                                       maxlength="191" required="" autofocus="">
                                            </div><!--col-->
                                            <div class="col-md-3">
                                            </div>
                                        </div><!--form-group-->

                                        <!--                                        <div class="form-group row">
                                            <label class="col-md-3 form-control-label" for="email">Email</label>
                                            <div class="col-md-9">
                                                <input class="form-control input-sm" type="email" name="email"
                                                       value="{{ old('email') }}" id="email" placeholder="Email Address"
                                                       maxlength="191" required="" autofocus="">
                                            </div>&lt;!&ndash;col&ndash;&gt;
                                        </div>&lt;!&ndash;form-group&ndash;&gt;-->

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="role_id">Role</label>
                                            <div class="col-md-3">
                                                <select class="form-control" name="role_id" id="role_id">
                                                    <option disabled>--Select User Role--</option>
                                                    @foreach (config('common.user_ids') as $id => $name)
                                                        @if(@$id != 1)
                                                            <option value='{{$id}}'>{{$name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div><!--col-->
                                            <div class="col-md-3">
                                            </div>
                                        </div><!--form-group-->

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="password">Password</label>
                                            <div class="col-md-3">
                                                <input class="form-control input-sm" type="password" name="password"
                                                       value="{{ old('password') }}" id="password"
                                                       placeholder="Password" maxlength="191" required="" autofocus="">
                                            </div><!--col-->
                                            <div class="col-md-3">
                                            </div>
                                        </div><!--form-group-->

                                        <div class="form-group row">
                                            <div class="col-md-3">
                                            </div>
                                            <label class="col-md-3 form-control-label" for="confirm_password">Confirm
                                                Password</label>
                                            <div class="col-md-3">
                                                <input class="form-control input-sm" type="password"
                                                       name="confirm_password" value="{{ old('confirm_password') }}"
                                                       id="confirm_password" placeholder="Confirm Password"
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
                                        <a class="btn btn-danger" href="{{ route("user_home") }}">Cancel</a>
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
        $(window).load(function () {

        });

    </script>

@endsection

@include('common')
