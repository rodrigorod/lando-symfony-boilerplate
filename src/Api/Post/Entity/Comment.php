<?php

namespace App\Api\Post\Entity;

use App\Api\User\Entity\User;
use App\Api\User\Entity\UserInterface;
use App\DependencyInjection\LikesAwareTrait;
use App\Repository\CommentRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment.
 *
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment implements CommentInterface
{
    use LikesAwareTrait;

    /**
     * Comment unique identifier.
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     *
     * @Assert\Type(Uuid::class)
     * @Assert\Unique()
     * @Assert\NotNull()
     *
     * @Groups({"view", "comment"})
     *
     * @OA\Property(
     *     property="id",
     *     nullable=false,
     *     type="string",
     *     description="Comment unique identifier.",
     *     format="uuid",
     *     example="1ed43b17-1a8f-6a98-9d43-57a84bcee731",
     * )
     *
     * @Groups({"view"})
     */
    protected Uuid $id;

    /**
     * Comment author.
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     *
     * @Assert\Type(UserInterface::class)
     * @Assert\NotNull()
     *
     * @OA\Property(
     *     property="author",
     *     nullable=false,
     *     type="object",
     *     allOf={
     *          @OA\Schema(ref=@Model(type=User::class))
     *     }
     * )
     *
     * @Groups({"view", "comment"})
     */
    protected UserInterface $author;

    /**
     * Comment post.
     *
     * @ORM\ManyToOne(targetEntity=Post::class)
     *
     * @Assert\Type(PostInterface::class)
     * @Assert\NotNull()
     *
     * @OA\Property(
     *     property="post",
     *     nullable=false,
     *     type="object",
     *     allOf={
     *          @OA\Schema(ref=@Model(type=Post::class))
     *     }
     * )
     */
    protected PostInterface $post;

    /**
     * Comment message.
     *
     * @ORM\Column(type="text", length=255)
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @OA\Property(
     *     property="message",
     *     nullable=false,
     *     type="string",
     *     description="Comment message.",
     *     example="My amazing comment message !",
     * )
     *
     * @Groups({"view", "comment"})
     */
    protected string $message;

    /**
     * Comment posted at date.
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime()
     * @Assert\NotNull()
     *
     * @Groups({"view", "comment"})
     *
     * @OA\Property(
     *     property="postedAt",
     *     nullable=false,
     *     type="datetime",
     *     description="Comment posted at date.",
     *     example="12:12:12T02:22:11",
     * )
     */
    protected DateTimeInterface $postedAt;

    public function __construct(string $message)
    {
        $this->message = $message;
        $this->postedAt = new DateTime();
        $this->likes = new ArrayCollection();
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthor(): UserInterface
    {
        return $this->author;
    }

    /**
     * {@inheritDoc}
     */
    public function setAuthor(UserInterface $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPost(): PostInterface
    {
        return $this->post;
    }

    /**
     * {@inheritDoc}
     */
    public function setPost(PostInterface $post): self
    {
        $this->post = $post;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * {@inheritDoc}
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPostedAt(): DateTimeInterface
    {
        return $this->postedAt;
    }
}
