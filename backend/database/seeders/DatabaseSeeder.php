<?php

namespace Database\Seeders;

use App\Models\ComparisonHistory;
use App\Models\Maintenance;
use App\Models\Motorcycle;
use App\Models\SparePart;
use App\Models\TroubleshootingHistory;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@rodadua.test',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        // Create test user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@rodadua.test',
            'password' => bcrypt('password'),
            'role' => User::ROLE_USER,
        ]);

        // Create additional users
        $users = User::factory(3)->create();

        $allUsers = collect([$admin, $user, ...$users]);

        // Create motorcycles for each user
        $motorcycles = collect();
        $allUsers->each(function ($u) use ($motorcycles) {
            $count = $u->isAdmin() ? 3 : rand(2, 4);
            $u->isAdmin()
                ? Motorcycle::factory($count)->create(['user_id' => $u->id])
                : Motorcycle::factory($count)->active()->create(['user_id' => $u->id]);
            $motorcycles->push(...Motorcycle::where('user_id', $u->id)->get());
        });

        // Create maintenance records
        $motorcycles->each(function ($m) {
            Maintenance::factory(rand(2, 5))->create([
                'motorcycle_id' => $m->id,
                'user_id' => $m->user_id,
            ]);
        });

        // Create troubleshooting histories
        $motorcycles->each(function ($m) {
            TroubleshootingHistory::factory(rand(1, 3))->create([
                'motorcycle_id' => $m->id,
                'user_id' => $m->user_id,
            ]);
        });

        // Create workshops
        Workshop::factory(15)->create();
        Workshop::factory(5)->highRated()->create();

        // Create spare parts
        SparePart::factory(20)->create();
        SparePart::factory(10)->inStock()->create();

        // Create comparison histories
        $motorcycleIds = $motorcycles->pluck('id')->toArray();
        $allUsers->each(function ($u) use ($motorcycleIds) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $pair = collect($motorcycleIds)->random(2);
                ComparisonHistory::factory()->create([
                    'user_id' => $u->id,
                    'motorcycle_id' => $pair[0],
                    'compared_motorcycle_id' => $pair[1],
                ]);
            }
        });
    }
}
