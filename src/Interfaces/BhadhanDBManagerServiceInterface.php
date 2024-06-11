<?php

namespace Bidhan\Bhadhan\Interfaces;

interface BhadhanDBManagerServiceInterface
{
    public function getCurrentDatabaseConnection(): string;
    public function getCurrentDatabaseName(): string;
    public function getAllDbTables(): array;
    public function getForeignKeys(string $table): array;
    public function getPrimaryKey(string $table): array;
    public function getAllTableWithSize(): array;
    public function getCurrentSchemaSize(): array;
    public function getAllDBViews(): array;
}
