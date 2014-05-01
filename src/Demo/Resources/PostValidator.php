<?php
namespace Demo\Resources;

use Cena\Cena\Validation\SimpleValidatorAbstract;

class PostValidator extends SimpleValidatorAbstract
{
    public function validate()
    {
        $this->required( 'title' );
        $this->required( 'status' );
        $this->required( 'publishAt' );
        $this->required( 'content' );
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