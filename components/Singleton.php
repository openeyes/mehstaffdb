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

namespace OEModule\mehstaffdb\components;

/**
 * Class Singleton
 *
 * Base for singleton classes
 */

abstract class Singleton
{
    /**
     * @var Singleton[]
     */
    protected static $instances;

    /**
     * Constructor
     *
     * @return void
     */
    private function __construct()
    {
        $this->init();
    }

    /**
     * Extend and put initialization code here
     * instead of the constructor
     */
    protected function init() {}

    /**
     * Get instance
     *
     * @return Singleton
     */
    public final static function get() : Singleton
    {
        $class = get_called_class();
        if (!isset(self::$instances[$class]) || is_null(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }

        return self::$instances[$class];
    }
}