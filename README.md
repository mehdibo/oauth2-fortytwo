# 42 Intranet OAuth 2.0 provider for the PHP League's OAuth 2.0 Client
[![Latest Stable Version](https://poser.pugx.org/mehdibo/oauth2-fortytwo/v)](//packagist.org/packages/mehdibo/oauth2-fortytwo)
[![Latest Unstable Version](https://poser.pugx.org/mehdibo/oauth2-fortytwo/v/unstable)](//packagist.org/packages/mehdibo/oauth2-fortytwo)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
![Unit tests](https://github.com/mehdibo/oauth2-fortytwo/workflows/Unit%20tests/badge.svg?branch=main)
![Packagist Downloads](https://img.shields.io/packagist/dt/mehdibo/oauth2-fortytwo)


This package provides 42 Intranet's OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require mehdibo/oauth2-fortytwo
```

## Usage

## Using with Symfony

You can use this library with [knpuniversity/oauth2-client-bundle](https://github.com/knpuniversity/oauth2-client-bundle)

Install [knpuniversity/oauth2-client-bundle](https://github.com/knpuniversity/oauth2-client-bundle) using composer:

```
composer require knpuniversity/oauth2-client-bundle
```

Install mehdibo/oauth2-fortytwo:

```
composer require mehdibo/oauth2-fortytwo
```

Add these lines to your `.env` file:
```
###> mehdibo/oauth2-fortytwo ###
OAUTH_FT_ID=
OAUTH_FT_SECRET=
###< mehdibo/oauth2-fortytwo ###
```

Configure the bundle to use FT's client, by updating `config/packages/knpu_oauth2_client.yml`:
```yaml
knpu_oauth2_client:
    clients:
        forty_two:
            type: generic
            provider_class: Mehdibo\OAuth2\Client\Provider\FortyTwo
            client_id: '%env(OAUTH_FT_ID)%'
            client_secret: '%env(OAUTH_FT_SECRET)%'
            redirect_route: auth_social_fortytwo_check # Here is the name of the route that will be used to check the code
```

Create the controller to handle the authentication logic, check [this](https://github.com/knpuniversity/oauth2-client-bundle#authenticating-with-guard) for more details about that.

```php
# src/Controller/FortyTwoController.php

namespace App\Controller;

use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\OpenApi;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class FortyTwoController extends AbstractController
{

    #[Route('/auth/login/fortytwo', name: 'auth_social_fortytwo_start')]
    public function connect(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('forty_two')
            ->redirect([], []);
    }

    #[Route('/auth/login/fortytwo/check', name: 'auth_social_fortytwo_check')]
    public function connectCheck(Request $request, ClientRegistry $clientRegistry): void
    {
        throw new \LogicException("This should be caught by the guard authenticator");
    }
}
```

The final step is to create an Authentication guard

## Authorization Code Flow

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
        printf('Hello %s!', $user->getLogin());

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