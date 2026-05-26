<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_schema_matches_the_expected_jghnewdb_tables_and_columns(): void
    {
        $this->assertTrue(Schema::hasTable('user_accounts'));
        $this->assertTrue(Schema::hasTable('course__students'));

        $this->assertTrue(Schema::hasColumns('students', [
            'user_account_id',
            'first_name',
            'middle_name',
            'last_name',
            'address',
            'contact',
            'email',
            'degree_id',
        ]));

        $this->assertTrue(Schema::hasColumns('posts', [
            'user_id',
            'title',
            'content',
        ]));
    }
}
