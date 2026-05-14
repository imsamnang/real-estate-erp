<header class="top-header">
  <nav class="navbar navbar-expand">
    <div class="mobile-toggle-icon d-xl-none">
      <i class="bi bi-list"></i>
    </div>
    <div class="top-navbar d-none d-xl-block">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.dashboard') }}">{{ __('messages.common.dashboard') }}</a>
        </li>
      </ul>
    </div>
    <form class="searchbar d-none d-xl-flex ms-auto" onsubmit="return false;">
      <div class="position-absolute top-50 translate-middle-y search-icon ms-3"><i class="bi bi-search"></i></div>
      <input class="form-control" type="text" placeholder="{{ __('messages.common.search') }}">
    </form>
    <div class="top-navbar-right ms-3">
      <ul class="navbar-nav align-items-center">

        {{-- Language switcher (Livewire, no refresh) --}}
        <li class="nav-item dropdown">
          @livewire('language-switcher')
        </li>

        {{-- User dropdown --}}
        <li class="nav-item dropdown dropdown-large">
          <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
            <div class="user-setting d-flex align-items-center gap-1">
              @if(auth()->user()->avatar)
                <img src="{{ asset('storage/'.auth()->user()->avatar) }}" class="user-img rounded-circle" alt="">
              @else
                <span class="user-img rounded-circle d-inline-flex align-items-center justify-content-center bg-primary text-white" style="width:36px;height:36px;">
                  {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </span>
              @endif
              <div class="user-name d-none d-sm-block ms-2">{{ auth()->user()->display_name }}</div>
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="#">
                <div class="d-flex align-items-center">
                  <div class="ms-1">
                    <h6 class="mb-0 dropdown-user-name">{{ auth()->user()->display_name }}</h6>
                    <small class="mb-0 dropdown-user-designation text-secondary">{{ auth()->user()->position ?? '—' }}</small>
                  </div>
                </div>
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item" href="#">
                <div class="d-flex align-items-center">
                  <div class="setting-icon"><i class="bi bi-person-fill"></i></div>
                  <div class="setting-text ms-3"><span>{{ __('messages.common.profile') }}</span></div>
                </div>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                <div class="d-flex align-items-center">
                  <div class="setting-icon"><i class="bi bi-speedometer"></i></div>
                  <div class="setting-text ms-3"><span>{{ __('messages.common.dashboard') }}</span></div>
                </div>
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item">
                  <div class="d-flex align-items-center">
                    <div class="setting-icon"><i class="bi bi-lock-fill"></i></div>
                    <div class="setting-text ms-3"><span>{{ __('messages.common.logout') }}</span></div>
                  </div>
                </button>
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>
