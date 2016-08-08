<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'seu_carro',
        'user'      => 'root',
        'password'  => '',
        'charset'   => 'utf8',
    ),
));

$app['security.jwt'] = [
    'secret_key' => 'Very_secret_key',
    'life_time'  => 86400,
    'options'    => [
        'username_claim' => 'sub', // default name, option specifying claim containing username
        'header_name' => 'X-Access-Token', // default null, option for usage normal oauth2 header
        'token_prefix' => 'Bearer',
    ]
];

$app['users'] = function () use ($app) {
    $users = [
        'admin' => array(
            'roles' => array('ROLE_ADMIN'),
            // raw password is foo
            'password' => '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==',
            'enabled' => true
        ),
    ];

    return new InMemoryUserProvider($users);
};

$app['security.firewalls'] = array(
    'login' => [
        'pattern' => 'auth|login|register|oauth',
        'anonymous' => true,
    ],
    'secured' => array(
        'pattern' => '^.*$',
        'logout' => array('logout_path' => '/logout'),
        'users' => $app['users'],
        'jwt' => array(
            'use_forward' => true,
            'require_previous_session' => false,
            'stateless' => true,
        )
    ),
);

$app->register(new Silex\Provider\TwigServiceProvider(), array(
		'twig.path' => __DIR__.'/../views',
));

$app->register(new Silex\Provider\SecurityServiceProvider());
$app->register(new Silex\Provider\SecurityJWTServiceProvider());

$app->post('/api/login', function(Request $request) use ($app){
    $usuario = $request->get('usuario');
    $senha = $request->get('senha');
    
       
    print_r($app['users']);
    exit();
        
    
    try {
        if (empty($usuario) || empty($senha)) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $usuario));
        }
        /**
         * @var $user User
         */
        $user = $app['users']->loadUserByUsername($usuario);
        
        if (! $app['security.encoder.digest']->isPasswordValid($user->getPassword(), $senha, '')) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $usuario));
        } else {
            $response = [
                'success' => true,
                'token' => $app['security.jwt.encoder']->encode(['name' => $user->getUsername()]),
            ];
        }
    } catch (UsernameNotFoundException $e) {
        $response = [
            'success' => false,
            'error' => 'Invalid credentials',
        ];
    }

    return $app->json($response, ($response['success'] == true ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST));
})->bind('autenticar');

    $app->get('/api/protected_resource', function() use ($app){
        return $app->json(['hello' => 'world']);
    });


return $app;