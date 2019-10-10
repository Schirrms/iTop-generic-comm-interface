<?php

// Copyright (C) 2018 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

/**
 * main.schirrms-comm-interface.php
 * 
 * @author Pascal Schirrmann <schirrms@schirrms.net>
 */
class GenericCommFunct
{
	/**
	 * Hopefully an external class for all big functions in that package
	 */

	public function IterateVirtInterfaces($nLevel, $nInt_id, $nRealInt_id, $aVirtInterfaces)
	{
		$nLevel++ ;
		$sOQL = "SELECT lnkGenericCommInterfaceToGenericCommVirtInterface WHERE genericcomminterface_id = :interface";
		$oLnkVirtInterfaceSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), array(), array('interface' => $nInt_id));
		while ($oLnkVirtInterface = $oLnkVirtInterfaceSet->Fetch())
		{
			$sOQL2 = "SELECT GenericCommVirtInterface WHERE id = :interface";
			$oVirtInterfaceSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL2), array(), array('interface' => $oLnkVirtInterface->Get('genericcommvirtinterface_id')));
			while ($oVirtInterface = $oVirtInterfaceSet->Fetch())
			{
				if ($oVirtInterface->Get('virttogenericredundancy') != NULL && $oVirtInterface->Get('virttogenericredundancy') != 'disabled')
				{
					$aVirtInterfaces[$oLnkVirtInterface->Get('genericcommvirtinterface_id')][] = array('level' => $nLevel, 'GenInt' => $nRealInt_id, 'VirtRedundancy' => $oVirtInterface->Get('virttogenericredundancy'));
					$aVirtInterfaces = GenericCommFunct::IterateVirtInterfaces($nLevel, $oLnkVirtInterface->Get('genericcommvirtinterface_id'), $oLnkVirtInterface->Get('genericcommvirtinterface_id'), $aVirtInterfaces);
				}
				else 
				{
					$aVirtInterfaces = GenericCommFunct::IterateVirtInterfaces($nLevel-1, $oLnkVirtInterface->Get('genericcommvirtinterface_id'), $nRealInt_id, $aVirtInterfaces);
				}
			}
		}
		return $aVirtInterfaces;
	}

	public function UpdateCIDependencies($device_id, $searchImpact = TRUE)
	{
		$sDebugFile=$_SERVER['CONTEXT_DOCUMENT_ROOT']."/debug/dd-".date("Y-m-d").".txt";
		file_put_contents($sDebugFile, "BEGIN : ".date("H:i:s")."\n", FILE_APPEND);
		file_put_contents($sDebugFile, "In the GenericCommInterface Class for the device ".$device_id."\n", FILE_APPEND);
		// no $aContextParam in this case...
		// file_put_contents($sDebugFile, print_r($aContextParam, true), FILE_APPEND);
		// get all GenericCommInterface of the current device
		$aConnDevImpacts = array();
		$aConnDevDepends = array();
		$aVirtInterfaces = array();
		$oDevice = MetaModel::GetObject('ConnectableCI', $device_id);
		if (is_object($oDevice))
		{
			// step 1 : collect the configuration for this device
			$sOQL = "SELECT	GenericCommPhysInterface WHERE connectableci_id = :device";
			$oPhysInterfaceSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL),array(),array('device' => $device_id));
			while ($oPhysInterface = $oPhysInterfaceSet->Fetch())
			{
				if ($oPhysInterface->Get('connected_to_id') != 0) 
				{
					if ($oPhysInterface->Get('connection_impact') == 'depends')
					{
						$aConnDevDepends[$oPhysInterface->GetKey()] = array('remoteDev' => $oPhysInterface->Get('connected_to_device_id'), 'remoteInt' => $oPhysInterface->Get('connected_to_id'));
						$aVirtInterfaces = GenericCommFunct::IterateVirtInterfaces(0, $oPhysInterface->GetKey(), $oPhysInterface->GetKey(), $aVirtInterfaces);
					}
					else 
					{
						$aConnDevImpacts[$oPhysInterface->Get('connected_to_device_id')] = '';
					}
				}
			}
			file_put_contents($sDebugFile, "Contents of the array \$aConnDevDepends (list of Devices impacting this device)\n", FILE_APPEND);
			file_put_contents($sDebugFile, print_r($aConnDevDepends, true), FILE_APPEND);
			file_put_contents($sDebugFile, "Contents of the array \$aConnDevImpacts (list of Devices Depending of this device)\n", FILE_APPEND);
			file_put_contents($sDebugFile, print_r($aConnDevImpacts, true), FILE_APPEND);
			file_put_contents($sDebugFile, "Contents of the array \$aVirtInterfaces (list of all virtual interfaces this device)\n", FILE_APPEND);
			file_put_contents($sDebugFile, print_r($aVirtInterfaces, true), FILE_APPEND);
			// now, build the link matrix
			$aDependDevice = array();
			$aDirectConnDevDepends = $aConnDevDepends; 
			foreach($aVirtInterfaces as $aVirt)
			{
				$aTmp = array('', array());
				foreach($aVirt as $aGen)
				{
					if (array_key_exists($aGen['GenInt'],$aConnDevDepends)) 
					{
						$aTmp['Redundancy'] = $aGen['VirtRedundancy'];
						$aTmp['remoteDev'][] = aConnDevDepends[$aGen['GenInt']]['remoteDev'];
						if (array_key_exists($aGen['GenInt'], $aDirectConnDevDepends)) { unset($aDirectConnDevDepends[$aGen['GenInt']]); }
					}
				}
				$bPush = TRUE;
				foreach( $aDependDevice as $aValid )
				{
					if ($aValid['Redundancy'] == $aTmp['Redundancy'] && $aValid['remoteDev'] == $aTmp['remoteDev'] ) { $bPush = FALSE; }
				}
				if ($bPush && $aTmp['Redundancy'] != '') { $aDependDevice[] = $aTmp; }
			}
			file_put_contents($sDebugFile, "Contents of the array \$aDependDevice (list of redundant connections of this device)\n", FILE_APPEND);
			file_put_contents($sDebugFile, print_r($aDependDevice, true), FILE_APPEND);
			file_put_contents($sDebugFile, "Contents of the array \$aDirectConnDevDepends (list of non redundant connections of this device)\n", FILE_APPEND);
			file_put_contents($sDebugFile, print_r($aDirectConDevDepends, true), FILE_APPEND);
			// I now have all the datas that must be put in the lnkTables (if not present) :
			// All direct connections should be in the lnkConnectableCIToConnectableCI0, in the form 'this device in dependantci_id and $aDirectConDevDepends[*]['remoteDev']
			// all kind of redundant connections should be in the $aDependDevice array : 
			// aDependDevice[*]['Redundancy'] contains the redundancy type
			// aDependDevice[*]['remoteDev'] is an array with all destination Devices
			// for the connection that impacts, it's not possible to determine the impact from here
			// so it will be necessary to re run this script for each impacted device, according to the $aConnDevImpacts array.
			

			// Step 2 : scan all lnk tables, to gather the existing connection for the current device
			$aLnkTableD = array();
			$aLnkTableI = array();
			for ($i=0; $i<10; $i++)
			{
				// is this CI impactor for the remote ?
				$sOQL = "SELECT	lnkConnectableCIToConnectableCI".$i." WHERE impactorci_id = :device";
				$aLnkTableD[$i] = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), array(), array('device' => $device_id));
				file_put_contents($sDebugFile, "lnkConnectableCIToConnectableCI".$i."->Count() (Impactor) = ".$aLnkTableD[$i]->Count()."\n", FILE_APPEND);
				// is this CI depedent from the remote ?
				$sOQL = "SELECT	lnkConnectableCIToConnectableCI".$i." WHERE dependantci_id = :device";
				$aLnkTableI[$i] = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), array(), array('device' => $device_id));
				file_put_contents($sDebugFile, "lnkConnectableCIToConnectableCI".$i."->Count() (Dependant) = ".$aLnkTableI[$i]->Count()."\n", FILE_APPEND);
				// remove uneeded connection
			}
		}
	}
}