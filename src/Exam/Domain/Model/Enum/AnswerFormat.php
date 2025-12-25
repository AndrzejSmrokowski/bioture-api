<?php

namespace Bioture\Exam\Domain\Model\Enum;

enum AnswerFormat: string
{
    case TEXT = 'text';
    case NUMBER = 'number';
    case CHOICE = 'choice';
    case BOOLEAN = 'boolean';
    case JSON = 'json';
    case MULTI_TEXT = 'multi_text';
    case TABLE = 'table';
    case ORDERING = 'ordering';
}
