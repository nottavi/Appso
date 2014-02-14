<?php
/**
 * Contact form for Sillonbol.com
 *
 * @author crevillo <crevillo@gmail.com>
 */

namespace AD\AppsoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class ContactType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add( 'name', 'text', array(
                'label' => 'Nom',
                'label_attr' => array(
                    'class' => 'infield'
                ),
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => false,
                    'pattern'     => '.{2,}' //minlength
                )
            ))
            ->add('email', 'email', array(
                'label' => 'Email',
                'label_attr' => array(
                    'class' => 'infield'
                ),
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => false
                )
            ))
            ->add('message', 'textarea', array(
               
                'label' => 'Votre message',
                'label_attr' => array(
                    'class' => 'infield'
                ),
                'attr' => array(
                    'class' => 'form-control',
                    'cols' => 90,
                    'rows' => 10,
                    'placeholder' => false,
                )
            ))
            ->add('Envoyer', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'name' => array(
                new NotBlank(array('message' => 'El nombre es obligatorio.')),
                new Length(array('min' => 2))
            ),
            'email' => array(
                new NotBlank(array('message' => 'El email no es correcto.')),
                new Email(array('message' => 'Email incorrecto.'))
            ),
            'message' => array(
                new NotBlank(array('message' => 'No admitimos mensajes en blanco.')),
                new Length(array('min' => 5))
            )
        ));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint
        ));
    }
    
    public function getName()
    {
        return 'contact';
    }

}
