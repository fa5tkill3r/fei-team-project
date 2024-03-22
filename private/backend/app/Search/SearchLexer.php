<?php

namespace App\Search;

function isWhitespace(string $ch): bool
{
    return $ch === ' ' || $ch === "\t" || $ch === "\n" || $ch === "\r";
}

class SearchLexer
{
    private int $position;
    private string $input;

    public function __construct(string $input)
    {
        $this->input = $input;
        $this->position = 0;
    }

    private function peek(): ?string
    {
        if ($this->position >= strlen($this->input)) {
            return null;
        }
        return $this->input[$this->position];
    }

    public function tokenize(): array
    {
        $tokens = [];
        while (true) {
            $token = $this->next();
            if ($token === null) {
                break;
            }
            $tokens[] = $token;
        }
        return $tokens;
    }

    public function next(): ?Token
    {
        $ch = $this->peek();
        if ($ch === null) {
            return null;
        }
        if (isWhitespace($ch)) {
            $this->skipWhitespace();
            $ch = $this->peek();
        }

        return match ($ch) {
            't' => $this->tryTokenizeTag(),
            'i' => $this->tryTokenizeStatus(),
            'a' => $this->tryTokenizeAssignee(),
            default => $this->tokenizeString(),
        };
    }

    private function readN(int $n): string
    {
        $str = '';
        for ($i = 0; $i < $n; $i++) {
            $ch = $this->peek();
            if ($ch === null) {
                break;
            }
            $str .= $ch;
            $this->position++;
        }
        return $str;
    }

    private function readUntilWhitespace(): string
    {
        $str = '';

        while (true) {
            $ch = $this->peek();
            if ($ch === null) {
                break;
            }
            if (isWhitespace($ch)) {
                break;
            }

            $str .= $ch;
            $this->position++;
        }

        return strtolower($str);
    }

    private function skipWhitespace(): void
    {
        while (true) {
            $ch = $this->peek();
            if ($ch === null) {
                break;
            }
            if (!isWhitespace($ch)) {
                break;
            }
            $this->position++;
        }
    }

    private function tokenizeString(): ?Token
    {
        $str = $this->readUntilWhitespace();

        if ($str === '') {
            return null;
        }

        return new Token(TokenType::STRING, $str);
    }

    private function tryTokenizePrefix(string $prefix, TokenType $type): ?Token
    {
        $word = $this->readN(strlen($prefix));

        if ($word !== $prefix) {
            $this->position -= strlen($word);
            return $this->tokenizeString();
        }

        $value = $this->readUntilWhitespace();
        return new Token($type, $value);
    }

    private function tryTokenizeTag(): ?Token
    {
        return $this->tryTokenizePrefix('tag:', TokenType::TAG);
    }

    private function tryTokenizeStatus(): ?Token
    {
        return $this->tryTokenizePrefix('is:', TokenType::STATUS);
    }

    private function tryTokenizeAssignee(): ?Token
    {
        return $this->tryTokenizePrefix('assignee:', TokenType::ASSIGNEE);
    }
}
