<?php

namespace Media101\Bird\Concerns;

use Media101\Bird\Models\Contact;

interface IsBirdMessage
{
    public function toContact(Contact $contact);
}
