# Bird.com Laravel Package

## Introduction

This package provides seamless integration with the Bird.com API, allowing you to manage contacts and send notifications via SMS.

## Installation

You can install the package via composer:

```bash
composer require 101media/bird
```

After installing the package, you need to publish the configuration file:

```bash
php artisan vendor:publish --tag="bird-config"
```

## Configuration

After publishing the configuration file, you need to set the following environment variables in your `.env` file:

```env
BIRD_ACCESS_KEY={--your-access-key--}
BIRD_WORKSPACE_ID={--your-workspace-id--}
BIRD_SMS_CHANNEL_ID={--your-sms-channel-id--}
```

All the configuration keys are available in `config/bird.php`.


## Usage

### Contact Management

#### Retrieve Contacts

To retrieve contacts from the API, you can use the `get` method of `ContactManager`:

```php
use Media101\_src\Services\Contacts\ContactManager;

$contacts = ContactManager::get(); // Retrieve all contacts with default settings
```

You can also retrieve a specific contact by ID:

```php
$contact = ContactManager::get('contact-id');
```

Additional parameters can be provided to customize the query:

```php
$contacts = ContactManager::get(null, 20, true, 'nextPageToken');
```

#### Create or Update Contacts

To create or update a contact, use the `createOrUpdate` method:

```php
use Media101\_src\Services\Contacts\BirdContact;
use Media101\_src\Services\Contacts\ContactManager;

$contact = (new BirdContact())
    ->name('John Doe')
    ->phone('+12345678901')
    ->email('john.doe@example.com');

$response = ContactManager::createOrUpdate($contact);
```

You can specify the identifier key (default is `phonenumber`):

```php
$response = ContactManager::createOrUpdate($contact, 'emailaddress');
```

#### Delete Contacts

To delete a contact, use the `delete` method:

```php
$response = ContactManager::delete('contact-id');
```

### Notifications

#### Sending SMS Notifications

At the moment this package only suppots sending SMS notifications with `text` type only. To send SMS notifications, you need to use the `SMSChannel` class. This class handles sending SMS notifications via Bird.com.

First, create a notification class that returns an instance of `SMSMessage` class:

```php
namespace App\Notifications;

use Media101\_src\Services\Contacts\BirdContact;
use Media101\_src\Services\Notifications\SMS\SMSMessage;

class OrderReceived
{

    public function __construct(
        private string $content
    ) {}
    
    public function via($notifiable): array
    {
        return [SMSChannel::class];
    }

    public function toSMS(User $notifiable): array
    {
        // The phone number must have a country code appended.
        $contact = (new BirdContact())->phone($notifiable->phone_number);
        
        return (new SMSMessage())
            ->to($contact)
            ->type(SMSType::TEXT)
            ->text($this->content);
    }
    
}
```
Finally, notify the notifiable entity:

```php
$user->notify(new OrderReceived('Your order has been received'));
```

## Exception Handling

The package uses custom exceptions to handle errors:

- `InvalidParameterException`: Thrown when a parameter is invalid.
- `ConnectionException`: Thrown when there is a connection error with the API.
- `NotAnSmsMessageException`: Thrown when the provided message is not an instance of `SMSMessage`.
- `NotificationNotSent`: Thrown when the notification could not be sent.

Make sure to catch these exceptions in your code to handle errors gracefully.

## Contributing

Please submit issues and pull requests to the [GitHub repository](https://github.com/101media/bird).

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Contact

For any inquiries or support, please contact [101Media](mailto:webapps@101media.nl).

---

This README now includes sections for both `SMSChannel` and `SMSMessage`, as well as the existing contact management functionality. If you have any more classes or details to add, let me know!
