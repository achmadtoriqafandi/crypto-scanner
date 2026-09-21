<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SecureAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:secure-admin {--email=admin@cryptoscanner.com} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update the administrator account with a secure password';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');

        if (!$password) {
            $password = $this->secret('Masukkan password baru untuk ' . $email);
            if (!$password) {
                $password = Str::random(16);
                $this->info("Password otomatis dibuat: {$password}");
            }
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $name = $this->ask('Masukkan nama untuk akun admin', 'System Administrator');
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);
            $this->info("✓ Akun Admin {$email} berhasil dibuat.");
        } else {
            $user->password = Hash::make($password);
            $user->save();
            $this->info("✓ Password untuk akun Admin {$email} berhasil diperbarui.");
        }

        return 0;
    }
}
