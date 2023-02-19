<?php

namespace App\Controller\Admin;

use App\Entity\Box;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class BoxCrudController extends AbstractCrudController
{
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }
    
    public static function getEntityFqcn(): string
    {
        return Box::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->hideOnForm()
        ;
        yield IntegerField::new('sn', 'Box')
            // ->onlyWhenCreating()
        ;
        yield TextField::new('cipher')
            ->setMaxLength(25)
            ->hideWhenCreating()
            ;
        yield AssociationField::new('org')
            ;
        yield AssociationField::new('product')
            ->onlyOnIndex()
        ;
        yield ArrayField::new('batch.batchPrizes', 'Box Prizes')
            ;
    }
    
    public function configureActions(Actions $actions): Actions
    {
        $batchNew = Action::new('batchNew')
            ->createAsGlobalAction()
            ->linkToUrl(function (){
                return $this->adminUrlGenerator
                    ->setController(BatchCrudController::class)
                    ->setDashboard(DashboardController::class)
                    ->setAction('new')
                    // ->set('menuIndex', 1)
                    ->set('type', 0)
                    ->generateUrl();
            });
        $batchEdit = Action::new('batchEdit')
            ->createAsGlobalAction()
            ->linkToUrl(function (){
                return $this->adminUrlGenerator
                    ->setController(BatchCrudController::class)
                    ->setDashboard(DashboardController::class)
                    ->setAction('new')
                    // ->set('menuIndex', 1)
                    ->set('type', 1)
                    ->generateUrl();
            });
        $batchQr = Action::new('batchQr')
            ->createAsGlobalAction()
            ->linkToUrl(function (){
                return $this->adminUrlGenerator
                    ->setController(BatchCrudController::class)
                    ->setDashboard(DashboardController::class)
                    ->setAction('new')
                    // ->set('menuIndex', 1)
                    ->set('type', 2)
                    ->generateUrl();
            });

        $listBottles = Action::new('listBottles')
            ->linkToUrl(function (Box $entity){
                return $this->adminUrlGenerator
                    ->setController(BottleCrudController::class)
                    // ->setDashboard(DashboardController::class)
                    ->setAction('index')
                    // ->set('menuIndex', 1)
                    ->set('box', $entity->getId())
                    ->generateUrl();
            });

        return $actions
            ->add('index', $batchEdit)
            ->add('index', $batchNew)
            ->add('index', $batchQr)
            ->add('index', $listBottles)
            ->disable(Action::DELETE, Action::EDIT, Action::NEW, Action::DETAIL)
        ;
    }
}
