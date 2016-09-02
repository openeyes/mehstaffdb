<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

/**
 * Class CreateTherapyApplicationFileCollections
 *
 * command script to find PDF files in a nested directory structure and create appropriate file collections for use in
 * the Therapy application module
 *
 */
class UserUpdateCommand extends CConsoleCommand
{
    public function getName()
    {
        return 'UserUpdate';
    }
    
    public function getHelp()
    {
            return $this->getName() . ":\n\n" . <<<EOH
Will update users in OE those in the staff db

Usage:

******************** Example Usage ********************************
/var/www/openeyes/protected>php yiic.php userupdate all

This will update all users in OE those in the MEH Staff Database

Type 'yes' or 'no'.

Are you sure ?: [no]
*******************************************************************
    
EOH;
    }
    
    /**
     * Update all users from the staff db
     */
    public function actionAll()
    {
        echo "\nThis will update all users in OE those in the MEH Staff Database\n";
        echo "\nType 'yes' or 'no'.\n\n";
        $confirmed = $this->prompt('Are you sure ?:','no');
        
        if( $confirmed === 'yes' ){
            $observer = new UserObserver();
            
            $users = User::model()->findAll(array('order' => 'username'));
            foreach ($users as $user) {
                $params['username'] = $user->username;
                $observer->updateUser($params);
            }
        }
    }
}