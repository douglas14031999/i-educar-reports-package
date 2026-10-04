<?php

use App\Menu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Disable transaction so that individual errors do not cancel the whole migration.
     */
    public $withinTransaction = false;

    public function up(): void
    {
        try {
            // 1. Remove permissões dos menus fictícios /relatorios/... (IDs 405 a 444)
            DB::statement("
                DELETE FROM pmieducar.menu_tipo_usuario 
                WHERE menu_id IN (
                    SELECT id FROM public.menus WHERE link LIKE '/relatorios%'
                );
            ");

            // 2. Remove os menus fictícios da tabela public.menus
            DB::statement("
                DELETE FROM public.menus 
                WHERE link LIKE '/relatorios%';
            ");

            // 3. Remove qualquer menu órfão cujo parent_id seja 404 (que nunca existiu)
            DB::statement("
                DELETE FROM public.menus 
                WHERE parent_id = 404;
            ");

            // 4. Remove também o menu quebrado 564 (Turmas cadastral completo -> 404) caso ainda reste
            DB::statement("
                DELETE FROM pmieducar.menu_tipo_usuario WHERE menu_id = 564;
                DELETE FROM public.menus WHERE id = 564;
            ");
        } catch (\Throwable $e) {
            error_log('Migration cleanup_fake_relatorios_orphan_menus error: ' . $e->getMessage());
        }
    }

    public function down(): void
    {
        // Operação de limpeza definitiva não reversível
    }
};
