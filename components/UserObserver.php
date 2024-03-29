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

//namespace OEModule\CSDClient\components;

use OEModule\mehstaffdb\components\CSDClient\CSDClient;

class UserObserver extends \BaseAPI
{

	/**
     * @return CSDClient
     */
	protected function getCSDClient(): CSDClient
    {
        return CSDClient::get();
    }


	/**
     * Updates user from CSD database
     *
     * @param array $params
	 * @return User
     */
	public function updateUser($params)
	{
		if (in_array($params['username'],Yii::app()->params['local_users'])) {
			return;
		}
		if (!isset($params['institution_authentication_id'])) {
			$institutionId = Institution::model()->find('remote_id = ?',array(Yii::app()->params['institution_code']))->id;
			$institution_authentication_id = InstitutionAuthentication::model()->find('institution_id= ?',array($institutionId))->id;
		} else {
			$institution_authentication_id = $params['institution_authentication_id'];
		}
			 
		if (Yii::app()->params['mehstaffdb_always_refresh'] || $this->isStale($params['username'], $institution_authentication_id)) {
			try {
				$username = $params['username'];
				$remote_user = $this->getCSDClient()->getUserData($username);
				if ($remote_user = $this->getCSDClient()->getUserData($username)) {
					$remote_user = json_decode($remote_user, true);

					$user = $this->getUser($username, $institution_authentication_id);
					$user_authentication = $this->getUserAuthentication($username, $institution_authentication_id);

					if (!$user) {
						$user = new User();
						$user_authentication = new UserAuthentication();
						$user_authentication->password = $this->genPassword();
						$user_authentication->password_repeat = $user_authentication->password;
						$user_authentication->institution_authentication_id = $institution_authentication_id;
						
						$preexists = false;
					} else {
						$preexists = true;
					}

					$user = $this->saveUser($user, $user_authentication, $remote_user);
					
					if (!$preexists) {
						$contact = new Contact();
					} else {
						if ($user->contact) {
							$contact = $user->contact;
						} else {
							$contact = new Contact();
						}
					}

					$contact = $this->saveContact($user, $contact);

					if ($user->contact_id !== $contact->id) {
						$user->contact_id = $contact->id;

						if (!$user->save(false)) {
							\Yii::log("Unable to save user contact: ".print_r($user->getErrors(),true), \CLogger::LEVEL_ERROR);
							throw new Exception("Unable to save user contact: ".print_r($user->getErrors(),true));
						}
					}
					return $user;
				} else {
					\Yii::log("User " . $username . " not found in the CSD database.", \CLogger::LEVEL_ERROR);
					return;
				}
			} catch (Exception $e) {
				\Yii::log("Unable to update user. Error: ". $e->getMessage(), \CLogger::LEVEL_ERROR);
				throw new Exception("Unable to save user contact: ".print_r($e->getMessage(),true));
			}
		}
	}


	/**
     * Finds User by username
     *
     * @param string $username
     * @return User|null
     */
	private function getUser(string $username, int $institution_authentication_id)
	{
		$criteria = new \CDbCriteria();
		$criteria->join = 'JOIN user_authentication ua ON t.id = ua.user_id';
		$criteria->addCondition('ua.username = :username');
		$criteria->params[':username'] = $username;
		$criteria->addCondition('ua.institution_authentication_id = :institution_authentication_id');
		$criteria->params[':institution_authentication_id'] = $institution_authentication_id;
		$user = \User::model()->find($criteria);
		return $user;
	}

	/**
     * Finds UserAuthentication by username
     *
     * @param string $username
     * @return UserAuthentication|null
     */
	private function getUserAuthentication(string $username, int $institution_authentication_id)
	{
		$criteria = new \CDbCriteria();
		$criteria->join = 'JOIN institution_authentication ia ON t.institution_authentication_id = ia.id';
		$criteria->addCondition('username = :username');
		$criteria->params[':username'] = $username;
		$criteria->addCondition('institution_authentication_id = :institution_authentication_id');
		$criteria->params[':institution_authentication_id'] = $institution_authentication_id;
		$user_authentication = \UserAuthentication::model()->find($criteria);
		return $user_authentication;
	}

	/**
     * Saves new User data which is coming from $remote_user
     *
     * @param User $user
	 * @param UserAuthentication $user_authentication
	 * @param array $remote_user
     * @return User
     */
	private function saveUser(User $user, UserAuthentication $user_authentication, array $remote_user): User
	{
		$user->code = $remote_user['code'];
		$user_authentication->username = $remote_user['username'];
		$user->first_name = $remote_user['first_name'];
		$user->last_name = $remote_user['last_name'];
		$mehstaffdb_default_email = Yii::app()->params['mehstaffdb_default_email'];
		if(strlen($mehstaffdb_default_email) != 0) {
			$user->email = $mehstaffdb_default_email;
		}
		$user->title = $remote_user['title'];
		$user->role = $remote_user['role'];
		$user->setdefaultSSORights();
		$user->doctor_grade_id = $this->getDoctorGradeFromJobTitle($remote_user['role']);
		$user->is_consultant = $remote_user['is_consultant'];
		$user->is_surgeon = $remote_user['is_surgeon'];
		$user_authentication->active = $remote_user['active'];
		$user->global_firm_rights = 1;

		if (!$user->save(false)) {
			\Yii::log('Unable to save user: '.print_r($user->getErrors(),true), \CLogger::LEVEL_ERROR);
			throw new Exception('Unable to save user: '.print_r($user->getErrors(),true));
		}

		if (!empty($remote_user['qualifications'])) {
			$contact = $user->contact;
			if (!$contact) {
				$contact = new Contact();
			}
			$contact->qualifications = $remote_user['qualifications'];
			$this->saveContact($user, $contact);
		}

		$user_authentication->user_id = $user->id;

		if (!$user_authentication->save()) {
			\Yii::log('Unable to save user: '.print_r($user_authentication->getErrors(),true), \CLogger::LEVEL_ERROR);
			throw new Exception('Unable to save user: '.print_r($user_authentication->getErrors(),true));
		}

		return $user;
	}

	/**
     * Saves new User data which is coming from $remote_user
     *
     * @param User $user
	 * @param Contact $contact
	 * @return Contact
	 * 
     */
	private function saveContact(User $user, Contact $contact): Contact
	{
		$contact->nick_name = $user->first_name;
		$contact->title = $user->title;
		$contact->first_name = $user->first_name;
		$contact->last_name = $user->last_name;

		if (!$contact->save(false)) {
			\Yii::log('Unable to save contact: '.print_r($contact->getErrors(),true), \CLogger::LEVEL_ERROR);
			throw new Exception('Unable to save contact: '.print_r($contact->getErrors(),true));
		}

		return $contact;
	}

	/**
     * Check if user is stale
     *
     * @param string $username
     * @return bool
     */
	private function isStale(string $username, int $institution_authentication_id): bool
	{
		$user = $this->getUser($username, $institution_authentication_id);

		if (!$user) {
			return true;
		}

		return (strtotime($user->last_modified_date) < (time() - Yii::app()->params['mehstaffdb_cache_time']));
	}

	/**
     * Connect job title to doctor grade id
     *
     * @param string $jobTitle
     * @return int ID of the doctor grade
     */
	private function getDoctorGradeFromJobTitle(string $jobTitle): int
	{
		$MEHDescription = array
		(
			1 => "Consultant",
			3 => "Associate Specialist",
			4 => "Fellow",
			5 => "Specialist Registrar",
			7 => "Trust Doctor",
			8 => "Senior House Officer",
			16 => "House Officer",
			20 => "Anaesthetist",
			21 => "Orthoptist",
			22 => "Optometrist",
			23 => "Clinical nurse specialist",
			24 => "Nurse",
			25 => "Health Care Assistant",
			26 => "Ophthalmic Technician",
			27 => "Surgical Care Practitioner",
			28 => "Clinical Assistant",
			29 => "RG1",
			30 => "RG2",
			31 => "ODP",
			32 => "Administration staff"
		);

		foreach($MEHDescription as $key=>$description){
			if(strpos($jobTitle, $description) !== false){
				return $key;
			}
		}
		return 33; // default value is Other
	}

	private function genPassword() {
		$consonants = 'bcdfghjklmnpqrstvz';
		$vowels = 'aeiou';
		$special_characters = '_!*@';

		$l = 8;
		$code = "";
	
		for ($i=0; $i<$l; $i++) {
			if ($i%2===0) {
				$r = rand(0,strlen($consonants)-1);
				$letter = substr($consonants,$r,1);
			} elseif ($i%4===3) {
				$r = rand(0,9);
				$letter = $r;
			} else {
				$r = rand(0,strlen($vowels)-1);
				$letter = substr($vowels,$r,1);
			}
	
			if ($i%4===0) {
				$letter = strtoupper($letter);
			}
			$code .= $letter;
		}

		$r = rand(0,strlen($special_characters)-1);
		$letter = substr($special_characters,$r,1);
		$code .= $letter;
			
		return $code;
	}
}
