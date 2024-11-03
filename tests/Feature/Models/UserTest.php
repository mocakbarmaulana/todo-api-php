<?php

it("has_fillable_properties", function() {
    $user = new \App\Models\User();

    expect($user->getFillable())->toBe([
        "name",
        "email",
        "password",
    ]);
});

it("has_hidden_properties", function() {
    $user = new \App\Models\User();

    expect($user->getHidden())->toBe([
        "password",
        "remember_token",
    ]);
});

it("return_jwt_identifier", function() {
    $user = new \App\Models\User();
    $user->id = 1;

    expect($user->getJWTIdentifier())->toBe(1);
});

it("return_jwt_custom_claims", function() {
    $user = new \App\Models\User();

    expect($user->getJWTCustomClaims())->toBe([]);
});
