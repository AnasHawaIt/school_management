<?php

namespace Modules\Finance\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Finance\Entities\Invoice;

interface InvoiceServiceInterface
{
    public function getAll(): Collection;
    public function getById(int $id): ?Invoice;
    public function generateForStudent(int $studentId, int $yearId, ?int $dueDays = null): Invoice;
    public function send(int $id): Invoice;
    public function cancel(int $id): Invoice;
    public function getOverdue(): Collection;
    public function getByStudent(int $studentId): Collection;
}
