<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CodeSequenceService
{
    /**
     * Generate the next sequential code for a module (e.g. "bookings" -> "BK-000001").
     * Scope is (company_id, branch_id, module).
     */
    public function next(string $module, ?string $unused = null): string
    {
        $user = Auth::user();
        $companyId = $user?->company_id;
        $branchId = $user?->branch_id;

        $row = DB::table('code_sequences')
            ->where('company_id', $companyId)
            ->where('branch_id', $branchId)
            ->where('module', $module)
            ->lockForUpdate()
            ->first();

        if (! $row) {
            $prefix = $this->derivePrefix($module);
            $rowId = DB::table('code_sequences')->insertGetId([
                'company_id' => $companyId,
                'branch_id' => $branchId,
                'module' => $module,
                'prefix' => $prefix,
                'next_number' => 1,
                'padding' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $row = DB::table('code_sequences')->where('id', $rowId)->first();
        }

        $number = $row->next_number;
        DB::table('code_sequences')->where('id', $row->id)->update([
            'next_number' => $number + 1,
            'updated_at' => now(),
        ]);

        return $row->prefix.str_pad((string) $number, (int) $row->padding, '0', STR_PAD_LEFT);
    }

    private function derivePrefix(string $module): string
    {
        $map = [
            'bookings' => 'BK-',
            'sale_contracts' => 'SC-',
            'invoices' => 'INV-',
            'payments' => 'PAY-',
            'refunds' => 'RFD-',
            'leads' => 'LD-',
            'customers' => 'CUS-',
            'rental_contracts' => 'RC-',
            'expenses' => 'EXP-',
            'journal_entries' => 'JE-',
            'projects' => 'PRJ-',
            'properties' => 'PRP-',
            'commissions' => 'CM-',
            'approval_requests' => 'AR-',
            'employees' => 'EMP-',
            'assets' => 'AST-',
            'land_parcels' => 'LP-',
        ];

        if (isset($map[$module])) {
            return $map[$module];
        }

        return strtoupper(Str::of($module)->limit(3, '')).'-';
    }
}
