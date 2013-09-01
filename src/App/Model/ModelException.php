<?php
namespace App\Model;

class ModelException extends \Exception
{
    protected $message = 'Something went wrong';
    protected $code = 500;
}