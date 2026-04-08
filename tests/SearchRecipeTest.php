<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class SearchRecipeTest extends TestCase
{
    private string $sourceFile;

    protected function setUp(): void
    {
        $this->sourceFile = __DIR__ . '/../project/src/views/recipes.php';
        $this->assertFileExists($this->sourceFile, 'Check your path to recipes.php.');
    }

    public function testDefaultSearchUsesDefaultFiltersAndSorting(): void
    {
        $sandbox = $this->makeSandbox();

        $runner = <<<'PHP'
<?php
error_reporting(E_ERROR | E_PARSE);

session_start();
$_SESSION['user_id'] = 17;

ob_start();
include __DIR__ . '/subject.php';
ob_end_clean();
PHP;

        file_put_contents($sandbox . '/runner.php', $runner);
        exec(PHP_BINARY . ' ' . escapeshellarg($sandbox . '/runner.php'), $output, $exitCode);

        $this->assertSame(0, $exitCode, implode("\n", $output));

        $sql = (string) file_get_contents($sandbox . '/prepared_sql.txt');
        $bind = json_decode((string) file_get_contents($sandbox . '/bind_log.json'), true);

        $this->assertStringContainsString('FROM recipes WHERE user_id = ?', $sql);
        $this->assertStringContainsString('AND prep_time <= 1000', $sql);
        $this->assertStringContainsString('AND cook_time <= 1000', $sql);
        $this->assertStringContainsString('ORDER BY recipe_name ASC', $sql);

        $this->assertSame('i', $bind['types']);
        $this->assertSame([17], $bind['params']);
    }

    public function testSearchByNameAddsLikeClauseAndStringParameter(): void
    {
        $sandbox = $this->makeSandbox();

        $runner = <<<'PHP'
<?php
error_reporting(E_ERROR | E_PARSE);

session_start();
$_SESSION['user_id'] = 21;

$_GET['search'] = 'pasta';

ob_start();
include __DIR__ . '/subject.php';
ob_end_clean();
PHP;

        file_put_contents($sandbox . '/runner.php', $runner);
        exec(PHP_BINARY . ' ' . escapeshellarg($sandbox . '/runner.php'), $output, $exitCode);

        $this->assertSame(0, $exitCode, implode("\n", $output));

        $sql = (string) file_get_contents($sandbox . '/prepared_sql.txt');
        $bind = json_decode((string) file_get_contents($sandbox . '/bind_log.json'), true);

        $this->assertStringContainsString('AND recipe_name LIKE ?', $sql);
        $this->assertStringContainsString('ORDER BY recipe_name ASC', $sql);

        $this->assertSame('is', $bind['types']);
        $this->assertSame([21, '%pasta%'], $bind['params']);
    }

    public function testFiltersAndSortingAreAddedToQuery(): void
    {
        $sandbox = $this->makeSandbox();

        $runner = <<<'PHP'
<?php
error_reporting(E_ERROR | E_PARSE);

session_start();
$_SESSION['user_id'] = 9;

$_GET['search'] = 'rice';
$_GET['sort'] = 'cook_time_desc';
$_GET['cook_time_filter'] = 'under_30';
$_GET['prep_time_filter'] = 'under_15';
$_GET['filter_gmo_free'] = '1';
$_GET['filter_vegan'] = '1';
$_GET['medium_diff'] = '1';

ob_start();
include __DIR__ . '/subject.php';
ob_end_clean();
PHP;

        file_put_contents($sandbox . '/runner.php', $runner);
        exec(PHP_BINARY . ' ' . escapeshellarg($sandbox . '/runner.php'), $output, $exitCode);

        $this->assertSame(0, $exitCode, implode("\n", $output));

        $sql = (string) file_get_contents($sandbox . '/prepared_sql.txt');
        $bind = json_decode((string) file_get_contents($sandbox . '/bind_log.json'), true);

        $this->assertStringContainsString('AND prep_time <= 15', $sql);
        $this->assertStringContainsString('AND cook_time <= 30', $sql);
        $this->assertStringContainsString('AND gmo_free = 1', $sql);
        $this->assertStringContainsString('AND vegan = 1', $sql);
        $this->assertStringContainsString("AND difficulty_level = 'Medium'", $sql);
        $this->assertStringContainsString('AND recipe_name LIKE ?', $sql);
        $this->assertStringContainsString('ORDER BY cook_time DESC', $sql);

        $this->assertSame('is', $bind['types']);
        $this->assertSame([9, '%rice%'], $bind['params']);
    }

    public function testOverSixtyMapsToOneThousandAndNameDescendingSort(): void
    {
        $sandbox = $this->makeSandbox();

        $runner = <<<'PHP'
<?php
error_reporting(E_ERROR | E_PARSE);

session_start();
$_SESSION['user_id'] = 33;

$_GET['sort'] = 'name_desc';
$_GET['cook_time_filter'] = 'over_60';
$_GET['prep_time_filter'] = 'over_60';
$_GET['filter_gluten_free'] = '1';
$_GET['filter_vegetarian'] = '1';
$_GET['hard_diff'] = '1';

ob_start();
include __DIR__ . '/subject.php';
ob_end_clean();
PHP;

        file_put_contents($sandbox . '/runner.php', $runner);
        exec(PHP_BINARY . ' ' . escapeshellarg($sandbox . '/runner.php'), $output, $exitCode);

        $this->assertSame(0, $exitCode, implode("\n", $output));

        $sql = (string) file_get_contents($sandbox . '/prepared_sql.txt');
        $bind = json_decode((string) file_get_contents($sandbox . '/bind_log.json'), true);

        $this->assertStringContainsString('AND prep_time <= 1000', $sql);
        $this->assertStringContainsString('AND cook_time <= 1000', $sql);
        $this->assertStringContainsString('AND gluten_free = 1', $sql);
        $this->assertStringContainsString('AND vegetarian = 1', $sql);
        $this->assertStringContainsString("AND difficulty_level = 'Hard'", $sql);
        $this->assertStringContainsString('ORDER BY recipe_name DESC', $sql);

        $this->assertSame('i', $bind['types']);
        $this->assertSame([33], $bind['params']);
    }

    private function makeSandbox(): string
    {
        $dir = sys_get_temp_dir() . '/search_recipe_test_' . bin2hex(random_bytes(6));
        mkdir($dir, 0777, true);

        // Copy both view and controller into sandbox
        copy($this->sourceFile, $dir . '/subject.php');
        copy(__DIR__ . '/../project/src/controllers/recipe_post.php', $dir . '/recipe_post.php');

        // Patch subject.php (recipes.php) include path
        $subject = file_get_contents($dir . '/subject.php');
        $subject = str_replace(
            "include __DIR__ . '/../controllers/recipe_post.php'",
            "include __DIR__ . '/recipe_post.php'",
            $subject
        );
        file_put_contents($dir . '/subject.php', $subject);

        // Patch recipe_post.php require paths
        $post = file_get_contents($dir . '/recipe_post.php');
        $post = str_replace(
            "require_once __DIR__ . '/../../config/login_page_config.php'",
            "require_once __DIR__ . '/login_page_config.php'",
            $post
        );
        $post = str_replace(
            "require_once __DIR__ . '/../models/sql_recipe_functions.php'",
            "require_once __DIR__ . '/sql_recipe_functions.php'",
            $post
        );
        file_put_contents($dir . '/recipe_post.php', $post);

        file_put_contents($dir . '/api_config.php', "<?php\n");

        file_put_contents($dir . '/login_page_config.php', <<<'PHP'
<?php

define('BASE_URL', '');

class FakeResultSet
{
    public function fetch_assoc()
    {
        return null;
    }
}

class FakeStmt
{
    private string $sql;

    public function __construct(string $sql)
    {
        $this->sql = $sql;
        file_put_contents(__DIR__ . '/prepared_sql.txt', $sql);
    }

    public function bind_param($types, &...$params): void
    {
        file_put_contents(
            __DIR__ . '/bind_log.json',
            json_encode([
                'types' => $types,
                'params' => $params,
            ], JSON_PRETTY_PRINT)
        );
    }

    public function execute(): void
    {
    }

    public function get_result()
    {
        return new FakeResultSet();
    }
}

class FakeConn
{
    public function prepare(string $sql)
    {
        return new FakeStmt($sql);
    }
}

$conn = new FakeConn();
PHP);

        file_put_contents($dir . '/sql_recipe_functions.php', <<<'PHP'
<?php

function deleteRecipe(...$args): void
{
}

function addRecipe(...$args): void
{
}

function createRecipe(...$args)
{
    return null;
}

function editRecipe(...$args): void
{
}

function recipeDisplayInformation($userId, $search_name, $prep_time_filter, $cook_time_filter, $order_by, $order_direction)
{
    global $conn;
    $sql = "SELECT recipe_id, recipe_name, description, prep_time, cook_time, difficulty_level, calories, gmo_free, gluten_free, lactose_free, vegan, vegetarian, meal_type FROM recipes WHERE user_id = ?
                AND prep_time <= $prep_time_filter 
                AND cook_time <= $cook_time_filter";

    $filter_gmo_free     = isset($_GET['filter_gmo_free'])     ? 1 : null;
    $filter_gluten_free  = isset($_GET['filter_gluten_free'])  ? 1 : null;
    $filter_lactose_free = isset($_GET['filter_lactose_free']) ? 1 : null;
    $filter_vegan        = isset($_GET['filter_vegan'])        ? 1 : null;
    $filter_vegetarian   = isset($_GET['filter_vegetarian'])   ? 1 : null;
    $filter_easy_diff    = isset($_GET['easy_diff'])           ? 1 : null;
    $filter_medium_diff  = isset($_GET['medium_diff'])         ? 1 : null;
    $filter_hard_diff    = isset($_GET['hard_diff'])           ? 1 : null;

    if ($filter_gmo_free)     $sql .= " AND gmo_free = 1";
    if ($filter_gluten_free)  $sql .= " AND gluten_free = 1";
    if ($filter_lactose_free) $sql .= " AND lactose_free = 1";
    if ($filter_vegan)        $sql .= " AND vegan = 1";
    if ($filter_vegetarian)   $sql .= " AND vegetarian = 1";
    if ($filter_easy_diff)    $sql .= " AND difficulty_level = 'Easy'";
    if ($filter_medium_diff)  $sql .= " AND difficulty_level = 'Medium'";
    if ($filter_hard_diff)    $sql .= " AND difficulty_level = 'Hard'";

    $params = [$userId];
    $types  = "i";

    if ($search_name) {
        $sql    .= " AND recipe_name LIKE ?";
        $params[] = '%' . $search_name . '%';
        $types   .= "s";
    }

    $sql .= " ORDER BY $order_by $order_direction";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result();
}

function getRecipeIngredients(...$args)
{
    return new class {
        public function fetch_assoc() { return null; }
    };
}

function getRecipesWithStepNumber(...$args)
{
    return new class {
        public function fetch_assoc() { return null; }
    };
}
PHP);

        return $dir;
    }
}