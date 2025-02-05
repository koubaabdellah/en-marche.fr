<?php

namespace App\Admin\ExecutiveOfficeMember;

use App\Form\PurifiedTextareaType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

abstract class AbstractExecutiveOfficeMemberAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_page' => 1,
        '_per_page' => 32,
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt',
    ];

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Général', ['class' => 'col-md-6'])
                ->add('firstName', TextType::class, [
                    'label' => 'Prénom',
                ])
                ->add('lastName', TextType::class, [
                    'label' => 'Nom',
                ])
                ->add('job', TextType::class, [
                    'label' => 'Poste',
                ])
        ;

        if ($this instanceof RenaissanceExecutiveOfficeMemberAdmin) {
            $formMapper
                ->add('president', CheckboxType::class, [
                    'label' => 'Président',
                    'required' => false,
                ])
            ;
        }

        $formMapper
                ->add('executiveOfficer', CheckboxType::class, [
                    'label' => 'Délégué général',
                    'required' => false,
                ])
                ->add('deputyGeneralDelegate', CheckboxType::class, [
                    'label' => 'Délégué général adjoint',
                    'required' => false,
                ])
                ->add('description', TextType::class, [
                    'label' => 'Description',
                    'required' => false,
                    'help' => 'La description de la biographie sera présente dans la liste des membres. (255 caractères maximum).',
                ])
                ->add('content', PurifiedTextareaType::class, [
                    'label' => 'Contenu',
                    'required' => false,
                    'attr' => ['class' => 'ck-editor'],
                    'purify_html_profile' => 'enrich_content',
                    'help' => 'Le contenu de la biographie sera présent dans la fiche du membre.',
                ])
                ->add('published', null, [
                    'label' => 'Publié',
                    'required' => false,
                ])
            ->end()
            ->with('Photo', ['class' => 'col-md-6'])
                ->add('image', FileType::class, [
                    'required' => false,
                    'label' => 'Ajoutez une photo',
                    'help' => 'La photo ne doit pas dépasser 1 Mo et ne doit pas faire plus de 1024x1024px.',
                ])
            ->end()
            ->with('Réseaux sociaux', ['class' => 'col-md-6'])
                ->add('facebookProfile', TextType::class, [
                    'label' => 'Facebook',
                    'required' => false,
                ])
                ->add('twitterProfile', TextType::class, [
                    'label' => 'Twitter',
                    'required' => false,
                ])
                ->add('instagramProfile', TextType::class, [
                    'label' => 'Instagram',
                    'required' => false,
                ])
                ->add('linkedInProfile', TextType::class, [
                    'label' => 'LinkedIn',
                    'required' => false,
                ])
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('lastName', null, [
                'label' => 'Nom',
            ])
            ->add('firstName', null, [
                'label' => 'Prénom',
            ])
            ->add('job', null, [
                'label' => 'Poste',
            ])
            ->add('published', null, [
                'label' => 'Publié',
            ])
            ->add('createdAt', null, [
                'label' => 'Date de création',
            ])
            ->add('updatedAt', null, [
                'label' => 'Date de dernière mise à jour',
            ])
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('firstName', null, [
                'label' => 'Prénom',
            ])
            ->addIdentifier('lastName', null, [
                'label' => 'Nom',
            ])
            ->add('job', null, [
                'label' => 'Poste',
            ])
            ->add('_image', 'thumbnail', [
                'label' => 'Photo',
                'virtual_field' => true,
            ])
        ;

        if ($this instanceof RenaissanceExecutiveOfficeMemberAdmin) {
            $listMapper
                ->add('president', null, [
                    'label' => 'Président',
                    'editable' => true,
                ])
            ;
        }

        $listMapper
            ->add('executiveOfficer', null, [
                'label' => 'Délégué général',
                'editable' => true,
            ])
            ->add('deputyGeneralDelegate', null, [
                'label' => 'Délégué général adjoint',
                'editable' => true,
            ])
            ->add('published', null, [
                'label' => 'Publié',
                'editable' => true,
            ])
            ->add('createdAt', null, [
                'label' => 'Date de création',
            ])
            ->add('updatedAt', null, [
                'label' => 'Dernière mise à jour',
            ])
            ->add('_action', null, [
                'virtual_field' => true,
                'actions' => [
                    'link' => [
                        'template' => 'admin/biography/executive_office_member/link.html.twig',
                    ],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery();

        $query
            ->andWhere(sprintf('%s.forRenaissance = :forRenaissance', $query->getRootAliases()[0]))
            ->setParameter('forRenaissance', $this->isForRenaissance())
        ;

        return $query;
    }

    protected function isForRenaissance(): bool
    {
        return false;
    }
}
