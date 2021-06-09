<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QboEmployees
 *
 * @ORM\Table(name="qbo_employees")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QboEmployeesRepository")
 * @ORM\HasLifecycleCallbacks
 */
class QboEmployees
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
     * @ORM\Column(name="emp_id", type="integer")
     */
    private $empId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="employee_type", type="string", length=255, nullable=true)
     */
    private $employeeType;

    /**
     * @var string|null
     *
     * @ORM\Column(name="employee_number", type="string", length=255, nullable=true)
     */
    private $employeeNumber;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="hired_date", type="date", nullable=true)
     */
    private $hiredDate;

    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=255)
     */
    private $displayName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    private $phone;

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
     * Set empId.
     *
     * @param int $empId
     *
     * @return QboEmployees
     */
    public function setEmpId($empId)
    {
        $this->empId = $empId;

        return $this;
    }

    /**
     * Get empId.
     *
     * @return int
     */
    public function getEmpId()
    {
        return $this->empId;
    }

    /**
     * Set employeeType.
     *
     * @param string|null $employeeType
     *
     * @return QboEmployees
     */
    public function setEmployeeType($employeeType = null)
    {
        $this->employeeType = $employeeType;

        return $this;
    }

    /**
     * Get employeeType.
     *
     * @return string|null
     */
    public function getEmployeeType()
    {
        return $this->employeeType;
    }

    /**
     * Set employeeNumber.
     *
     * @param string|null $employeeNumber
     *
     * @return QboEmployees
     */
    public function setEmployeeNumber($employeeNumber = null)
    {
        $this->employeeNumber = $employeeNumber;

        return $this;
    }

    /**
     * Get employeeNumber.
     *
     * @return string|null
     */
    public function getEmployeeNumber()
    {
        return $this->employeeNumber;
    }

    /**
     * Set hiredDate.
     *
     * @param \DateTime|null $hiredDate
     *
     * @return QboEmployees
     */
    public function setHiredDate($hiredDate = null)
    {
        $this->hiredDate = $hiredDate;

        return $this;
    }

    /**
     * Get hiredDate.
     *
     * @return \DateTime|null
     */
    public function getHiredDate()
    {
        return $this->hiredDate;
    }

    /**
     * Set displayName.
     *
     * @param string $displayName
     *
     * @return QboEmployees
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set phone.
     *
     * @param string|null $phone
     *
     * @return QboEmployees
     */
    public function setPhone($phone = null)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.
     *
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
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
