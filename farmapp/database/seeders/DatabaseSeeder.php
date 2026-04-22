<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name'     => 'Admin',
            'email'    => 'admin@farm.local',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // Grab seeded users and products
        $customer = \App\Models\User::where('email', 'customer@farm.local')->first();
        $products = \App\Models\Product::take(3)->get();

        if ($customer && $products->count()) {
            $order = Order::create([
                'user_id'          => $customer->id,
                'status'           => 'pending',
                'total'            => 0,
                'delivery_address' => '12 Mango Lane, Thrissur, Kerala 680001',
                'notes'            => 'Please leave at the gate.',
            ]);

            $total = 0;
            foreach ($products as $product) {
                $qty   = rand(1, 4);
                $price = $product->price;
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity'   => $qty,
                    'unit_price' => $price,
                ]);
                $total += $qty * $price;
            }

            $order->update(['total' => $total]);
        }
    }
}
