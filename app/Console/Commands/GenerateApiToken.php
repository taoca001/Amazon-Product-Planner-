<?php

namespace App\Console\Commands;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Console\Command;

class GenerateApiToken extends Command
{
    protected $signature = 'api:token {user_id : The user ID to create a token for} {--name=default : Name for the token}';

    protected $description = 'Generate a new API token for a user (shown only once)';

    public function handle(): int
    {
        $user = User::find($this->argument('user_id'));

        if (!$user) {
            $this->error('User not found.');
            return self::FAILURE;
        }

        $plainToken = ApiToken::generateToken();

        ApiToken::create([
            'user_id' => $user->id,
            'name' => $this->option('name'),
            'token' => ApiToken::hashToken($plainToken),
        ]);

        $this->info('Token created for user: ' . $user->email);
        $this->newLine();
        $this->warn('⚠️  Copy this token now — it will NOT be shown again:');
        $this->newLine();
        $this->line($plainToken);
        $this->newLine();

        return self::SUCCESS;
    }
}
