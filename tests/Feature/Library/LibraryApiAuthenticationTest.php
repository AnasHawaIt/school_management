<?php

namespace Tests\Feature\Library;

use Tests\TestCase;

class LibraryApiAuthenticationTest extends TestCase
{
    public function test_library_catalog_endpoints_require_authentication(): void
    {
        $endpoints = [
            '/api/library/authors',
            '/api/library/categories',
            '/api/library/Publishers',
            '/api/library/members',
            '/api/library/books',
            '/api/library/transactions',
        ];

        foreach ($endpoints as $endpoint) {
            $this->getJson($endpoint)->assertUnauthorized();
        }
    }
}
