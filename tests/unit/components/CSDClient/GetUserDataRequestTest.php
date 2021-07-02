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

namespace OEModule\CSDClient\tests\unit\components\CSDClient;

class GetUserDataRequestTest extends \CTestCase
{
    /**
     * @return GetUserDataRequest
     */
    private function getInstance()
    {
        //\Yii::app()->params["pre_assessment_api_base_url"] = "http://example.com";
        return new GetUserDataRequest(CSDClient::get());
    }

    /** @test */
    public function testGetActionName()
    {
        $this->assertEquals("CSDAPI/api/staff?DomainUsername=WILLIAMSS",
            $this->getInstance()
                ->setUsername("WILLIAMSS")
                ->getActionName());
    }

    /** @test */
    public function testGetTimeout()
    {
        $this->assertIsNumeric($this->getInstance()->getTimeout());
    }
}