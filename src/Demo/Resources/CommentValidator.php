<?php
namespace Demo\Resources;

use Cena\Cena\Validation\ValidatorInterface;

class CommentValidator implements ValidatorInterface
{
    protected $entity;
    
    /**
     * set entity object.
     *
     * @param object $entity
     * @return mixed
     */
    public function setEntity( $entity )
    {
        $this->entity = $entity;
    }

    /**
     * validate the input.
     *
     * @param array $input
     * @return array
     */
    public function validate( $input )
    {
        return $input;
    }

    /**
     * verify that the entity is valid.
     *
     * @return bool
     */
    public function verify()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return true;
    }

    /**
     * get the errors as array.
     * returns array as
     *     [ 'key' => 'error message' ]
     *
     * @return array
     */
    public function getErrors()
    {
        return array();
    }
}