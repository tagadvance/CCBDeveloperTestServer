<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$configuration = [
    'settings' => [
        // Note: for debugging only. Disable in "production".
        'displayErrorDetails' => true
    ]
];
$container = new \Slim\Container($configuration);
$app = new \Slim\App($container);

$container['db'] = function ($c) {
    $database = $user = $password = 'sakila';
    $host = 'mysql';
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    return new PDO("mysql:host={$host};dbname={$database};charset=utf8mb4", $user, $password, $options);
};

$app->get('/movies', function (Request $request, Response $response, array $args) use ($app) {
    /**
     *
     * @var PDO $db
     */
    $db = $this->get('db');
    
    $params = $request->getQueryParams();
    $title = isset($params['title']) ? "%{$params['title']}%" : '%';
    $rating = $params['rating'] ?? '%';
    $category = isset($params['category']) ? "%{$params['category']}%" : '%';
    
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
            f.film_id = fc.film_id) AS category_names,
    f.last_update
FROM
    film f
        INNER JOIN
    language l ON f.language_id = l.language_id
WHERE
    title LIKE :title
        AND rating LIKE :rating
HAVING category_names LIKE :category;
SQL;
    try {
        $statement = $db->prepare($query);
        $statement->bindValue('title', $title);
        $statement->bindValue('rating', $rating);
        $statement->bindValue('category', $category);
        if ($statement->execute()) {
            $movies = $statement->fetchAll(PDO::FETCH_OBJ);
            return $response->withJson($movies);
        }
    } catch (\PDOException $e) {
        return $response->withJson([
            'message' => 'An error has occurred.'
        ], $status = 500);
    }
});

$app->run();
