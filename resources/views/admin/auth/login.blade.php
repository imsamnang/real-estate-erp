<!doctype html>
<html lang="{{ app()->getLocale() }}" class="minimal-theme">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ __('messages.common.login') }} — {{ __('messages.app.name') }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Battambang:wght@400;700&display=swap" rel="stylesheet">
  <link href="{{ asset('assets/backend/assets/css/style.css') }}" rel="stylesheet">
  
</head>
<body class="bg-light">
  <main class="auth-wrapper d-flex align-items-center justify-content-center min-vh-100 px-3">
    <div class="auth-card card shadow-lg border-0" style="max-width:420px;width:100%;border-radius:16px;">
      <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
          <div class="logo-circle d-inline-flex align-items-center justify-content-center bg-primary text-white mb-3"
               style="width:56px;height:56px;border-radius:14px;font-weight:700;font-size:1.2rem;">RE</div>
          <h4 class="mb-1">{{ __('messages.common.welcome_back') }}</h4>
          <p class="text-muted small mb-0">{{ __('messages.common.sign_in_subtitle') }}</p>
        </div>

        @if ($errors->any())
          <div class="alert alert-danger py-2 px-3 small mb-3">
            {{ $errors->first() }}
          </div>
        @endif

        <form action="{{ route('admin.login.attempt') }}" method="POST" autocomplete="off" novalidate>
          @csrf
          <div class="mb-3">
            <label class="form-label small text-muted">{{ __('messages.common.username') }} / {{ __('messages.common.email') }}</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" name="login" value="{{ old('login') }}" required autofocus
                     class="form-control @error('login') is-invalid @enderror"
                     placeholder="admin">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small text-muted">{{ __('messages.common.password') }}</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" name="password" required
                     class="form-control @error('password') is-invalid @enderror"
                     placeholder="••••••••">
            </div>
          </div>

          <div class="form-check mb-3">
            <input type="checkbox" name="remember" value="1" id="remember" class="form-check-input">
            <label for="remember" class="form-check-label small">{{ __('messages.common.remember_me') }}</label>
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="bi bi-box-arrow-in-right me-1"></i>
            {{ __('messages.common.sign_in') }}
          </button>
        </form>

        <p class="text-center text-muted small mt-4 mb-0">
          {{ __('messages.app.name') }} &copy; {{ date('Y') }}
        </p>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.js"></script>
  @flasher_render
</body>
</html>
