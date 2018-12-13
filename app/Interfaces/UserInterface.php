<?php

namespace App\Interfaces;

interface UserInterface
{
    public function isBanned();
    public function allowedToLogin();
}
