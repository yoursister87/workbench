<?php

class  ErrorConst {
	
	//成功时返回
	const SUCCESS_CODE = "0";
	const SUCCESS_MSG = "[数据返回成功]";

	//数据库连接失败时返回
	const E_DB_CONNECT_MSG = "[数据连接异常]";
	const E_DB_CONNECT_CODE = "1001";
	
	//参数不合法时返回
	const E_PARAM_INVALID_MSG = "[参数不合法]";
	const E_PARAM_INVALID_CODE = "1002";
	
	//SQL语句执行失败
	const E_SQL_FAILED_MSG = "[SQL语句执行失败]";
	const E_SQL_FAILED_CODE = "1003";

    // 逻辑执行失败
    const E_INNER_FAILED_MSG = "[逻辑执行失败]";
    const E_INNER_FAILED_CODE = "1004";

    const E_DB_FAILED_CODE = "1005";
    const E_DB_FAILED_MSG = "[数据库失败]";

    const E_INTERFACE_FAILED_CODE = "1006";
    const E_INTERFACE_FAILED_MSG = "[接口异常]";

    //数据不存在
    const E_DATA_NOT_EXIST_CODE = "1007";
    const E_DATA_NOT_EXIST_MSG = "[访问数据不存在]";

    //数据重复
    const E_DATA_DUPLICATE_CODE = "1008";
    const E_DATA_DUPLICATE_MSG = "[数据重复]";

    //操作超限
    const E_OPERATION_OVERFLOW_CODE = "1009";
    const E_OPERATION_OVERFLOW_MSG = "[操作超限]";

	const Ganji_Default_Image = "http://stacdn201.ganjistatic1.com/att/promotion/project/20140625_zhineng/shop_default_img.png";

}
