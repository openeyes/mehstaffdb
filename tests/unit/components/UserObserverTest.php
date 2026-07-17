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

namespace OEModule\mehstaffdb\tests\unit\components;

class UserObserverTest extends \OEDbTestCase
{
    /** @var \CDbTransaction */
    private $transaction;

    protected function setUp(): void
    {
        $this->transaction = \Yii::app()->db->beginTransaction();
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->transaction->rollback();
    }

    /**
     * Invokes one of UserObserver's private methods for the purpose of testing.
     *
     * @param mixed ...$args
     * @return mixed
     */
    private function invokePrivate(\UserObserver $observer, string $method, ...$args)
    {
        $reflection = new \ReflectionMethod(\UserObserver::class, $method);
        $reflection->setAccessible(true);
        return $reflection->invoke($observer, ...$args);
    }

    public function testGetDoctorGradeFromJobTitleMapsKnownRoles(): void
    {
        $observer = new \UserObserver();

        $this->assertSame(1, $this->invokePrivate($observer, "getDoctorGradeFromJobTitle", "Consultant"));
        $this->assertSame(4, $this->invokePrivate($observer, "getDoctorGradeFromJobTitle", "Fellow"));
        $this->assertSame(22, $this->invokePrivate($observer, "getDoctorGradeFromJobTitle", "Optometrist"));
    }

    public function testGetDoctorGradeFromJobTitleMatchesOnSubstring(): void
    {
        $observer = new \UserObserver();

        // The lookup matches when the mapped description appears anywhere in the title.
        $this->assertSame(1, $this->invokePrivate($observer, "getDoctorGradeFromJobTitle", "Locum Consultant"));
    }

    public function testGetDoctorGradeFromJobTitleDefaultsToOther(): void
    {
        $observer = new \UserObserver();

        // 33 is the documented "Other" fallback for an unrecognised job title.
        $this->assertSame(33, $this->invokePrivate($observer, "getDoctorGradeFromJobTitle", "Completely Unknown Title"));
    }

    public function testUpdateUserSkipsUsersListedAsLocal(): void
    {
        \Yii::app()->params["local_users"] = ["localadmin"];

        $observer = $this->getMockBuilder(\UserObserver::class)
            ->onlyMethods(["getCSDClient"])
            ->getMock();
        // A local user must short-circuit before any CSD lookup is attempted.
        $observer->expects($this->never())->method("getCSDClient");

        $this->assertNull($observer->updateUser(["username" => "localadmin"]));
    }
}
