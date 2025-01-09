<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Plan;
use App\Models\PlanDetail;
use App\Models\Supply;
use Illuminate\Database\Seeder;

class FullDatabaseSeeder extends Seeder
{
    public function run()
    {
        // Số lượng dữ liệu
        $numSupplies = 5; // Số lượng nhà cung cấp
        $numPlans = 3;    // Số lượng quốc gia
        $numPlanDetailsPerPlan = 4; // PlanDetail mỗi quốc gia
        $numInventoryPerSupply = 10; // Inventory mỗi Supply

        // Tạo dữ liệu Supplies
        $supplies = Supply::factory()
            ->sequence(
                ['name' => 'Orange_5G_10days'],
                ['name' => 'Orange_5G_20days'],
                ['name' => 'Orange_5G_30days'],
                ['name' => 'Cujp_5G_30days'],
                ['name' => 'Joytel_5G_30days'],
            )
            ->count($numSupplies)
            ->create();

        // Tạo dữ liệu Plans
        $plans = Plan::factory()->count($numPlans)
            ->sequence(
                ['country' => 'France'],
                ['country' => 'English'],
                ['country' => 'Viet Nam']
            )
            ->create();

        // Tạo PlanDetail cho mỗi Plan
        // mỗi quốc gia đều có supplie từ a-z với giá nhất định
        foreach ($plans as $plan) {
            foreach ($supplies as $supply) {
                PlanDetail::factory()->create([
                    'plan_id' => $plan->id,
                    'supply_id' => $supply->id,
                    'price' => 1000000,
                ]);
            }
        }

        // Tạo Inventory cho mỗi Supply
        // mỗi supplie đều có 10 sim
        foreach ($supplies as $key => $supply) {
            Inventory::factory()
                ->count($numInventoryPerSupply)
                ->sequence(fn ($sequence) => [
                    'supply_id' => $supply->id,
                    'iccid' => $key . '8986000000000000000' . $sequence->index,
                    'status' => 'available',
                ])
                ->create();
        }

    }
}
