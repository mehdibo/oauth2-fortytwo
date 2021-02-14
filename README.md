# 42 Intranet OAuth 2.0 provider for the PHP League's OAuth 2.0 Client
![Latest version](https://img.shields.io/github/v/tag/mehdibo/oauth2-fortytwo)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![Unit tests](https://github.com/mehdibo/oauth2-fortytwo/workflows/Unit%20tests/badge.svg?branch=develop)
![Packagist Downloads](https://img.shields.io/packagist/dt/mehdibo/oauth2-fortytwo)


This package provides 42 Intranet's OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require mehdibo/oauth2-fortytwo
```

## Usage

### Authorization Code Flow

```php
$provider = new \Mehdibo\OAuth2\Client\Provider\FortyTwo([
    'clientId'          => '{uid}',
    'clientSecret'      => '{secret}',
    'redirectUri'       => '{redirect-uri}',
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        /**
         * @var $user \Mehdibo\OAuth2\Client\Provider\ResourceOwner
        */
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $user->getLogin()));

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}
```

### Managing Scopes

You can add extra scopes by passing them to the `getAuthorizationUrl()` method

```php

$options = [
    'scope' => ['public','profile','projects']
];

$authorizationUrl = $provider->getAuthorizationUrl($options);
```

If no scopes are passed, only `public` is used

## Testing

``` bash
$ ./vendor/bin/phpunit
```