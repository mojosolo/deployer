<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'https://github.com/deployphp/deployer.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('3000')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/larryville');

// Hooks

after('deploy:failed', 'deploy:unlock');
