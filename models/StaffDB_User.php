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

/**
 * This is the model class for table "MUUID_Staff_Table".
 *
 * The followings are the available columns in table 'MUUID_Staff_Table':
 * @property integer MUUID_Staff_UniqueID
 * @property string MUUID_Staff_MUUID
 * @property string MUUID_Staff_Title
 * @property string MUUID_Staff_NameFirst
 * @property string MUUID_Staff_NameMiddle
 * @property string MUUID_Staff_NameLast
 * @property string MUUID_Staff_KnownAs_NameFirst
 * @property string MUUID_Staff_Gender
 * @property string MUUID_Staff_JobTitle
 * @property integer MUUID_Staff_NHSOrganisationNameID
 * @property integer MUUID_Staff_MoorfieldsSiteID
 * @property integer MUUID_Staff_DepartmentSduID
 * @property integer MUUID_Staff_DepartmentID
 * @property string MUUID_Staff_DepartmentSub
 * @property string MUUID_Staff_Location
 * @property string MUUID_Staff_Comments
 * @property string MUUID_Staff_Home_Phone
 * @property string MUUID_Staff_Mobile_Phone
 * @property string MUUID_Staff_Internal_Phone
 * @property string MUUID_Staff_Internal_Bleep
 * @property string MUUID_Staff_PPsecretary_Phone
 * @property string MUUID_Staff_NHSsecretary_Phone
 * @property string MUUID_Staff_Clerk1_Phone
 * @property string MUUID_Staff_Clerk2_Phone
 * @property boolean MUUID_Staff_IsVoiceMailRequired
 * @property string MUUID_Staff_Notes_Phone
 * @property string MUUID_Staff_MyBossMUUID
 * @property string MUUID_Staff_MyAppraiserMUUID
 * @property string MUUID_Staff_MyCoordinatorMUUID
 * @property string MUUID_Staff_EmailAddress
 * @property string MUUID_Staff_EmailAddressDelegate
 * @property string MUUID_Staff_DomainUsername
 * @property integer MUUID_Staff_EthnicityID
 * @property string MUUID_Staff_EmployeeID
 * @property date MUUID_Staff_DateOfStarting
 * @property boolean MUUID_Staff_LeftMEH
 * @property date MUUID_Staff_DateOfLeaving
 * @property string MUUID_Staff_PersonnelID
 * @property string MUUID_Staff_PhotoCardID
 * @property boolean MUUID_Staff_IsPhotoCardIDDisplayPermitted
 * @property integer MUUID_Staff_GMCReferenceNumber
 * @property string MUUID_Staff_CreatedBy
 * @property date MUUID_Staff_CreatedDate
 * @property string EPR_MedicalDegrees
 * @property string EPR_JobType
 * @property string EPR_JobDescription
 * @property string EPR_LetterSignoff
 * @property integer EPR_MedicalGrade
 * @property integer EPR_Service_CodeID
 * @property integer EPR_Firm_CodeID
 * @property string EPR_ConsultantNameText
 * @property string EPR_ConsultantCode
 * @property integer EPR_DefaultWardID
 * @property integer EPR_RoleID
 */

class StaffDB_User extends MultiActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PAS_Gp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated db connection name
	 */
	public function connectionId()
	{
		return 'db_staff';
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'MUUID_Staff_Table';
	}

	/**
	 * @return array primary key for the table
	 */
	public function primaryKey()
	{
		return array('MUUID_Staff_UniqueID');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('MUUID_Staff_UniqueID',$this->MUUID_Staff_UniqueID,true);
		$criteria->compare('MUUID_Staff_MUUID',$this->MUUID_Staff_MUUID,true);
		$criteria->compare('MUUID_Staff_Title',$this->MUUID_Staff_Title,true);
		$criteria->compare('MUUID_Staff_NameFirst',$this->MUUID_Staff_NameFirst,true);
		$criteria->compare('MUUID_Staff_NameMiddle',$this->MUUID_Staff_NameMiddle,true);
		$criteria->compare('MUUID_Staff_NameLast',$this->MUUID_Staff_NameLast,true);
		$criteria->compare('MUUID_Staff_KnownAs_NameFirst',$this->MUUID_Staff_KnownAs_NameFirst,true);
		$criteria->compare('MUUID_Staff_Gender',$this->MUUID_Staff_Gender,true);
		$criteria->compare('MUUID_Staff_JobTitle',$this->MUUID_Staff_JobTitle,true);
		$criteria->compare('MUUID_Staff_NHSOrganisationNameID',$this->MUUID_Staff_NHSOrganisationNameID,true);
		$criteria->compare('MUUID_Staff_MoorfieldsSiteID',$this->MUUID_Staff_MoorfieldsSiteID,true);
		$criteria->compare('MUUID_Staff_DepartmentSduID',$this->MUUID_Staff_DepartmentSduID,true);
		$criteria->compare('MUUID_Staff_DepartmentID',$this->MUUID_Staff_DepartmentID,true);
		$criteria->compare('MUUID_Staff_DepartmentSub',$this->MUUID_Staff_DepartmentSub,true);
		$criteria->compare('MUUID_Staff_Location',$this->MUUID_Staff_Location,true);
		$criteria->compare('MUUID_Staff_Comments',$this->MUUID_Staff_Comments,true);
		$criteria->compare('MUUID_Staff_Home_Phone',$this->MUUID_Staff_Home_Phone,true);
		$criteria->compare('MUUID_Staff_Mobile_Phone',$this->MUUID_Staff_Mobile_Phone,true);
		$criteria->compare('MUUID_Staff_Internal_Phone',$this->MUUID_Staff_Internal_Phone,true);
		$criteria->compare('MUUID_Staff_Internal_Bleep',$this->MUUID_Staff_Internal_Bleep,true);
		$criteria->compare('MUUID_Staff_PPsecretary_Phone',$this->MUUID_Staff_PPsecretary_Phone,true);
		$criteria->compare('MUUID_Staff_NHSsecretary_Phone',$this->MUUID_Staff_NHSsecretary_Phone,true);
		$criteria->compare('MUUID_Staff_Clerk1_Phone',$this->MUUID_Staff_Clerk1_Phone,true);
		$criteria->compare('MUUID_Staff_Clerk2_Phone',$this->MUUID_Staff_Clerk2_Phone,true);
		$criteria->compare('MUUID_Staff_IsVoiceMailRequired',$this->MUUID_Staff_IsVoiceMailRequired,true);
		$criteria->compare('MUUID_Staff_Notes_Phone',$this->MUUID_Staff_Notes_Phone,true);
		$criteria->compare('MUUID_Staff_MyBossMUUID',$this->MUUID_Staff_MyBossMUUID,true);
		$criteria->compare('MUUID_Staff_MyAppraiserMUUID',$this->MUUID_Staff_MyAppraiserMUUID,true);
		$criteria->compare('MUUID_Staff_MyCoordinatorMUUID',$this->MUUID_Staff_MyCoordinatorMUUID,true);
		$criteria->compare('MUUID_Staff_EmailAddress',$this->MUUID_Staff_EmailAddress,true);
		$criteria->compare('MUUID_Staff_EmailAddressDelegate',$this->MUUID_Staff_EmailAddressDelegate,true);
		$criteria->compare('MUUID_Staff_DomainUsername',$this->MUUID_Staff_DomainUsername,true);
		$criteria->compare('MUUID_Staff_EthnicityID',$this->MUUID_Staff_EthnicityID,true);
		$criteria->compare('MUUID_Staff_EmployeeID',$this->MUUID_Staff_EmployeeID,true);
		$criteria->compare('MUUID_Staff_DateOfStarting',$this->MUUID_Staff_DateOfStarting,true);
		$criteria->compare('MUUID_Staff_LeftMEH',$this->MUUID_Staff_LeftMEH,true);
		$criteria->compare('MUUID_Staff_DateOfLeaving',$this->MUUID_Staff_DateOfLeaving,true);
		$criteria->compare('MUUID_Staff_PersonnelID',$this->MUUID_Staff_PersonnelID,true);
		$criteria->compare('MUUID_Staff_PhotoCardID',$this->MUUID_Staff_PhotoCardID,true);
		$criteria->compare('MUUID_Staff_IsPhotoCardIDDisplayPermitted',$this->MUUID_Staff_IsPhotoCardIDDisplayPermitted,true);
		$criteria->compare('MUUID_Staff_GMCReferenceNumber',$this->MUUID_Staff_GMCReferenceNumber,true);
		$criteria->compare('MUUID_Staff_CreatedBy',$this->MUUID_Staff_CreatedBy,true);
		$criteria->compare('MUUID_Staff_CreatedDate',$this->MUUID_Staff_CreatedDate,true);
		$criteria->compare('EPR_MedicalDegrees',$this->EPR_MedicalDegrees,true);
		$criteria->compare('EPR_JobType',$this->EPR_JobType,true);
		$criteria->compare('EPR_JobDescription',$this->EPR_JobDescription,true);
		$criteria->compare('EPR_LetterSignoff',$this->EPR_LetterSignoff,true);
		$criteria->compare('EPR_MedicalGrade',$this->EPR_MedicalGrade,true);
		$criteria->compare('EPR_Service_CodeID',$this->EPR_Service_CodeID,true);
		$criteria->compare('EPR_Firm_CodeID',$this->EPR_Firm_CodeID,true);
		$criteria->compare('EPR_ConsultantNameText',$this->EPR_ConsultantNameText,true);
		$criteria->compare('EPR_ConsultantCode',$this->EPR_ConsultantCode,true);
		$criteria->compare('EPR_DefaultWardID',$this->EPR_DefaultWardID,true);
		$criteria->compare('EPR_RoleID',$this->EPR_RoleID,true);

		return new CActiveDataProvider(get_class($this), array(
				'criteria'=>$criteria,
		));
	}
}
