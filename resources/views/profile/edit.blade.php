@extends('adminlte::page')

@section('title', 'Profile')

@section('content_header')
    <h1>User Profile</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- Update Profile Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Update Profile Information</h3>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Update Password</h3>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <!--div class="card mt-3">
                <div class="card-header bg-danger">
                    <h3 class="card-title text-white">Delete Account</h3>
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </!--div-->
        </div>
    </div>
@stop
