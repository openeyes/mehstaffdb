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

namespace OEModule\mehstaffdb\components\CSDClient;

use OEModule\mehstaffdb\components\CSDClient\GetUserDataRequest;
use OEModule\mehstaffdb\components\Singleton;

/**
 * Class CSDClient
 *
 * This is a facade for all CSD API calls. Other classes should only
 * call methods of this class.
 *
 * @method static CSDClient get()
 */

class CSDClient extends Singleton
{

    /**
     * Sends request
     *
     * @param Request $request
     * @return string|null JSON-encoded data on success or null on failure
     */
    private function sendRequest(Request $request): ?string
    {
        $response = $request->submit();
        if(!$response["success"]) {
            return null;
        }

        return $response["success"] > 0 && isset($response["result"]) ? json_encode($response["result"]) : null;
    }

    /**
     * Retrieves patient data from CSD
     *
     * @param string $username
     * @return string|null JSON-encoded data on success or null on failure
     */
    public function getUserData(string $username): ?string
    {
        return $this->sendRequest((new GetUserDataRequest($this))->setUsername($username));
    }
}