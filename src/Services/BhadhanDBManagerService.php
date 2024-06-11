<?php

namespace Bidhan\Bhadhan\Services;

use Bidhan\Bhadhan\Interfaces\BhadhanDBManagerServiceInterface;
use Illuminate\Support\Facades\DB;

class BhadhanDBManagerService implements BhadhanDBManagerServiceInterface
{
    private $currentConnection;

    public function __construct()
    {
        $this->currentConnection = config('bhadhan.db_connection');
    }

    public function getCurrentDatabaseConnection(): string
    {
        return $this->currentConnection;
    }

    public function getCurrentDatabaseName(): string
    {
        return DB::connection()->getDatabaseName();
    }

    public function getAllDbTables(): array
    {
        return DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = \'public\' ORDER BY table_name');
    }

    public function getForeignKeys(string $table): array
    {
        return DB::select(
            "SELECT 
                tc.constraint_name, 
                kcu.column_name, 
                ccu.table_name AS foreign_table_name,
                ccu.column_name AS foreign_column_name
            FROM 
                information_schema.table_constraints AS tc 
                JOIN information_schema.key_column_usage AS kcu
                ON tc.constraint_name = kcu.constraint_name
                JOIN information_schema.constraint_column_usage AS ccu
                ON ccu.constraint_name = tc.constraint_name
            WHERE 
                tc.constraint_type = 'FOREIGN KEY' AND tc.table_name = ?;",
            [$table]
        );
    }

    public function getPrimaryKey(string $table): array
    {
        return DB::select(
            "SELECT 
                kcu.column_name
            FROM 
                information_schema.table_constraints tc
            JOIN 
                information_schema.key_column_usage kcu
            ON tc.constraint_name = kcu.constraint_name
            AND tc.table_schema = kcu.table_schema
            WHERE 
                tc.constraint_type = 'PRIMARY KEY' 
                AND tc.table_name = ?
                AND kcu.column_name = 'id';",
            [$table]
        );
    }

    public function getAllTableWithSize(): array
    {
        $connection = $this->getCurrentDatabaseConnection();

        if ($connection === 'pgsql') {
            return DB::select(
                "SELECT
                    schemaname || '.' || tablename AS table_name,
                    pg_size_pretty(pg_total_relation_size(schemaname || '.' || tablename)) AS total_size
                FROM
                    pg_tables
                WHERE
                    schemaname NOT IN ('pg_catalog', 'information_schema')
                ORDER BY
                    pg_total_relation_size(schemaname || '.' || tablename) DESC
                LIMIT 10;"
            );
        }

        if ($connection === 'mysql') {
            return DB::select(
                "SELECT
                    table_name AS table_name,
                    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS total_size_mb
                FROM
                    information_schema.tables
                WHERE
                    table_schema = ?
                ORDER BY
                    (data_length + index_length) DESC
                LIMIT 10;",
                [DB::connection()->getDatabaseName()]
            );
        }

        return [];
    }

    public function getCurrentSchemaSize(): array
    {
        $connection = $this->getCurrentDatabaseConnection();

        if ($connection === 'pgsql') {
            return DB::select(
                "SELECT pg_size_pretty(pg_database_size(?)) AS total_size;",
                [DB::connection()->getDatabaseName()]
            );
        }

        if ($connection === 'mysql') {
            return DB::select(
                "SELECT
                    table_schema AS database_name,
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS total_size_mb
                FROM
                    information_schema.tables
                WHERE
                    table_schema = ?;",
                [DB::connection()->getDatabaseName()]
            );
        }

        return [];
    }

    public function getAllDBViews(): array
    {
        $connection = $this->getCurrentDatabaseConnection();

        if ($connection === 'pgsql') {
            return DB::select(
                "SELECT
                    table_catalog,
                    table_schema,
                    table_name AS view_name,
                    view_definition,
                    is_updatable,
                    check_option
                FROM
                    information_schema.views
                WHERE
                    table_schema NOT IN ('pg_catalog', 'information_schema')
                ORDER BY
                    table_schema,
                    table_name;"
            );
        }

        return [];
    }
}
