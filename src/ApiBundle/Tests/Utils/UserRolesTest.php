<?php

/*
 * This file is part of the Incipio package.
 *
 * (c) Théo FIDRY <theo.fidry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ApiBundle\Tests\Utils;

use ApiBundle\Utils\UserRoles;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class UserRolesTest.
 *
 * @coversDefaultClass ApiBundle\Utils\UserRoles
 *
 * @author             Théo FIDRY <theo.fidry@gmail.com>
 */
class UserRolesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the service's methods.
     *
     * @covers ::getRoles
     * @dataProvider hierarchyProvider
     */
    public function testService(array $hierarchy, array $expected)
    {
        $service = new UserRoles($hierarchy);
        $this->assertEquals($expected, $service->getRoles());
    }

    /**
     * Provides set of data returned by the `%security.role_hierarchy.roles%` parameter
     *
     * @return array
     */
    public function hierarchyProvider()
    {
        $return = [];

        $return[] = [
            [
                'ROLE_ADMIN' => [
                    'ROLE_USER',
                ],
            ],
            [
                'ROLE_ADMIN',
                'ROLE_USER',
            ]
        ];

        $return[] = [
            [
                'ROLE_ADMIN'             => [
                    'ROLE_USER',
                ],
                'ROLE_SUPER_ADMIN'       => [
                    'ROLE_ADMIN',
                    'ROLE_ALLOWED_TO_SWITCH',
                ],
                'ROLE_SUPER_SUPER_ADMIN' => [
                    'ROLE_SUPER_ADMIN',
                    'NEW_ROLE',
                ]
            ],
            [
                'ROLE_ADMIN',
                'ROLE_USER',
                'ROLE_SUPER_ADMIN',
                'ROLE_ALLOWED_TO_SWITCH',
                'ROLE_SUPER_SUPER_ADMIN',
                'NEW_ROLE',
            ]
        ];

        return $return;
    }
}
