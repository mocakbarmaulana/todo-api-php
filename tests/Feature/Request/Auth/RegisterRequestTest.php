<?php

use Illuminate\Support\Facades\Validator;

it("valid_register_request", function () {
    $data = [
        "name" => "Test User",
        "email" => "test@email.com",
        "password" => "password",
        "password_confirmation" => "password",
    ];

    $request = new \App\Http\Requests\Auth\RegisterRequest();
    // Get the rules and modify them to remove the unique constraint
    $rules = $request->rules();
    $rules['email'] = str_replace('unique:users', '', $rules['email']); // Remove the unique rule


    $validator = Validator::make($data, $rules);

    expect($validator->passes())->toBeTrue();
});

it("invalid register request", function () {
    $data = [
        "name" => "Test User",
        "email" => "test",
        "password" => "password",
    ];

    $request = new \App\Http\Requests\Auth\RegisterRequest();

    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue();
});

it('authorizes the register request', function () {
    $request = new \App\Http\Requests\Auth\RegisterRequest();

    // Call the authorize method and check the output
    $isAuthorized = $request->authorize();

    // Assert that it returns the expected value (true or false)
    expect($isAuthorized)->toBeTrue(); // or `toBeFalse()` if you want it false
});
