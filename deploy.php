<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config
set('repository', 'YOUR_REPOSITORY_URL');
set('application', 'larryville');
set('keep_releases', 5);

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
host('your-server.com')
    ->set('remote_user', 'your-ssh-user')
    ->set('deploy_path', '/var/www/larryville');

// Tasks
task('build', function () {
    cd('{{release_path}}');
    run('npm install');
    run('npm run build');
});

after('deploy:failed', 'deploy:unlock');
