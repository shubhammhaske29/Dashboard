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
                    <h3 class="box-title"><?php echo "Toilets"; ?></h3>
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
                            <h4 class="card-title mb-0">Toilets</h4>
                        </div><!--col-->

                        <div class="col-sm-1 right">
                            <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                                <a href="{{ route("add_toilet") }}" class="btn btn-success ml-1" data-toggle="tooltip" data-placement="top" title="Add">Add <i class="fa fa-plus-circle"></i></a>
                            </div><!--btn-toolbar-->
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
                                        <th>Zone</th>
                                        <th>Ward</th>
                                        <th>Name</th>
                                        <th>Number</th>
                                        <th>Address</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Edit/Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $count = 1; ?>
                                    @foreach($toilets as $toilet)
                                        <tr>
                                            <td>{{ $count++ }}.</td>
                                            <td>{{ $toilet->zone }}</td>
                                            <td>{{ $toilet->ward }}</td>
                                            <td>{{ $toilet->name }}</td>
                                            <td>{{ $toilet->number }}</td>
                                            <td>{{ $toilet->address }}</td>
                                            <td>{{ $toilet->latitude }}</td>
                                            <td>{{ $toilet->longitude }}</td>
                                            <td>
                                                <a href="{{ route("edit_toilet",$toilet->id) }}" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                                <a href="{{ route("delete_toilet",$toilet->id) }}" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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