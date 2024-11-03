<?php

use Illuminate\Support\Facades\Validator;

it("valid login request", function () {
    $data = [
        "email" => "test@email.com",
        "password" => "password",
    ];

    $request = new \App\Http\Requests\Auth\LoginRequest();

    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

it("invalid login request", function () {
    $data = [
        "email" => "test",
        "password" => "password",
    ];

    $request = new \App\Http\Requests\Auth\LoginRequest();

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
});

it('authorizes the login request', function () {
    $request = new \App\Http\Requests\Auth\LoginRequest();

    // Call the authorize method and check the output
    $isAuthorized = $request->authorize();

    // Assert that it returns the expected value (true or false)
    expect($isAuthorized)->toBeTrue(); // or `toBeFalse()` if you want it false
});
