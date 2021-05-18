<?php

namespace App\Command;

use App\Entity\Post;
use App\Entity\UserPypPost;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PostGarbageCollector extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->ignoreValidationErrors();

        $this->setName('post:garbage-collector')
            ->setDescription('Start post garbage collection.')
            ->setHelp('Start post garbage collection, removing all posts older than 3 hours.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting post garbage collection...');

        $qb = $this->em->getRepository(Post::class)->createQueryBuilder('p');

        $posts = $qb->select('p')
            ->where(
                $qb->expr()->lt('p.datetime_posted', ':datetime')
            )
            ->setParameter(':datetime', (new DateTime())->sub(new DateInterval('PT3H'))->format('Y-m-d H:i:s'))
            ->getQuery()
            ->execute();

        /** @var Post $post */
        foreach ($posts as $post) {
            $userPypPosts = $this->em->getRepository(UserPypPost::class)->findBy(['post' => $post]);

            foreach ($userPypPosts as $userPypPost) {
                $this->em->remove($userPypPost);
            }

            $this->em->remove($post);
        }

        $this->em->flush();

        $output->writeln('Done!');

        return Command::SUCCESS;
    }
}
