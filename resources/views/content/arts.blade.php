@extends ('layout')

@section ('content')

    <h1>Arts Courses</h1>

    <content-items 
        can-publish="{{ auth()->user()->canPublish() }}"
        can-add-courses="{{ auth()->user()->email == 'mike.minckler@brentwood.ca' }}"
        category="arts"
    ></content-items>

@endsection
