@extends('adminlte::page')

@section('title', 'Add Item')

@section('content_header')
    <h1>Add Item</h1>
@stop

@section('content')
    <form action="{{ route('items.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Item Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('items.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@stop
