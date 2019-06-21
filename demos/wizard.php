<?php

namespace atk4\login\demo;

use atk4\login\Model\AccessRule;
use atk4\login\Model\Role;
use atk4\login\Model\User;
use atk4\schema\MigratorConsole;
use atk4\ui\Console;
use atk4\ui\View;
use atk4\ui\Wizard;

include '../vendor/autoload.php';
include 'db.php';

$app = new App('centered');
/** @var Wizard $wizard */
$wizard=$app->add('Wizard');

$wizard->addStep('Quickly checking if database is OK', function(View $page) {
    $console = $page->add(MigratorConsole::class);

    /*
    $button = $page->add(['Button', '<< Back', 'huge wide blue'])
        ->addStyle('display', 'none')
        ->link(['index']);
    */

    $console->migrateModels([User::class, Role::class, AccessRule::class]);
});

$wizard->addStep('Populate Sample Data', function(View $page) {
    $page->add('Console')->set(function(Console $c) {

        $c->output('populating data');

        (new Role($c->app->db))->each('delete')->import(['user', 'admin']);
        (new User($c->app->db))->each('delete')->import([
            ['name'=>'user', 'role'=>'user', 'password'=>'user'],
            ['name'=>'admin', 'role'=>'admin', 'password'=>'admin'],
        ]);

    });
});

$wizard->addFinish(function($p) {
    $p->app->redirect(['index']);
});
