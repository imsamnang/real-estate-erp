@include('admin.layouts.admin_partials.head')

<body>

  <div class="wrapper">

    @include('admin.layouts.admin_partials.header')

    @include('admin.layouts.admin_partials.left_sidebar')

    <main class="page-content">

      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">@yield('breadcrumbTitle', __('messages.common.dashboard'))</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door"></i></a>
              </li>
              @hasSection('breadcrumb')
                @yield('breadcrumb')
              @else
                <li class="breadcrumb-item active" aria-current="page">@yield('pageTitle', __('messages.common.dashboard'))</li>
              @endif
            </ol>
          </nav>
        </div>
        <div class="ms-auto">
          @yield('breadcrumbActions')
        </div>
      </div>

      @yield('content')

    </main>

    <div class="overlay nav-toggle-icon"></div>

    <a href="#" class="back-to-top"><i class="bi bi-arrow-up"></i></a>

  </div>

  @include('admin.layouts.admin_partials.scripts')

</body>

</html>
