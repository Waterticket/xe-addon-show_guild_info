<?php
if(!defined("__XE__")) exit;

if($called_position != 'after_module_proc' || $this->act != 'dispMemberInfo') return;

$member_srl = Context::get('member_srl');
if(!$member_srl)
{
    $logged_info = Context::get('logged_info');
    $member_srl = $logged_info->member_srl;
}

// Display Data
$displayDatas = Context::get('displayDatas');
$oGuildModel = getModel('guild');
$guildObj = $oGuildModel->GetGuildDataByMemberSrl($member_srl);
$maxGuildCnt = $oGuildModel->getConf("max_join_count");

debugPrint($guildObj);
if(empty($guildObj[0]->user_data)) return;

$cnt = 1;
foreach($guildObj as $inc => $obj)
{
    if($obj->user_data->guild_grade >= 3){ // 길드 가입 상태가 [가입 대기] 이상일경우
        $enc_data = json_decode(base64_decode($obj->guild_data->guild_logo));
        $logo_srl = $enc_data->url;
        $melist = new stdClass;
        $melist->title = "가입".$oGuildModel->getClassLang('guild_name') . (($maxGuildCnt == 1) ? "":($cnt++)); // 길드명
        $melist->value = "<img src=\"".$logo_srl."\" style=\"width: 1.5em; height: 1.5em; bottom: 0px; border-radius:50%;\">&nbsp;"."<a href=\"".getNotEncodedUrl('','mid','guild','act','dispGuildViewInfo','guild_id',$obj->guild_data->guild_srl)."\" style=\"text-decoration:none;\" title=\"".$oGuildModel->getClassLang('guild_name')." 이동\">".$obj->guild_data->guild_name."</a> [ ".$oGuildModel->rank_title($obj->user_data->guild_grade)." ]";
        $displayDatas[] = $melist;
    }
}

Context::set('displayDatas', $displayDatas);