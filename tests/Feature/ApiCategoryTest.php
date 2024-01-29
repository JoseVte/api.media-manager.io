<?php

test('show all categories', function () {
    $response = $this->get($this->routeApi.'/categories');

    $response->assertResponseOk();
    $response->response->assertJsonStructure([
        ['name', 'value'],
    ]);
});
