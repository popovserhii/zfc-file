<?php
namespace Popov\ZfcFile;

return [
    'controllers' => [
        'invokables' => [
            'file' => Controller\FileController::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'file' => View\Helper\FileHelper::class,
        ],
    ],
    'service_manager' => [
        'aliases' => [
            //'File' => Model\Files::class,
            'FileService' => Service\FileService::class,
        ],
        'invokables' => [
            Service\FileService::class => Service\FileService::class
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src//Model'],
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Model' => __NAMESPACE__ . '_driver',
                ],
            ],
        ],
    ],
];
