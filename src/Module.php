<?php

namespace Reliv\SymfonizeZF;

class Module
{
    /**
     * getConfig() is a requirement for all Modules in ZF2.  This
     * function is included as part of that standard.  See Docs on ZF2 for more
     * information.
     *
     * @return array Returns array to be used by the ZF2 Module Manager
     */
    public function getConfig()
    {
        return [
            'service_manager' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
            ],
            'controllers' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
            ],
            'view_helpers' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
            ],
            'controller_plugins' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
            ],
            'input_filters' => [
                'abstract_factories' => [
                    'Reliv\SymfonizeZf\ContainerBridge\ContainerBridge'
                ],
            ]
        ];
    }
}
