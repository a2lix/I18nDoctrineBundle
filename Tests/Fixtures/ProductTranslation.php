<?php

namespace A2lix\I18nDoctrineBundle\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class ProductTranslation implements \A2lix\I18nDoctrineBundle\Doctrine\Interfaces\OneLocaleInterface
{
    use \A2lix\I18nDoctrineBundle\Doctrine\ORM\Util\Translation;

    /**
     * @ORM\Column(name="title", nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\Length(min="3")
     */
    private $description;

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }
}