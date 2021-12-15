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

    public function getEvaluationPoints(): int
    {
        return $this->response['correction_point'];
    }

    public function getWallet(): int
    {
        return $this->response['wallet'];
    }

    public function getAnonymizationDate(): \DateTimeInterface
    {
        return new \DateTime($this->response['anonymize_date']);
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

    /**
     * @return array<int, array<string, mixed>
     */
    public function getCursusUsers(): array
    {
        return $this->response['cursus_users'];
    }

    /**
     * @return array<string, mixed>|null Returns the cursus user or null if it doesn't exist
     */
    public function getCursusUser(int $cursusId): ?array
    {
        foreach ($this->getCursusUsers() as $cursusUser) {
            if ($cursusUser['cursus']['id'] === $cursusId) {
                return $cursusUser;
            }
        }
        return null;
    }

    /**
     * @return int Returns 0 if a primary campus was not found
     */
    public function getPrimaryCampusId(): int
    {
        foreach ($this->response['campus_users'] as $campusUser) {
            if ($campusUser['is_primary']) {
                return $campusUser['campus_id'];
            }
        }
        return 0;
    }
}
