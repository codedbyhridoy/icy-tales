<?php

// TODO: Quick fixes
arch()->preset()->laravel()
    ->ignoring('App\Http\Controllers')
    ->ignoring('App\Http\Middleware');

arch()->preset()->security();

arch('Do not use env helper in code')
    ->expect(['env'])
    ->not->toBeUsed();

arch('Services classes should have proper suffix')
    ->expect('App\Services')
    ->toHaveSuffix('Service');
