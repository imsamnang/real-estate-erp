<div class="dropdown" wire:ignore.self>
  <a class="nav-link dropdown-toggle dropdown-toggle-nocaret px-2 d-inline-flex align-items-center"
     href="#"
     role="button"
     data-bs-toggle="dropdown"
     aria-expanded="false"
     aria-label="{{ __('messages.common.language') ?? 'Language' }}">
    <i class="bi bi-translate me-1"></i>
    <span class="d-none d-sm-inline">{{ $locales[$locale] ?? strtoupper($locale) }}</span>
    <i class="bi bi-chevron-down dropdown-chevron"></i>
  </a>
  <ul class="dropdown-menu dropdown-menu-end lang-switcher-menu">
    @foreach($locales as $code => $label)
      <li>
        <button type="button"
                class="dropdown-item {{ $locale === $code ? 'active' : '' }}"
                wire:click="switch('{{ $code }}')">
          <span class="lang-code">{{ strtoupper($code) }}</span>
          <span class="lang-label">{{ $label }}</span>
          @if($locale === $code)
            <i class="bi bi-check2 lang-check"></i>
          @endif
        </button>
      </li>
    @endforeach
  </ul>
</div>
