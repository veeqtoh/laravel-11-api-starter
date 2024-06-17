<?php

test('concerns directory contains only traits')
    ->expect('App\Concerns')
    ->toBeTraits();
