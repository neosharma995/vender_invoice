@extends('adminlte::page')

@section('title', 'Edit Item')

@section('content_header')
    <h1>Edit Item</h1>
@stop

@section('content')
    <form action="{{ route('items.update', $item) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Item Name</label>
            <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('items.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@stop
