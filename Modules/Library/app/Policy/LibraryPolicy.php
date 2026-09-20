<?php

namespace Modules\Library\app\Policy;

use Modules\Core\app\Entities\User;
use Modules\Library\app\Entities\BookCopy;
use Modules\Library\app\Entities\Borrowing;
use Modules\Library\app\Entities\Fine;

class LibraryPolicy
{
    public function viewCatalog(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermission('library.catalog.view');
    }
    public function manageCatalog(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermission('library.catalog.manage');
    }
    public function viewCirculation(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermission('library.circulation.view');
    }
    public function manageCirculation(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermission('library.circulation.manage');
    }
    public function viewFines(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermission('library.fines.view');
    }
    public function manageFines(User $user): bool
    {
        return $user->isAdmin() || $user->hasPermission('library.fines.manage');
    }
    public function updateCopy(User $user, BookCopy $copy): bool
    {
        return $this->manageCatalog($user);
    }
    public function updateBorrowing(User $user, Borrowing $borrowing): bool
    {
        return $this->manageCirculation($user);
    }
    public function updateFine(User $user, Fine $fine): bool
    {
        return $this->manageFines($user);
    }
}
