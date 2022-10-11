<?php

namespace App\Api\Post\Entity;

use App\Api\User\Entity\UserInterface;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;

/**
 * Interface CommentInterface.
 */
interface CommentInterface
{
    /**
     * Get comment unique identifier.
     *
     * @return string
     *  Unique identifier
     */
    public function getId(): string;

    /**
     * Get comment author.
     *
     * @return UserInterface
     *  User
     */
    public function getAuthor(): UserInterface;

    /**
     * Set comment author.
     *
     * @param UserInterface $author
     *  User
     */
    public function setAuthor(UserInterface $author): self;

    /**
     * Get comment post.
     *
     * @return PostInterface
     *  Post
     */
    public function getPost(): PostInterface;

    /**
     * Set comment post.
     *
     * @param PostInterface $post
     *  Post
     */
    public function setPost(PostInterface $post): self;

    /**
     * Get comment message.
     *
     * @return string
     *  Message
     */
    public function getMessage(): string;

    /**
     * Set comment message.
     *
     * @param string $message
     *  Message
     */
    public function setMessage(string $message): self;

    /**
     * Get comment posting date.
     *
     * @return DateTimeInterface
     *  Date
     */
    public function getPostedAt(): DateTimeInterface;
}

