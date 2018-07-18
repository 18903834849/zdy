<?php

namespace zdy;

/**
 * Excel导出/读取
 * Class PHPExcel
 * @package zdy
 */
class PHPExcel
{
    
    /**
     * 导出Excel文件
     * @param string $expTitle 导出的表格名称
     * @param array  $cell     表格栏目 [ 'id' => '订单ID', 'out_trade_no', =>  '订单编号']
     * @param array  $data     表格列表 [ [ 'id' => '1' , 'out_trade_no' => '20180101020205' ], [ 'id' => '1' , 'out_trade_no' => '20180101020205' ]]
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public static function exportExcel($expTitle, $cell, $data)
    {
        
        // 表格栏目数据转换成 [ ['id', '订单ID'], ['out_trade_no', ''订单编号] ]这种格式
        $fields = [];
        foreach ($cell as $key => $value) {
            if (is_numeric($key)) {
                $key = $value;
            }
            $fields[]      = $key;
            $expCellName[] = [$key, $value];
        }
        
        // 去掉栏目不存在的列
        foreach ($data as $item) {
            $value = [];
            foreach ($fields as $field) {
                $value[$field] = isset($item[$field]) ? $item[$field] : '';
            }
            $expTableData[] = $value;
        }
        
        include_once dirname(__FILE__) . '/PHPExcel/PHPExcel.php';
        
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $xlsTitle;//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum  = count($expCellName);
        $dataNum  = count($expTableData);
        
        $objPHPExcel = new \PHPExcel();
        $cellName    = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1');//合并单元格
        //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
        
        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '2', $expCellName[$i][1]);
        }
        
        // Miscellaneous glyphs, UTF-8
        for ($i = 0; $i < $dataNum; $i++) {
            for ($j = 0; $j < $cellNum; $j++) {
                // 避免大数字不显示的问题
                $value = $expTableData[$i][$expCellName[$j][0]];
                if (is_numeric($value) && $value > 2147483647) {
                    $value .= ' ';
                }
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + 3), $value);
            }
        }
        
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
    
    /**
     *  读取Excel文件
     * @param string   $filename 文件名
     * @param callable $callback 回调方法
     * @return array|bool
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public static function readExcelRows($filename, $callback = null)
    {
        include_once dirname(__FILE__) . '/PHPExcel/PHPExcel.php';
        
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        if (!$PHPReader->canRead($filename)) {
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($filename)) {
                return false;
            }
        }
        
        $PHPExcel     = $PHPReader->load($filename);
        $currentSheet = $PHPExcel->getSheet(0);//读取第一个工作表
        $allColumn    = $currentSheet->getHighestColumn();//取得最大的列号
        $allRow       = $currentSheet->getHighestRow();//取得一共有多少行
        $rows         = [];
        
        for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
            $row = array();
            /**从第A列开始输出*/
            for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
                $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65, $currentRow)->getValue(); /*ord()将字符转为十进制数*/
                /**如果输出汉字有乱码，则需将输出内容用iconv函数进行编码转换，如下将gb2312编码转为utf-8编码输出*/
                //$arr[$currentRow][]=  iconv('utf-8','gb2312', $val)."＼t";
                $row[$currentRow][] = trim($val);
            }
            if ($callback instanceof \Closure) {
                $callback($row[$currentRow]);
            } else {
                $rows[] = $row;
            }
        }
        return $rows;
    }
    
}