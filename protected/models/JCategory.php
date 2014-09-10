<?php
class JCategory extends CActiveRecord
{
    private $scondition = false;/*默认搜索条件*/
    public function primaryKey()
    {
         return 'catid';
    }
    /**
     * @return string 数据表名字
     */
    public function tableName()
    {
        return '{{category}}';
    }
  public function getDbConnection()
  {
      if(self::$db===null){
        self::$db = Ak::getDb();
      }
      return self::$db;
  }     

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function attributeLabels()
    {
        $result = array(
                'catname' => '分类',
        ); 
        return $result;
    }
}