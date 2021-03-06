<?php

namespace Component\Engine\Storage;

use Component\Entity\Achievement\ActionDefinitionCollection;
use Component\Entity\Achievement\ActionDefinitionInterface;
use Component\Entity\Achievement\AchievementDefinitionCollection;
use Component\Entity\Achievement\AchievementDefinitionInterface;
use Component\Entity\Achievement\PersonalAchievementInterface;
use Component\Entity\Achievement\PersonalActionInterface;
use Component\Entity\Achievement\LevelCollection;
use Component\Entity\Achievement\LevelInterface;
use Component\Entity\PlayerCollection;
use Component\Entity\PlayerInterface;
use Component\Entity\ActivityInterface;
use Component\Entity\ActivityCollection;

interface StorageInterface
{
    public function isNew(object $object): bool;

    public function findPlayer(int $id): ?PlayerInterface;

    public function findPlayerBy(array $criteria = []): PlayerCollection;

    public function refreshPlayer(PlayerInterface $player): void;

    public function recalculatePlayerScore(PlayerInterface $player): void;

    public function recalculatePlayerLevel(PlayerInterface $player): void;

    public function savePlayer(PlayerInterface $player): void;

    public function findAchievementDefinition(string $name): ?AchievementDefinitionInterface;

    public function findAchievementDefinitionBy(array $criteria = []): AchievementDefinitionCollection;

    public function saveAchievementDefinition(AchievementDefinitionInterface $achievementDefinition): void;

    public function findActionDefinition(string $name): ?ActionDefinitionInterface;

    public function findActionDefinitionBy(array $criteria = []): ActionDefinitionCollection;

    public function saveActionDefinition(ActionDefinitionInterface $actionDefinition): void;

    public function savePersonalAction(PersonalActionInterface $personalAction): void;

    public function savePersonalAchievement(PersonalAchievementInterface $personalAchievement): void;

    public function findLevel(string $name): ?LevelInterface;

    public function findLevelBy(array $criteria = []): LevelCollection;

    public function saveLevel(LevelInterface $level);

    public function saveActivity(ActivityInterface $activity): void;

    public function findActivity(int $id): ?ActivityInterface;

    public function findActivityBy(array $criteria = []): ActivityCollection;
}
