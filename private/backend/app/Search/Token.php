<?php

namespace App\Search;

class Token
{
    public TokenType $type;
    public ?string $value;

    public function __construct(TokenType $type, ?string $value)
    {
        $this->type = $type;
        $this->value = $value;
    }
}
