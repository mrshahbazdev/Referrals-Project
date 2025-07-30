<?php

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use App\Listeners\LogSuccessfulLogin;
use App\Listeners\LogSuccessfulLogout;
use App\Listeners\LogRegisteredUser;

Event::listen(Registered::class, LogRegisteredUser::class);
Event::listen(Login::class, LogSuccessfulLogin::class);
Event::listen(Logout::class, LogSuccessfulLogout::class);
