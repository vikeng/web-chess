<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class GameTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateGame()
    {
        $game = new App\Entities\Game();
        $this->assertInstanceOf(App\Entities\Game::class, $game);
    }

    public function testSaveGame()
    {
        $count = App\Entities\Game::all()->count();
        $game = new App\Entities\Game();
        $game->jcf = app(App\Chess\JCFGame::class)->doMove(new App\Chess\Move('e2', 'e4'))->getJCF();
        $game->owner_id = App\Entities\User::first()->id;
        $game->public = 2;
        $game->save();

        $this->assertSame(($count + 1), App\Entities\Game::all()->count());
    }

    public function testGetTags()
    {
        $game = App\Entities\Game::first();
        $this->assertInstanceOf(App\Entities\Tag::class, $game->tags()->first());
    }

    public function testShareGame()
    {
        $count = App\Entities\User::first()->sharedGames->count();
        $count2 = App\Entities\Game::first()->sharedWith->count();
        App\Entities\Game::first()->share(App\Entities\User::first()->id, 2);
        $this->assertSame($count + 1, App\Entities\User::first()->sharedGames->count());
        $this->assertSame($count + 1, App\Entities\Game::first()->sharedWith->count());
    }

    public function testGameAttribute()
    {
        $model = new App\Entities\Game();
        $this->assertInstanceOf(App\Chess\BCFGame::class, $model->game);
        $game = new App\Chess\BCFGame();
        $game->doMove(new App\Chess\Move('b2', 'b3'));
        $model->game = $game;
        $this->assertEquals($game, $model->game);
        $this->assertEquals($game->getBCF(), $model->bcf);
    }
}
