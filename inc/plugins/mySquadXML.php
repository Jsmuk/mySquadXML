<?PHP
/**
Copyright 2013 James "Jsm" McCartney

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
**/

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("index_start","mySquadXML_page");

function mySquadXML_info()
{
	return array(
		"name"		=> "mySquadXML",
		"description"		=> "Plugin for managing ArmA2 Squad.XML from inside of MyBB",
		"website"		=> "https://github.com/Jsmuk/mySquadXML",
		"author"		=> "Jsm",
		"authorsite"		=> "https://github.com/Jsmuk/mySquadXML",
		"version"		=> "0.1",
		"guid" 			=> "",
		"compatibility"	=> "*"
		);
}
function mySquadXML_install()
{
	global $db;
	$setting_group = array(
		'name'	=>	'mySquadXML',
		'title'	=>	'mySquadXML Settings',
		'description' => 'Settings to configure mySquadXML',
		'disporder' => '1',
		'isdefault' => 'no'
		);
	$db->insert_query("settinggroups",$setting_group);
	$gid = $db->insert_id();
	
	$setting = array(
		'name' => 'mySquadXML_squadnick',
		'title' => 'Squad Nick',
		'description' => 'Sets the "Squad Nick" to appear in the XML',
		'optionscode' => 'text',
		'value' => 'SquadNick',
		'disporder' => '1',
		'gid' => intval($gid)
		);
	$db->insert_query('settings',$setting);
	$setting = array(
		'name' => 'mySquadXML_squadname',
		'title' => 'Squad Name',
		'description' => 'Sets the squad name to appear in the XML',
		'optionscode' => 'text',
		'value' => 'Squad Name',
		'disporder' => '2',
		'gid' => intval($gid)
		);
	$db->insert_query('settings',$setting);
	$setting = array(
		'name' => 'mySquadXML_squademail',
		'title' => 'Squad Email',
		'description' => 'Sets the email address that appears for the squad',
		'optionscode' => 'text',
		'value' => 'x@x.x',
		'disporder' => '3',
		'gid' => intval($gid)
		);
	$db->insert_query('settings',$setting);
	$setting = array(
		'name' => 'mySquadXML_web',
		'title' => 'Squad Website',
		'description' => 'Sets the website for the squad',
		'optionscode' => 'text',
		'value' => 'www.google.com',
		'disporder' => '4',
		'gid' => intval($gid)
		);
	$db->insert_query('settings',$setting);
	$setting = array(
		'name' => 'mySquadXML_picture',
		'title' => 'Squad Logo',
		'description' => 'The squad logo (relative to the forum root, ideally a paa)',
		'optionscode' => 'text',
		'value' => 'logo.paa',
		'disporder' => '5',
		'gid' => intval($gid)
		);
	$db->insert_query('settings',$setting);
	$setting = array(
		'name' => 'mySquadXML_title',
		'title' => 'Squad Title',
		'description' => 'This is the title that appears on on vehicles etc',
		'optionscode' => 'text',
		'value' => 'Squad',
		'disporder' => '6',
		'gid' => intval($gid)
		);
	$db->insert_query('settings',$setting);
	$setting = array(
		'name' => 'mySquadXML_groups',
		'title' => 'Allowed groups',
		'description' => 'Comma delimited list of groups that are added to the Squad.XML. There are two approches here, either set it to a single group and add everyone to that group or a complete list of groups as per the default example. <br \> (Default: 2,3,4,6 / Users,Administrators,Super Moderators, Moderators)',
		'optionscode' => 'text',
		'value' => '2,3,4,6',
		'disporder' => '6',
		'gid' => intval($gid)
		);
	$db->insert_query('settings',$setting);
	$setting = array(
		'name' => 'mySquadXML_rewrite',
		'title' => 'Use rewrite',
		'description' => 'Set this to yes if you have set up URL rewriting to rewrite squadxml and squaddtd to index.php?squadxml and index.php?squaddtd',
		'optionscode' => 'yesno',
		'value' => '0',
		'disporder' => '7',
		'gid' => intval($gid)
		);
	$db->insert_query('settings',$setting);
		
	rebuild_settings();
	// Add the profile fields
	global $db;
	$new_profilefield = array(
					'name'    => 'ArmA UID',
					'description' => 'ArmA Player ID',
					'type' => 'text',
					'maxlength' => 100,
					'disporder' => 4,
					'required' => 0,
					'editable' => 1,
					'hidden' => 1,
					'postnum' => 0
				);
	$query = $db->insert_query("profilefields", $new_profilefield);
	$fid = $db->insert_id($query);
	$db->query("ALTER TABLE ".TABLE_PREFIX."userfields ADD fid".$fid." text;");
	$new_profilefield = array(
					'name'    => 'ArmA Remark',
					'description' => 'ArmA Squad XML Remark',
					'type' => 'text',
					'maxlength' => 255,
					'disporder' => '3',
					'required' => 0,
					'editable' => 1,
					'hidden' => 1,
					'postnum' => 0
				);
	$query = $db->insert_query("profilefields", $new_profilefield);
	$fid = $db->insert_id($query);
	$db->query("ALTER TABLE ".TABLE_PREFIX."userfields ADD fid".$fid." text;");
	$new_profilefield = array(
					'name'    => 'ArmA In-game Name',
					'description' => 'Name used in ArmA',
					'type' => 'text',
					'maxlength' => 255,
					'disporder' => '3',
					'required' => 0,
					'editable' => 1,
					'hidden' => 1,
					'postnum' => 0
				);
	$query = $db->insert_query("profilefields", $new_profilefield);
	$fid = $db->insert_id($query);
	$db->query("ALTER TABLE ".TABLE_PREFIX."userfields ADD fid".$fid." text;");

			
}
function mySquadXML_activate()
{
	
}
function mySquadXML_uninstall()
{
	global $db;
	$db->write_query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN ('mySquadXML_title','mySquadXML_picture','mySquadXML_web','mySquadXML_squademail','mySquadXML_squadname','mySquadXML_squadnick')");

	$db->write_query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name = 'mySquadXML'");
	rebuild_settings(); 
	
	$query = $db->query("SELECT fid, name FROM ".TABLE_PREFIX."profilefields WHERE name='ArmA UID'");
	$fid1 = $db->fetch_field($query, "fid");
	$query = $db->query("SELECT fid, name FROM ".TABLE_PREFIX."profilefields WHERE name='ArmA Remark'");
	$fid2 = $db->fetch_field($query, "fid");
	$query = $db->query("SELECT fid, name FROM ".TABLE_PREFIX."profilefields WHERE name='ArmA In-game Name'");
	$fid3 = $db->fetch_field($query, "fid");
	$db->query("DELETE FROM ".TABLE_PREFIX."profilefields WHERE name='ArmA UID'");
	$db->query("DELETE FROM ".TABLE_PREFIX."profilefields WHERE name='ArmA Remark'");
	$db->query("DELETE FROM ".TABLE_PREFIX."profilefields WHERE name='ArmA In-game Name'");
	$db->query("ALTER TABLE ".TABLE_PREFIX."userfields DROP fid".$fid1."");
	$db->query("ALTER TABLE ".TABLE_PREFIX."userfields DROP fid".$fid2."");
	$db->query("ALTER TABLE ".TABLE_PREFIX."userfields DROP fid".$fid3."");
}
function mySquadXML_is_installed()
{
	global $db;
	$query = $db->simple_select('settinggroups','name',"name='mySquadXML'",array("limit" => 1));
	if ($db->num_rows($query) == 1)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function mySquadXML_page()
{
	global $mybb;
	if (isset($_GET['squadxml']))
	{
		if ((bool)$mybb->settings['mySquadXML_rewrite'])
		{
			$DtdUrl = $mybb->settings['bburl']."/squaddtd";
		}
		else
		{
			$DtdUrl = $mybb->settings['bburl']."/index.php?squaddtd";
		}
		// Maybe this isn't the best or most efficent way to output the XML but it sure is the simplest
		header('Content-Type: text/xml');
		// File header
		echo '<?xml version="1.0"?>
<!DOCTYPE squad SYSTEM "'.$DtdUrl.'">
		';
		
		echo '
	<squad nick="'.$mybb->settings['mySquadXML_squadnick'].'">
		<name>'.$mybb->settings['mySquadXML_squadname'].'</name>
		<email>'.$mybb->settings['mySquadXML_squademail'].'</email>
		<web>'.$mybb->settings['mySquadXML_web'].'</web>
		<picture>'.$mybb->settings['mySquadXML_picture'].'</picture>
		<title>'.$mybb->settings['mySquadXML_title'].'</title>
		';
		
		$members = mySquadXML_get_members($mybb->settings['mySquadXML_groups']);
		
		$i = 0;
		while ($i <= count($members))
		{
			if ($members[$i]['ArmA_UID'] == "" || $members[$i]['ArmA_IGName'] == "")
			{
				// NOTHING
			}
			else
			{
				mySquadXML_member_output($members[$i]['username'],$members[$i]['ArmA_IGName'],$members[$i]['email'],$members[$i]['ArmA_UID'],$members[$i]['ArmA_Remark']);
			}
			$i++;
		}
		echo '
	</squad>
		';
		die();
	}
	if (isset($_GET['squaddtd']))
	{
		header('Content-Type: text/xml');
		echo '<?xml version="1.0"?>
<!ELEMENT squad (name, email, web?, picture?, title?, member+)>
<!ATTLIST squad nick CDATA #REQUIRED>
<!ELEMENT member (name, email, icq?, remark?)>
<!ATTLIST member id CDATA #REQUIRED nick CDATA #REQUIRED>
<!ELEMENT name (#PCDATA)>
<!ELEMENT email (#PCDATA)>
<!ELEMENT icq (#PCDATA)>
<!ELEMENT web (#PCDATA)>
<!ELEMENT picture (#PCDATA)>
<!ELEMENT title (#PCDATA)>
<!ELEMENT remark (#PCDATA)>
		';
		die();
	}
}
function mySquadXML_member_output($name,$nick,$email,$uid,$remark)
{
	// TODO: Possibly add more information (Grab web address if there is one? ICQ if its set? Does anyone even use ICQ anymore)
	echo '
		<member id="'.$uid.'" nick="'.$nick.'">
					<name>'.$name.'</name>
					<email>'.$email.'</email>
					<remark>'.$remark.'</remark>
			</member>';
}
function mySquadXML_get_members($groups)
{
	global $db;
	$return;
	$i = 0;
	// TODO: Make this slightly tider and use less queries, cannot be good to do three queries
	$query = $db->query("SELECT fid, name FROM ".TABLE_PREFIX."profilefields WHERE name='ArmA UID'");
	$UIDfid = $db->fetch_field($query, "fid");
	$query = $db->query("SELECT fid, name FROM ".TABLE_PREFIX."profilefields WHERE name='ArmA Remark'");
	$RemarkFid = $db->fetch_field($query, "fid");
	$query = $db->query("SELECT fid, name FROM ".TABLE_PREFIX."profilefields WHERE name='ArmA In-game Name'");
	$GameNameFid = $db->fetch_field($query, "fid");
	$ex = explode(',',$groups);
	if (count($ex) == 1)
	{
		
		$group = (int)$ex[0];
		$queryUser = $db->query("SELECT `uid`,`username`,`email` FROM `".TABLE_PREFIX."users` WHERE `usergroup` = ".$group.";");
		while ($row = $db->fetch_array($queryUser))
		{
			$return[$i]['username'] = $row['username'];
			$return[$i]['email'] = $row['email'];
			$customfidquery = $db->query("SELECT `fid".$UIDfid."`,`fid".$RemarkFid."`,`fid".$GameNameFid."` FROM `".TABLE_PREFIX."userfields` WHERE `ufid` = ".$row['uid']." LIMIT 1");
			$customfields = $db->fetch_array($customfidquery);
			$return[$i]['ArmA_Remark'] = $customfields['fid'.$RemarkFid];
			$return[$i]['ArmA_UID'] = $customfields['fid'.$UIDfid];
			$return[$i]['ArmA_IGName'] = $customfields['fid'.$GameNameFid];
			$i++;
		}
	}
	else
	{
		$x = 0;
		while ($x <= count($ex))
		{
			$group = (int)$ex[$x];
		$queryUser = $db->query("SELECT `uid`,`username`,`email` FROM `".TABLE_PREFIX."users` WHERE `usergroup` = ".$group.";");
		while ($row = $db->fetch_array($queryUser))
		{
			$return[$i]['username'] = $row['username'];
			$return[$i]['email'] = $row['email'];
			$customfidquery = $db->query("SELECT `fid".$UIDfid."`,`fid".$RemarkFid."`,`fid".$GameNameFid."` FROM `".TABLE_PREFIX."userfields` WHERE `ufid` = ".$row['uid']." LIMIT 1");
			$customfields = $db->fetch_array($customfidquery);
			$return[$i]['ArmA_Remark'] = $customfields['fid'.$RemarkFid];
			$return[$i]['ArmA_UID'] = $customfields['fid'.$UIDfid];
			$return[$i]['ArmA_IGName'] = $customfields['fid'.$GameNameFid];
			$i++;
		}
		$x++;
		}
	}
	return $return;
}
?>