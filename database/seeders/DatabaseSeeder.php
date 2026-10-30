<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Required data only. SinoWala needs no rows to start: the Super Admin is the account
 * whose email matches SUPER_ADMIN_EMAIL, created by signing up on the website.
 *
 * For sample data on a local machine: php artisan db:seed --class=DemoSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        //
    }
}
