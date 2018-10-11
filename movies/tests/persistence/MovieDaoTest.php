<?php
declare(strict_types = 1);
namespace tests\persistence;

use PHPUnit\Framework\TestCase;
use persistence\MovieDao;

final class MovieDaoTest extends TestCase
{

    private static $pdo = null;

    static function setUpBeforeClass()
    {
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ];
        self::$pdo = new \PDO('sqlite::memory:', $username = null, $password = null, $options);
        
        $file = __DIR__ . '/test.sql';
        $sql = file_get_contents($file);
        $result = self::$pdo->exec($sql);
    }

    static function tearDownAfterClass()
    {
        self::$pdo = null;
    }

    function test_constructor()
    {
        new MovieDao(self::$pdo);
        
        // Avoid "This test did not perform any assertions"
        $this->assertTrue(true);
    }

    function test_getFilmRatings()
    {
        $dao = new MovieDao(self::$pdo);
        $ratings = $dao->getFilmRatings();
        
        $this->assertCount($expected = 5, $ratings);
    }

    function test_getCategories()
    {
        $dao = new MovieDao(self::$pdo);
        $categories = $dao->getCategories();
        
        $this->assertCount($expected = 16, $categories);
    }
    
    function test_retrieveFilms() {
        $dao = new MovieDao(self::$pdo);
        $films = $dao->retrieveFilms();
        
        $this->assertCount($expected = 10, $films);
    }
    
    function test_retrieveFilms_search_by_title() {
        $dao = new MovieDao(self::$pdo);
        $films = $dao->retrieveFilms($title = 'af');
        
        $this->assertCount($expected = 2, $films);
    }
    
    function test_retrieveFilms_filter_by_rating() {
        $dao = new MovieDao(self::$pdo);
        $films = $dao->retrieveFilms($title = null, $rating = 'pg');
        
        $this->assertCount($expected = 2, $films);
    }
    
    function test_retrieveFilms_filter_by_category() {
        $dao = new MovieDao(self::$pdo);
        $films = $dao->retrieveFilms($title = null, $rating = null, $category = 'action');
        
        $this->assertCount($expected = 1, $films);
    }
    
    function test_retrieveFilms_with_title_rating_and_category() {
        $dao = new MovieDao(self::$pdo);
        $films = $dao->retrieveFilms($title = 'dino', $rating = 'pg', $category = 'action');
        
        $this->assertCount($expected = 1, $films);
    }
    
    function test_retrieveFilmByFilmId() {
        $dao = new MovieDao(self::$pdo);
        $film = $dao->retrieveFilmByFilmId(1);
        
        $this->assertNotEmpty($film);
    }
    
    function test_retrieveFilmByFilmId_with_nonexistent_film_id() {
        $dao = new MovieDao(self::$pdo);
        $film = $dao->retrieveFilmByFilmId(0);
        
        $this->assertEmpty($film);
    }
    
    function test_retrieveActorsByFilmId() {
        $dao = new MovieDao(self::$pdo);
        $actors = $dao->retrieveActorsByFilmId(1);
        
        $this->assertCount($expected = 1, $actors);
    }
    
    function test_retrieveActorsByFilmId_with_nonexistent_film_id() {
        $dao = new MovieDao(self::$pdo);
        $actors = $dao->retrieveActorsByFilmId(0);
        
        $this->assertEmpty($actors);
    }
    
}