<?php

namespace Media101\_src\Services\Notifications\SMS\Utils;

enum SMSType: string
{

    case TEXT = 'text';
    case HTML = 'html';
    case IMAGE = 'image';
    case FILE = 'file';
    case GIF = 'gif';
    case LOCATION = 'location';
    case CAROUSEL = 'carousel';
    case LIST = 'list';
    case SECTION = 'section';
    case AUTHENTICATION = 'authentication';
    case TEMPLATE = 'template';
}
