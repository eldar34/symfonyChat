<?php

namespace App\Form;


use App\DTO\Message\CreateMessageDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextType::class, [
                'attr' => [
                    'class' => 'form-control border-0 bg-white',
                    'placeholder' => 'Напишите сообщение...',
                    'data-chat-form-target' => 'input',
                ],
                'constraints' => [
                    new NotBlank(message: 'Сообщение не может быть пустым'),
                ],
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreateMessageDTO::class,
            'csrf_protection' => true,
            'attr' => [
                'data-controller' => 'chat-form',
                'data-action' => 'turbo:submit-end->chat-form#clear',
                'class' => 'p-3 border-top d-flex'
            ]
        ]);
    }
}