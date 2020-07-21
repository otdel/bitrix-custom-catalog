<?php

namespace Oip\SocialStore\Product\Entity;


class Product
{
    /** @var int $id */
    private $id;
    /** @var string $name */
    private $name;
    /** @var string|null $name */
    private $code;
    /** @var string|null $picture */
    private $picture;
    /** @var string|null $description */
    private $description;
    /** @var string|null $link */
    private $link;
    /** @var string $article */
    private $article;

    /**
     * @param int $id
     * @param string $article
     * @param string|null $name
     * @param string|null $code
     * @param string|null $picture
     * @param string|null $description
     * @param string|null $link
     */
    public function __construct(
        int $id,
        string $article,
        ?string $name = null,
        ?string $code = null,
        ?string $picture = null,
        ?string $description = null,
        ?string $link = null)
    {
        $this->id = $id;
        $this->article = $article;
        $this->name = $name;
        $this->code = $code;
        $this->picture = $picture;
        $this->description = $description;
        $this->link = $link;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return string|null
     */
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getArticle(): string
    {
        return $this->article;
    }
}