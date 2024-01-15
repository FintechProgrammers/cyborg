<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ Config::get('app.name', 'Laravel') }} | @yield('title')</title>

    @include('admin.layouts.partials._styles')
</head>

<body data-sidebar="dark">
    <div id="layout-wrapper ">
        @include('admin.layouts.partials._header')
        @include('admin.layouts.partials._sidebar')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">@yield('title')</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a
                                                href="#">{{ Config::get('app.name', 'Laravel') }}</a></li>
                                        <li class="breadcrumb-item active">@yield('title')</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.partials._scripts')

    <script src="{{ asset('assets/admin/js/app.js') }}"></script>

    @stack('scripts')

</body>

</html>
