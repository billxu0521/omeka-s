<?php
namespace Omeka\Api\Representation;

class UserRepresentation extends AbstractEntityRepresentation
{
    /**
     * {@inheritDoc}
     */
    public function getControllerName()
    {
        return 'user';
    }

    public function getJsonLd()
    {
        $entity = $this->getData();
        return array(
            'o:name' => $entity->getName(),
            'o:email' => $entity->getEmail(),
            'o:created' => $this->getDateTime($entity->getCreated()),
            'o:role' => $entity->getRole(),
            'o:is_active' => $entity->isActive(),
        );
    }

    public function name()
    {
        return $this->getData()->getName();
    }

    public function email()
    {
        return $this->getData()->getEmail();
    }

    public function role()
    {
        return $this->getData()->getRole();
    }

    public function created()
    {
        return $this->getData()->getCreated();
    }

    public function getEntity()
    {
        return $this->getData();
    }

    public function displayRole()
    {   
        $roleIndex = $this->getData()->getRole();
        $roleLabels = $this->getServiceLocator()->get('Omeka\Acl')->getRoleLabels();
        if (isset($roleLabels[$roleIndex])) {
            return $roleLabels[$roleIndex];
        }
        return $roleIndex;
    }

    /**
     * Get the item count for this user.
     *
     * @return int
     */
    public function itemCount()
    {
        $response = $this->getServiceLocator()->get('Omeka\ApiManager')
            ->search('items', array(
                'owner_id' => $this->id(),
                'limit' => 0,
            ));
        return $response->getTotalResults();
    }

        /**
     * Get the item set count for this user.
     *
     * @return int
     */
    public function itemSetCount()
    {
        $response = $this->getServiceLocator()->get('Omeka\ApiManager')
            ->search('item_sets', array(
                'owner_id' => $this->id(),
                'limit' => 0,
            ));
        return $response->getTotalResults();
    }
}
