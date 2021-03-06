<?php

namespace Component\Engine\Storage;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Criteria;
use Component\Entity\Achievement\ActionDefinition;
use Component\Entity\Achievement\ActionDefinitionInterface;
use Component\Entity\Achievement\ActionDefinitionCollection;
use Component\Entity\Achievement\AchievementDefinition;
use Component\Entity\Achievement\AchievementDefinitionCollection;
use Component\Entity\Achievement\AchievementDefinitionInterface;
use Component\Entity\Achievement\PersonalAchievementInterface;
use Component\Entity\Achievement\PersonalActionInterface;
use Component\Entity\Achievement\LevelCollection;
use Component\Entity\Achievement\LevelInterface;
use Component\Entity\Achievement\Level;
use Component\Entity\Player;
use Component\Entity\PlayerCollection;
use Component\Entity\PlayerInterface;
use Component\Entity\ActivityInterface;
use Component\Entity\Activity;
use Component\Entity\ActivityCollection;

class DoctrineStorage implements StorageInterface
{
    /** @var EntityManagerInterface */
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function isNew(object $object): bool
    {
        return !$this->manager->contains($object);
    }

    public function findPlayer(int $id): ?PlayerInterface
    {
        return $this->manager->getRepository(Player::class)->find($id);
    }

    public function findPlayerBy(array $criteria = []): PlayerCollection
    {
        $result = $this->manager->getRepository(Player::class)->findBy($criteria);

        return new PlayerCollection($result);
    }

    public function refreshPlayer(PlayerInterface $player): void
    {
        $this->manager->refresh($player);
    }

    public function recalculatePlayerScore(PlayerInterface $player): void
    {
        $score = 0;
        foreach ($player->getPersonalAchievements() as $personalAchievement) {
            $score += $personalAchievement->getAchievementDefinition()->getPoints();
        }

        $player->setScore($score);
    }

    public function recalculatePlayerLevel(PlayerInterface $player): void
    {
        $criteria = Criteria::create();
        $criteria->orderBy(['level' => 'DESC']);

        $level = $this->findLevelBy()
            ->matching($criteria)
            ->filter(function (LevelInterface $level) use ($player) {
                return $level->getPoints() <= $player->getScore();
            })
            ->first();

        if ($level) {
            $player->setLevel($level->getLevel());
        }
    }

    public function savePlayer(PlayerInterface $player): void
    {
        $this->manager->persist($player);
        $this->manager->flush();
    }

    public function findAchievementDefinition(string $name): ?AchievementDefinitionInterface
    {
        return $this->manager->getRepository(AchievementDefinition::class)->find($name);
    }

    public function findAchievementDefinitionBy(array $criteria = []): AchievementDefinitionCollection
    {
        $result = $this->manager->getRepository(AchievementDefinition::class)->findBy($criteria);

        return new AchievementDefinitionCollection($result);
    }

    public function saveAchievementDefinition(AchievementDefinitionInterface $achievementDefinition): void
    {
        $this->manager->persist($achievementDefinition);
        $this->manager->flush();
    }

    public function findActionDefinition(string $name): ?ActionDefinitionInterface
    {
        return $this->manager->getRepository(ActionDefinition::class)->find($name);
    }

    public function findActionDefinitionBy(array $criteria = []): ActionDefinitionCollection
    {
        $result = $this->manager->getRepository(ActionDefinition::class)->findBy($criteria);

        return new ActionDefinitionCollection($result);
    }

    public function saveActionDefinition(ActionDefinitionInterface $actionDefinition): void
    {
        $this->manager->persist($actionDefinition);
        $this->manager->flush();
    }

    public function savePersonalAction(PersonalActionInterface $personalAction): void
    {
        $this->manager->persist($personalAction);
        $this->manager->flush();
    }

    public function savePersonalAchievement(PersonalAchievementInterface $personalAchievement): void
    {
        $this->manager->persist($personalAchievement);
        $this->manager->flush();
    }

    public function findLevel(string $name): ?LevelInterface
    {
        return $this->manager->getRepository(Level::class)->find($name);
    }

    public function findLevelBy(array $criteria = []): LevelCollection
    {
        $result = $this->manager->getRepository(Level::class)->findBy($criteria);

        return new LevelCollection($result);
    }

    public function saveLevel(LevelInterface $level): void
    {
        $this->manager->persist($level);
        $this->manager->flush();
    }

    public function saveActivity(ActivityInterface $activity): void
    {
        $this->manager->persist($activity);
        $this->manager->flush();
    }

    public function findActivity(int $id): ?ActivityInterface
    {
        return $this->manager->getRepository(Activity::class)->find($id);
    }

    public function findActivityBy(array $criteria = []): ActivityCollection
    {
        $result = $this->manager->getRepository(Activity::class)->findBy($criteria);

        return new ActivityCollection($result);
    }
}
