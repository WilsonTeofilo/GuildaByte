<?php

namespace App\Actions\Client;

use App\Models\ScopeAddendum;

class AcceptAddendumAction
{
    public function execute(ScopeAddendum $addendum, string $ip, string $userAgent): ScopeAddendum
    {
        if ($addendum->project->client_id !== auth()->id()) {
            abort(403);
        }

        $addendum->update([
            'status' => 'accepted',
            'accepted_at' => now(),
            'ip_address' => $ip,
            'user_agent' => $userAgent
        ]);

        return $addendum;
    }
}
