<div class="dropdown" wire:ignore.self>
  <a class="nav-link dropdown-toggle dropdown-toggle-nocaret px-2" href="#" data-bs-toggle="dropdown">
    <i class="bi bi-translate me-1"></i>
    <span class="d-none d-sm-inline">{{ $locales[$locale] ?? strtoupper($locale) }}</span>
  </a>
  <ul class="dropdown-menu dropdown-menu-end">
    @foreach($locales as $code => $label)
      <li>
        <button type="button"
                class="dropdown-item d-flex align-items-center gap-2 {{ $locale === $code ? 'active' : '' }}"
                wire:click="switch('{{ $code }}')">
          <span class="badge bg-light text-dark text-uppercase">{{ $code }}</span>
          <span>{{ $label }}</span>
          @if($locale === $code)
            <i class="bi bi-check2 ms-auto"></i>
          @endif
        </button>
      </li>
    @endforeach
  </ul>
</div>
