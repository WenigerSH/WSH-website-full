<?php

namespace Wsh\WebsiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Nekland\Bundle\FeedBundle\Item\ItemInterface;

/**
 * Blog post entity
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wsh\WebsiteBundle\Entity\PageRepository")
 */
class BlogPost extends PageAbstract implements ItemInterface
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
     * @var string
     *
     * @ORM\Column(name="leadText", type="text")
     */
    private $leadText;

    /**
     * @var \stdClass
     *
	 * @Assert\File(maxSize="6000000")
     */
    private $leadPhoto;

	/**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isFeatured", type="boolean")
     */
    private $isFeatured;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="category", type="string", nullable=true)
     */
    private $category;

    /**
     * @ORM\Column(type="string")
     *
     * @var string author name
     */
    private $author;

    /**
     * @var string optional quote string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $quote;

    public function __construct()
    {
        parent::__construct();
        $this->isFeatured = false;
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
     * Set title
     *
     * @param string $title
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set leadText
     *
     * @param string $leadText
     * @return Page
     */
    public function setLeadText($leadText)
    {
        $this->leadText = $leadText;
    
        return $this;
    }

    /**
     * Get leadText
     *
     * @return string 
     */
    public function getLeadText()
    {
        return $this->leadText;
    }

    /**
     * Set leadPhoto
     *
     * @param \stdClass $leadPhoto
     * @return Page
     */
    public function setLeadPhoto($leadPhoto)
    {
        $this->leadPhoto = $leadPhoto;
    
        return $this;
    }

    /**
     * Get leadPhoto
     *
     * @return \stdClass 
     */
    public function getLeadPhoto()
    {
        return $this->leadPhoto;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Page
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
     * Set isSystemic
     *
     * @param boolean $isSystemic
     * @return Page
     */
    public function setIsSystemic($isSystemic)
    {
        $this->isSystemic = $isSystemic;
    
        return $this;
    }

    /**
     * Get isSystemic
     *
     * @return boolean 
     */
    public function getIsSystemic()
    {
        return $this->isSystemic;
    }

    /**
     * Set isPublished
     *
     * @param boolean $isPublished
     * @return Page
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;
    
        return $this;
    }

    /**
     * Get isPublished
     *
     * @return boolean 
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * Set isFeatured
     *
     * @param boolean $isFeatured
     * @return Page
     */
    public function setIsFeatured($isFeatured)
    {
        $this->isFeatured = $isFeatured;
    
        return $this;
    }

    /**
     * Get isFeatured
     *
     * @return boolean 
     */
    public function getIsFeatured()
    {
        return $this->isFeatured;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Page
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
     * Set slug
     *
     * @param string $slug
     * @return Page
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Page
     */
    public function setType($type)
    {

        if (!array_key_exists($type, self::$pageTypes)) {
            throw new \Exception('Undefined page type, given "'.$type.'" in '.__FUNCTION__);
        }
        $this->type = $type;
    
        return $this;
    }


    /**
     * Set category
     *
     * @param \stdClass $category
     * @return Page
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \stdClass 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set $metaTitle
     *
     * @param string $metaTitle
     * @return Page
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;
    
        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string 
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     * @return Page
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    
        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string 
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     * @return Page
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
    
        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string 
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

	public function __toString()
	{
        $title = $this->getTitle();
		return empty($title) ? '' : $title;
	}

    /**
     * @param string $quote
     */
    public function setQuote($quote)
    {
        $this->quote = $quote;
    }

    /**
     * @return string
     */
    public function getQuote()
    {
        return $this->quote;
    }


	/**
	 * Upload methods 
	 */
	public function getAbsolutePath()
	{
	    return null === $this->path
	        ? null
	        : $this->getUploadRootDir().'/'.$this->path;
	}

	public function getWebPath()
	{
	    return null === $this->path
	        ? null
	        : $this->getUploadDir().'/'.$this->path;
	}

	protected function getUploadRootDir()
	{
	    // the absolute directory path where uploaded
	    // documents should be saved
	    return __DIR__.'/../../../../web/'.$this->getUploadDir();
	}

	public function getUploadDir()
	{
	    // get rid of the __DIR__ so it doesn't screw up
	    // when displaying uploaded doc/image in the view.
	    return 'uploads/blog_posts';
	}
	
	/**
	 * Uploads leadPhoto
	 */
	public function upload()
	{
	    // the file property can be empty if the field is not required
	    if (null === $this->leadPhoto) {
	        return;
	    }

	    // use the original file name here but you should
	    // sanitize it at least to avoid any security issues

	    // move takes the target directory and then the
	    // target filename to move to
	    $this->leadPhoto->move(
	        $this->getUploadRootDir(),
	        $this->leadPhoto->getClientOriginalName()
	    );

	    // set the path property to the filename where you've saved the file
	    $this->path = $this->leadPhoto->getClientOriginalName();

	    // clean up the file property as you won't need it anymore
	    $this->leadPhoto = null;
	}
	
	public function getPath()
	{
		return $this->path;
	}

    public function setPath($path)
    {
        $this->path = $path;
    }

    /*
     * Return the title of your rss, something like "My blog rss"
     * @return string
     */
    public function getFeedTitle()
    {
        return $this->getTitle();
    }

    /*
     * Return the description of your rss, something like "This is the rss of my blog about foo and bar"
     * @return string
     */
    public function getFeedDescription()
    {
        return $this->getLeadText();
    }

    /*
     * Return the route of your item
     * @return array with
     * [0]
     *      =>
     *      	['route']
     *      			=>
     *          			[0] =>  'route_name'
     *          			[1] =>  array of params of the route
     *     	=>
     *      	['other parameter'] => 'content' (you can use for atom)
     * [1]
     *     	=>
     *     		['url'] => 'http://mywebsite.com'
     *     	=>
     *      	['other parameter'] => 'content' (you can use for atom)
     */
    public function getFeedRoutes()
    {
        return array(
            0 => array(
                'route' => ['blog', array('slug' => $this->getSlug())]
            )
        );
    }

    /**
     * @return unique identifier (for editing)
     */
    public function getFeedId()
    {
        return $this->getId();
    }

    /**
     * @abstract
     * @return \DateTime
     */
    public function getFeedDate()
    {
        return $this->getCreatedAt();
    }

}
