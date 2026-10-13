<?php
namespace App\DTO;

class ApiResponse
{
    /** @var int */
    public $statusCode;

    /** @var int */
    public $code;

    /** @var string */
    public $message;

    /** @var string */
    public $time;

    /** @var array */
    public $data;

    public function __construct(
        $statusCode = 500,
        $code = 0,
        $message = '',
        $time = '',
        $data = []
    ) {
        $this->statusCode = $statusCode;
        $this->code = $code;
        $this->message = $message;
        $this->time = $time;
        $this->data = $data;
    }

    public function isOk(): bool
    {
        return $this->statusCode == 200 && $this->code == 1000;
    }
}
