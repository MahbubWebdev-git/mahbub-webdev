<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

it('loads the about page without middleware resolution errors', function () {
    $this->get('/about')->assertOk();
});
