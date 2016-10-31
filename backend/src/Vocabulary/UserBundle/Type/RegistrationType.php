<?php

declare(strict_types = 1);

namespace Vocabulary\UserBundle\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Vocabulary\CoreBundle\Type\BaseApiType;

class RegistrationType extends BaseApiType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class);
    }
}