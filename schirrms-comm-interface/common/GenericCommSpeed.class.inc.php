<?php
/**
 * Copyright (c) 2019 Schirrms.
 */

/**
 * Class GenericCommSpeed : should calculate the human speed after creation/update of a bitSpeed
 */
class GenericCommSpeed
{
	public function SetHumanSpeed()
	{
		// read the new value of the bitSpeed
		$dBitSpeed = $this->Get('bitspeed');
		
		// failsafe, the field cannot be empty
		$sHumanSpeed = $dBitSpeed." b";

		if(is_numeric($dBitSpeed))
		{
			$sUnit = ' KMGTP';
			for ($iLevel=15; $iLevel >0 ;$iLevel=$iLevel-3)
			{
				if ($dBitSpeed >= 10**$iLevel)
				{
					$sHumanSpeed = sprintf('%.3g ', $dBitSpeed / (10**$iLevel)) . substr($sUnit, $iLevel/3, 1);
					break;
				}
			}
		}

		$this->Set('humanspeed', $sValueLabel);
	}

	public function OnUpdate()
	{
		$this->SetHumanSpeed();
	}
	
	public function GetInitialStateAttributeFlags($sAttCode, &$aReasons = array())
	{
		// Hide the calculated field in object creation form
		if (($sAttCode == 'humanspeed'))
			return(OPT_ATT_HIDDEN | parent::GetInitialStateAttributeFlags($sAttCode, $aReasons));
			return parent::GetInitialStateAttributeFlags($sAttCode, $aReasons);
	}
 
	public function GetAttributeFlags($sAttCode, &$aReasons = array(), $sTargetState = '')
	{
		// Force the computed field to be read-only, preventing it to be written
		if (($sAttCode == 'humanspeed'))
			return(OPT_ATT_READONLY | parent::GetAttributeFlags($sAttCode, $aReasons, $sTargetState));
			return parent::GetAttributeFlags($sAttCode, $aReasons, $sTargetState);
	}

}