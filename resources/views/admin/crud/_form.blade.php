@php
  $row = $row ?? null;
@endphp
<div class="row g-3">
  @foreach($cfg['fields'] as $f)
    @php
      [$name, $type, $labelKey, $opts] = array_pad($f, 4, []);
      $opts = $opts ?? [];
      // Read-only fields are system-set (timestamps, audit blobs, *_by user
      // ids set by approvers/cancellers) and are not rendered in the form.
      if (! empty($opts['read_only'])) continue;
      $label = __('messages.'.$labelKey);
      $value = old($name, $row?->{$name});
      $required = ($opts['required'] ?? false) || ($row === null && ($opts['required_on_create'] ?? false));
      $colClass = in_array($type, ['text','json']) ? 'col-12' : 'col-md-6';
    @endphp

    <div class="{{ $colClass }}">
      <label class="form-label">
        {{ $label }} @if($required) <span class="text-danger">*</span> @endif
      </label>

      @switch($type)
        @case('string')
          <input type="text" name="{{ $name }}" value="{{ $value }}"
                 class="form-control @error($name) is-invalid @enderror" {{ $required ? 'required' : '' }}
                 {{ ($opts['auto'] ?? false) && $row === null ? 'placeholder='.__('messages.common.code').'…' : '' }}>
          @break

        @case('password')
          <input type="password" name="{{ $name }}"
                 class="form-control @error($name) is-invalid @enderror"
                 placeholder="{{ $row === null ? __('messages.common.password') : '••••••••' }}"
                 {{ $row === null && ($opts['required_on_create'] ?? false) ? 'required' : '' }}>
          @break

        @case('text')
          <textarea name="{{ $name }}" rows="3"
                    class="form-control @error($name) is-invalid @enderror">{{ $value }}</textarea>
          @break

        @case('integer')
          <input type="number" step="1" name="{{ $name }}" value="{{ $value }}"
                 class="form-control @error($name) is-invalid @enderror" {{ $required ? 'required' : '' }}>
          @break

        @case('decimal')
          <input type="number" step="0.01" name="{{ $name }}" value="{{ $value }}"
                 class="form-control @error($name) is-invalid @enderror" {{ $required ? 'required' : '' }}>
          @break

        @case('date')
          <input type="text" name="{{ $name }}"
                 value="{{ $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : '' }}"
                 class="form-control datepicker @error($name) is-invalid @enderror"
                 {{ $required ? 'required' : '' }}>
          @break

        @case('datetime')
          <input type="text" name="{{ $name }}"
                 value="{{ $value ? \Carbon\Carbon::parse($value)->format('Y-m-d H:i') : '' }}"
                 class="form-control datetimepicker @error($name) is-invalid @enderror"
                 {{ $required ? 'required' : '' }}>
          @break

        @case('bool')
          <div class="form-check form-switch">
            <input type="hidden" name="{{ $name }}" value="0">
            <input type="checkbox" class="form-check-input" name="{{ $name }}" value="1"
                   {{ $value ? 'checked' : '' }}>
          </div>
          @break

        @case('enum')
          <select name="{{ $name }}" class="form-select tomselect @error($name) is-invalid @enderror" {{ $required ? 'required' : '' }}>
            <option value="">— {{ __('messages.common.select') }} —</option>
            @foreach(($opts['options'] ?? []) as $opt)
              <option value="{{ $opt }}" {{ (string) $value === (string) $opt ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ', $opt)) }}</option>
            @endforeach
          </select>
          @break

        @case('foreign')
          <select name="{{ $name }}" class="form-select tomselect @error($name) is-invalid @enderror" {{ $required ? 'required' : '' }}>
            <option value="">— {{ __('messages.common.select') }} —</option>
            @foreach(($options[$name] ?? []) as $optId => $optLabel)
              <option value="{{ $optId }}" {{ (string) $value === (string) $optId ? 'selected' : '' }}>{{ $optLabel }}</option>
            @endforeach
          </select>
          @break

        @case('json')
          @if(! empty($opts['multi_select_model']))
            @php
              $relName = $name;
              $current = collect();
              if ($row && method_exists($row, $relName)) {
                  $rel = $row->{$relName}();
                  $related = $rel->getRelated();
                  $qualifiedKey = $related->getTable().'.'.$related->getKeyName();
                  $current = $rel->pluck($qualifiedKey)->all();
              }
              $current = old($name, $current);
            @endphp
            <select name="{{ $name }}[]" multiple
                    class="form-select tomselect @error($name) is-invalid @enderror">
              @foreach(($options[$name] ?? []) as $optId => $optLabel)
                <option value="{{ $optId }}" {{ in_array($optId, (array) $current) ? 'selected' : '' }}>{{ $optLabel }}</option>
              @endforeach
            </select>
          @else
            <textarea name="{{ $name }}" rows="2"
                      class="form-control @error($name) is-invalid @enderror">{{ is_array($value) ? json_encode($value) : $value }}</textarea>
          @endif
          @break

        @default
          <input type="text" name="{{ $name }}" value="{{ $value }}"
                 class="form-control @error($name) is-invalid @enderror">
      @endswitch

      @error($name)
        <div class="invalid-feedback d-block small">{{ $message }}</div>
      @enderror
    </div>
  @endforeach
</div>

<div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top">
  <a href="{{ route('admin.'.$cfg['route'].'.index') }}" class="btn btn-light">
    <i class="bi bi-x-lg me-1"></i>{{ __('messages.common.cancel') }}
  </a>
  <button type="submit" class="btn btn-primary">
    <i class="bi bi-save me-1"></i>{{ __('messages.common.save') }}
  </button>
</div>
