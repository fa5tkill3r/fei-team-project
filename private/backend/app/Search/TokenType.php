<?php

namespace App\Search;

enum TokenType: string
{
    case STATUS = 'status';
    case TAG = 'tag';
    case ASSIGNEE = 'assignee';
    case STRING = 'string';
}
