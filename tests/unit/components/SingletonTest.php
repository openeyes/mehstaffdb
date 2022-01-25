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

use OEModule\CSDClient\components\Singleton;

class SingletonTest extends \CTestCase
{
    public function testGet()
    {
        $stub1 = $this->getMockClass(Singleton::class, ["get"], [], "class1");
        $stub2 = $this->getMockClass(Singleton::class, ["get"], [], "class2");
        $obj1 = $stub1::get();
        $obj2 = $stub2::get();
        $this->assertNotSame($obj1, $obj2);
        $this->assertInstanceOf($stub1, $obj1);
        $this->assertInstanceOf($stub2, $obj2);
        $obj3 = $stub1::get();
        $this->assertSame($obj1, $obj3);
    }
}