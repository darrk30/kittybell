<?php

namespace Database\Seeders;

use App\Models\CashSummary;
use App\Models\Client;
use App\Models\User;
use App\Models\DocumentType;
use App\Models\DeliveryType;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Belen Cano',
            'email' => 'belen@gmail.com',
            'password' => Hash::make('123123123'),
        ]);

        $roleModel = \BezhanSalleh\FilamentShield\Support\Utils::getRoleModel();
        $role = $roleModel::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $user->assignRole($role);

        // Tipos de documento
        $documentTypes = [
            ['name' => 'DNI', 'code' => 'DNI', 'max_length' => 8, 'is_active' => true],
            ['name' => 'RUC', 'code' => 'RUC', 'max_length' => 11, 'is_active' => true],
        ];

        foreach ($documentTypes as $type) {
            DocumentType::create($type);
        }

        // Tipos de entrega
        DeliveryType::create([
            'name' => 'Entrega Gratis',
            'extra_price' => 0,
            'is_active' => true,
        ]);

        // 🔹 Crear cliente "Público en general"
        $dniType = DocumentType::where('code', 'DNI')->first();

        Client::firstOrCreate([
            'document_number' => '00000000',
        ], [
            'name' => 'Público en general',
            'email' => null,
            'phone' => null,
            'address' => null,
            'is_active' => true,
            'document_type_id' => $dniType?->id,
        ]);

        // 🔹 Crear caja principal
        CashSummary::firstOrCreate([
            'code' => 'CAJA-001',
        ], [
            'name' => 'Caja Principal',
            'current_balance' => 0,
        ]);

        $this->call([
            ShieldSeeder::class,
        ]);
    }
}
