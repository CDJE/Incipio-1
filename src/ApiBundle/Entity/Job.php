<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dunglas\ApiBundle\Annotation\Iri;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Job.
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity("title")
 *
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class Job
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
     * @var string
     *
     * @Iri("http://schema.org/roleName")
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\Type("string")
     * @Assert\Length(
     *  min = 2,
     *  max = 100
     * )
     */
    private $title;

    /**
     * @var string Job title abbreviation.
     *
     * @Iri("https://schema.org/alternateName")
     * @ORM\Column(name="abbreviation", type="string", length=255, nullable=true)
     * @Assert\Type("string")
     * @Assert\Length(max = 20)
     */
    private $abbreviation;

    /**
     * @var bool If false the job cannot be used anymore.
     *
     * @ORM\Column(name="enabled", type="boolean")
     * @Assert\Type("bool")
     * @Assert\NotNull
     */
    private $enabled = true;

    /**
     * Get id.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set Abbreviation.
     *
     * @param string|null $abbreviation
     *
     * @return $this
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get Abbreviation.
     *
     * @return string|null
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * Set enabled.
     *
     * @param bool $enabled
     *
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled.
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
}