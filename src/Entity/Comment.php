<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ApiResource(
 *  normalizationContext={"groups"={"comment_read"}},
 *  collectionOperations={
 *      "get"={
 *          "security"="is_granted('ROLE_USER') or object.user == user"
 *      },
 *      "post"={"security"="is_granted('ROLE_USER')"}
 *  }
 * )
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"comment_read", "product_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"comment_read", "product_read"})
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="comment")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"comment_read"})
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comment")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"comment_read"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @Groups({"comment_read", "product_read"})
     */
    public function getAuthor()
    {
        return $this->user->getFirstName() . " " . $this->user->getLastName();
    }

}
