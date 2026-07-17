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

namespace OEModule\mehstaffdb\tests\unit\components\CSDClient;

use OEModule\mehstaffdb\components\CSDClient\GetUserDataRequest;

class GetUserDataRequestTest extends \CTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Request::__construct() reads these from the application params and
        // throws when any of them is empty, so they must be populated first.
        \Yii::app()->params["csd_api_key"] = "test-api-key";
        \Yii::app()->params["csd_api_url"] = "http://example.com";
        \Yii::app()->params["csd_api_timeout"] = "30";
    }

    private function getInstance(): GetUserDataRequest
    {
        return new GetUserDataRequest();
    }

    public function testGetActionNameBuildsTheStaffLookupPath(): void
    {
        $this->assertEquals(
            "CSDAPI/api/staff?DomainUsername=WILLIAMSS",
            $this->getInstance()
                ->setUsername("WILLIAMSS")
                ->getActionName()
        );
    }

    public function testGetTimeoutReturnsTheConfiguredValueAsInt(): void
    {
        $this->assertSame(30, $this->getInstance()->getTimeout());
    }

    public function testConstructorThrowsWhenARequiredSettingIsMissing(): void
    {
        \Yii::app()->params["csd_api_url"] = "";

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("csd_api_url not set");

        new GetUserDataRequest();
    }
}
