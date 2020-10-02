@extends('layout')

@section('content')

<h1>Typo3 Content Updater Tool</h1>

<div class="section">
    @if (!auth()->check())
        <p>Please <a href="{{ route('login') }}" class="button">Login</a> to update content</p> 
    @else
        <div class="link"><a href="{{ route('courses') }}" class="button">Update Academic Courses</a></div>
        <div class="link"><a href="{{ route('profiles') }}" class="button">Update Faculty Bios</a></div>
        <div class="link"><a href="{{ route('arts') }}" class="button">Update Arts Courses</a></div>
    @endif
</div>

@endsection
