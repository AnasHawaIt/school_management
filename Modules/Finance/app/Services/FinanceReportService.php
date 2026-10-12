<?php

namespace Modules\Finance\Services;

use Modules\Finance\Contracts\Repositories\PaymentRepositoryInterface;
use Modules\Finance\Contracts\Repositories\StudentFeeRepositoryInterface;
use Modules\Finance\Contracts\Services\FinanceReportServiceInterface;

class FinanceReportService implements FinanceReportServiceInterface
{
    public function __construct(
        protected PaymentRepositoryInterface    $paymentRepo,
        protected StudentFeeRepositoryInterface $studentFeeRepo,
    ) {}

    public function getDashboard(int $yearId): array
    {
        $summary = $this->studentFeeRepo->getTotalByYear($yearId);

        return [
            'total_expected'    => $summary['total_expected'],
            'total_collected'   => $summary['total_paid'],
            'total_outstanding' => $summary['total_remaining'],
            'collection_rate'   => $summary['total_expected'] > 0
                ? round(($summary['total_paid'] / $summary['total_expected']) * 100, 2)
                : 0,
            'overdue_count' => $this->studentFeeRepo->getOverdue()->count(),
        ];
    }

    public function getCollectionReport(string $from, string $to): array
    {
        $payments = $this->paymentRepo->getByDateRange($from, $to);

        return [
            'from'    => $from,
            'to'      => $to,
            'total'   => $payments->sum('amount'),
            'count'   => $payments->count(),
            'by_method' => [
                'cash'          => $payments->where('method', 'cash')->sum('amount'),
                'bank_transfer' => $payments->where('method', 'bank_transfer')->sum('amount'),
                'stripe'        => $payments->where('method', 'stripe')->sum('amount'),
                'paypal'        => $payments->where('method', 'paypal')->sum('amount'),
            ],
            'payments' => $payments,
        ];
    }

    public function getOutstandingReport(int $yearId): array
    {
        $unpaid = $this->studentFeeRepo->getUnpaidByYear($yearId);
        $overdue = $this->studentFeeRepo->getOverdue();

        return [
            'unpaid_count'      => $unpaid->count(),
            'unpaid_amount'     => $unpaid->sum('remaining_amount'),
            'overdue_count'     => $overdue->count(),
            'overdue_amount'    => $overdue->sum('remaining_amount'),
            'unpaid_fees'       => $unpaid,
            'overdue_fees'      => $overdue,
        ];
    }
}
