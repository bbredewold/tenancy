<?php

declare(strict_types=1);

namespace Stancl\Tenancy\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Stancl\Tenancy\Events\DatabaseDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Stancl\Tenancy\Events\DeletingDatabase;
use Stancl\Tenancy\Database\Contracts\TenantWithDatabase;

class DeleteDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var TenantWithDatabase&Model $tenant
     * @param TenantWithDatabase&Model $tenant
     */
    public function __construct(
        protected TenantWithDatabase $tenant,
    ) {
    }

    public function handle(): void
    {
        event(new DeletingDatabase($this->tenant));

        $this->tenant->database()->manager()->deleteDatabase($this->tenant);

        event(new DatabaseDeleted($this->tenant));
    }
}
