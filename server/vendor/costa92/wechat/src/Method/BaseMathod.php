<?php
/**
 * Created by PhpStorm.
 * User: costa92
 * Date: 2017/3/29
 * Time: 下午5:17
 */

namespace Costa92\Wechat\Method;


/**
 * Class BaseMathod
 * @package Costa92\Wechat\Method
 *
 * @method SqlForm     \Costa92\Wechat\DataSql\SqlForm
 * @method SqlOtherForm  \Costa92\Wechat\DataSql\SqlOtherForm
 * @method SqlRed      \Costa92\Wechat\DataSql\SqlRed
 * @method SqlUser     \Costa92\Wechat\DataSql\SqlUser
 * @method SqlAttach   \Costa92\Wechat\DataSql\SqlAttach
 * @method SqlVoice    \Costa92\Wechat\DataSql\SqlVoice
 * @method SqlHelp     \Costa92\Wechat\DataSql\SqlHelp
 * @method SqlHelpLogs \Costa92\Wechat\DataSql\SqlHelpLogs
 * @method SqlMatch     \Costa92\Wechat\DataSql\SqlMatch
 * @method SqlSMS       \Costa92\Wechat\DataSql\SqlSMS
 * @method SqlPicture   \Costa92\Wechat\DataSql\SqlPicture
 * @method SqlPhoto     \Costa92\Wechat\DataSql\SqlPhoto
 * @method SqlPictureLink   \Costa92\Wechat\DataSql\SqlPictureLink
 * @method SqlSeniorHelp    \Costa92\Wechat\DataSql\SqlSeniorHelp
 * @method SqlSeniorHelpLogs  \Costa92\Wechat\DataSql\SqlSeniorHelpLogs
 * @method SqlDafan     \Costa92\Wechat\DataSql\SqlDafan
 * @method SqlAnswer    \Costa92\Wechat\DataSql\SqlAnswer
 * @method SqlAppletUser    \Costa92\Wechat\DataSql\SqlAppletUser
 * @method SqlAutoForm    \Costa92\Wechat\DataSql\SqlAutoForm
 * @method SqlAppletGroup    \Costa92\Wechat\DataSql\SqlAppletGroup
 * @method SqlVisits    \Costa92\Wechat\DataSql\SqlVisits
 * @method SqlUserShare    \Costa92\Wechat\DataSql\SqlUserShare
 * @method SqlNewHelp    \Costa92\Wechat\DataSql\SqlNewHelp
 * @method SqlNewHelpLogs    \Costa92\Wechat\DataSql\SqlNewHelpLogs
 * @method SqlRestrictHelp    \Costa92\Wechat\DataSql\SqlRestrictHelp
 * @method SqlRestrictHelpLimit    \Costa92\Wechat\DataSql\SqlRestrictHelpLimit
 * @method SqlRestrictHelpLogs    \Costa92\Wechat\DataSql\SqlRestrictHelpLogs
 * @method SqlLForm    \Costa92\Wechat\DataSql\SqlLForm
 */
class BaseMathod
{

    public static function SelectDataSql($DataSql="",$table="",$n="",$type=""){
         $class =trimall(static::getClass($DataSql));
         $class =  new $class;
         if($n){
         	return $class->setTable($table,$n,$type);
         }else{
         	return $class->setTable($table);
         }
    }

    public static function getClass($method =""){
        return getDocCommentMethod(BaseMathod::class,$method);
    }

}