<?php
namespace Demo\Resources;

use Cena\Cena\Validation\SimpleValidatorAbstract;

class TagValidator extends SimpleValidatorAbstract
{
    public function validate()
    {
        $this->required( 'tag' );
    }

    public function verify()
    {
        $this->useAsInput( $this->entity );
        $this->validate();
    }
}