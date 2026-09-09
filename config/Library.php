<?php
return [
    'max_active_loans_per_member' => (int) env('LIBRARY_MAX_ACTIVE_LOANS_PER_MEMBER', 5),
    'loan_days' => (int) env('LIBRARY_LOAN_DAYS', 14),
    'renewal_days' => (int) env('LIBRARY_RENEWAL_DAYS', 14),
    'fine_per_day' => (float) env('LIBRARY_FINE_PER_DAY', 1),
    'lost_copy_compensation' => (float) env('LIBRARY_LOST_COPY_COMPENSATION', 50),
    'damaged_copy_compensation' => (float) env('LIBRARY_DAMAGED_COPY_COMPENSATION', 25),
];
