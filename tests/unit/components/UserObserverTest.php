<?php
/**
 * OpenEyes
 *
 * (C) OpenEyes Foundation, 2021
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2021, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/agpl-3.0.html The GNU Affero General Public License V3.0
 */

namespace OEModule\CSDClient\tests\unit\components;

use OEModule\CSDClient\components\UserObserver;
use OEModule\CSDClient\components\CSDClient\CSDClient;

class UserObserverTest extends \OEDbTestCase
{
    public $fixtures = [
        "user" => \User::class,
    ];

    /** @var \CDbTransaction */
    private $transaction;
    /** @var CSD */
    private $api;

    /*public function testClassCanBeFound()
    {
        $api = \Yii::app()->moduleAPI->get("CSDClient");
        $this->assertInstanceOf(UserObserver::class, $api);
    }*/

    public function setUp()
    {
        $this->transaction = \Yii::app()->db->beginTransaction();
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->transaction->rollback();
    }


    /** @test */
    public function testUpdateUser()
    {
        $this->api = \Yii::app()->moduleAPI->get("CSDClient");
        $this->api = $this->getMockBuilder(UserObserver::class)
            ->setMethods(["getCSDClient"])
            ->getMock();
        $mockCSDClient = $this->getMockBuilder(CSDClient::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockCSDClient->method("getUserData")->willReturn(json_encode(["hello" => "world"]));
        $this->api->method("getCSDClient")->willReturn($mockCSDClient);

        //$mock_user = $this->user("user1");
        //$this->assertEquals($this->api->updateUser($mock_user), $mock_user);
    }
}