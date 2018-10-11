<?php

namespace persistence;

use persistence\PersistenceException;

class MovieDao {
    
    /**
     * 
     * @var \PDO
     */
    private $pdo;
    
    function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    function getFilmRatings(): array {
        $query = 'SELECT DISTINCT(rating) FROM film f;';
        $statement = $this->pdo->prepare($query);
        if ($statement->execute()) {
            return $statement->fetchAll(\PDO::FETCH_COLUMN, 0);
        }
        
        return [];
    }
    
    function getCategories(): array {
        $query = 'SELECT name FROM category;';
        $statement = $this->pdo->prepare($query);
        if ($statement->execute()) {
            return $statement->fetchAll(\PDO::FETCH_COLUMN, 0);
        }
        
        return [];
    }
    
    /**
     * 
     * @param string $title A partial or complete movie title.
     * @param string $rating One of: "PG", "G", "NC-17", "PG-13", or "R".
     * @param string $category One of: "Action", "Animation", "Children", "Classics", "Comedy", "Documentary", "Drama", "Family", "Foreign", "Games", "Horror", "Music", "New", "Sci-Fi", "Sports", "Travel".
     * @return array An array of films.
     */
    function retrieveFilms(string $title = null, string $rating = null, string $category = null): array {
        $title = isset($title) ? "%{$title}%" : '%';
        $rating = $rating ?? '%';
        $category = $category ?? '%';
        
        $query = <<<SQL
SELECT
    f.film_id,
    title,
    description,
    release_year,
    rating,
    (SELECT
            GROUP_CONCAT(c.name)
        FROM
            film_category fc
                INNER JOIN
            category c ON fc.category_id = c.category_id
        WHERE
            f.film_id = fc.film_id) AS category_names
FROM
    film f
WHERE
    title LIKE :title
        AND rating LIKE :rating
HAVING category_names LIKE :category;
SQL;
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('title', $title);
        $statement->bindValue('rating', $rating);
        $statement->bindValue('category', $category);
        if ($statement->execute()) {
            return $statement->fetchAll(\PDO::FETCH_OBJ);
        }
        
        return [];
    }
    
    function retrieveFilmByFilmId(int $film_id): object {
        $query = <<<SQL
SELECT
    f.film_id,
    title,
    description,
    release_year,
    l.name AS language_name,
    rental_duration,
    rental_rate,
    length,
    replacement_cost,
    rating,
    special_features,
    (SELECT
            GROUP_CONCAT(c.name)
        FROM
            film_category fc
                INNER JOIN
            category c ON fc.category_id = c.category_id
        WHERE
            f.film_id = fc.film_id) AS category_names
FROM
    film f
        INNER JOIN
    language l ON f.language_id = l.language_id
WHERE
    f.film_id = :film_id;
SQL;
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('film_id', $film_id);
        if ($statement->execute()) {
            try {
                return $statement->fetch(\PDO::FETCH_OBJ);
            } finally {
                $statement->closeCursor();
            }
        }
        
        return new \stdClass();
    }
    
    function retrieveActorsByFilmId(int $film_id): array {
        $query = <<<SQL
SELECT
    a.first_name, a.last_name
FROM
    film_actor fa
        INNER JOIN
    actor a ON fa.actor_id = a.actor_id
WHERE
    fa.film_id = :film_id;
SQL;
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('film_id', $film_id);
        if ($statement->execute()) {
            try {
                return $statement->fetchAll(\PDO::FETCH_OBJ);
            } finally {
                $statement->closeCursor();
            }
        }
        
        return [];
    }
    
}