<?php

namespace Malwarebytes\ZendeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 */
class Comment
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
     * @var \Malwarebytes\ZendeskBundle\Entity\Ticket
     */
    private $ticket;


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
     * @return Comment
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
     * Set ticket
     *
     * @param \Malwarebytes\ZendeskBundle\Entity\Ticket $ticket
     * @return Comment
     */
    public function setTicket(\Malwarebytes\ZendeskBundle\Entity\Ticket $ticket = null)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * Get ticket
     *
     * @return \Malwarebytes\ZendeskBundle\Entity\Ticket 
     */
    public function getTicket()
    {
        return $this->ticket;
    }
    /**
     * @var string
     */
    private $zendeskID;


    /**
     * Set zendeskID
     *
     * @param string $zendeskID
     * @return Comment
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
     * @var string
     */
    private $body;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \CM\UserBundle\Entity\User
     */
    private $author;


    /**
     * Set body
     *
     * @param string $body
     * @return Comment
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Comment
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
     * @return Comment
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

    /**
     * Set author
     *
     * @param \CM\UserBundle\Entity\User $author
     * @return Comment
     */
    public function setAuthor(\CM\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \CM\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
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
}
