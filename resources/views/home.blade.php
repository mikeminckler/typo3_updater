@extends('layout')

@section('content')

<h1>Typo3 Content Updater Tool</h1>

<div class="section">
    @if (!auth()->check())
        <p>Please <a href="{{ route('login') }}" class="button">Login</a> to update content</p> 
    @else
        <div class="link"><a href="{{ route('content') }}" class="button">Update Content</a></div>
    @endif
</div>

@endsection
