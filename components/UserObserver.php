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

class UserObserver
{
	public function updateUser($params)
	{
		if (in_array($params['username'],Yii::app()->params['local_users'])) {
			return;
		}

		if (Yii::app()->params['mehstaffdb_always_refresh'] || $this->is_stale($params['username'])) {
			try {
				if ($remote_user = StaffDB_User::model()->find("MUUID_Staff_DomainUsername='{$params['username']}'")) {
					if (!$user = User::model()->find('username=?',array($params['username']))) {
						$user = new User;
						$preexists = false;
					} else {
						$preexists = true;
					}

					$user->code = $remote_user->MUUID_Staff_MUUID;
					$user->username = $remote_user->MUUID_Staff_DomainUsername;
					$user->first_name = $remote_user->MUUID_Staff_NameFirst;
					$user->last_name = $remote_user->MUUID_Staff_NameLast;
					$user->email = Yii::app()->params['mehstaffdb_default_email'];
					$user->title = $remote_user->MUUID_Staff_Title;
					$user->qualifications = $remote_user->EPR_MedicalDegrees;
					$user->role = $remote_user->MUUID_Staff_JobTitle;
					$user->password = 'faed6633f5a86241f3e0c2bb2bb768fd';
					$user->is_doctor = ($user->qualifications != '' && $user->qualifications != '.') ? 1 : 0;
					$user->is_clinical = $remote_user->MUUID_Staff_IsClinical;
					$user->is_consultant = $remote_user->MUUID_Staff_IsConsultant;
					$user->is_surgeon = $remote_user->MUUID_Staff_IsSurgeon;
					$user->active = !$remote_user->MUUID_Staff_LeftMEH;
					$user->global_firm_rights = 1;

					if (!$user->save(false)) {
						throw new Exception('Unable to save user: '.print_r($user->getErrors(),true));
					}

					if (!$preexists) {
						$contact = new Contact;
					} else {
						if ($uca = UserContactAssignment::model()->find('user_id=?',array($user->id))) {
							$contact = $uca->contact;
						} else {
							$contact = new Contact;
						}
					}

					$contact->nick_name = $user->first_name;
					$contact->title = $user->title;
					$contact->first_name = $user->first_name;
					$contact->last_name = $user->last_name;
					$contact->qualifications = $user->qualifications;

					if (!$contact->save()) {
						throw new Exception('Unable to save contact: '.print_r($contact->getErrors(),true));
					}

					if (!$preexists || !$uca) {
						$uca = new UserContactAssignment;
						$uca->user_id = $user->id;
						$uca->contact_id = $contact->id;
						if (!$uca->save()) {
							throw new Exception('Unable to save uca: '.print_r($uca->getErrors(),true));
						}
					}
				}
			} catch (Exception $e) {
				// silently return back to UserIdentity without having refreshed the user
			}
		}
	}

	public function is_stale($username)
	{
		if (!$user = User::model()->find('username=?',array($username))) {
			return true;
		}

		return (strtotime($user->last_modified_date) < (time() - Yii::app()->params['mehstaffdb_cache_time']));
	}
}
