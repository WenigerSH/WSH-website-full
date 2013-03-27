<?php

namespace Wsh\WebsiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Text page entity
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Weniger\GmsBundle\Entity\PageRepository")
 */
class Page extends PageAbstract
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isSystemic", type="boolean")
     */
    private $isSystemic;

        public function __construct()
    {
        parent::__construct();
        $this->isSystemic = false;
        $this->isPublished = true;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param boolean $isSystemic
     */
    public function setIsSystemic($isSystemic)
    {
        $this->isSystemic = $isSystemic;
    }

    /**
     * @return boolean
     */
    public function getIsSystemic()
    {
        return $this->isSystemic;
    }

    /**
     * @ORM\PreRemove
     */
    public function preRemove()
    {
        if ($this->getIsSystemic() == true) {
            throw new \Exception('This page is systemic and can\'t be removed');
        }
    }

	public function __toString()
	{
		return $this->getTitle();
	}

}
