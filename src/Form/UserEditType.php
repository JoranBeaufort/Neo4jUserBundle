<?php
namespace JoranBeaufort\Neo4jUserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('profileImageFile', FileType::class, array(
                'required' => false))
            ->add('email', EmailType::class, array(
                'required' => false))
            ->add('profileDescription', TextType::class, array(
                'required' => false))
            ->add('current_password', PasswordType::class, array(
                'required' => true,
                'label' => 'Password*',
                'mapped' => false,
                'constraints' => new UserPassword(),
                ))
            ->add('plainPassword', RepeatedType::class, array(
                'required' => false,
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'New Password'),
                'second_options' => array('label' => 'Confirm New Password'),
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JoranBeaufort\Neo4jUserBundle\Entity\User',
        ));
    }
}




