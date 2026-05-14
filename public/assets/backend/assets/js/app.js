/*
 * Real-Estate ERP — global front-end helpers
 * - CSRF setup
 * - SweetAlert2 delete confirmation interceptor for forms with `data-confirm-delete`
 * - flatpickr auto-init on `.flatpickr-input`, `.datepicker`, `.datetimepicker`
 * - Tom Select auto-init on `.tomselect`
 * - Sidebar toggle, back-to-top
 * - DataTable factory with sane defaults for server-side mode
 */
(function () {
  'use strict';

  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  // --- Global fetch wrapper with CSRF ----------------------------------
  window.apiFetch = function (url, options = {}) {
    options.headers = Object.assign({}, {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': csrfToken || '',
      'Accept': 'application/json',
    }, options.headers || {});
    return fetch(url, options);
  };

  // --- jQuery AJAX CSRF ------------------------------------------------
  if (window.jQuery && csrfToken) {
    jQuery.ajaxSetup({
      headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
    });
  }

  // --- SweetAlert2 delete confirmation --------------------------------
  function bindDeleteConfirms(scope = document) {
    scope.querySelectorAll('form[data-confirm-delete], button[data-confirm-delete], a[data-confirm-delete]').forEach((el) => {
      if (el.dataset.confirmBound) return;
      el.dataset.confirmBound = '1';

      const handler = function (e) {
        e.preventDefault();
        e.stopPropagation();
        const message = el.getAttribute('data-confirm-message') || 'Are you sure you want to delete this item?';
        Swal.fire({
          title: 'Confirm',
          text: message,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: el.getAttribute('data-confirm-button') || 'Delete',
          cancelButtonText: el.getAttribute('data-cancel-button') || 'Cancel',
          confirmButtonColor: '#d33',
          reverseButtons: true,
        }).then((r) => {
          if (!r.isConfirmed) return;
          if (el.tagName === 'FORM') { el.submit(); return; }
          if (el.tagName === 'A' && el.href) {
            // Build a temporary POST form for safety (CSRF + spoof method).
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = el.getAttribute('href');
            const csrf = document.createElement('input');
            csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = csrfToken || '';
            form.appendChild(csrf);
            const method = document.createElement('input');
            method.type = 'hidden'; method.name = '_method'; method.value = el.getAttribute('data-method') || 'DELETE';
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
            return;
          }
          if (el.tagName === 'BUTTON' && el.form) { el.form.submit(); }
        });
      };

      if (el.tagName === 'FORM') {
        el.addEventListener('submit', function (e) {
          if (!el.dataset.confirmed) handler(e);
          else delete el.dataset.confirmed;
        });
      } else {
        el.addEventListener('click', handler);
      }
    });
  }

  // --- flatpickr + Tom Select auto-init -------------------------------
  function initFieldEnhancements(scope = document) {
    if (window.flatpickr) {
      scope.querySelectorAll('.flatpickr-input, .datepicker').forEach((el) => {
        if (el._flatpickr) return;
        flatpickr(el, { dateFormat: el.dataset.dateFormat || 'Y-m-d', allowInput: true });
      });
      scope.querySelectorAll('.datetimepicker').forEach((el) => {
        if (el._flatpickr) return;
        flatpickr(el, { enableTime: true, dateFormat: el.dataset.dateFormat || 'Y-m-d H:i', time_24hr: true, allowInput: true });
      });
    }
    if (window.TomSelect) {
      scope.querySelectorAll('select.tomselect').forEach((el) => {
        if (el.tomselect) return;
        new TomSelect(el, {
          create: false,
          plugins: el.multiple ? ['remove_button'] : [],
          allowEmptyOption: true,
        });
      });
    }
  }

  // --- Sidebar toggle / back-to-top -----------------------------------
  function initLayoutInteractions() {
    document.querySelectorAll('.mobile-toggle-icon, .toggle-icon').forEach((el) => {
      el.addEventListener('click', () => document.querySelector('.wrapper')?.classList.toggle('sidebar-open'));
    });
    document.querySelector('.overlay')?.addEventListener('click', () => document.querySelector('.wrapper')?.classList.remove('sidebar-open'));

    const back = document.querySelector('.back-to-top');
    if (back) {
      window.addEventListener('scroll', () => {
        back.classList.toggle('is-visible', window.scrollY > 200);
      }, { passive: true });
      back.addEventListener('click', (e) => { e.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); });
    }
  }

  // --- DataTable factory ----------------------------------------------
  window.makeServerSideDataTable = function (selector, url, columns, extra = {}) {
    if (!window.jQuery || !jQuery.fn.dataTable) return null;
    const $table = jQuery(selector);
    if (!$table.length) return null;
    if (jQuery.fn.dataTable.isDataTable(selector)) { $table.DataTable().destroy(); }

    return $table.DataTable(Object.assign({
      processing: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      ajax: {
        url,
        type: 'GET',
        data: function (d) { /* keep default DT params */ }
      },
      columns,
      order: extra.order || [[0, 'desc']],
      lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
      pageLength: extra.pageLength || 10,
      language: {
        emptyTable: 'No records found.',
        zeroRecords: 'No matching records found.',
        info: 'Showing _START_ to _END_ of _TOTAL_ entries',
        infoEmpty: 'Showing 0 entries',
        lengthMenu: 'Show _MENU_ entries',
        search: '',
        searchPlaceholder: 'Search...',
        processing: '<div class="spinner-border spinner-border-sm text-primary"></div>',
        paginate: { previous: '<i class="bi bi-chevron-left"></i>', next: '<i class="bi bi-chevron-right"></i>' }
      },
      drawCallback: () => initFieldEnhancements(),
    }, extra));
  };

  // --- Boot ------------------------------------------------------------
  document.addEventListener('DOMContentLoaded', () => {
    bindDeleteConfirms();
    initFieldEnhancements();
    initLayoutInteractions();
  });

  // Re-initialize after Livewire re-renders.
  document.addEventListener('livewire:navigated', () => {
    bindDeleteConfirms();
    initFieldEnhancements();
  });
  document.addEventListener('livewire:initialized', () => {
    if (window.Livewire) {
      Livewire.hook('morph.updated', ({ el }) => {
        bindDeleteConfirms(el);
        initFieldEnhancements(el);
      });
    }
  });

  // Listen for locale changes -> reload DataTables to pick up translated columns
  document.addEventListener('locale-changed', () => {
    document.querySelectorAll('table.dataTable').forEach((t) => {
      if (jQuery.fn.dataTable.isDataTable(t)) jQuery(t).DataTable().ajax.reload(null, false);
    });
  });
})();
