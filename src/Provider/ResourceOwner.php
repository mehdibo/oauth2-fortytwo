<?php


namespace Mehdibo\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\GenericResourceOwner;

class ResourceOwner extends GenericResourceOwner
{

    /**
     * @param array<string, mixed> $response
     */
    public function __construct(array $response)
    {
        parent::__construct($response, "id");
    }

    public function getEmail(): string
    {
        return $this->response['email'];
    }

    public function getLogin(): string
    {
        return $this->response['login'];
    }

    public function getFirstName(): string
    {
        return $this->response['first_name'];
    }

    public function getLastName(): string
    {
        return $this->response['last_name'];
    }

    public function getImageUrl(): string
    {
        return $this->response['image_url'];
    }

    public function getIsStaff(): bool
    {
        return $this->response['staff?'];
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = [];
        foreach ($this->response['roles'] as $role) {
            $roles[] = $role['name'];
        }
        return $roles;
    }

    public function getPrimaryCampusId(): int
    {
        foreach ($this->response['campus_users'] as $campusUser) {
            if ($campusUser['is_primary']) {
                return $campusUser['campus_id'];
            }
        }
        return null;
    }
}
