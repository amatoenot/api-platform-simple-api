<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ApiResource(
    operations: [
        new Post()
    ]
)]
#[ApiResource(
    uriTemplate: '/books/{id}/reviews', 
    uriVariables: [
        'id' => new Link(
            fromClass: Book::class,
            toProperty: 'book'
        )
    ], 
    operations: [
        new GetCollection()
    ]
)]
#[ApiResource(
    uriTemplate: '/books/{bookId}/reviews/{id}', 
    uriVariables: [
        'bookId' => new Link(fromClass: Book::class, toProperty: 'book'),
        'id' => new Link(fromClass: Review::class)
    ], 
    operations: [
        new Get()
    ]
)]
class Review
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column]
    #[Assert\Range(min: 1, max: 5)]
    private int $rating;

    #[ORM\Column(length: 255)]
    private string $reviewerName;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private Book $book;

    public function __construct(
        int $rating,
        Book $book
    ) {
        $this->id = Uuid::v4();
        $this->rating = $rating;
        $this->book = $book;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getReviewerName(): ?string
    {
        return $this->reviewerName;
    }

    public function setReviewerName(string $reviewerName): self
    {
        $this->reviewerName = $reviewerName;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }
}
