<?php

use Hybridly\RouteExtractor\RouteExtractor;
use Illuminate\Broadcasting\BroadcastController;
use Illuminate\Support\Facades\Route;
use Pest\Expectation;

beforeEach(function () {
    app()->setBasePath(str_replace('/vendor/orchestra/testbench-core/laravel', '', app()->basePath()));
});

test('built-in routes are extracted', function () {
    expect(app(RouteExtractor::class)->getRoutes())
        ->toHaveCount(1)
        ->sequence(
            fn (Expectation $route) => $route->toBe([
                'domain' => null,
                'method' => ['POST'],
                'uri' => 'hybridly',
                'name' => 'hybridly.endpoint',
                'bindings' => [],
                'wheres' => [],
            ]),
        );
});

test('routes can be filtered out', function () {
    Route::get('/filtered-route', fn () => response())->name('filtered-route');
    Route::get('/ok', fn () => response())->name('ok');

    config(['hybridly.router.exclude' => ['hybridly', 'filtered*']]);

    expect(app(RouteExtractor::class)->getRoutes())
        ->toHaveCount(1)
        ->sequence(fn ($_, $key) => $key->toBe('ok'));
});

test('only named routes are extracted', function () {
    Route::get('/not-named', fn () => response());
    Route::get('/named', fn () => response())->name('named');

    config(['hybridly.router.exclude' => 'hybridly']);

    expect(app(RouteExtractor::class)->getRoutes())
        ->toHaveCount(1)
        ->sequence(
            fn (Expectation $route) => $route->toBe([
                'domain' => null,
                'method' => ['GET', 'HEAD'],
                'uri' => 'named',
                'name' => 'named',
                'bindings' => [],
                'wheres' => [],
            ]),
        );
});

test('routes from vendors are excluded by default', function () {
    Route::get('/vendor-route', [BroadcastController::class, 'authenticate'])->name('vendor-route');

    config(['hybridly.router.exclude' => 'hybridly']);

    expect(app(RouteExtractor::class)->getRoutes())->toHaveCount(0);
});

test('routes from vendors can be opted-in', function () {
    Route::get('/vendor-route', [BroadcastController::class, 'authenticate'])->name('vendor-route');

    config(['hybridly.router.allowed_vendors' => ['laravel/framework']]);
    config(['hybridly.router.exclude' => 'hybridly']);

    expect(app(RouteExtractor::class)->getRoutes())
        ->toHaveCount(1)
        ->sequence(fn ($_, $key) => $key->toBe('vendor-route'));
});
