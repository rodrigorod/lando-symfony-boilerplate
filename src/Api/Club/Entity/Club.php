<?php

namespace App\Api\Club\Entity;

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
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use App\Api\Post\Entity\Post;

/**
 * Class Club.
 *
 * @ORM\Table(name="club")
 * @ORM\Entity(repositoryClass=ClubRepository::class)
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
     */
    protected Uuid $id;

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
     */
    protected string $slug;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"view", "profile"})
     */
    protected string $name;

    /**
     * Club banner image.
     *
     * @ORM\Column(type="string")
     *
     * @Assert\Type("string")
     * @Assert\NotNull()
     *
     * @Groups({"view", "profile"})
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
     * @Groups({"view"})
     */
    protected Collection $members;

    /**
     * Club categories.
     *
     * @ORM\Column(type="array")
     *
     * @Assert\Type("array")
     * @Assert\NotNull
     *
     * @Groups({"view"})
     */
    protected array $categories = [];

    /**
     * Club posts.
     *
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="club")
     *
     * @Assert\Type("array")
     * @Assert\NotNull()
     *
     * @Groups({"view"})
     */
    protected Collection $posts;

    public function __construct(array $values = [])
    {
        $this->createdAt = new DateTime();
        $this->members = new ArrayCollection();
        $this->posts = new ArrayCollection();

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
    public function getCategories(): array
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
}
