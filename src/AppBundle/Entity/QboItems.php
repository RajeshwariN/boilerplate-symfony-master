<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QboItems
 *
 * @ORM\Table(name="qbo_items")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QboItemsRepository")
 * @ORM\HasLifecycleCallbacks
 */
class QboItems
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="item_id", type="integer")
     */
    private $itemId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="full_qualified_name", type="string", length=255)
     */
    private $fullQualifiedName;

    /**
     * @var int
     *
     * @ORM\Column(name="income_account_ref", type="integer")
     */
    private $incomeAccountRef;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set itemId.
     *
     * @param int $itemId
     *
     * @return QboItems
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * Get itemId.
     *
     * @return int
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return QboItems
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return QboItems
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set fullQualifiedName.
     *
     * @param string $fullQualifiedName
     *
     * @return QboItems
     */
    public function setFullQualifiedName($fullQualifiedName)
    {
        $this->fullQualifiedName = $fullQualifiedName;

        return $this;
    }

    /**
     * Get fullQualifiedName.
     *
     * @return string
     */
    public function getFullQualifiedName()
    {
        return $this->fullQualifiedName;
    }

    /**
     * Set incomeAccountRef.
     *
     * @param int $incomeAccountRef
     *
     * @return QboItems
     */
    public function setIncomeAccountRef($incomeAccountRef)
    {
        $this->incomeAccountRef = $incomeAccountRef;

        return $this;
    }

    /**
     * Get incomeAccountRef.
     *
     * @return int
     */
    public function getIncomeAccountRef()
    {
        return $this->incomeAccountRef;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return QboItems
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return QboItems
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime("now");
        $this->updatedAt = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update

     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime("now");
    }
}
