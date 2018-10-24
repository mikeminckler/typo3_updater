@extends ('layout')

@section ('content')

    <h1>Content Items</h1>

    <content-items 
        can-publish="{{ auth()->user()->canPublish() }}"
        can-add-courses="{{ auth()->user()->email == 'mike.minckler@brentwood.ca' }}"
    ></content-items>

@endsection
