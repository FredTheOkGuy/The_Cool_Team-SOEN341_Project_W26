<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class CreateRecipeTest extends TestCase
{
    private string $sourceFile;

    protected function setUp(): void
    {
        $this->sourceFile = __DIR__ . '/../project/src/controllers/recipe_creation_post.php';
        $this->assertFileExists($this->sourceFile, 'Check your path to recipe_creation_post.php.');
    }

    public function testCreateRecipeIsTriggeredWhenCreatePostFlagIsSet(): void
    {
        $sandbox = $this->makeSandbox();

        $runner = <<<'PHP'
<?php
error_reporting(E_ERROR | E_PARSE);

session_start();
$_SESSION['user_id'] = 1;

$_POST['create_recipe'] = '1';
$_POST['ingredients']   = json_encode(['chicken', 'garlic', 'lemon']);
$_POST['meal_type']     = 'dinner';

ob_start();
include __DIR__ . '/subject.php';
ob_end_clean();
PHP;

        file_put_contents($sandbox . '/runner.php', $runner);
        exec(PHP_BINARY . ' ' . escapeshellarg($sandbox . '/runner.php'), $output, $exitCode);

        $this->assertSame(0, $exitCode, implode("\n", $output));
        $this->assertFileExists($sandbox . '/create_called.txt');
    }

    public function testCreateRecipePassesCorrectIngredientsAndMealType(): void
    {
        $sandbox = $this->makeSandbox();

        $runner = <<<'PHP'
<?php
error_reporting(E_ERROR | E_PARSE);

session_start();
$_SESSION['user_id'] = 5;

$_POST['create_recipe'] = '1';
$_POST['ingredients']   = json_encode(['egg', 'butter', 'flour']);
$_POST['meal_type']     = 'breakfast';

ob_start();
include __DIR__ . '/subject.php';
ob_end_clean();
PHP;

        file_put_contents($sandbox . '/runner.php', $runner);
        exec(PHP_BINARY . ' ' . escapeshellarg($sandbox . '/runner.php'), $output, $exitCode);

        $this->assertSame(0, $exitCode, implode("\n", $output));

        $callFile = $sandbox . '/create_args.json';
        $this->assertFileExists($callFile);

        $args = json_decode((string) file_get_contents($callFile), true);
        $this->assertSame('egg, butter, flour', $args['ingredients_string']);
        $this->assertSame('breakfast', $args['meal_type']);
    }

    public function testSaveRecipeIsTriggeredWhenSavePostFlagIsSet(): void
    {
        $sandbox = $this->makeSandbox();

        $recipe = [
            'name'              => 'Test Recipe',
            'description'       => 'A test recipe',
            'prep_time_minutes' => 10,
            'cook_time_minutes' => 20,
            'difficulty'        => 'easy',
            'calories'          => 400,
            'gmo_free'          => true,
            'gluten_free'       => false,
            'lactose_free'      => false,
            'vegan'             => false,
            'vegetarian'        => true,
            'ingredients'       => ['egg', 'butter'],
            'steps'             => ['Mix ingredients', 'Cook for 20 mins'],
        ];

        $runner = '<?php' . "\n" .
            'error_reporting(E_ERROR | E_PARSE);' . "\n" .
            'session_start();' . "\n" .
            '$_SESSION[\'user_id\'] = 3;' . "\n" .
            '$_POST[\'save_recipe\'] = \'1\';' . "\n" .
            '$_POST[\'recipe_data\'] = \'' . json_encode($recipe) . '\';' . "\n" .
            '$_POST[\'meal_type\'] = \'dinner\';' . "\n" .
            'ob_start();' . "\n" .
            'include __DIR__ . \'/subject.php\';' . "\n" .
            'ob_end_clean();' . "\n";

        file_put_contents($sandbox . '/runner.php', $runner);
        exec(PHP_BINARY . ' ' . escapeshellarg($sandbox . '/runner.php'), $output, $exitCode);

        $this->assertSame(0, $exitCode, implode("\n", $output));
        $this->assertFileExists($sandbox . '/add_recipe_called.txt');
    }

    public function testCreateIsNotTriggeredWhenNoPostFlagIsSet(): void
    {
        $sandbox = $this->makeSandbox();

        $runner = <<<'PHP'
<?php
error_reporting(E_ERROR | E_PARSE);

session_start();
$_SESSION['user_id'] = 9;

ob_start();
include __DIR__ . '/subject.php';
ob_end_clean();
PHP;

        file_put_contents($sandbox . '/runner.php', $runner);
        exec(PHP_BINARY . ' ' . escapeshellarg($sandbox . '/runner.php'), $output, $exitCode);

        $this->assertSame(0, $exitCode, implode("\n", $output));
        $this->assertFileDoesNotExist($sandbox . '/create_called.txt');
        $this->assertFileDoesNotExist($sandbox . '/add_recipe_called.txt');
    }

    private function makeSandbox(): string
    {
        $dir = sys_get_temp_dir() . '/create_recipe_test_' . bin2hex(random_bytes(6));
        mkdir($dir, 0777, true);

        copy($this->sourceFile, $dir . '/subject.php');

        // Patch require paths in the controller
        $post = (string) file_get_contents($dir . '/subject.php');
        $post = str_replace(
            "require_once __DIR__ . '/../../config/api_config.php'",
            "require_once __DIR__ . '/api_config.php'",
            $post
        );
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
        // Neutralise the redirect after save
        $post = str_replace(
            'header("Location: " . BASE_URL . "/src/views/recipes.php");',
            '// redirected',
            $post
        );
        $post = str_replace('exit();', '// exit', $post);
        file_put_contents($dir . '/subject.php', $post);

        file_put_contents($dir . '/api_config.php', "<?php\n");

        file_put_contents($dir . '/login_page_config.php', <<<'PHP'
<?php
define('BASE_URL', '');
$conn = new stdClass();
PHP);

        // Stub model functions — record calls to files for assertions
        file_put_contents($dir . '/sql_recipe_functions.php', <<<'PHP'
<?php

function createRecipe($userId, $ingredients_string, $meal_type)
{
    file_put_contents(__DIR__ . '/create_called.txt', 'called');
    file_put_contents(__DIR__ . '/create_args.json', json_encode([
        'user_id'            => $userId,
        'ingredients_string' => $ingredients_string,
        'meal_type'          => $meal_type,
    ]));
    return [
        'name'              => 'Generated Recipe',
        'description'       => 'Auto-generated',
        'prep_time_minutes' => 5,
        'cook_time_minutes' => 10,
        'difficulty'        => 'easy',
        'calories'          => 300,
        'gmo_free'          => false,
        'gluten_free'       => false,
        'lactose_free'      => false,
        'vegan'             => false,
        'vegetarian'        => false,
        'ingredients'       => [],
        'steps'             => [],
    ];
}

function addRecipe(...$args): void
{
    file_put_contents(__DIR__ . '/add_recipe_called.txt', 'called');
}

function editRecipe(...$args): void {}
function deleteRecipe(...$args): void {}
PHP);

        return $dir;
    }
}