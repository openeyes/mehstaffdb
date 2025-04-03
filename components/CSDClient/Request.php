<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

namespace OEModule\mehstaffdb\components\CSDClient;

abstract class Request implements RequestInterface
{
    /** @var string */
    protected string $csd_api_key;
    /** @var string */
    protected string $csd_api_url;
    /** @var string */
    protected string $csd_api_timeout;

    /**
     * Child classes must extend this to be able
     * to automatically configure the class instance
     *
     * @return array
     */
    protected function getSettingKeys(): array
    {
        return [
            "csd_api_key",
            "csd_api_url",
            "csd_api_timeout"
        ];
    }

    public function __construct()
    {
        foreach ($this->getSettingKeys() as $setting_key) {
            $setting_value = \Yii::app()->params[$setting_key];
            if (strlen($setting_value) === 0) {
                throw new \Exception("$setting_key not set");
            }
            $this->$setting_key = $setting_value;
        }
    }

    /**
     * @inheritDoc
     */
    public function getTimeout(): int
    {
        return (int)$this->csd_api_timeout;
    }

    /**
     * HTTP headers sent along with the request
     *
     * @return string[]
     */
    protected function getRequestHeader(): array
    {
        return [
            'Content-type: application/json',
            'APIKey: ' . $this->csd_api_key,
        ];
    }

    /**
     * @inheritDoc
     */
    public function submit() 
    {
        $base_url = $this->csd_api_url;
        $url = $base_url."/".$this->getActionName();
        $ch = curl_init($url);

        if(!empty($header = $this->getRequestHeader())) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->getTimeout());
        curl_setopt($ch, CURLOPT_VERBOSE, false);

        $result = curl_exec($ch);
        $err = curl_errno($ch);

        if($err > 0) {
            \Yii::log("CSD Client error: ". $err, \CLogger::LEVEL_ERROR);
            return [
                'success' => 0,
                'message' => "CSD Client error: ". $err
            ];
        }

        $result_array = json_decode($result, true);

        $success = 1;
        if($result_array==null) {
            $success = 0;
            \Yii::log("CSD Client error: User not found", \CLogger::LEVEL_ERROR);
        } else if(!array_key_exists("code", $result_array) || !array_key_exists("username", $result_array)) {
            $success = 0;
            \Yii::log("CSD Client error:  ".$result_array["message"], \CLogger::LEVEL_ERROR);
        }

        return [
            'success' => $success,
            'result' => $result_array
        ];
    }
}