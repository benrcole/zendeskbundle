<?php

namespace Malwarebytes\ZendeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 */
class Ticket
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $comments;

    /**
     * @var \CM\UserBundle\Entity\User
     */
    private $owner;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Ticket
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add comments
     *
     * @param \Malwarebytes\ZendeskBundle\Entity\Comment $comments
     * @return Ticket
     */
    public function addComment(\Malwarebytes\ZendeskBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Malwarebytes\ZendeskBundle\Entity\Comment $comments
     */
    public function removeComment(\Malwarebytes\ZendeskBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set owner
     *
     * @param \CM\UserBundle\Entity\User $owner
     * @return Ticket
     */
    public function setOwner(\CM\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \CM\UserBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }
    /**
     * @var string
     */
    private $zendeskID;


    /**
     * Set zendeskID
     *
     * @param string $zendeskID
     * @return Ticket
     */
    public function setZendeskID($zendeskID)
    {
        $this->zendeskID = $zendeskID;

        return $this;
    }

    /**
     * Get zendeskID
     *
     * @return string 
     */
    public function getZendeskID()
    {
        return $this->zendeskID;
    }
    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue()
    {
        $this -> setCreatedAt(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
        $this -> setUpdatedAt(new \DateTime());
    }
    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Ticket
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Ticket
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
