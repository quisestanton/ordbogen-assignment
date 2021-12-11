<?php

Route::get('/', [Dashboard::class, 'displayPage']);
Route::get('/signin', [SigninPage::class, 'displayPage']);
Route::get('/signin/form', [SigninPage::class, 'displaySigninForm']);
Route::get('/signup/form', [SigninPage::class, 'displaySignupForm']);
Route::get('/signup-verification/form', [SigninPage::class, 'displaySignupVerificationForm']);
Route::get('/signout', [SigninPage::class, 'signout']);
Route::post('/signin', [SigninPage::class, 'signin']);
Route::post('/signup', [SigninPage::class, 'signup']);
Route::post('/signup-verification', [SigninPage::class, 'verifySignup']);
