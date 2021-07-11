<?php
declare(strict_types = 1);

namespace App\Command;

use App\Entity\Genre;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class LoadGenresCommand
 *
 * php bin/console app:load:genres
 */
class LoadGenresCommand extends AbstractCommand
{
	/**
	 * @var string
	 */
	protected static $defaultName = 'app:load:genres';

	/**
	 * Set description.
	 */
	protected function configure()
	{
		$this->setDescription('This command loads music/radio genre list.');
	}

	/**
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 *
	 * @return integer|void
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);

		try {
			foreach ($this->getGenreListData() as $name) {
				$this->em->persist(
					(new Genre())->setName($name)
				);
			}

			$this->em->flush();
		} catch (\Exception $e) {
			$io->error($e->getMessage());

			return Command::FAILURE;
		}

		$io->success('Genre list loading is completed successfully');

		return Command::SUCCESS;
	}

	/**
	 * @return array|string[]
	 */
	private function getGenreListData(): array
	{
		return [
			"Blues",
			"Classic Rock",
			"Country",
			"Dance",
			"Disco",
			"Hip-Hop",
			"Jazz",
			"Metal",
			"Pop",
			"R&B",
			"Rap",
			"Reggae",
			"Rock",
			"Techno",
			"Jazz+Funk",
			"Classical",
			"Gospel",
			"Instrumental Pop",
			"Instrumental Rock",
			"Ethnic",
			"Gothic",
			"Techno-Industrial",
			"Electronic",
			"Pop-Folk",
			"Eurodance",
			"Comedy",
			"Rave",
			"Polka",
			"Retro",
			"Musical",
			"Rock & Roll",
			"Hard Rock",
			"Folk",
			"Folk-Rock",
			"Latin",
			"Humour",
			"Dance Hall",
		];

// full list of genres

//		return [
//			"Blues", "Classic Rock", "Country", "Dance", "Disco", "Funk", "Grunge",
//			"Hip-Hop", "Jazz", "Metal", "New Age", "Oldies", "Other", "Pop", "R&B",
//			"Rap", "Reggae", "Rock", "Techno", "Industrial", "Alternative", "Ska",
//			"Death Metal", "Pranks", "Soundtrack", "Euro-Techno", "Ambient",
//			"Trip-Hop", "Vocal", "Jazz+Funk", "Fusion", "Trance", "Classical",
//			"Instrumental", "Acid", "House", "Game", "Sound Clip", "Gospel",
//			"Noise", "AlternRock", "Bass", "Soul", "Punk", "Space", "Meditative",
//			"Instrumental Pop", "Instrumental Rock", "Ethnic", "Gothic",
//			"Darkwave", "Techno-Industrial", "Electronic", "Pop-Folk",
//			"Eurodance", "Dream", "Southern Rock", "Comedy", "Cult", "Gangsta",
//			"Top 40", "Christian Rap", "Pop/Funk", "Jungle", "Native American",
//			"Cabaret", "New Wave", "Psychadelic", "Rave", "Showtunes", "Trailer",
//			"Lo-Fi", "Tribal", "Acid Punk", "Acid Jazz", "Polka", "Retro",
//			"Musical", "Rock & Roll", "Hard Rock", "Folk", "Folk-Rock",
//			"National Folk", "Swing", "Fast Fusion", "Bebob", "Latin", "Revival",
//			"Celtic", "Bluegrass", "Avantgarde", "Gothic Rock", "Progressive Rock",
//			"Psychedelic Rock", "Symphonic Rock", "Slow Rock", "Big Band",
//			"Chorus", "Easy Listening", "Acoustic", "Humour", "Speech", "Chanson",
//			"Opera", "Chamber Music", "Sonata", "Symphony", "Booty Bass", "Primus",
//			"Porn Groove", "Satire", "Slow Jam", "Club", "Tango", "Samba",
//			"Folklore", "Ballad", "Power Ballad", "Rhythmic Soul", "Freestyle",
//			"Duet", "Punk Rock", "Drum Solo", "Acapella", "Euro-House", "Dance Hall",
//		];
	}
}
