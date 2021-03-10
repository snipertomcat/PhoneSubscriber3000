<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        @page { margin: 0px; }
        body { margin: 50px 0 0 0; }
    </style>
    <body class="path-{{ getBodyClass() }} route-{{ Route::currentRouteName() }}">
            <div v-cloak>
                @yield('content')
            </div>
    </body>
</html>
