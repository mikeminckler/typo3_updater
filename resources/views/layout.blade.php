<!doctype html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <title>Content Updater</title>

        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">

    </head>

    <body>

        <div id="app">

            <div class="header">
                <div class="user-info">
                    @if (auth()->check())
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div @click="logout" class="button">Logout</div>
                    @else

                    @endif
                </div>
            </div>

             <div class="content">
                @yield ('content')
            </div>

        </div>

        <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
        <script src="{{ mix('/js/app.js') }}"></script>

    </body>

</html>
