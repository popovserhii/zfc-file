<?php
namespace Popov\ZfcFile\Model;

use Doctrine\ORM\Mapping as ORM;
use Popov\ZfcCore\Model\DomainAwareTrait;
use Popov\ZfcEntity\Model\Entity;
use Popov\ZfcUser\Model\User;

/**
 * @ORM\Entity(repositoryClass="Popov\ZfcFile\Model\Repository\FileRepository")
 * @ORM\Table(name="file")
 */
class File
{
    use DomainAwareTrait;

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * File belongs to Item (ID)
     *
     * @var integer
     * @ORM\Column(name="itemId", type="integer", nullable=false)
     */
    private $itemId;

    /**
     * @var \DateTime
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * Registered system entity
     *
     * @var Entity
     * @ORM\ManyToOne(targetEntity="Popov\ZfcEntity\Model\Entity", cascade={"persist"})
     * @ORM\JoinColumn(name="entityId", referencedColumnName="id")
     */
    protected $entity;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Popov\ZfcUser\Model\User", cascade={"persist"})
     * @ORM\JoinColumn(name="createdBy", referencedColumnName="id")
     */
    protected $createdBy;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return File
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return File
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param int $itemId
     * @return File
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return File
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     * @return File
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     * @return File
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}
