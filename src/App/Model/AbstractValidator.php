<?php
namespace App\Model;

use Symfony\Component\Validator\Validator;

abstract class AbstractValidator
{
    /**
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return Validator
     */
    protected function validator()
    {
        return $this->validator;
    }
}