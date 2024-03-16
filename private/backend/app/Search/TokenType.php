<?php

namespace App\Search;

enum TokenType: string
{
    case STATUS = 'status';
    case TAG = 'tag';
    case STRING = 'string';
}
