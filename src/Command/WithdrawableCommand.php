<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Conf;
use App\Entity\Reward;
use App\Entity\Share;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'app:withdrawable',
    description: 'Settle reward/share to withdrawable',
)]
class WithdrawableCommand extends Command
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        $conf = $this->em->getRepository(Conf::class)->find(1);
        $date1 = (new \DateTime('now', new \DateTimeZone('Asia/Shanghai')))
            ->sub(new \DateInterval('P'. $conf->getReturnDays() .'D'));

        $rewards = $this->em->getRepository(Reward::class)->findBeforeDate($date1);
        $shares = $this->em->getRepository(Share::class)->findBeforeDate($date1);
        
        foreach ($rewards as $r) {
            $r->setStatus(1);
        }

        foreach ($shares as $s) {
            $s->setStatus(1);
        }

        $rewardsOfReturn = $this->em->getRepository(Reward::class)->findBy(['status' => 3 ]);
        $sharesOfReturn = $this->em->getRepository(Share::class)->findBy(['status' => 3]);

        foreach ($rewardsOfReturn as $r) {
            $amount = $r->getAmount();
            $referrer = $r->getReferrer();
            $referrer->setWithdrawable($referrer->getWithdrawable() + $amount);
            $r->setStatus(4);
        }

        foreach ($sharesOfReturn as $s) {
            $amount = $s->getAmount();
            $org = $s->getOrg();
            $org->setWithdrawable($org->getWithdrawable() + $amount);
            $s->setStatus(4);
        }

        $this->em->flush();

        $io->note(sprintf('%s rewards, %s shares', count($rewards), count($shares)));

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        // $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
