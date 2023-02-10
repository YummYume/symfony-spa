<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:delete-unverified-users',
    description: 'Deletes unverified users who registered for a given time.',
    aliases: ['app:d:u:u']
)]
final class DeleteUnverifiedUsersCommand extends Command
{
    public function __construct(private readonly UserRepository $userRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command will delete unverified users (users who did not click the link sent to their email after registering). You can specify a deadline to only delete users created since a certain time.')
            ->addOption(
                'date',
                'd',
                InputOption::VALUE_REQUIRED,
                sprintf(
                    'The deadline date. Unverified users created before this date will be selected. Defaults to "now" (%s).',
                    (new \DateTime())->format('Y-m-d H:i:s')
                ),
                'now'
            )
            ->addOption(
                'interval',
                'i',
                InputOption::VALUE_REQUIRED,
                'An interval to add to the deadline date. See https://www.php.net/manual/en/dateinterval.createfromdatestring.php.',
                null
            )
            ->addOption(
                'dump',
                null,
                InputOption::VALUE_NONE,
                'Show a table with all the users found.'
            )
            ->addOption(
                'safe',
                's',
                InputOption::VALUE_NONE,
                'Do a safe run and do not delete any user, even if the -n (--no-interaction) option is specified.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $io = new SymfonyStyle($input, $output);

            $io->title('Delete unverified users');

            $date = new \DateTime();
            $interval = null;

            try {
                $date = new \DateTime($input->getOption('date'));
            } catch (\Exception $e) {
                $io->error(sprintf('Invalid date "%s" given.', $input->getOption('date')));

                return Command::INVALID;
            }

            if (null !== $input->getOption('interval')) {
                try {
                    $interval = \DateInterval::createFromDateString($input->getOption('interval'));
                } catch (\Exception $e) {
                    $io->error(sprintf('Invalid interval "%s" given.', $input->getOption('interval')));

                    return Command::INVALID;
                }

                $date = $date->add($interval);
            }

            $io->section(sprintf('Finding unverified users created before %s', $date->format('Y-m-d H:i:s')));

            $users = $this->userRepository->findUnverifiedSince($date);
            $userCount = \count($users);

            if ($input->getOption('dump') && $userCount > 0) {
                $io->table(
                    ['UUID (base 32)', 'UUID (base 58)', 'Email', 'Username', 'Created At'],
                    array_map(static fn (User $user): array => [
                        $user->getId()->toBase32(),
                        $user->getId()->toBase58(),
                        $user->getEmail(),
                        $user->getProfile()->getUsername(),
                        $user->getCreatedAt()->format('Y-m-d H:i:s'),
                    ], $users),
                );
            }

            $io->info(sprintf('Found %s user%s.', $userCount, $userCount > 1 ? 's' : ''));

            if ($userCount <= 0) {
                return Command::SUCCESS;
            }

            $confirmDeletion = $input->getOption('safe') ? false : $input->getOption('no-interaction');

            if (!$input->getOption('safe') && !$confirmDeletion) {
                $confirmDeletion = $io->confirm('Do you want to proceed to deletion?');
            }

            if ($confirmDeletion) {
                $io->section('Deleting users');

                $deleted = 0;

                foreach ($users as $user) {
                    try {
                        $this->userRepository->remove($user, true);
                    } catch (\Exception $e) {
                        $io->warning(sprintf('Could not delete user with email "%s" : %s', $user->getEmail(), $e->getMessage()));
                    }

                    ++$deleted;

                    $io->info(sprintf('Deleted user %s with email "%s".', $user->getProfile()?->getUsername(), $user->getEmail()));
                }

                $io->success(sprintf('Deleted %s user%s.', $deleted, $deleted > 1 ? 's' : ''));

                if ($userCount > $deleted) {
                    $undeleted = $userCount > $deleted;

                    $io->warning(sprintf('Could not delete %s user%s.', $undeleted, $undeleted > 1 ? 's' : ''));
                }
            } elseif ($input->getOption('safe')) {
                $io->info('The -s (--safe) option was specified. No deletion will happen.');
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error(sprintf('An error occurred while executing the command : %s', $e->getMessage()));

            return Command::FAILURE;
        }
    }
}
