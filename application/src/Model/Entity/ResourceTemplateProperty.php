<?php
namespace Omeka\Model\Entity;

use Omeka\Model\Entity\Property;
use Omeka\Model\Entity\ResourceTemplate;

/**
 * @Entity
 * @Table(
 *     uniqueConstraints={
 *         @UniqueConstraint(
 *             columns={"resource_template_id", "property_id"}
 *         )
 *     }
 * )
 */
class ResourceTemplateProperty extends AbstractEntity
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="ResourceTemplate", inversedBy="resourceTemplateProperties")
     * @JoinColumn(nullable=false)
     */
    protected $resourceTemplate;

    /**
     * @ManyToOne(targetEntity="Property")
     * @JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $property;

    /**
     * @Column(nullable=true)
     */
    protected $alternateLabel;

    /**
     * @Column(type="text", nullable=true)
     */
    protected $alternateComment;

    /**
     * @Column(type="integer", nullable=true)
     */
    protected $position;

    public function getId()
    {
        return $this->id;
    }

    public function setResourceTemplate(ResourceTemplate $resourceTemplate = null)
    {
        $this->synchronizeOneToMany($resourceTemplate, 'resourceTemplate',
            'getResourceTemplateProperties');
    }

    public function getResourceTemplate()
    {
        return $this->resourceTemplate;
    }

    public function setProperty(Property $property)
    {
        $this->property = $property;
    }

    public function getProperty()
    {
        return $this->property;
    }

    public function setAlternateLabel($alternateLabel)
    {
        $this->alternateLabel = $alternateLabel;
    }

    public function getAlternateLabel()
    {
        return $this->alternateLabel;
    }

    public function setAlternateComment($alternateComment)
    {
        $this->alternateComment = $alternateComment;
    }

    public function getAlternateComment()
    {
        return $this->alternateComment;
    }

    public function getPosition()
    {
        return $this->sortOrder;
    }

    public function setPosition($position)
    {
        $this->position = (int) $position;
    }
}