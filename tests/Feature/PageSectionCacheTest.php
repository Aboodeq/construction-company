<?php

use App\Models\PageSection;

test('getByKey survives a real serialize round-trip through the database cache store', function () {
    config(['cache.default' => 'database']);

    PageSection::create([
        'key' => 'why_choose_us',
        'title' => 'لماذا تختارنا',
        'extra_data' => ['points' => [['icon' => 'clock', 'title' => 'الالتزام بالوقت', 'description' => 'وصف']]],
    ]);

    // First call is a cache miss and writes the serialized value to the database cache table.
    $first = PageSection::getByKey('why_choose_us');

    // Second call is a cache hit: this is the path that broke, since config/cache.php sets
    // `serializable_classes => false`, which silently turns any cached object back into a
    // useless __PHP_Incomplete_Class unless the model is rehydrated from a plain array.
    $second = PageSection::getByKey('why_choose_us');

    expect($first)->toBeInstanceOf(PageSection::class)
        ->and($first->title)->toBe('لماذا تختارنا')
        ->and($second)->toBeInstanceOf(PageSection::class)
        ->and($second->title)->toBe('لماذا تختارنا')
        ->and($second->extra_data['points'][0]['title'])->toBe('الالتزام بالوقت');
});

test('getByKey returns null for a missing key without throwing', function () {
    config(['cache.default' => 'database']);

    expect(PageSection::getByKey('does_not_exist'))->toBeNull();
});
