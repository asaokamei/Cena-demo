<?php
namespace Demo\Resources;

use Cena\Cena\Validation\SimpleValidatorAbstract;

class CommentValidator extends SimpleValidatorAbstract
{
    public function validate()
    {
        $this->required( 'comment' );
    }

    /**
     * verify that the entity is valid.
     *
     * @return void
     */
    public function verify()
    {
        $this->useAsInput( $this->entity );
        $this->validate();
    }
}