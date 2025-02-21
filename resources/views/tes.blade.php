<!doctype html>
<html>
    <head>
        <title>Laravel Notify</title>
        @notifyCss
    </head>
    <body>

        @include('notify::components.notify')

        <h1>hello</h1>
        {{-- <x-notify::notify /> --}}
        @notifyJs
    </body>
</html>
