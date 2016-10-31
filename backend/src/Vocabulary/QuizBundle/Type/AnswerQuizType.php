<?php

declare(strict_types = 1);

namespace Vocabulary\QuizBundle\Type;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Vocabulary\CoreBundle\Type\BaseApiType;

class AnswerQuizType extends BaseApiType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('answer_id', IntegerType::class);
    }
}