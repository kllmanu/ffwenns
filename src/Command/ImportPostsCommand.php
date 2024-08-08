<?php

namespace App\Command;

use App\Client\Kirby;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\NewsModel;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'ffwenns:import:posts', description: 'Import posts')]
class ImportPostsCommand extends Command
{
    public function __construct(private readonly ContaoFramework $framework, private readonly Kirby $kirby)
    {
        $this->framework->initialize();

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $posts = $this->kirby->posts();

        foreach ($posts as $index => $post) {
            if (NewsModel::findByAlias($post['sid'])) {
                continue;
            }

            $output->writeln("Create post #" . $index + 1);

            $newPost = new NewsModel();
            $newPost->alias = $post['sid'];
            $newPost->pid = match ($post['category'] ?? '') {
                'Einsätze' => 2,
                'Übungen' => 3,
                'Tätigkeiten' => 4,
                default => 1
            };
            $newPost->author = 1;
            $newPost->headline = $post['title'];
            $newPost->date = strtotime($post['date']);
            $newPost->time = strtotime($post['date']);
            $newPost->teaser = $post['text'];

            if (strlen($post['sid']) > 4) {
                $newPost->canonicalLink = "https://facebook.com/ffwenns/posts/" . $post['sid'];
            }

            if ($post['images']) {
                $newPost->addImage = true;
                $newPost->singleSRC = $post['images'][0];
                $newPost->addEnclosure = true;
                $newPost->enclosure = $post['images'];
            }

            $newPost->published = 1;
            $newPost->tstamp = time();
            $newPost->save();

        }

        return Command::SUCCESS;
    }
}