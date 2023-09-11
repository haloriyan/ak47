<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('css/base/column.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/color.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/button.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @yield('head')
</head>
<body>
    
<header class="fixed top-0 left-0 right-0 bg-white flex row item-center pl-4 pr-4 h-60 border bottom gap-20 index-4">
    <div class="pointer text size-24" onclick="toggleLeftMenu()">
        <i class="bx bx-menu"></i>
    </div>
    <h1 class="text size-20 m-0 flex grow-1">@yield('title')</h1>
    <div class="flex row item-center gap-10">
        <div class="h-40 ratio-1-1 rounded-max bg-primary flex centerize">
            {{ $myData->initial }}
        </div>
        <div>{{ $myData->name }}</div>
    </div>
</header>

<div class="LeftMenuOverlay bg-black transparent fixed top-0 left-0 right-0 bottom-0 d-none index-2" onclick="toggleLeftMenu()"></div>
<nav class="LeftMenu fixed bottom-0 w-20 bg-white index-4" style="top: 61px">
    <div class="h-30"></div>
    @php
        $routeName = Route::currentRouteName();
    @endphp
    <a href="{{ route('admin.dashboard') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-40 text {{ $routeName == 'admin.dashboard' ? 'active' : 'black' }}">
        <i class="bx bx-home"></i>
        <div class="flex grow-1">Dashboard</div>
        <i class="bx bx-chevron-right"></i>
    </a>

    <div class="text size-12 muted mt-2 ml-2 mb-1">Master Data</div>
    <a href="{{ route('admin.package') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-40 text {{ $routeName == 'admin.package' ? 'active' : 'black' }}">
        <i class="bx bx-box"></i>
        <div class="flex grow-1">Packages</div>
        <i class="bx bx-chevron-right"></i>
    </a>
    <a href="{{ route('admin.user') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-40 text {{ $routeName == 'admin.user' ? 'active' : 'black' }}">
        <i class="bx bx-group"></i>
        <div class="flex grow-1">Users</div>
        <i class="bx bx-chevron-right"></i>
    </a>
    <a href="{{ route('admin.organizer') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-40 text {{ $routeName == 'admin.organizer' ? 'active' : 'black' }}">
        <i class="bx bx-group"></i>
        <div class="flex grow-1">Organizer</div>
        <i class="bx bx-chevron-right"></i>
    </a>
    <a href="{{ route('admin.event') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-40 text {{ $routeName == 'admin.event' ? 'active' : 'black' }}">
        <i class="bx bx-calendar"></i>
        <div class="flex grow-1">Events</div>
        <i class="bx bx-chevron-right"></i>
    </a>
    <a href="{{ route('admin.category') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-40 text {{ $routeName == 'admin.category' ? 'active' : 'black' }}">
        <i class="bx bx-calendar"></i>
        <div class="flex grow-1">Categories</div>
        <i class="bx bx-chevron-right"></i>
    </a>
    <a href="{{ route('admin.topic') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-40 text {{ $routeName == 'admin.topic' ? 'active' : 'black' }}">
        <i class="bx bx-comment"></i>
        <div class="flex grow-1">Topic</div>
        <i class="bx bx-chevron-right"></i>
    </a>
    <a href="{{ route('admin.city') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-40 text {{ $routeName == 'admin.city' ? 'active' : 'black' }}">
        <i class="bx bx-map"></i>
        <div class="flex grow-1">Cities</div>
        <i class="bx bx-chevron-right"></i>
    </a>

    <div class="h-40"></div>
    <a href="{{ route('admin.logout') }}" class="MenuItem flex row gap-20 item-center pl-2 pr-2 h-40 text textblack red">
        <i class="bx bx-log-out"></i>
        <div class="flex grow-1">Logout</div>
    </a>
</nav>

<div class="content absolute left-0 right-0">
    @yield('content')
</div>

<script src="{{ asset('js/base.js') }}"></script>
<script>
    const toggleLeftMenu = () => {
        let LeftMenu = select(".LeftMenu");
        let overlay = select(".LeftMenuOverlay");
        if (LeftMenu.classList.contains('active')) {
            overlay.classList.add('d-none');
        } else {
            overlay.classList.remove('d-none');
        }

        LeftMenu.classList.toggle('active');
    }
</script>
@yield('javascript')

</body>
</html>