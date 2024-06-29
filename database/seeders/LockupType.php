<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LockupType extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => '{"en": "Catalog Input Type"}',
                'key' => 'catalogInputType',
                'is_active'=>'1',
                'is_system'=>'1',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'id'    => 2,
                'name' => '{"en": "Gender Types","ar": "أنواع الجنس", "fr": "Genre"}',
                'key' => 'genderType',
                'is_active' => '1',
                'is_system' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'    => 3,
                'name' => '{"en": "City","ar": "مدينة", "fr": "Ville"}',
                'key' => 'cityType',
                'is_active' => '1',
                'is_system' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'    => 4,
                'name' => '{"en": "Country","ar": "بلد", "fr": "de campagne"}',
                'key' => 'countryType',
                'is_active' => '1',
                'is_system' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'    => 5,
                'name' => '{"en": "Currency Type"}',
                'key' => 'currencyType',
                'is_active' => '1',
                'is_system' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'    => 6,
                'name' => '{"en": "Order Status"}',
                'key' => 'orderStatus',
                'is_active' => '1',
                'is_system' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'    => 7,
                'name' => '{"en": "Payment Method"}',
                'key' => 'paymentMethod',
                'is_active'=>'1',
                'is_system'=>'1',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'id'    => 8,
                'name' => '{"en": "Payment Status"}',
                'key' => 'paymentStatus',
                'is_active'=>'1',
                'is_system'=>'1',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'id'    => 9,
                'name' => '{"en": "State"}',
                'key' => 'stateType',
                'is_active' => '1',
                'is_system' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'    => 10,
                'name' => '{"en": "Address Type"}',
                'key' => 'addressType',
                'is_active' => '1',
                'is_system' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'    => 11,
                'name' => '{"en": "Day Type"}',
                'key' => 'day_type',
                'is_active' => '1',
                'is_system' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 12,
                'name' => '{"en":"Coupon Rule"}',
                'key' => 'couponRule',
                'is_active'=>'1',
                'is_system'=>'1',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
        ];
        DB::table('lockup_types')->insertOrIgnore($data);
    }
}
