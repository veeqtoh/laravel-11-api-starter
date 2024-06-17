<?php

use App\Concerns\ApiResponses;
use App\Http\Services\BaseService;

test('models extends base model')
    ->expect('App\Services')
    ->toExtend(BaseService::class);

test('service classes use the API response trait.')
    ->expect('App\Concerns')
    ->toUse(ApiResponses::class);

test('all services are classes')
    ->expect('App\Services')
    ->toBeClasses();

arch('all services have a constructor')
    ->expect('App\Services')
    ->toHaveConstructor();

