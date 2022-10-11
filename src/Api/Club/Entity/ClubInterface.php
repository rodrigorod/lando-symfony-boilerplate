<?php

namespace App\Api\Club\Entity;

use App\Api\Category\Entity\Category;
use App\Api\Category\Entity\CategoryInterface;
use App\Api\Post\Entity\PostInterface;
use App\Api\User\Entity\UserInterface;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;

/**
 * Interface ClubInterface.
 */
interface ClubInterface
{
    /**
     * Get club id.
     *
     * @return string
     *  Unique identifier
     */
    public function getId(): string;

    /**
     * Get club slug.
     *
     * @return string
     *  Slug
     */
    public function getSlug(): string;

    /**
     * Get club banner image.
     *
     * @return string
     *  Image
     */
    public function getBannerImage(): string;

    /**
     * Set club banner image.
     *
     * @param string $bannerImage
     *  Banner image
     */
    public function setBannerImage(string $bannerImage): self;

    /**
     * Get club image.
     *
     * @return string
     *  Image
     */
    public function getImage(): string;

    /**
     * Set club image.
     *
     * @param string $image
     *  Image
     */
    public function setImage(string $image): self;

    /**
     * Get club name.
     *
     * @return string
     *  Name
     */
    public function getName(): string;

    /**
     * Set club name.
     *
     * @param string $name
     *  Name
     */
    public function setName(string $name): self;

    /**
     * Get club description.
     *
     * @return string
     *  Description
     */
    public function getDescription(): string;

    /**
     * Set club description.
     *
     * @param string $description
     *  Description
     */
    public function setDescription(string $description): self;

    /**
     * Get club location.
     */
    public function getLocation(): string;

    /**
     * Set club location.
     *
     * @param string $location
     *  Location
     */
    public function setLocation(string $location): self;

    /**
     * Get club owner.
     */
    public function getOwner(): UserInterface;

    /**
     * Set club owner.
     *
     * @param UserInterface $owner
     *  Owner
     */
    public function setOwner(UserInterface $owner): self;

    /**
     * Get club members.
     *
     * @return Collection<UserInterface>
     */
    public function getMembers(): Collection;

    /**
     * Add club member.
     *
     * @param UserInterface $member
     *  Member
     */
    public function addMember(UserInterface $member): self;

    /**
     * Remove club member.
     *
     * @param UserInterface $member
     *  Member
     */
    public function removeMember(UserInterface $member): self;

    /**
     * Get club creation date.
     *
     * @return DateTimeInterface
     *  Date
     */
    public function getCreatedAt(): DateTimeInterface;

    /**
     * Get club categories.
     *
     * @return Collection<CategoryInterface>
     *  Categories
     */
    public function getCategories(): Collection;

    /**
     * Get club posts.
     *
     * @return Collection<PostInterface>
     *  Posts
     */
    public function getPosts(): Collection;

    /**
     * Add club post.
     *
     * @param PostInterface $post
     *  Member
     */
    public function addPost(PostInterface $post): self;

    /**
     * Remove club post.
     *
     * @param PostInterface $post
     *  Post
     */
    public function removePost(PostInterface $post): self;

    /**
     * Add club category.
     *
     * @param Category $category
     *  Category
     */
    public function addCategory(Category $category): self;

    /**
     * Remove club category.
     *
     * @param Category $category
     *  Category
     */
    public function removeCategory(Category $category): self;

    /**
     * Get members count.
     *
     * @return int
     *  Count
     */
    public function getMembersCount(): int;

    /**
     * Get posts count.
     *
     * @return int
     *  Count
     */
    public function getPostsCount(): int;
}
