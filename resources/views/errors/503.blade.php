@extends('layouts.app')
@section('title', 'Site Under Maintenance')
@section('content')
<div class="container text-center" style="margin-top: 100px;">
    <h1>We'll be back soon!</h1>
    <p class="lead">Sorry for the inconvenience, but we're performing some maintenance at the moment.<br>
    If you are an admin, you can <a href="/{{ \DB::table('settings')->where('key', 'maintenance_secret')->value('value') }}">access the site here</a>.</p>
    <p>— The Team</p>
</div>
@endsection 
