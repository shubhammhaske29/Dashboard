<?php
/**
 * Created by PhpStorm.
 * User: jitendra
 * Date: 5/5/18
 * Time: 4:29 PM
 */
?>

@extends('layouts.app')

@section('contentheader_title')
@endsection

@section('main-content')

    <div class="container-fluid spark-screen">
    <div class="col-md-6 col-md-offset-3">
        @if(Session::has('message'))
            <div class="callout callout-success bg-green disabled">
                <p>{{ Session::get('message') }}</p>
            </div>
        @endif
        @if(Session::has('error'))
            <div class="callout callout-danger bg-red disabled">
                <p>{{ Session::get('error') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div id="error" class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <div class="header">
                    There are following errors:
                </div>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="box box-primary">
            <div class="box-header with-border text-center">
                <h3 class="box-title">Change Password</h3>
            </div>
            <form  enctype="multipart/form-data" action="{{ route("change-password") }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="box-body">
                    <div class="form-group">
                        <label for="old_password">Old Password</label>
                        <input required type="password" class="form-control" id="old_password" name="old_password" placeholder="Old Password" value="{{ old('old_password') }}">
                    </div>
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input required type="password" class="form-control" id="password" name="password" placeholder="New Password" value="{{ old('password') }}">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input required type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" value="{{ old('password_confirmation') }}">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>

@endsection
