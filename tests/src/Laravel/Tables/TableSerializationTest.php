<?php

use Hybridly\Tables;
use Hybridly\Tables\Table;
use Illuminate\Pagination\Paginator;

class TableSerializationTest extends Table
{
    public function getPaginatedRecords(): Paginator
    {
        return new Paginator([], 15);
    }

    public function getKeyName(): string
    {
        return '';
    }

    protected function defineColumns(): array
    {
        return [
            Tables\Columns\Column::make('id')->sortable()->label('#'),
            Tables\Columns\Column::make('name')->sortable()->metadata(['font' => 'mono']),
            Tables\Columns\Column::make('email')->sortable(),
            Tables\Columns\Column::make('is_active')->sortable(),
        ];
    }
}

test('it serializes the table', function () {
    makeHybridMockRequest(properties: [
        'table' => TableSerializationTest::make(),
    ])->assertHybridProperties([
        'table.id',
        'table.keyName',
        'table.records',
        'table.columns',
        'table.filters',
        'table.inlineActions',
        'table.bulkActions',
        'table.currentSorts',
        'table.currentFilters',
        'table.scope',
    ]);
});
