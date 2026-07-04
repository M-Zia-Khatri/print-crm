<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class ModulePageController extends Controller
{
    public function __invoke(string $module): Response
    {
        abort_unless(in_array($module, $this->modules(), true), 404);

        return Inertia::render('crm/module', [
            'module' => str($module)->headline()->toString(),
            'slug' => $module,
        ]);
    }

    /** @return array<int, string> */
    private function modules(): array
    {
        return ['users', 'roles', 'permissions', 'companies', 'products', 'services', 'quotes', 'invoices', 'payments', 'tasks', 'notes', 'activity-logs'];
    }
}
