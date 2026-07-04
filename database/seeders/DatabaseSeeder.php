<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'CRM Admin', 'password' => Hash::make('password')]
        );

        $permissions = collect(['view dashboard', 'manage users', 'manage customers', 'manage invoices', 'manage settings'])
            ->map(fn (string $name): Permission => Permission::query()->firstOrCreate(['name' => $name]));

        $role = Role::query()->firstOrCreate(['name' => 'Administrator'], ['description' => 'Full CRM access']);
        $role->permissions()->sync($permissions->pluck('id'));
        $admin->roles()->syncWithoutDetaching([$role->id]);

        $company = Company::query()->firstOrCreate(['name' => 'Acme Print Co.'], [
            'email' => 'hello@acmeprint.test',
            'phone' => '+1 555 0100',
            'country' => 'US',
        ]);

        Customer::query()->firstOrCreate(['email' => 'jane.customer@example.com'], [
            'company_id' => $company->id,
            'owner_id' => $admin->id,
            'first_name' => 'Jane',
            'last_name' => 'Customer',
            'phone' => '+1 555 0101',
            'status' => 'qualified',
            'source' => 'referral',
        ]);

        Product::query()->firstOrCreate(['sku' => 'BUS-CARD-001'], [
            'name' => 'Business Cards',
            'type' => 'product',
            'unit_price' => 49.00,
        ]);

        Setting::query()->firstOrCreate(['key' => 'crm.currency'], ['value' => ['code' => 'USD']]);
    }
}
