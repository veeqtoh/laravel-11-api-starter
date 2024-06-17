<?php

use Illuminate\Database\Eloquent\Model;

test('models extends base model')
    ->expect('App\Models')
    ->toExtend(Model::class);

test('model factories extend the base factory class')
    ->expect('Database\Factories;')
    ->toExtend('Illuminate\Database\Eloquent\Factories\Factory');

test('all models are classes')
    ->expect('App\Models')
    ->toBeClasses();
