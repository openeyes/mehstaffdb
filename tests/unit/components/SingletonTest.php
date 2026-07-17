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

use OEModule\mehstaffdb\components\Singleton;

/**
 * Concrete subclasses used to exercise the abstract Singleton base.
 * Each distinct subclass should resolve to its own single shared instance.
 */
class SingletonTestSubjectA extends Singleton
{
}

class SingletonTestSubjectB extends Singleton
{
}

class SingletonTest extends \CTestCase
{
    public function testGetReturnsInstanceOfTheCalledClass(): void
    {
        $this->assertInstanceOf(SingletonTestSubjectA::class, SingletonTestSubjectA::get());
        $this->assertInstanceOf(SingletonTestSubjectB::class, SingletonTestSubjectB::get());
    }

    public function testGetReturnsTheSameInstanceOnRepeatedCalls(): void
    {
        $this->assertSame(SingletonTestSubjectA::get(), SingletonTestSubjectA::get());
    }

    public function testDifferentSubclassesResolveToDifferentInstances(): void
    {
        $this->assertNotSame(SingletonTestSubjectA::get(), SingletonTestSubjectB::get());
    }
}
