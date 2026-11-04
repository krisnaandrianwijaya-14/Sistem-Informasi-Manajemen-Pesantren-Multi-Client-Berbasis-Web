<?php
namespace App\Modules\Platform\Support;

use App\Models\Client;
use RuntimeException;

class ClientContext
{
    private ?Client $client = null;

    public function set(Client $client): void { $this->client = $client; }

    public function get(): Client {
        if (!$this->client) throw new RuntimeException('Client Context kosong.');
        return $this->client;
    }

    public function getId(): int { return $this->get()->id; }
    public function hasClient(): bool { return $this->client !== null; }
}
