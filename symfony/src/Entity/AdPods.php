<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdPodsRepository")
 */
class AdPods
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="binary")
     */
    private $videos_id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $start_offset;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clip_path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $metadata;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideosId()
    {
        return $this->videos_id;
    }

    public function setVideosId($videos_id): self
    {
        $this->videos_id = $videos_id;

        return $this;
    }

    public function getStartOffset(): ?string
    {
        return $this->start_offset;
    }

    public function setStartOffset(string $start_offset): self
    {
        $this->start_offset = $start_offset;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getClipPath(): ?string
    {
        return $this->clip_path;
    }

    public function setClipPath(string $clip_path): self
    {
        $this->clip_path = $clip_path;

        return $this;
    }

    public function getMetadata(): ?string
    {
        return $this->metadata;
    }

    public function setMetadata(?string $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }
}
