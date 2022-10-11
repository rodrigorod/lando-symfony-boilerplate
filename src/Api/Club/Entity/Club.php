<?php

namespace App\Api\Club\Entity;

use App\Api\Category\Entity\Category;
use App\Api\Post\Entity\Post;
use App\Api\Post\Entity\PostInterface;
use App\Api\User\Entity\User;
use App\Api\User\Entity\UserInterface;
use App\DependencyInjection\TimerAwareTrait;
use App\Repository\ClubRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Club.
 *
 * @ORM\Table(name="club")
 * @ORM\Entity(repositoryClass=ClubRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Club implements ClubInterface
{
    use TimerAwareTrait;

    /**
     * Club id.
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     *
     * @Assert\Uuid()
     * @Assert\NotNull()
     *
     * @Groups({"view", "profile"})
     *
     * @OA\Property(
     *     property="id",
     *     nullable=false,
     *     type="string",
     *     format="uid",
     *     description="Unique identifier.",
     *     example="1ed42fe2-16f6-6368-98b6-d93168bb499c",
     * )
     */
    protected Uuid $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"view", "profile"})
     *
     * @OA\Property(
     *     property="name",
     *     nullable=false,
     *     type="string",
     *     description="Club name.",
     *     example="My amazing club",
     * )
     */
    protected string $name;

    /**
     * Club slug.
     *
     * @ORM\Column(type="string", length=128, unique=true)
     * @Gedmo\Slug(fields={"name"})
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"view", "profile"})
     *
     * @OA\Property(
     *     property="slug",
     *     nullable=false,
     *     type="string",
     *     format="slug",
     *     description="Club slug.",
     *     example="my-amazing-club",
     * )
     */
    protected string $slug;

    /**
     * Club banner image.
     *
     * @ORM\Column(type="string")
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"view", "profile"})
     *
     * @OA\Property(
     *     property="bannerImage",
     *     nullable=false,
     *     type="string",
     *     description="Club banner image",
     *     example="banner.png",
     *     default="default.png",
     * )
     */
    protected string $bannerImage = 'default.png';

    /**
     * Club image.
     *
     * @ORM\Column(type="string")
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"view", "profile"})
     *
     * @OA\Property(
     *     property="image",
     *     nullable=false,
     *     type="string",
     *     description="Club image.",
     *     example="image.png",
     *     default="default.png",
     * )
     */
    protected string $image = 'default.png';

    /**
     * Club description.
     *
     * @ORM\Column(type="text")
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"view"})
     *
     * @OA\Property(
     *     property="description",
     *     nullable=false,
     *     type="string",
     *     description="Club description",
     *     example="My amazing club description !",
     * )
     */
    protected string $description;

    /**
     * Club location.
     *
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"view"})
     *
     * @OA\Property(
     *     property="location",
     *     nullable=false,
     *     type="string",
     *     description="Club location.",
     *     example="bern",
     * )
     */
    protected string $location;

    /**
     * Club owner.
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"view"})
     *
     * @OA\Property(
     *     property="owner",
     *     nullable=false,
     *     type="object",
     *     allOf={
     *          @OA\Schema(ref=@Model(type=User::class))
     *     },
     * )
     */
    protected UserInterface $owner;

    /**
     * Club members.
     *
     * @ORM\ManyToMany(targetEntity=User::class)
     * @ORM\JoinTable(name="club_members")
     *
     * @Assert\Type("array")
     * @Assert\NotNull()
     *
     * @Groups({"read"})
     *
     * @OA\Property(
     *     property="members",
     *     nullable=false,
     *     type="array",
     *     @OA\Items(ref=@Model(type=User::class)),
     * )
     */
    protected Collection $members;

    /**
     * Club categories.
     *
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="clubs")
     * @ORM\JoinTable(name="club_categories")
     * @Assert\NotNull()
     *
     * @Groups({"view"})
     *
     * @OA\Property(
     *     property="categories",
     *     nullable=false,
     *     type="array",
     *     @OA\Items(ref=@Model(type=Category::class)),
     * )
     */
    protected Collection $categories;

    /**
     * Club posts.
     *
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="club")
     *
     * @Assert\Type("array")
     * @Assert\NotNull()
     *
     * @Groups({"read"})
     *
     * @OA\Property(
     *     property="posts",
     *     nullable=false,
     *     type="array",
     *     @OA\Items(ref=@Model(type=Post::class)),
     * )
     */
    protected Collection $posts;

    /**
     * Club members count.
     *
     * @Assert\Type("integer")
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\NotNull()
     *
     * @Groups({"list"})
     *
     * @OA\Property(
     *     property="membersCount",
     *     nullable=false,
     *     type="integer",
     *     example="100",
     *     default="0",
     * )
     */
    protected int $membersCount = 0;

    /**
     * Club members count.
     *
     * @Assert\Type("integer")
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\NotNull()
     *
     * @Groups({"list"})
     *
     * @OA\Property(
     *     property="postsCount",
     *     nullable=false,
     *     type="integer",
     *     example="100",
     *     default="0",
     * )
     */
    protected int $postsCount = 0;

    public function __construct(array $values = [])
    {
        $this->createdAt = new DateTime();
        $this->members = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->categories = new ArrayCollection();

        foreach ([
            'name',
            'bannerImage',
            'image',
            'description',
            'location',
        ] as $property) {
            if (isset($values[$property])) {
                $this->{$property} = $values[$property];
            }
        }
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTime();
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
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * {@inheritDoc}
     */
    public function getBannerImage(): string
    {
        return $this->bannerImage;
    }

    /**
     * {@inheritDoc}
     */
    public function setBannerImage(string $bannerImage): self
    {
        $this->bannerImage = $bannerImage;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * {@inheritDoc}
     */
    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * {@inheritDoc}
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * {@inheritDoc}
     */
    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getOwner(): UserInterface
    {
        return $this->owner;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwner(UserInterface $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    /**
     * {@inheritDoc}
     */
    public function addMember(UserInterface $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removeMember(UserInterface $member): self
    {
        $this->members->removeElement($member);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * {@inheritDoc}
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * {@inheritDoc}
     */
    public function addPost(PostInterface $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removePost(PostInterface $post): self
    {
        $this->posts->removeElement($post);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMembersCount(): int
    {
        return $this->membersCount = $this->posts->count();
    }

    /**
     * {@inheritDoc}
     */
    public function getPostsCount(): int
    {
        return $this->postsCount = $this->posts->count();
    }
}
