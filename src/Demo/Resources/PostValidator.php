<?php
namespace Demo\Resources;

use Cena\Cena\Validation\SimpleValidatorAbstract;

class PostValidator extends SimpleValidatorAbstract
{
    public function validate()
    {
        $this->required( 'title' );
        $this->required( 'publishAt' );
        $this->required( 'content' );
    }
}