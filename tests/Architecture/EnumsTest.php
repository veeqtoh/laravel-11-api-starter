<?php

test('enums directory contains only enums')
    ->expect('App\Enums')
    ->toBeEnums();
