<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config
set('repository', 'https://YOUR_GITHUB_TOKEN@github.com/mojosolo/larryville.git');
set('application', 'larryville');
set('keep_releases', 5);

// Git configuration
set('git_tty', false);
set('git_cache', false);

// Shared files/dirs between deploys 
add('shared_files', [
    '.env'
]);

add('shared_dirs', [
    'storage',
    'bootstrap/cache',
]);

// Writable dirs by web server 
add('writable_dirs', [
    'bootstrap/cache',
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
]);

// Hosts
host('localhost')
    ->set('remote_user', get_current_user())
    ->set('deploy_path', '/Users/david/Herd/larryville')
    ->set('writable_mode', 'chmod')
    ->set('writable_chmod_mode', '0755')
    ->set('writable_chmod_recursive', true);

// Tasks
task('build', function () {
    cd('{{release_path}}');
    run('npm install');
    run('npm run build');
});

after('deploy:failed', 'deploy:unlock');
