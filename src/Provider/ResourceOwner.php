<?php


namespace Mehdibo\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\GenericResourceOwner;

final class ResourceOwner extends GenericResourceOwner
{

    /**
     * @param array<string, mixed> $response
     */
    public function __construct(array $response)
    {
        parent::__construct($response, "id");
    }

    public function getEmail(): ?string
    {
        return $this->response['email'] ?? null;
    }

    public function getLogin(): ?string
    {
        return $this->response['login'] ?? null;
    }

    public function getFirstName(): ?string
    {
        return $this->response['first_name'] ?? null;
    }

    public function getLastName(): ?string
    {
        return $this->response['last_name'] ?? null;
    }

    public function getImageUrl(): ?string
    {
        return $this->response['image_url'] ?? null;
    }

    public function getIsStaff(): ?bool
    {
        return $this->response['staff?'] ?? null;
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

    public function getPrimaryCampusId(): ?int
    {
        foreach ($this->response['campus_users'] as $campusUser) {
            if ($campusUser['is_primary']) {
                return $campusUser['campus_id'];
            }
        }
        return null;
    }
}