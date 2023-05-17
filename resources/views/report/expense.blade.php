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
                    <form action="{{route("expense_home")}}" method="post" enctype="multipart/form-data" id="report_form">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-2">
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="text" class="form-control form-control-sm fa fa-calendar datepicker rtm-iex-dsm-datepicker" name="start_date" id="start_date" @if(isset($start_date)) value="{{ date('d-m-Y',strtotime($start_date)) }}" @else value="{{ date('d-m-Y') }}" @endif readonly/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="text" class="form-control form-control-sm fa fa-calendar datepicker rtm-iex-dsm-datepicker" name="end_date" id="end_date" @if(isset($end_date)) value="{{ date('d-m-Y',strtotime($end_date)) }}" @else value="{{ date('d-m-Y') }}" @endif readonly/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Vehicle</label>
                                        <select class="form-control" name="vehicle_id" id="vehicle_id">
                                            @foreach ($vehicles as $vehicle)
                                                <option value='{{$vehicle->id}}' @if(isset($vehicle_id) && $vehicle_id == $vehicle->id) selected @endif>{{$vehicle->number}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label style="padding-top: 22px;"></label>
                                        <button type="submit" id="search" class="btn btn-md btn-block btn-success">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="box-body with-border">

                    @if ($message = Session::get('message'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
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
                                        <th>Vehicle Number</th>
                                        <th>Start Read</th>
                                        <th>End Read</th>
                                        <th>Petrol</th>
                                        <th>Diesel</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $count = 1; ?>
                                    @foreach($assign_toilets as $assign_toilet)
                                        <tr>
                                            <td>{{ $count++ }}.</td>
                                            <td>{{ $assign_toilet->expense_date }}</td>
                                            <td>{{ $assign_toilet->number }}</td>
                                            <td>{{ $assign_toilet->start_read }}</td>
                                            <td>{{ $assign_toilet->end_read }}</td>
                                            <td>{{ $assign_toilet->petrol_amount }}</td>
                                            <td>{{ $assign_toilet->diesel_amount }}</td>
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
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#start_date,#end_date').datepicker({
                autoclose: true,
                "format": "dd-mm-yyyy",
            });

            //$('#tab-header').html('Users');
            $.noConflict();
            $('#user_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                    }
                ]
            });

        });

    </script>

@endsection

@include('common')