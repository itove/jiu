<?php

namespace App\Controller\Admin;

use App\Entity\RetailReturn;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class RetailReturnCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RetailReturn::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            AssociationField::new('store')->HideWhenCreating(),
            AssociationField::new('store')->onlyWhenCreating()->setQueryBuilder (
                fn (QueryBuilder $qb) => $qb->andWhere('entity.id = :id')->setParameter('id', $this->getUser()->getOrg())
            ),
            AssociationField::new('consumer'),
            AssociationField::new('product')->HideWhenCreating(),
            AssociationField::new('product')->onlyWhenCreating()->setQueryBuilder (
                fn (QueryBuilder $qb) => $qb->andWhere('entity.org = :org')->setParameter('org', $this->getUser()->getOrg())
            ),
            IntegerField::new('quantity'),
            MoneyField::new('amount')->setCurrency('CNY')->onlyOnIndex(),
            MoneyField::new('voucher')->setCurrency('CNY')->onlyOnIndex(),
            DateTimeField::new('date')->HideOnForm(),
        ];
    }
}