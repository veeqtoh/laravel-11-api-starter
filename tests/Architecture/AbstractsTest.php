<?php

test('abstract directory contains only abstracts')
    ->expect('App\Abstracts')
    ->toBeAbstract();
