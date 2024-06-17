<?php

use App\Abstracts\AbstractRepository;

test('repositories extends base abstract class')
    ->expect('App\Repositories')
    ->toExtend(AbstractRepository::class);

test('all repositories are classes')
    ->expect('App\Repositories')
    ->toBeClasses();

arch('all repositories have a constructor')
    ->expect('App\Repositories')
    ->toHaveConstructor();
