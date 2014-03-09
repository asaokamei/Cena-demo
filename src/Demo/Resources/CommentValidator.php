<?php
namespace Demo\Resources;

use Cena\Cena\Validation\SimpleValidatorAbstract;

class CommentValidator extends SimpleValidatorAbstract
{
    public function validate()
    {
        $this->required( 'comment' );
    }
}