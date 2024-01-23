<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ Config::get('app.name', 'Laravel') }} | @yield('title')</title>

    @include('admin.layouts.partials._styles')
</head>

<body>
    <div class="auth-page">
        <div class="container-fluid p-0">
            <div class="row g-0 justify-content-center">
                <div class="col-xxl-5 col-lg-5 col-md-5">
                    <div class="auth-full-page-content d-flex p-sm-5 p-4">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-4 mb-md-5 text-center">
                                    <a href="#" class="d-block auth-logo">
                                        <img src="{{ asset('assets/admin/images/cyborlogo.png') }}" alt=""
                                            height="28"> <span class="logo-txt">{{ Config::get('app.name') }}</span>
                                    </a>
                                </div>
                                <div class="card card-body auth-content my-auto">
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end auth full page content -->
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container fluid -->
    </div>

    @include('admin.layouts.partials._scripts')
</body>

</html>
