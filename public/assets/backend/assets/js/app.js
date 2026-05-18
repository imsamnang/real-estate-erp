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
  //
  // We use *event delegation* on `document` instead of per-element listeners.
  // This is critical because Yajra server-side DataTables replaces the entire
  // <tbody> on every redraw (pagination, search, sort, ajax reload), so any
  // listener bound to a row's form is lost. Delegation on `document` survives
  // arbitrary DOM replacement and means rows added later (DataTable redraw,
  // Livewire morph, manual injection) all get the SweetAlert2 confirm flow
  // automatically.
  function runDeleteConfirm(el) {
    const message = el.getAttribute('data-confirm-message') || 'Are you sure you want to delete this item?';
    return Swal.fire({
      title: el.getAttribute('data-confirm-title') || 'Confirm',
      text: message,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: el.getAttribute('data-confirm-button') || 'Delete',
      cancelButtonText: el.getAttribute('data-cancel-button') || 'Cancel',
      confirmButtonColor: '#d33',
      reverseButtons: true,
    });
  }

  function submitConfirmedForm(form) {
    // Mark form so the delegated submit handler lets it through this time.
    form.dataset.confirmed = '1';
    if (typeof form.requestSubmit === 'function') form.requestSubmit();
    else form.submit();
  }

  function bindDeleteConfirmDelegation() {
    // Click on any element (or descendant) marked with data-confirm-delete.
    // Forms also fire their own `submit` event handled below; the click path
    // covers buttons/anchors and short-circuits the form submit before it runs.
    document.addEventListener('click', function (e) {
      const trigger = e.target.closest('[data-confirm-delete]');
      if (!trigger) return;
      // For buttons inside forms, intercept the submit instead so we go
      // through the form's submission path (CSRF + _method already wired).
      if (trigger.tagName === 'BUTTON' && trigger.form && trigger.form.hasAttribute('data-confirm-delete')) {
        return; // let the form's submit handler below take over
      }
      e.preventDefault();
      e.stopPropagation();
      runDeleteConfirm(trigger).then((r) => {
        if (!r.isConfirmed) return;
        if (trigger.tagName === 'FORM') { submitConfirmedForm(trigger); return; }
        if (trigger.tagName === 'A' && trigger.getAttribute('href')) {
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = trigger.getAttribute('href');
          const csrf = document.createElement('input');
          csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = csrfToken || '';
          form.appendChild(csrf);
          const method = document.createElement('input');
          method.type = 'hidden'; method.name = '_method'; method.value = trigger.getAttribute('data-method') || 'DELETE';
          form.appendChild(method);
          document.body.appendChild(form);
          form.submit();
          return;
        }
        if (trigger.tagName === 'BUTTON' && trigger.form) { submitConfirmedForm(trigger.form); }
      });
    }, true);

    // Forms with data-confirm-delete: intercept submit (works for redrawn rows).
    document.addEventListener('submit', function (e) {
      const form = e.target.closest('form[data-confirm-delete]');
      if (!form) return;
      if (form.dataset.confirmed) { delete form.dataset.confirmed; return; }
      e.preventDefault();
      e.stopPropagation();
      runDeleteConfirm(form).then((r) => {
        if (r.isConfirmed) submitConfirmedForm(form);
      });
    }, true);
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
      drawCallback: function () {
        // DataTables replaces <tbody> on every draw — re-init flatpickr /
        // Tom Select for any newly-rendered fields. Delete confirms are
        // handled globally via event delegation, so no re-binding is needed.
        initFieldEnhancements();
      },
    }, extra));
  };

  // --- Boot ------------------------------------------------------------
  document.addEventListener('DOMContentLoaded', () => {
    bindDeleteConfirmDelegation();
    initFieldEnhancements();
    initLayoutInteractions();
  });

  // Re-initialize field enhancements after Livewire re-renders. Delete
  // confirms use document-level delegation and don't need re-binding.
  document.addEventListener('livewire:navigated', () => {
    initFieldEnhancements();
  });
  document.addEventListener('livewire:initialized', () => {
    if (window.Livewire) {
      Livewire.hook('morph.updated', ({ el }) => {
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
