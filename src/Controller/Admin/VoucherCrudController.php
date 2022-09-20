<?php

namespace App\Controller\Admin;

use App\Entity\Voucher;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use App\Entity\Choice;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

class VoucherCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Voucher::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // yield IdField::new('id')->onlyOnIndex();
        yield AssociationField::new('org')->onlyOnIndex();
        yield AssociationField::new('org')
            ->onlyOnForms()
            ->setFormTypeOptions(['required' => 'required'])
            ->setQueryBuilder (
                fn (QueryBuilder $qb) => $qb
                    ->andWhere('entity.type <= 3')
                    ->andWhere('entity.type != 0')
            );
        yield AssociationField::new('consumer')->onlyOnIndex();
        yield MoneyField::new('voucher')->setCurrency('CNY');
        yield ChoiceField::new('type')->setChoices(Choice::VOUCHER_TYPES)->HideWhenCreating();
        yield ChoiceField::new('type')
            ->setChoices(['内部调控' => 30])
            ->onlyWhenCreating()
            ->setHelp('总公司<b>灵活发放</b>代金券时，类型为<b>内部调控</b>');
        yield TextField::new('note');
        yield DateTimeField::new('date')->HideOnForm();
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->isGranted('ROLE_HEAD')) {
            return $actions
                ->disable(Action::DELETE, Action::EDIT)
            ;
        } else {
            return $actions
                ->disable(Action::DELETE, Action::EDIT, Action::NEW)
            ;
        }
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $userOrg = $this->getUser()->getOrg()->getId();
        $response = $this->container->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if (!$this->isGranted('ROLE_HEAD')) {
            $response->andWhere("entity.org = $userOrg");
        }
        return $response;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $helpIndex = '总公司可以通过<b>新增代金券</b>为下级机构<b>灵活发放</b>代金券<br/>如需修改顾客代金券，请在<b>顾客</b>管理中编辑';
        $helpNew = '总公司<b>灵活发放</b>代金券时，类型为<b>内部调控</b><br/>如需修改顾客代金券，请在<b>顾客</b>管理中编辑';
        return $crud
            ->setDefaultSort(['id' => 'DESC'])
            ->setHelp('index', $helpIndex)
            ->setHelp('new', $helpNew)
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('date')
        ;
    }
}
