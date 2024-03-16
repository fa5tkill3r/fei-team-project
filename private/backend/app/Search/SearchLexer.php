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

        if ($ch === 't') {
            return $this->tryTokenizeTag();
        }
        if ($ch == 'i') {
            return $this->tryTokenizeStatus();
        }

        return $this->tokenizeString();
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

    private function tryTokenizeTag(): ?Token
    {
        $word = $this->readN(4);

        if ($word !== 'tag:') {
            $this->position -= strlen($word);
            return $this->tokenizeString();
        }

        $tag = $this->readUntilWhitespace();
        return new Token(TokenType::TAG, $tag);
    }

    private function tryTokenizeStatus(): ?Token
    {
        $word = $this->readN(3);

        if ($word !== 'is:') {
            $this->position -= strlen($word);
            return $this->tryTokenizeTag();
        }

        $status = $this->readUntilWhitespace();
        return new Token(TokenType::STATUS, $status);
    }
}
