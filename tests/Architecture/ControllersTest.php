<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;

test('controllers extends base controller class')
    ->expect('App\Controllers')
    ->toExtend(Controller::class);

test('API controller classes extends base Api controller class')
    ->expect('App\Http\Controllers\Api\V1')
    ->toExtend(ApiController::class);

test('all controllers are classes')
    ->expect('App\Http\Controllers\Api\V1')
    ->toBeClasses();
