{{-- Module form partial for "sale_contract_items" — wraps the shared partial so each module
     has its own dedicated blade file as requested. --}}
@include('admin.crud._form', ['cfg' => $cfg, 'options' => $options ?? [], 'row' => $row ?? null])
