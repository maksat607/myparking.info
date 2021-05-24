<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AddSupperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'super_admin:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create SupperAdmin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $details = $this->getDetails();

        $superAdmin = User::create($details);

        if($this->assignRole($superAdmin, $details['role'])) {
            $this->display($superAdmin);
        } else {

            $this->error('The SuperAdmin role is not linked!');
        }

    }

    public function assignRole($superAdmin, $role) {
        $superAdmin->roles()->detach();
        if($superAdmin->assignRole($role->name)) {
            return true;
        }
        return false;
    }

    /**
     * Ask for admin details.
     *
     * @return array
     */
    private function getDetails()
    {

        $details['name'] = $this->ask('Name');
//        $details['email'] = $this->ask('Email');
        $details['email'] = $this->askValid('Email', 'Email', ['required', 'email']);
        $details['password'] = $this->secret('Password');
        $details['confirm_password'] = $this->secret('Confirm password');

        while (! $this->isValidPassword($details['password'], $details['confirm_password'])) {
            if (! $this->isRequiredLength($details['password'])) {
                $this->error('Password must be more that eight characters');
            }

            if (! $this->isMatch($details['password'], $details['confirm_password'])) {
                $this->error('Password and Confirm password do not match');
            }

            $details['password'] = $this->secret('Password');
            $details['confirm_password'] = $this->secret('Confirm password');
        }

        $superAdminRole = Role::where('name', 'SuperAdmin')->first();

        $details['role'] = $superAdminRole;

        $details['password'] = Hash::make($details['password']);

        $details['status'] = 1;

        $details['parent_id'] = null;



        return $details;
    }

    /**
     * Display created admin.
     *
     * @param array $admin
     * @return void
     */
    private function display(User $superAdmin)
    {
        $headers = ['Name', 'Email', 'Role'];

        $fields = [
            'name' => $superAdmin->name,
            'email' => $superAdmin->email,
            'admin' => $superAdmin->getRoleNames()[0]
        ];

        $this->info('SuperAdmin created!');
        $this->table($headers, [$fields]);
    }

    /**
     * Check if password is vailid
     *
     * @param string $password
     * @param string $confirmPassword
     * @return boolean
     */
    private function isValidPassword($password, $confirmPassword)
    {
        return $this->isRequiredLength($password) &&
            $this->isMatch($password, $confirmPassword);
    }

    /**
     * Check if password and confirm password matches.
     *
     * @param string $password
     * @param string $confirmPassword
     * @return bool
     */
    private function isMatch($password, $confirmPassword)
    {
        return $password === $confirmPassword;
    }

    /**
     * Checks if password is longer than six characters.
     *
     * @param string $password
     * @return bool
     */
    private function isRequiredLength($password)
    {
        return strlen($password) > 8;
    }

    protected function askValid($question, $field, $rules)
    {
        $value = $this->ask($question);

        if($message = $this->validateInput($rules, $field, $value)) {
            $this->error($message);

            return $this->askValid($question, $field, $rules);
        }

        return $value;
    }


    protected function validateInput($rules, $fieldName, $value)
    {
        $validator = Validator::make(
            [
                $fieldName => $value
            ],
            [
                $fieldName => $rules
            ]
        );

        return $validator->fails()
            ? $validator->errors()->first($fieldName)
            : null;
    }
}
