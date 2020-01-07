<?php

namespace App\Form;

use App\Entity\User;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'Емаил, используется для входа'])
            ->add('firstName', null, ['label' => 'Имя'])
            ->add('lastName', null, ['label' => 'Фамилия'])
            ->add('birthday', BirthdayType::class, [
                'required' => false,
                'empty_data' => '',
                'label' => 'Дата рождения'
            ])
            ->add('city', null, ['label' => 'Город'])
            ->add('interests', null, ['label' => 'Интересы'])
            ->add('sex', ChoiceType::class, [
                'choices' => [
                    'мужчинко' => 'мужчинко',
                    'женщинко' => 'женщинко',
                    'Боевой вертолёт АПАЧ-8' => 'Боевой вертолёт АПАЧ-8',
                    'Сказочное существо' => 'Сказочное существо',
                ],
                'label' => 'Гендерная принадлежность или как там это называется'
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Пароль',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
