<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use MongoDB\BSON\Binary;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideosRepository")
 *
 */
class Videos
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid_binary_ordered_time", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidOrderedTimeGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $bucket_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $folder_path;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $metadata;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $video_path;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $thumbnail_path;

    public function getId()
    {
        return $this->id;
    }

    public function getBucketName(): ?string
    {
        return $this->bucket_name;
    }

    public function setBucketName(string $bucket_name): self
    {
        $this->bucket_name = $bucket_name;

        return $this;
    }

    public function getFolderPath(): ?string
    {
        return $this->folder_path;
    }

    public function setFolderPath(?string $folder_path): self
    {
        $this->folder_path = $folder_path;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMetadata(): ?string
    {
        return $this->metadata;
    }

    public function setMetadata(string $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function getVideoPath(): ?string
    {
        return $this->video_path;
    }

    public function setVideoPath(string $video_path): self
    {
        $this->video_path = $video_path;

        return $this;
    }
    public function getThumbnailPath(): ?string
    {
        return $this->thumbnail_path;
    }

    public function setThumbnailPath(string $thumbnail_path): self
    {
        $this->thumbnail_path = $thumbnail_path;

        return $this;
    }
}
