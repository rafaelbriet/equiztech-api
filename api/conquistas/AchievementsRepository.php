<?php

class AchievementRepository {
    private mysqli $connection;

    function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }
}