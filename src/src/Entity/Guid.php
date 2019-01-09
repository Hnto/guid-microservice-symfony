<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GuidRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Guid
{
    const GUID_STATUS_ISSUED = 'issued';
    const GUID_STATUS_ASSIGNED = 'assigned';

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $value;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="status", type="string", columnDefinition="enum('issued','assigned')")
     */
    private $status = 'issued';

    /**
     * @ORM\Column(type="datetime")
     */
    private $assignedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $assignedTo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $deletedAt;

    /**
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return Guid
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     *
     * @return Guid
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return Guid
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getAssignedAt(): ?\DateTimeInterface
    {
        return $this->assignedAt;
    }

    /**
     * @param \DateTimeInterface $assignedAt
     *
     * @return Guid
     */
    public function setAssignedAt(\DateTimeInterface $assignedAt): self
    {
        $this->assignedAt = $assignedAt;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getAssignedTo(): ?string
    {
        return $this->assignedTo;
    }

    /**
     * @param string $assignedTo
     *
     * @return Guid
     */
    public function assignTo(string $assignedTo): self
    {
        $this->assignedTo = $assignedTo;
        $this->assignedAt = new \DateTime();
        $this->status = self::GUID_STATUS_ASSIGNED;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAssigned()
    {
        return $this->status === self::GUID_STATUS_ASSIGNED;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTimeInterface $deletedAt
     *
     * @return Guid
     */
    public function setDeletedAt(\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersistSetCreatedAt()
    {
        $this->createdAt = new \DateTime();
    }
}
