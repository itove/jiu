<?php

namespace App\Controller\Admin;

use App\Entity\Batch;
use App\Entity\BatchPrize;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class BatchCrudController extends AbstractCrudController
{
    private RequestStack $requestStack;
    private $type;
    
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $request = $this->requestStack->getCurrentRequest();
        $this->type = $request->query->get('type');
    }
    
    public static function getEntityFqcn(): string
    {
        return Batch::class;
    }

    public function configureFields(string $pageName): iterable
    {
        if (! is_null($this->type) && $this->type == 1) {
            yield TextField::new('snStart')
                ->setRequired(true)
                ;
            yield IntegerField::new('qty')
                ->setRequired(false)
            ;
            yield TextField::new('snEnd')
                ;
        }
        
        yield IdField::new('id')
            ->hideOnForm()
        ;
        if (! is_null($this->type) && $this->type == 0) {
            yield IntegerField::new('qty')
                ->onlyWhenCreating()
                ;
        }
        yield TextField::new('snStart')
            ->hideWhenCreating()
        ;
        yield TextField::new('snEnd')
            ->hideWhenCreating()
        ;
        yield IntegerField::new('bottleQty');
        yield CollectionField::new('batchPrizes')
            ->hideOnIndex()
            // ->allowAdd(false)
            // ->allowDelete(false)
            ->renderExpanded()
            ->setRequired(true)
            ->useEntryCrudForm()
        ;
        yield ArrayField::new('batchPrizes')
            ->onlyOnIndex()
        ;
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        // if (! is_null($this->type) && $this->type == 1) {
        //     return $crud
        //         ->setPageTitle('edit', 'Batch New')
        //     ;
        // } else {
        //     return $crud
        //         ->setPageTitle('edit', 'Batch Edit')
        //     ;
        // }
        return $crud
            ->setPageTitle('new', fn () => $this->type == 1 ? 'Batch Edit' : 'Batch New');
        
    }

    public function createEntity(string $entityFqcn)
    {
        $batch = new Batch();
        $item = new BatchPrize();
        $batch->addBatchPrize($item);
        
        return $batch;
    }
}
