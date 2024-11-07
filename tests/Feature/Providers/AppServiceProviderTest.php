<?php

it("test_binds_random_joke_service_to_the_container", function () {
    $this->assertInstanceOf(
        \App\Service\RandomJokeService::class,
        app(\App\Service\RandomJokeService::class)
    );
});
