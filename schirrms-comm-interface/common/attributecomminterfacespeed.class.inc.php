<?php
/**
 * Copyright (c) 2019 Schirrms.
 */

/**
 * Class AttributeCommInterfaceSpeed
 */
class AttributeCommInterfaceSpeed extends AttributeDecimal
{
	/**
	 * @inheritdoc
	 */
	public function GetValueLabel($sValue)
	{
		$sValueLabel = parent::GetValueLabel($sValue);
		if(($sValueLabel !== null) && ($sValueLabel !== ''))
		{
			if(is_numeric($sValueLabel))
			{
				$sUnit = ' KMGTP';
				for ($iLevel=15; $iLevel >0 ;$iLevel=$iLevel-3)
				{
					if ($sValueLabel >= 10**$iLevel)
					{
						$sValueLabel = sprintf('%.3g ', $sValueLabel / (10**$iLevel)) . substr($sUnit, $iLevel/3, 1);
						break;
					}
				}
			}
		}
		else
		{
			$sValueLabel = '-';
		}

		return $sValueLabel;
	}

	/**
	 * @inheritdoc
	 */
	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		$sHTMLValue = parent::GetAsHTML($sValue, $oHostObject, $bLocalize);
		if(($sHTMLValue !== null) && ($sHTMLValue !== ''))
		{
			if(is_numeric($sHTMLValue))
			{
				$sUnit = ' KMGTP';
				for ($iLevel=15; $iLevel >0 ;$iLevel=$iLevel-3)
				{
					if ($sHTMLValue >= 10**$iLevel)
					{
						$sHTMLValue = sprintf('%.3g ', $sHTMLValue / (10**$iLevel)) . substr($sUnit, $iLevel/3, 1);
						break;
					}
				}
			}
			else
			{
				//where we here ?
				$sHTMLValue .= '-TestPS';
			}
		}
		else
		{
			$sHTMLValue = '-';
		}

		return $sHTMLValue;
	}
}