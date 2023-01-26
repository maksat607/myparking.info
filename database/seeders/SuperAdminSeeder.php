<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{

    public array $userData = [];

    public function __construct()
    {
        $this->userData = [
            'name' => 'SuperAdmin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456789'),
            'status' => true,
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('users')->truncate();
        $superAdmin = User::create($this->userData);
        $superAdmin->assignRole('SuperAdmin');
        $this->display($superAdmin);
    }

    private function display(User $superAdmin)
    {
        $headers = ['Name', 'Email', 'Role', 'Password'];

        $fields = [
            'name' => $superAdmin->name,
            'email' => $superAdmin->email,
            'admin' => $superAdmin->getRoleNames()[0],
            'password' => '123456789'
        ];

        $this->command->info('SuperAdmin created!');
        $this->command->table($headers, [$fields]);
    }
}
