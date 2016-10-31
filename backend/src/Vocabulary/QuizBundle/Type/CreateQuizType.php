<?php

declare(strict_types = 1);

namespace Vocabulary\QuizBundle\Type;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Vocabulary\CoreBundle\Type\BaseApiType;
use Vocabulary\QuizBundle\Entity\Quiz;

class CreateQuizType extends BaseApiType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', ChoiceType::class, [
            'choices' => [Quiz::TYPE_RU, Quiz::TYPE_EN]
        ]);
    }
}