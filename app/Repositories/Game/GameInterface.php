<?php

namespace App\Repositories\Game;

interface GameInterface
{
    public function list($request);

    public function updateOrCreate($request);

    public function updateOrCreateGameSetting($request);

    public function toggleIsActive($request);

    public function gameSetting($gameId);


}
