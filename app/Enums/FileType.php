<?php

namespace App\Enums;

enum FileType: string
{
    case IDENTITY_DOCUMENT = 'identity_document';
    case PHOTO = 'photo';
    case VISA = 'visa';
}
