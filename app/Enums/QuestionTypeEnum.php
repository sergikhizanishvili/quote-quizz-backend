<?php

namespace App\Enums;

enum QuestionTypeEnum: string
{
    use Traits\RichEnum;
    
    case BINARY = 'binary';
    case MULTIPLE = 'multiple';
}
