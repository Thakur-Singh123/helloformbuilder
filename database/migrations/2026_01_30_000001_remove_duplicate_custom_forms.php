<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Remove duplicate custom forms (same admin_id + name), keeping the oldest (min id).
     */
    public function up(): void
    {
        $duplicates = DB::table('custom_forms')
            ->select('admin_id', 'name', DB::raw('MIN(id) as keep_id'), DB::raw('GROUP_CONCAT(id ORDER BY id) as ids'))
            ->groupBy('admin_id', 'name')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $row) {
            $keepId = (int) $row->keep_id;
            $ids = array_map('intval', explode(',', $row->ids));
            $deleteIds = array_filter($ids, fn($id) => $id !== $keepId);

            foreach ($deleteIds as $formId) {
                DB::table('form_submissions')->where('custom_form_id', $formId)->delete();
                DB::table('shortcodes')->where('custom_form_id', $formId)->delete();
                DB::table('form_versions')->where('custom_form_id', $formId)->delete();
                DB::table('custom_form_fields')->where('custom_form_id', $formId)->delete();
                DB::table('custom_forms')->where('id', $formId)->delete();
            }
        }
    }

    public function down(): void
    {
        // No reversible operation for duplicate removal
    }
};
