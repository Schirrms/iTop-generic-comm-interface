<?php
/**
 * Localized data
 *
 * @copyright   Copyright (C) 2013 XXXXX
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

Dict::Add('EN US', 'English', 'English', array(
	// Dictionary entries go here
	'Class:GenericPhysCommConnector' => 'Connector Type',
	'Class:GenericPhysCommConnector/Attribute:type' => 'Physical connector type',
	'Class:GenericPhysCommConnector/Attribute:type+' => 'This is the form of the connector (RJ-45, Fiber LC...)',
	'Class:GenericPhysCommConnector/Attribute:comment' => 'Comments',
	'Class:GenericPhysCommConnector/Attribute:genericcommphysinterface_list' => 'Interfaces with this connector list',
	'Class:GenericCommProtocol' => 'Connection Protocol',
	'Class:GenericCommProtocol/Attribute:protocol' => 'Connection protocol',
	'Class:GenericCommProtocol/Attribute:protocol+' => 'This is the low level protocol of communication between the devices on this interface (also known as \'Layer Two protocol\' for network connections)',
	'Class:GenericCommProtocol/Attribute:comment' => 'Comments',
	'Class:GenericCommProtocol/Attribute:genericcomminterface_list' => 'Interfaces using this protocol list',
	'Class:GenericCommSpeed' => 'Interface Speed',
	'Class:GenericCommSpeed/Attribute:humanspeed' => 'Human readable interface speed',
	'Class:GenericCommSpeed/Attribute:humanspeed+' => 'This fiead is read only, and calculated from the speed in bit per second',
	'Class:GenericCommSpeed/Attribute:bitspeed' => 'interface speed in bit per second',
	'Class:GenericCommSpeed/Attribute:comment' => 'Comments',
	'Class:GenericCommSpeed/Attribute:genericcomminterface_list' => 'Interfaces running at this speed',
	'Class:VirtualCommRedundancy' => 'Redundancy Mode',
	'Class:VirtualCommRedundancy/Attribute:protocol' => 'Redundancy mode',
	'Class:VirtualCommRedundancy/Attribute:protocol+' => 'This is the king of protocol used by the device to ensure redundancy (LACP, Active/tandby...)',
	'Class:VirtualCommRedundancy/Attribute:comment' => 'Comments',
	'Class:VirtualCommRedundancy/Attribute:virtualcommredundancy_list' => 'Virtual interfaces using this redundancy protocol',
	'Class:GenericCommInterface' => 'Connection General Interface',
	'Class:GenericCommInterface/Attribute:name' => 'Name',
	'Class:GenericCommInterface/Attribute:comment' => 'Comments',
	'Class:GenericCommInterface/Attribute:commproto_id' => 'Connection Protocol',
));
?>
