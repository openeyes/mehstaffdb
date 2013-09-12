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
class UserCheckerCommand extends CConsoleCommand
{
	protected $attribute_comparison = array(
		'is_surgeon' => 'MUUID_Staff_IsSurgeon');

	public function getName()
	{
		return 'UserChecker';
	}

	public function getHelp()
	{
		return $this->getName() . ":\n\n" . <<<EOH
Will compare users in OE with those in the staff db to check for discrepancies

EOH;
	}

	/**
	 * main method to run the command for checking users against staff db.
	 *
	 * @param array $args
	 * @return int|void
	 */
	public function run($args)
	{
		foreach (User::model()->findAll() as $user) {
			if ($staff_user = StaffDB_User::model()->find("MUUID_Staff_DomainUsername=?", array($user->username)) ) {
				if (!$staff_user->MUUID_Staff_LeftMEH) {
					foreach ($this->attribute_comparison as $oe_field => $staff_field) {
						if ($user->$oe_field != $staff_user->$staff_field) {
							echo $user->username . " field mismatch " . $oe_field . " oe:" . $user->$oe_field . ", staff:" . $staff_user->$staff_field . "\n";
						}
					}
				}			
			}
		}

	}

}
