<?php

namespace App\Exceptions;

class ProductIsNotEnoughException extends \Exception
{
    protected $message = 'Not enough product available';
}