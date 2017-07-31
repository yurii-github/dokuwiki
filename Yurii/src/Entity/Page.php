<?php

namespace Yurii\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="dw_page")
 **/
class Page
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     **/
    private $id;

    /**
     * @ORM\Column(name="page_id", type="string", unique=true)
     **/
    private $pageId;

    /**
     * @ORM\Column(type="string")
     **/
    private $lang;

    /**
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="pages", cascade={"all"})
     * @ORM\JoinColumn(name="base_id", referencedColumnName="id")
     */
    private $basePage;

    /**
     * @var Page[]
     * @ORM\OneToMany(targetEntity="Page", mappedBy="id")
     */
    private $pages;

    /**
     * @return Page[]
     */
    public function getPages() {
        return $this->pages;
    }



    /**
     * Get base page
     *
     * @return Page
     */
    public function getBasePage()
    {
        return $this->basePage;
    }

    /**
     * Get base page
     *
     * @return Page | null
     */
    public function setBasePage(Page $page)
    {
        $this->basePage = $page;
        return $this;
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
     * Set pageId
     *
     * @param string $pageId
     *
     * @return Page
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;

        return $this;
    }

    /**
     * Get pageId
     *
     * @return string
     */
    public function getPageId()
    {
        return $this->pageId;
    }


    /**
     * Set lang
     *
     * @param string $lang
     *
     * @return Page
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }
}

