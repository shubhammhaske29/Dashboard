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
                    <h3 class="box-title"><?php echo "Report"; ?></h3>
                </div>
                <div class="box-body with-border">

                    @if ($message = Session::get('message'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>Whoops!</strong> There were some problems with your input.
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-sm-11">
                            <h4 class="card-title mb-0">Report</h4>
                        </div><!--col-->
                    </div><!--row-->
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table id="user_table" class="table table-bordered">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Assign Date</th>
                                        <th>Toilet Name</th>
                                        <th>Vehicle Number</th>
                                        <th>Cleaning Type</th>
                                        <th>Zone</th>
                                        <th>Ward</th>
                                        <th>Download</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $count = 1; ?>
                                    @foreach($assign_toilets as $assign_toilet)
                                        <tr>
                                            <td>{{ $count++ }}.</td>
                                            <td>{{ $assign_toilet->assign_date }}</td>
                                            <td>{{ $assign_toilet->name }}</td>
                                            <td>{{ $assign_toilet->number }}</td>
                                            <td>{{ $assign_toilet->cleaning_type_name }}</td>
                                            <td>{{ $assign_toilet->zone }}</td>
                                            <td>{{ $assign_toilet->ward }}</td>
                                            <td>
                                                <a href="{{ route("delete_assign_toilet",$assign_toilet->id) }}" data-toggle="tooltip" data-placement="top" title="download" class="btn btn-file btn-sm"><i class="fa fa-file-zip-o"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div><!--col-->
                    </div><!--row-->
                </div>
                <div class="box-footer">
                </div>
            </div>
        </div>

    </div>
    <script type="text/javascript">
        $( document ).ready(function() {
            //$('#tab-header').html('Users');
            $.noConflict();
            $('#user_table').DataTable();

        });

    </script>

@endsection

@include('common')